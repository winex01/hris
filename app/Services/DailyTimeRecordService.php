<?php

namespace App\Services;

use App\Models\DailyTimeRecord;

class DailyTimeRecordService
{   
    protected $dtr;

    protected $shift;

    protected $logs;

    public function __construct(DailyTimeRecord $dtr)
    {
        $this->dtr = $dtr;
        
        $this->shift = $this->shiftDetails($this->dtr->date);
        
        $this->logs = $this->logs($this->dtr->date);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getDateListColumn()
    {

        return '<span title="'.daysOfWeekFromDate($this->dtr->date).'">'.$this->dtr->date.'</span>';
    }

    public function getShiftScheduleListColum()
    {
        if ($this->shift != null) {
            $url = backpack_url('shiftschedules/'.$this->shift->id.'/show');
            return anchorNewTab($url, $this->shift->name, $this->shift->details_text);
        }

        return;
    }

    public function getLogsListColumn()
    {
        $html = "";
        if ($this->logs) {
            foreach ($this->logs as $log) {
                $title = "";
                $url = backpack_url('dtrlogs/'.$log->id.'/show');
                $typeBadge = $log->dtrLogType->nameBadge;
                
                $title .= '<span class="'.config('appsettings.link_color').'" title="'.$log->log.'">'.$typeBadge.' '.carbonTimeFormat($log->log).'</span>';
                $title .= "<br>";
            
                $html .= anchorNewTab($url, $title);
            }
        }

        return $html;
    }

    public function getLateListColumn()
    {
        return displayHourTimeInHtml($this->getLate());;
    }

    public function getLate()
    {
        // if no shift schedule return null
        // if no logs return null
        if (!$this->shift || !$this->logs) {
            return;
        } 
        
        // if logs not valid
        if (!$this->validateLogs()) {
            return 'invalid';
        }
        
        // get logs with type In = 1
        $logs = $this->logs->where('dtr_log_type_id', 1)->sortBy('logs');
        
        $workingHoursWithDate = $this->shift->working_hours_with_date;

        $lateDuration = '00:00';

        $i = 0;
        foreach ($logs as $dtrLog) { // loop for IN's
            $workingStart = $workingHoursWithDate[$i]['start'];
            $timeIn = carbonDateHourMinuteFormat($dtrLog->log); 

            // if late, then add late to lateDuration
            if (carbonInstance($timeIn)->greaterThan($workingStart)) {
                $late = carbonTimeFormatDiff($workingStart, $timeIn);
                $lateDuration = carbonAddHourTimeFormat($lateDuration, $late);
            }

            $i++;
        }

        return $lateDuration;
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function validateLogs()
    {
        $timeInCounts = $this->logs->where('dtr_log_type_id', 1)->count();
        $timeOutCounts = $this->logs->where('dtr_log_type_id', 2)->count();
    
        // if logs not complete then false
        if ($timeInCounts != $timeOutCounts) {
            return false;
        }

        return true; // success
    }

    public function shiftDetails($date)
    {
        $shiftDetails = null;
        
        $shift = $this->dtr->employee->employeeShiftSchedules()->date($date)->first();
        if ($shift) {
            $shiftDetails = $shift->details($date);
        }
        
        $changeShift = $this->dtr->employee->changeShiftSchedules()->date($date)->first();
        if ($changeShift) {
            // if todays date has employee changeshift then return that instead
            $shiftDetails = $changeShift->shiftSchedule()->first();
        }
        
        if ($shiftDetails) {
            $shiftDetails->date = $date;
            $dbRelativeDayStart = $shiftDetails->relative_day_start;
            unset($shiftDetails->relative_day_start); // i unset this obj. property and added again at the bottom to chnage order.
            $shiftDetails->db_relative_day_start = $dbRelativeDayStart; 
            $shiftDetails->start_working_at = null;
            $shiftDetails->end_working_at = null; // custom object
            $shiftDetails->relative_day_start = null;
            $shiftDetails->relative_day_end = null;

            if (!$shiftDetails->open_time) {

                // custom/added obj properties
                $shiftDetails->start_working_at = $date .' '.$shiftDetails->start_working_hours;
                $shiftDetails->end_working_at = $date .' '.$shiftDetails->end_working_hours;
                
                if (carbonInstance($shiftDetails->end_working_at)->lessThan($shiftDetails->start_working_at)) {
                    $shiftDetails->end_working_at = addDaysToDate($date) .' '.$shiftDetails->end_working_hours;
                }

                $shiftDetails->relative_day_start = $date . ' '.$dbRelativeDayStart;

                if (carbonInstance($shiftDetails->relative_day_start)->greaterThan($date.' '.$shiftDetails->start_working_at)) {
                    $shiftDetails->relative_day_start = subDaysToDate($date). ' '.$dbRelativeDayStart;
                }
                $shiftDetails->relative_day_end = carbonInstance($shiftDetails->relative_day_start)->addDay()->format('Y-m-d H:i');
            }else {
                // over shift is open time set WH and OH to null
                $shiftDetails->working_hours = null;
                $shiftDetails->overtime_hours = null;
            }


            // working_hours_with_date init
            $shiftDetails->working_hours_with_date = null;

            if ($shiftDetails->working_hours) {
                $shiftDetails->working_hours = $shiftDetails->working_hours['working_hours'];
                
                // assign value to working_hours_with_date
                $shiftDetails->working_hours_with_date = collect($shiftDetails->working_hours)
                    ->mapWithKeys(function ($item, $key) use ($date) {
            
                        $whStart =  $date .' '.$item['start'];
                        $whEnd =  $date .' '.$item['end'];
                        
                        if (carbonInstance($whEnd)->lessThan($whStart)) {
                            $whEnd = addDaysToDate($date) .' '.$item['end'];
                        }

                        return [
                            $key => [
                                'start' => $whStart,
                                'end' => $whEnd,
                            ]
                        ];
                    })->toArray();
                // end assign value to working_hours_with_date
            }

            if ($shiftDetails->overtime_hours) {
                $shiftDetails->overtime_hours = $shiftDetails->overtime_hours['overtime_hours'];
            }


            $detailsText = "";
            $detailsText .= "Name : $shiftDetails->name\n";
            $detailsText .= "Open Time : ".booleanOptions()[$shiftDetails->open_time]."\n";

            $detailsText .= "Working Hours :\n";
            if (count($shiftDetails->working_hours_in_array) > 0) {
                $temp = "   ".implode(",\n   ", $shiftDetails->working_hours_in_array);
                $detailsText .= $temp."\n";
            }

            $detailsText .= "Overtime Hours :\n";
            if (count($shiftDetails->overtime_hours_in_array) > 0) {
                $temp = "   ".implode(",\n   ", $shiftDetails->overtime_hours_in_array);
                $detailsText .= $temp."\n";
            }


            $detailsText .= "Dynamic Break : ".booleanOptions()[$shiftDetails->dynamic_break]."\n"; 
            $detailsText .= "Dynamic Break Credit : $shiftDetails->dynamic_break_credit\n";


            $detailsText .= "Relative Day Start : ".carbonDateTimeFormat($shiftDetails->relative_day_start)."\n";
            $detailsText .= "Relative Day End : ".carbonDateTimeFormat($shiftDetails->relative_day_end)."\n";
            
            $shiftDetails->details_text = $detailsText;
        }// end if $shiftDetails

        return $shiftDetails;  

    }


    /**
     * @param  orderBy: asc / desc
     * @return collection
     */
    public function logs($date = null, $logTypes = null, $orderBy = 'asc') 
    {
        $logs = null;
        $date = ($date == null) ? currentDate() : $date;
        $shiftToday = $this->shift;
        
        if ($logTypes == null) {
            $logTypes = dtrLogTypes();
        }else { 
            if (!is_array($logTypes)) {
                $logTypes = (array) $logTypes;
            }
        }

        if ($shiftToday) {
            if (!$shiftToday->open_time) {
                // !open_time
                $logs = $this->dtr->employee->dtrLogs()
                    ->with('dtrLogType')
                    ->whereBetween('log', [$shiftToday->relative_day_start, $shiftToday->relative_day_end])
                    ->whereIn('dtr_log_type_id', $logTypes);
            }else {
                // open_time
                $logs = $this->dtr->employee->dtrLogs()
                    ->with('dtrLogType')
                    ->whereDate('log', '=', $shiftToday->date)
                    ->whereIn('dtr_log_type_id', $logTypes);

                //deduct 1 day to date and if not open_time, be sure to add whereNotBetween to avoid retrieving prev. logs.
                $prevShift = $this->shiftDetails(subDaysToDate($shiftToday->date));
                if ($prevShift && !$prevShift->open_time) {
                    $logs = $logs->whereNotBetween('log', [$prevShift->relative_day_start, $prevShift->relative_day_end]);
                }

                // return compact('prevShift', 'shiftToday', 'logs'); // NOTE:: for debug only
            }
        }

        if ($logs) {
            return $logs->orderBy('log', $orderBy)->get();
        }

        return $logs;

    }
}
