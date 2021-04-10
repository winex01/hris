<!-- This file is used to store topbar (left) items -->
<x-clock></x-clock>

@php
	$employee = auth()->user()->employee;
	
	// total array count multiple by 2 bec. it is pairs, for todays shift.
	$totalAcceptableLogs = count($employee->shiftToday()->wh) * 2; 

	// TODO:: show or hide in/out button using totalAcceptableLogs
@endphp

@if ($employee)
	<li class="nav-item px-3 ml-n4">
		<button id="timeIn" class="btn btn-info btn-sm"><i class="las la-clock"></i> IN &nbsp; &nbsp;</button>
		<button id="timeOut" class="btn btn-secondary btn-sm ml-1"><i class="las la-stopwatch"></i> OUT</button>

		@if ($employee->shiftToday()->dynamic_break)
			<button id="timeBreakStart" class="btn btn-danger btn-sm ml-1"><i class="las la-hourglass-start"></i> BREAK START</button>
			<button id="timeBreakEnd" class="btn btn-danger btn-sm ml-1"><i class="las la-hourglass-end"></i> BREAK END</button>
		@endif
	</li>
@endif

@php
	// TODO:: if IN is enable OUT and others disable, if IN is disabled OUT and others is enable - enable/disable = hide/show
@endphp

@push('after_scripts')
<script type="text/javascript">
	$('#timeIn').click(function(event) {
		
	});
</script>
@endpush

