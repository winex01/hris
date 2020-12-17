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
| Model
|--------------------------------------------------------------------------
*/
if (! function_exists('removeCommonTableColumn')) {
	function removeCommonTableColumn() {
		return [
			'id',
			'created_at',
			'updated_at',
			'deleted_at',
			'personable_id',
			'personable_type',
		];
	}
}

if (! function_exists('getTableColumnsWithDataType')) {
	function getTableColumnsWithDataType($tableName, $removeOthers = null, $tableSchema = null) {
		if ($tableSchema == null) {
			$tableSchema = \Config::get('database.connections.'.Config::get('database.default'))['database'];
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
	function classInstance($class) {
		// remove App\Models\ so i could have choice
		// to provide it in parameter
		$class = str_replace('App\\Models\\','', $class);

		$class = str_replace('_id','', $class);
        $class = ucfirst(\Str::camel($class));
        $class = "\\App\\Models\\".$class;
        
        return new $class;
	}
}

if (! function_exists('stringContains')) {
	function stringContains($myString, $word) {
		return strpos($myString, $word) !== false;
	}
}