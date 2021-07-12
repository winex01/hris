<!-- This file is used to store topbar (left) items -->
<x-clock></x-clock>

{{-- TODO:: --}}
@if (emp()->clockLoggerButton()['show'])
	<li class="nav-item px-3 ml-n4">
		
		<button id="clockButtonIn" 
			{{ emp()->clockLoggerButton()['in'] ? '' : 'disabled' }}
			class="btn {{ emp()->clockLoggerButton()['in'] ? trans('lang.clock_button_enable_color') : trans('lang.clock_button_disable_color') }} btn-md" onclick="loggedClock(1)">
			{!! trans('lang.clock_button_in') !!}
		</button>
		<button id="clockButtonOut"
			{{ emp()->clockLoggerButton()['out'] ? '' : 'disabled' }}
			class="btn {{ emp()->clockLoggerButton()['out'] ? trans('lang.clock_button_enable_color') : trans('lang.clock_button_disable_color') }} btn-md ml-1" onclick="loggedClock(2)">
			{!! trans('lang.clock_button_out') !!}
		</button>

		{{-- TODO:: --}}
		{{-- <button id="clockButtonBreakStart"
			{{ emp()->clockLoggerButton()['breakStart'] ? '' : 'disabled' }}
			onclick="loggedClock(3)" class="btn btn-danger btn-md ml-1"><i class="las la-hourglass-start"></i> BREAK START
		</button>
		<button id="clockButtonBreakEnd"
			{{ emp()->clockLoggerButton()['breakEnd'] ? '' : 'disabled' }}
			onclick="loggedClock(4)" class="btn btn-danger btn-md ml-1"><i class="las la-hourglass-end"></i> BREAK END
		</button>  --}}
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
				        
				    	// console.log(data);
				    	if (data.clockLoggerButton) {
				    		// IN 
				    		if (data.clockLoggerButton.in) {
				    			//enable IN
				    			{!! enableButton('clockButtonIn') !!}
				    			$('#clockButtonIn').removeClass('btn-secondary');
				    			$('#clockButtonIn').addClass('btn-info');
				    		}else {
				    			//disable IN
				    			{!! disableButton('clockButtonIn') !!}
				    			$('#clockButtonIn').removeClass('btn-info');
				    			$('#clockButtonIn').addClass('btn-secondary');
				    		}

				    		// OUT 
				    		if (data.clockLoggerButton.out) {
				    			//enable OUT
				    			{!! enableButton('clockButtonOut') !!}
				    			$('#clockButtonOut').removeClass('btn-secondary');
				    			$('#clockButtonOut').addClass('btn-info');
				    		}else {
				    			//disable OUT
				    			{!! disableButton('clockButtonOut') !!}
				    			$('#clockButtonOut').removeClass('btn-info');
				    			$('#clockButtonOut').addClass('btn-secondary');
				    		}

				    		// BREAK START 
				    		if (data.clockLoggerButton.breakStart) {
				    			//enable BREAK START
				    			{!! enableButton('clockButtonBreakStart') !!}
				    			$('#clockButtonBreakStart').removeClass('btn-secondary');
				    			$('#clockButtonBreakStart').addClass('btn-info');
				    		}else {
				    			//disable BREAK START
				    			{!! disableButton('clockButtonBreakStart') !!}
				    			$('#clockButtonBreakStart').removeClass('btn-info');
				    			$('#clockButtonBreakStart').addClass('btn-secondary');
				    		}

				    		// BREAK END 
				    		if (data.clockLoggerButton.breakEnd) {
				    			//enable BREAK END
				    			{!! enableButton('clockButtonBreakEnd') !!}
				    			$('#clockButtonBreakEnd').removeClass('btn-secondary');
				    			$('#clockButtonBreakEnd').addClass('btn-info');
				    		}else {
				    			//disable BREAK END
				    			{!! disableButton('clockButtonBreakEnd') !!}
				    			$('#clockButtonBreakEnd').removeClass('btn-info');
				    			$('#clockButtonBreakEnd').addClass('btn-secondary');
				    		}

				    	}
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