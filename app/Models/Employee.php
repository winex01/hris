<?php

namespace App\Models;

use App\Models\Model;

class Employee extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'employees';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    public static function boot() 
    {
        parent::boot();

        static::deleted(function($data) {
            if ($data->photo) {
                (new self)->deleteFileFromStorage($data, $data->photo);
            }
        });

        static::addGlobalScope('orderByFullName', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $orderBy = 'asc';
            $builder->orderBy('last_name', $orderBy);
            $builder->orderBy('first_name', $orderBy);
            $builder->orderBy('middle_name', $orderBy);
            $builder->orderBy('badge_id', $orderBy);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - A
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - B
    |--------------------------------------------------------------------------
    */
    public function bloodType()
    {
        return $this->belongsTo(\App\Models\BloodType::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - C
    |--------------------------------------------------------------------------
    */
    public function changeShiftSchedules()
    {
        return $this->hasMany(\App\Models\ChangeShiftSchedule::class);
    }

    public function citizenship()
    {
        return $this->belongsTo(\App\Models\Citizenship::class);
    }

    public function civilStatus()
    {
        return $this->belongsTo(\App\Models\CivilStatus::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - D
    |--------------------------------------------------------------------------
    */
    public function dailyTimeRecords()
    {
        return $this->hasMany(\App\Models\DailyTimeRecord::class);
    }

    public function dependents()
    {
        return $this->hasMany(\App\Models\Dependent::class);
    }

    public function dtrLogs()
    {
        return $this->hasMany(\App\Models\DtrLog::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - E
    |--------------------------------------------------------------------------
    */
    public function educationalBackgrounds()
    {
        return $this->hasMany(\App\Models\EducationalBackground::class);
    }

    public function employeeShiftSchedules()
    {
        return $this->hasMany(\App\Models\EmployeeShiftSchedule::class);
    }

    public function employmentInformation()
    {
        return $this->hasMany(\App\Models\EmploymentInformation::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - F
    |--------------------------------------------------------------------------
    */
    public function familyDatas()
    {
        return $this->hasMany(\App\Models\FamilyData::class);
    }
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS - G
    |--------------------------------------------------------------------------
    */
    public function gender()
    {
        return $this->belongsTo(\App\Models\Gender::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - H
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - I
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - J
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - K
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - L
    |--------------------------------------------------------------------------
    */
    public function leaveApplications()
    {
        return $this->hasMany(\App\Models\LeaveApplication::class);
    }

    public function leaveApprovers()
    {
        return $this->hasMany(\App\Models\LeaveApprover::class);
    }

    public function leaveCredits()
    {
        return $this->hasMany(\App\Models\LeaveCredit::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - M
    |--------------------------------------------------------------------------
    */
    public function medicalInformations()
    {
        return $this->hasMany(\App\Models\MedicalInformation::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - N
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - O
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - P
    |--------------------------------------------------------------------------
    */
    public function performanceAppraisals()
    {
        return $this->hasMany(\App\Models\PerformanceAppraisal::class);
    }

    public function professionalOrgs()
    {
        return $this->hasMany(\App\Models\ProfessionalOrg::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - Q
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - R
    |--------------------------------------------------------------------------
    */
     public function religion()
    {
        return $this->belongsTo(\App\Models\Religion::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - S
    |--------------------------------------------------------------------------
    */
    public function skillAndTalents()
    {
        return $this->hasMany(\App\Models\SkillAndTalent::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - T
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - U
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->hasOne(\App\Models\User::class);
    }
    

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - V
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - W
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - X
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - Y
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS - Z
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeOrderByFullName($query)
    {
        return $query->orderBy('last_name')
                ->orderBy('first_name')
                ->orderBy('middle_name')
                ->orderBy('badge_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getFullNameAttribute()
    {
        return $this->last_name.' '.$this->first_name.' '.$this->middle_name;
    }

    //alias of full_name
    public function getNameAttribute()
    {
        return $this->full_name_with_badge;
    }

    public function getFullNameWithBadgeAttribute()
    {
        return $this->full_name.' - ('.$this->badge_id.')';
    }

    public function getPhotoAttribute($value)
    {
        return ($value != null) ? 'storage/'.$value : $value;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setPhotoAttribute($value)
    {
        $attribute_name = 'photo';
        // or use your own disk, defined in config/filesystems.php
        $disk = 'public'; 
        // destination path relative to the disk above
        $destination_path = 'images/photo'; 

        $this->uploadImageToDisk($value, $attribute_name, $disk, $destination_path);
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function searchEmployeeNameLike($searchTerm) // this method is not a scope
    {
        return (new self())
                ->where('last_name', 'like', '%'.$searchTerm.'%')
                ->orWhere('first_name', 'like', '%'.$searchTerm.'%')
                ->orWhere('middle_name', 'like', '%'.$searchTerm.'%')
                ->orWhere('badge_id', 'like', '%'.$searchTerm.'%');
    }

    public function employeeNameAnchor()
    {
        return anchorNewTab(employeeInListsLinkUrl($this->id), $this->name);
    }

    /**
     * @param  orderBy: asc / desc
     * @return collection
     */
    public function logs($date = null, $logTypes = null, $orderBy = 'asc') 
    {
        $logs = null;
        $date = ($date == null) ? currentDate() : $date;
        $shiftToday = $this->shiftDetails($date); 
        
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
                $logs = $this->dtrLogs()
                    ->whereBetween('log', [$shiftToday->relative_day_start, $shiftToday->relative_day_end])
                    ->whereIn('dtr_log_type_id', $logTypes);
            }else {
                // open_time
                $logs = $this->dtrLogs()
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

    public function shiftDetails($date)
    {
        $shiftDetails = null;

        $shift = $this->employeeShiftSchedules()->date($date)->first();
        if ($shift) {
            $shiftDetails = $shift->details($date);
        }

        $changeShift = $this->changeShiftSchedules()->date($date)->first();
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

    public function shiftToday()
    {
        $date = currentDate();
        $currentShift = $this->shiftDetails($date);
        $prevShift = $this->shiftDetails(subDaysToDate($date, 1));
        $currentDateTime = currentDateTime();

        //return compact('currentDateTime', 'currentShift', 'prevShift'); // NOTE:: comment this, for debug only

        // currentShift not open_time
        if ($currentShift && !$currentShift->open_time) {
            $dayStart = $currentShift->relative_day_start;
            $dayEnd = $currentShift->relative_day_end;
            if (carbonInstance($currentDateTime)->betweenIncluded($dayStart, $dayEnd)) {
                return $currentShift;
            }
        }

        // prevShift not open_time
        if ($prevShift && !$prevShift->open_time) {
            $dayStart = $prevShift->relative_day_start;
            $dayEnd = $prevShift->relative_day_end;
            if (carbonInstance($currentDateTime)->betweenIncluded($dayStart, $dayEnd)) {
                return $prevShift;
            }
        }

        if ($currentShift) {
            // currentShift open_time
            if ($currentShift->open_time) {
                return $currentShift;
            }

            // prevShift open_time
            if ($prevShift && $prevShift->open_time) {
                return $prevShift;
            }
        }

        return;
    }

    /**
     * show or hide Employee Time Clock buttons.
     * @return associative array
     */
    public function timeClock()
    {
        $in         = false;
        $out        = false;
        $breakStart = false;
        $breakEnd   = false;
        $hasShift   = false;

        $shiftToday = $this->shiftToday();
        
        if ($shiftToday) {
            $logsToday = $this->logs($shiftToday->date, [1,2]); // 1 = in, 2 = OUT
            $breaksToday = $this->logs($shiftToday->date, [3,4]); // 3 = break start , 4 = break end 
            $hasShift = true;

            // in
            if (($logsToday->last() == null) || $logsToday->last()->dtr_log_type_id == 2) {
                $in = true;
            }

            // out
            if ($logsToday->last() && $logsToday->last()->dtr_log_type_id == 1) {
                $out = true;
            }
            
            // break start
            if ($out && $shiftToday->dynamic_break && $breaksToday->last() == null) {
                $breakStart = true;
            }
            
            // break end
            if ($out && $breaksToday->last() && $breaksToday->last()->dtr_log_type_id == 3) {    
                $breakEnd = true;
                $out = false;
            }
           
            // logs in/out limit
            $outLimit = count($shiftToday->working_hours);
            $logOuts = $this->logs($shiftToday->date, [2]); // 2 = Out
            $totalOutLogs = ($logOuts != null) ? count($logOuts) : 0; 
            if ($totalOutLogs >= $outLimit) {
                $in = false;
            }
        }

        return [
            'hasShift'   => $hasShift,
            'in'         => $in,
            'out'        => $out,
            'breakStart' => $breakStart,
            'breakEnd'   => $breakEnd,
        ];
    }

}
