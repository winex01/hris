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

if (! function_exists('canView')) {
	/*
	*
	*/
	function canView($permission) {
		abort_unless(
			auth()->user()->can($permission.'_view'), 403
		);
	}

}

if (! function_exists('canCreate')) {
	/*
	*
	*/
	function canCreate($permission) {
		abort_unless(
			auth()->user()->can($permission.'_create'), 403
		);
	}

}

if (! function_exists('canEdit')) {
	/*
	*
	*/
	function canEdit($permission) {
		abort_unless(
			auth()->user()->can($permission.'_edit'), 403
		);
	}

}

if (! function_exists('canDelete')) {
	/*
	*
	*/
	function canDelete($permission) {
		abort_unless(
			auth()->user()->can($permission.'_delete'), 403
		);
	}

}
