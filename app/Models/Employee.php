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
   
    /**
     * @param  orderBy: asc / desc
     * @return collection
     */
    public function logsToday($orderBy = 'asc')
    {
        $logs = null;
        $shiftToday = $this->shiftToday();

        if ($shiftToday) {
            if (!$shiftToday->open_time) {
                // !open_time
                $logs = $this->dtrLogs()
                    ->whereBetween('log', [$shiftToday->relative_day_start, $shiftToday->relative_day_end])
                    ->whereIn('dtr_log_type_id', [1,2]); // 1 = IN, 2 = OUT
            }else {
                // open_time
                $logs = $this->dtrLogs()
                    ->whereDate('log', '=', $shiftToday->date)
                    ->whereIn('dtr_log_type_id', [1,2]); // 1 = IN, 2 = OUT

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
            $shiftDetails->start_working_hours = null;
            $shiftDetails->relative_day_start = null;
            $shiftDetails->relative_day_end = null;

            if (!$shiftDetails->open_time) {
                // custom/added obj properties
                $shiftDetails->start_working_hours = $date .' '.$shiftDetails->working_hours['working_hours'][0]['start'];
                $shiftDetails->relative_day_start = $date . ' '.$dbRelativeDayStart;

                if (carbonInstance($shiftDetails->relative_day_start)->greaterThan($shiftDetails->start_working_hours)) {
                    $shiftDetails->relative_day_start = subDaysToDate($date). ' '.$dbRelativeDayStart;
                }
                $shiftDetails->relative_day_end = carbonInstance($shiftDetails->relative_day_start)->addDay()->format('Y-m-d H:i');
            }else {
                // over shift is open time set WH and OH to null
                $shiftDetails->working_hours = null;
                $shiftDetails->overtime_hours = null;
            }

            if ($shiftDetails->working_hours) {
                $shiftDetails->working_hours = $shiftDetails->working_hours['working_hours'];
            }

            if ($shiftDetails->overtime_hours) {
                $shiftDetails->overtime_hours = $shiftDetails->overtime_hours['overtime_hours'];
            }
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
     * show or hide Time log buttons ex: IN / OUT / Break and Etc.
     * @return associative array booleans
     */
    public function clockLoggerButton()
    {
        $show       = false;
        $in         = 'disabled';
        $out        = 'disabled';
        $breakStart = 'disabled';
        $breakEnd   = 'disabled';

        $shiftToday = $this->shiftToday();
        $logsToday = $this->logsToday();
        $totalInOutLOgs = ($logsToday) ? count($logsToday->all()) : 0;
        $totalAcceptableLogs = 0;

        if ($shiftToday) { // TODO:: settings permission add as &&
            $show = true;
            if ($shiftToday->open_time) {
                $totalAcceptableLogs = 2; // if open time the default total acceptable logs is 2
            }else { // !open_time
                // total for IN / OUT log type only
                $totalAcceptableLogs = count($shiftToday->working_hours) * 2; // mult. by 2 bec. its pair
            }

            // TODO::        
            if ($totalInOutLOgs < $totalAcceptableLogs) {
                // TODO:: in
                // TODO:: out
            }
        }

        // TODO:: break start
        // TODO:: break end
        return [
            'show'       => $show,
            'in'         => $in,
            'out'        => $out,
            'breakStart' => $breakStart,
            'breakEnd'   => $breakEnd,
        ];
    }

}
