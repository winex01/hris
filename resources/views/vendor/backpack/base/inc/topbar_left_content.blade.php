<!-- This file is used to store topbar (left) items -->
<x-clock></x-clock>

@php
	$employee = auth()->user()->employee;
@endphp

@if ($employee)
	<li class="nav-item px-3 ml-n4">
		<button class="btn btn-info btn-sm"><i class="las la-clock"></i> IN &nbsp; &nbsp;</button>
		<button class="btn btn-secondary btn-sm ml-1"><i class="las la-stopwatch"></i> OUT</button>

		@if ($employee->shiftToday()->dynamic_break)
			<button class="btn btn-danger btn-sm ml-1"><i class="las la-hourglass-start"></i> BREAK START</button>
			<button class="btn btn-danger btn-sm ml-1"><i class="las la-hourglass-end"></i> BREAK END</button>
		@endif
	</li>
@endif

@php
	// TODO:: create migration for dtr logs
	// TODO:: if IN is enable OUT and others disable, if IN is disabled OUT and others is enable - enable/disable = hide/show

	// log
	// type -FK: IN,OUT,BREAK IN, BREAK OUT
	// desc - for edit reasons
@endphp


