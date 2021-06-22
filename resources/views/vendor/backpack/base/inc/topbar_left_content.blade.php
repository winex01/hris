<!-- This file is used to store topbar (left) items -->
<x-clock></x-clock>

{{-- TODO:: --}}
{{-- @php
	$employee = auth()->user()->employee;
	
	// total array count multiply by 2 bec. it is pairs
	// $totalAcceptableLogs = count($employee->shiftToday()->wh) * 2; 
@endphp

@if ($employee->shiftToday())
	<li class="nav-item px-3 ml-n4">
		<button onclick="loggedClock(1)" class="btn btn-info btn-sm"><i class="las la-clock"></i> IN &nbsp; &nbsp;</button>
		<button onclick="loggedClock(2)" class="btn btn-secondary btn-sm ml-1"><i class="las la-stopwatch"></i> OUT</button>

		@if ($employee->shiftToday()->dynamic_break)
			<button onclick="loggedClock(3)" class="btn btn-danger btn-sm ml-1"><i class="las la-hourglass-start"></i> BREAK START</button>
			<button onclick="loggedClock(4)" class="btn btn-danger btn-sm ml-1"><i class="las la-hourglass-end"></i> BREAK END</button>
		@endif
	</li>
@endif

@push('after_scripts')
<script type="text/javascript">
	function loggedClock(type) {
		$.ajax({
			url: '{{ route('dtrlogs.loggedClock') }}',
			type: 'post',
			data: {
				empId : '{{ $employee->id }}',
				type : type
			},
			success: function (data) {
				if (data) {
					new Noty({
                        type: data.alertColor,
                        text: data.text
                    }).show();
				}
			}
		});
	}
</script>
@endpush --}}

