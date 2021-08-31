
<?php 

use Illuminate\Support\Carbon;

 /*
|--------------------------------------------------------------------------
| Roles And Permissions
|--------------------------------------------------------------------------
*/
if (! function_exists('hasAuthority')) {
	function hasAuthority($permission) {
		return auth()->user()->can($permission);
	}
}

if (! function_exists('hasNoAuthority')) {
	function hasNoAuthority($permission) {
		return !hasAuthority($permission);
	}
}

/*
|--------------------------------------------------------------------------
| Logs
|--------------------------------------------------------------------------
*/
if (! function_exists('enableQueryLog')) {
	function enableQueryLog() {
		return \DB::enableQueryLog();
	}
}

if (! function_exists('dumpQuery')) {
	function dumpQuery() {
		dd(DB::getQueryLog());
	}
}

/*
|--------------------------------------------------------------------------
| DB related
|--------------------------------------------------------------------------
*/
if (! function_exists('removeCommonTableColumn')) {
	function removeCommonTableColumn() {
		return [
			'id',
			'created_at',
			'updated_at',
			'deleted_at',
			'crud',
		];
	}
}

if (! function_exists('getTableColumnsWithDataType')) {
	function getTableColumnsWithDataType($tableName, $removeOthers = null, $tableSchema = null) {
		if ($tableSchema == null) {
			$tableSchema = config('database.connections.'.config('database.default'))['database'];
		}

		$results = \DB::select("
			SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$tableSchema' AND TABLE_NAME = '$tableName' 
			ORDER BY ORDINAL_POSITION ASC
		");

		$data = [];
		foreach ($results as $row) {
			$data[$row->COLUMN_NAME] = $row->DATA_TYPE;
		}

		$remove = removeCommonTableColumn();

		if ($removeOthers != null) {
			$remove = array_merge($remove, $removeOthers);
		}

		$data = collect($data)->filter(function ($dataType, $column) use ($remove) {
			return !in_array($column, $remove);
		})->toArray(); 

		return $data;
	}//end func
}

if (! function_exists('getTableColumns')) {
	function getTableColumns($tableName, $removeOthers = null, $tableSchema = null) {
		$data = getTableColumnsWithDataType($tableName, $removeOthers, $tableSchema);
		return collect($data)->keys()->toArray();
	}
}

/*
|--------------------------------------------------------------------------
| Create Instance Related
|--------------------------------------------------------------------------
*/
if (! function_exists('classInstance')) {
	function classInstance($class, $useFullPath = false) {
		if ($useFullPath) {
			return new $class;
		}

		// remove App\Models\ so i could have choice
		// to provide it in parameter
		$class = str_replace('App\\Models\\','', $class);

		$class = str_replace('_id','', $class);
        $class = ucfirst(\Str::camel($class));
        $class = "\\App\\Models\\".$class;
        
        return new $class;
	}
}

if (! function_exists('modelInstance')) {
	function modelInstance($class) {
		$class = str_replace('_id','', $class);
        $class = ucfirst(\Str::camel($class));
        $class = "\\App\\Models\\".$class;
        
        return new $class;
	}
}

if (! function_exists('scopeInstance')) {
	function scopeInstance($class) {
		$class = str_replace('_id','', $class);
        $class = ucfirst(\Str::camel($class));
        $class = "\\App\\Scopes\\".$class;
        
        return new $class;
	}
}

if (! function_exists('crudInstance')) {
	function crudInstance($class) {
		$class = str_replace('_id','', $class);
        $class = ucfirst(\Str::camel($class));
        $class = "\\App\\Http\\Controllers\\Admin\\".$class;
        
        return new $class;
	}
}


/*
|--------------------------------------------------------------------------
| Employee Related
|--------------------------------------------------------------------------
*/
if (! function_exists('employeeLists')) {
	function employeeLists() {
        return modelInstance('Employee')
	        ->orderBy('last_name')
	        ->orderBy('first_name')
	        ->orderBy('middle_name')
	        ->orderBy('badge_id')
	        ->get(['id', 'last_name', 'first_name', 'middle_name', 'badge_id'])
	        ->pluck("name", "id")
	        ->toArray();
	}
}

/**
 * @param none
 * @return currently logged employee details
 */
if (! function_exists('loggedEmployee')) {
	function loggedEmployee() {
		return auth()->user()->employee;
	}
}

/**
 * short alias for loggedEmployee 
 */
if (! function_exists('emp')) {
	function emp() {
		return loggedEmployee();
	}
}

/*
|--------------------------------------------------------------------------
| Payroll Related
|--------------------------------------------------------------------------
*/
if (! function_exists('openPayrollDetails')) {
	function openPayrollDetails() {
		// get payroll period first start and payroll period last end
		$temp = modelInstance('PayrollPeriod')
		  ->open()
		  ->selectRaw('MIN(payroll_start) as date_start')
		  ->selectRaw('MAX(payroll_end) as date_end')
		  ->selectRaw('GROUP_CONCAT(grouping_id) as grouping_ids')
		  ->first();

		// add 1 day to payroll end to include time exceed to date ex; aug. 31 08:30
		$temp->date_end = addDaysToDate($temp->date_end);

		// use this employee id lists in condition in view/table
		$temp->employee_ids = modelInstance('EmploymentInformation')
		  ->grouping(explode(',', $temp->grouping_ids))
		  ->pluck('employee_id')
		  ->toArray();

		return $temp;
	}
}

//get grouping ids with payroll name as description
if (! function_exists('openPayrollGroupingIds')) {
	function openPayrollGroupingIds() {
		return modelInstance('PayrollPeriod')
		  ->open()
		  ->pluck('grouping_id', 'name')
		  ->all();
	}
}

/*
|--------------------------------------------------------------------------
| Backpack Related
|--------------------------------------------------------------------------
*/
if (! function_exists('disableLineButtons')) {
	function disableLineButtons($crud) {
		$crud->denyAccess('calendar');
        $crud->denyAccess('show');
        $crud->denyAccess('update');
        $crud->denyAccess('delete');
        $crud->denyAccess('bulkDelete');
        $crud->denyAccess('forceDelete');
        $crud->denyAccess('forceBulkDelete');
        $crud->denyAccess('revise');
	}
}

if (! function_exists('booleanOptions')) {
	function booleanOptions() {
		return [
            0   => 'No',
            1   => 'Yes'
        ];
	}
}

/*
|--------------------------------------------------------------------------
| String related stuff
|--------------------------------------------------------------------------
*/
if (! function_exists('stringContains')) {
	function stringContains($myString, $needle) {
		return strpos($myString, $needle) !== false;
	}
}

if (! function_exists('startsWith')) {
	function startsWith($haystack, $needle) {
	    return substr_compare($haystack, $needle, 0, strlen($needle)) === 0;
	}
}

if (! function_exists('endsWith')) {
	function endsWith($haystack, $needle) {
	    return substr_compare($haystack, $needle, -strlen($needle)) === 0;
	}
}


if (! function_exists('relationshipMethodName')) {
	function relationshipMethodName($col) {
		$method = str_replace('_id', '', $col);
		$method = \Str::camel($method);
		
		return $method;
	}
}

if (! function_exists('convertToClassName')) {
	function convertToClassName($str) {
		$str = relationshipMethodName($str); 
		return ucfirst($str);
	}
}

if (! function_exists('convertColumnToHumanReadable')) {
	function convertColumnToHumanReadable($col) {
		$col = \Str::snake($col);
		
		$col = endsWith($col, '_id') ? str_replace('_id', '', $col) : $col;

        $col = str_replace('_', ' ', $col);
        $col = ucwords($col);

        return $col;
	}
}

if (! function_exists('convertToTitle')) {
	function convertToTitle($string) {
		$string = str_replace('_', ' ', $string);
        $string = ucwords($string);

        return $string;
	}
}

if (! function_exists('phoneNumberRegex')) {
	function phoneNumberRegex() {
		return 'regex:/^([0-9\s\-\+\(\).]*)$/';
	}
}

if (! function_exists('convertKbToMb')) {
	function convertKbToMb($kb) {
		return $kb / 1000;
	}
}

/*
|--------------------------------------------------------------------------
| Number related stuff
|--------------------------------------------------------------------------
*/
if (! function_exists('pesoCurrency')) {
	function pesoCurrency($value) {
		return trans('lang.currency').
			number_format(
				$value, 
				config('appsettings.decimal_precision')
			);
	}
}

/*
|--------------------------------------------------------------------------
| Date / Time Related Stuff
|--------------------------------------------------------------------------
*/
if (! function_exists('currentDateTime')) {
	function currentDateTime($withSeconds = true) {
		return currentDate().' '.currentTime($withSeconds);
	}
}

/*
	List of carbonInstance usefull functions:
	->betweenIncluded($first, $second));
	->betweenExcluded($first, $second));
	->equalTo($second)); 
	->notEqualTo($second));  
	->greaterThan($second));  
	->greaterThanOrEqualTo($second));
	->lessThan($second)); 
	->lessThanOrEqualTo($second));
*/
if (! function_exists('carbonInstance')) {
	function carbonInstance($dateTime) {
		return Carbon::create($dateTime);
	}
}

if (! function_exists('carbonTime')) {
	function carbonTime($time) {
		return Carbon::createFromFormat('H:i', $time);
	}
}

if (! function_exists('subHoursToTime')) {
	function subHoursToTime($time, $n = 1) {
		return Carbon::createFromFormat('H:i', $time)->subHours($n)->format('H:i');
	}
}

if (! function_exists('subMinutesToTime')) {
	function subMinutesToTime($time, $n = 1) {
		return Carbon::createFromFormat('H:i', $time)->subMinutes($n)->format('H:i');
	}
}

if (! function_exists('subMinutesToTimestamp')) {
	function subMinutesToTimestamp($timestamp, $n = 1) {
		// timestamp ex: '2021-06-25 12:20'
		return Carbon::create($timestamp)->subMinutes($n)->format('Y-m-d H:i');
	}
}

if (! function_exists('serverDateTime')) {
	function serverDateTime() {
		return date('Y-m-d H:i:s');
	}
}

if (! function_exists('currentTime')) {
	function currentTime($withSeconds = true) {
		
		if (!$withSeconds) {
			return date('H:i');
		}

		return date('H:i:s');
	}
}

if (! function_exists('currentDate')) {
	function currentDate($format = 'Y-m-d') {
		return date($format);
	}
}

if (! function_exists('daysOfWeek')) {
	function daysOfWeek() {
		return [
            'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'
        ];
	}
}

if (! function_exists('getWeekday')) {
	function getWeekday($date) {
		// NOTE:: 0 - Sun, 1 - Mon and so on..
	    return date('w', strtotime($date));
	}
}

if (! function_exists('addMonthsToDate')) {
	function addMonthsToDate($date, $n = 1) {
		return Carbon::createFromDate($date)->addMonth($n)->format('Y-m-d');
	}
}

if (! function_exists('addDaysToDate')) {
	function addDaysToDate($date, $n = 1) {
		return Carbon::createFromDate($date)->addDays($n)->format('Y-m-d');
	}
}

if (! function_exists('subDaysToDate')) {
	function subDaysToDate($date, $n = 1) {
		return Carbon::createFromDate($date)->subDays($n)->format('Y-m-d');
	}
}

if (! function_exists('defaultFullCalendarOptions')) {
	function defaultFullCalendarOptions($addOns = []) {
		$option = [
            'header' => [
                'left' => 'prev,next today',
                'center' => 'title',
                'right' => 'month,basicWeek',
            ],
            'buttonText' => [
                'today' => 'Today',
                'month' => 'Month',
                'week'  => 'Week',
            ]
        ];

        return array_merge($option, $addOns);
	}
}

/*
|--------------------------------------------------------------------------
| Misc. or Views/html/blade files helper
|--------------------------------------------------------------------------
*/

/**
 * enable button in views using ID
 */
if (! function_exists('enableButton')) {
	function enableButton($id) {
		return '$("#'.$id.'").removeAttr("disabled");';
	}
}

/**
 * disable button in views using ID
 */
if (! function_exists('disableButton')) {
	function disableButton($id) {
		return '$("#'.$id.'").prop("disabled", true);';		
	}
}

// not really db query but string url
if (! function_exists('urlQuery')) {
	function urlQuery() {
		$data = \Request::query();
		unset($data['persistent-table']);
		
		return $data;
	}
}

if (! function_exists('isJson')) {
	function isJson($string) {
		json_decode($string);
     	return (json_last_error() == JSON_ERROR_NONE);
	}
}