<?php 

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
		$col = str_replace('_id', '', $col);
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