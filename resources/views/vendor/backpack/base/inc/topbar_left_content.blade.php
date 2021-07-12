<!-- This file is used to store topbar (left) items -->
<x-clock></x-clock>

{{-- TODO:: --}}
{{-- TODO:: add dialog confirm to the last OUT button --}}
@if (emp() && emp()->clockLoggerButton()['show'])
	<li class="nav-item px-3 ml-n4">
		@if (emp()->clockLoggerButton()['in'])
			<button id="clockButtonIn" class="btn btn-info btn-md" onclick="loggedClock(1)"> 
				{!! trans('lang.clock_button_in') !!}
			</button>
		@endif

		@if (emp()->clockLoggerButton()['out'])
			<button id="clockButtonOut" class="btn btn-danger btn-md ml-1" onclick="loggedClock(2)"> 
				{!! trans('lang.clock_button_out') !!}
			</button>
		@endif

		@if (emp()->clockLoggerButton()['breakStart'])
			<button id="clockButtonBreakStart" class="btn btn-danger btn-md ml-1" onclick="loggedClock(3)"> 
				{!! trans('lang.clock_button_break_start') !!}
			</button>
		@endif

		@if (emp()->clockLoggerButton()['breakEnd'])
			<button id="clockButtonBreakEnd" class="btn btn-danger btn-md ml-1" onclick="loggedClock(4)"> 
				{!! trans('lang.clock_button_break_end') !!}
			</button> 
		@endif
	</li>

	@push('after_scripts')
	<script type="text/javascript">
		function loggedClock(type) {
			$.ajax({
				url: '{{ route('dtrlogs.loggedClock') }}',
				type: 'post',
				data: {
					empId : '{{ emp()->id }}',
					type : type
				},
				success: function (data) {
					if (data) {
	                    window.swal({
				          title: data.text,
				          icon: "success",
				          timer: 2000,
				        });
				        
				    	console.log(data);
				    	// TODO:: 
					}
				},
				error: function () {
					window.location.href = "{{ request()->url() }}";
				}
			});
		}
	</script>
	@endpush
@endif