<?php 

if (! function_exists('authorize')) {
	/*
	*
	*/
	function authorize($permission) {
		abort_unless(
			auth()->user()->can($permission), 403
		);
	}

}

if (! function_exists('hasAuthority')) {
	/*
	*
	*/
	function hasAuthority($permission) {

		return auth()->user()->can($permission);

	}

}

if (! function_exists('hasNoAuthority')) {
	/*
	*
	*/
	function hasNoAuthority($permission) {

		return !hasAuthority($permission);
	}

}

if (! function_exists('strSingular')) {
	/*
	*
	*/
	function strSingular($str) {

		return \Illuminate\Support\Str::singular($str);
	}

}

if (! function_exists('strPlural')) {
	/*
	*
	*/
	function strPlural($str) {

		return \Illuminate\Support\Str::plural($str);
	}

}