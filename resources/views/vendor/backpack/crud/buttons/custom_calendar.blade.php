@if ($crud->hasAccess('calendar'))
	<a href="{{ url($crud->route.'/calendar') }}" class="btn btn-outline-primary" data-style="zoom-in">
		<span class="ladda-label">
			<i class="las la-business-time"></i> 
			{{ trans('lang.calendar_operation') }}
		</span>
	</a>
@endif

