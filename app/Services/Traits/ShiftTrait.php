<?php 

namespace App\Services\Traits;


trait ShiftTrait
{
    // * NOTE:: Require employee property instance
    public function shiftDetails($date)
    {
        $shiftDetails = null;
        
        $shift = $this->employee->employeeShiftSchedules()->date($date)->first();
        if ($shift) {
            $shiftDetails = $shift->details($date);
        }
        
        $changeShift = $this->employee->changeShiftSchedules()->date($date)->first();
        if ($changeShift) {
            // if todays date has employee changeshift then return that instead
            $shiftDetails = $changeShift->shiftSchedule()->first();
        }
        
        // if no shift schedule return null
        if (!$shiftDetails) {
            return;
        }

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
    
        // if shift not open time, add wh and oh in details
        if (!$shiftDetails->open_time) {
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
        }

        $detailsText .= "Dynamic Break : ".booleanOptions()[$shiftDetails->dynamic_break]."\n"; 
        $detailsText .= "Dynamic Break Credit : $shiftDetails->dynamic_break_credit\n";

        // if shift not open time
        if (!$shiftDetails->open_time) {
            $detailsText .= "Relative Day Start : ".carbonDateTimeFormat($shiftDetails->relative_day_start)."\n";
            $detailsText .= "Relative Day End : ".carbonDateTimeFormat($shiftDetails->relative_day_end)."\n";
        }
        
        $shiftDetails->details_text = $detailsText;

        return $shiftDetails;  
    }
}