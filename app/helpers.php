<?php 

 /*
|--------------------------------------------------------------------------
| Roles And Permissions
|--------------------------------------------------------------------------
*/
if (! function_exists('authorize')) {
	function authorize($permission) {
		abort_unless(
			auth()->user()->can($permission), 403
		);
	}
}

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
| String
|--------------------------------------------------------------------------
*/
if (! function_exists('strSingular')) {
	function strSingular($str) {
		return \Illuminate\Support\Str::singular($str);
	}
}

if (! function_exists('strPlural')) {
	function strPlural($str) {
		return \Illuminate\Support\Str::plural($str);
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
		];
	}
}

if (! function_exists('getTableColumnsWithDataType')) {
	function getTableColumnsWithDataType($tableName, $removeOthers = null, $tableSchema = null) {
		if ($tableSchema == null) {
			$tableSchema = env('DB_DATABASE');
		}

		$results = \DB::select("
			SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'hris' AND TABLE_NAME = '$tableName' 
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


if (! function_exists('getModelAttributes')) {
	function getModelAttributes($instance, $removeOthers = null) {
		$data = \Schema::getColumnListing(
			($instance)->getTable()
		);

		$remove = removeCommonTableColumn();

		if ($removeOthers != null) {
			$remove = array_merge($remove, $removeOthers);
		}

		$data = collect($data)->filter(function ($value) use ($remove) {
			return !in_array($value, $remove);
		}); 

		return $data;
	}
}

if (! function_exists('removeModelAttributesOf')) {
	function removeModelAttributesOf($inputs, $instance) {
		return collect($inputs)->forget(
			getModelAttributes($instance)
		)->toArray();
	}
}

if (! function_exists('getOnlyAttributesFrom')) {
	function getOnlyAttributesFrom($array, $instance) {
		 return collect($array)->only(
		 	getModelAttributes($instance)
		 )->toArray();
	}
}

if (! function_exists('collectOnlyModelAttributes')) {
	function collectOnlyModelAttributes($array, $instance) {
		 return collect($array)
				->only(getModelAttributes($instance))
				->toArray();
	}
}

/*
|--------------------------------------------------------------------------
| Arrays
|--------------------------------------------------------------------------
*/
if (! function_exists('removeArrayKeys')) {
	function removeArrayKeys($data, $removeKeys) {
		 return collect($data)
				->diffKeys($removeKeys)
				->toArray();
	}
}

if (! function_exists('flipArray')) {
	function flipArray($array) {
		 return collect($array)
				->flip()
				->toArray();
	}
}

if (! function_exists('collectOnly')) {
	function collectOnly($array, $array2) {
		 return collect($array)
				->only($array2)
				->toArray();
	}
}

if (! function_exists('arrayMerge')) {
	function arrayMerge($data, $others = null) {
		if ($others != null) {
            $data = collect($data)->merge($others)->toArray();
        }

        return $data;
	}
}



