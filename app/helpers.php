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
| Model / DB related
|--------------------------------------------------------------------------
*/
if (! function_exists('booleanOptions')) {
	function booleanOptions() {
		return [
            0   => 'No',
            1   => 'Yes'
        ];
	}
}

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

if (! function_exists('scopeInstance')) {
	function scopeInstance($class) {
		$class = str_replace('_id','', $class);
        $class = ucfirst(\Str::camel($class));
        $class = "\\App\\Scopes\\".$class;
        
        return new $class;
	}
}

if (! function_exists('employeeLists')) {
	function employeeLists() {
        return \App\Models\Employee::
	        orderBy('last_name')
	        ->orderBy('first_name')
	        ->orderBy('middle_name')
	        ->orderBy('badge_id')
	        ->get(['id', 'last_name', 'first_name', 'middle_name', 'badge_id'])
	        ->pluck("name", "id")
	        ->toArray();
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
					config('hris.decimal_precision')
				);
	}
}

/*
|--------------------------------------------------------------------------
| Date related stuff
|--------------------------------------------------------------------------
*/
if (! function_exists('currentDate')) {
	function currentDate($format = 'Y-m-d') {
		return date($format);
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
