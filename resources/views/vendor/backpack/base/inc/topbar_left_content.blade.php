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
				    			$('#clockButtonIn').removeClass('{{ trans('lang.clock_button_disable_color') }}');
				    			$('#clockButtonIn').addClass('{{ trans('lang.clock_button_enable_color') }}');
				    		}else {
				    			//disable IN
				    			{!! disableButton('clockButtonIn') !!}
				    			$('#clockButtonIn').removeClass('{{ trans('lang.clock_button_enable_color') }}');
				    			$('#clockButtonIn').addClass('{{ trans('lang.clock_button_disable_color') }}');
				    		}

				    		// OUT 
				    		if (data.clockLoggerButton.out) {
				    			//enable OUT
				    			{!! enableButton('clockButtonOut') !!}
				    			$('#clockButtonOut').removeClass('{{ trans('lang.clock_button_disable_color') }}');
				    			$('#clockButtonOut').addClass('{{ trans('lang.clock_button_enable_color') }}');
				    		}else {
				    			//disable OUT
				    			{!! disableButton('clockButtonOut') !!}
				    			$('#clockButtonOut').removeClass('{{ trans('lang.clock_button_enable_color') }}');
				    			$('#clockButtonOut').addClass('{{ trans('lang.clock_button_disable_color') }}');
				    		}

				    		// BREAK START 
				    		if (data.clockLoggerButton.breakStart) {
				    			//enable BREAK START
				    			{!! enableButton('clockButtonBreakStart') !!}
				    			$('#clockButtonBreakStart').removeClass('{{ trans('lang.clock_button_disable_color') }}');
				    			$('#clockButtonBreakStart').addClass('{{ trans('lang.clock_button_enable_color') }}');
				    		}else {
				    			//disable BREAK START
				    			{!! disableButton('clockButtonBreakStart') !!}
				    			$('#clockButtonBreakStart').removeClass('{{ trans('lang.clock_button_enable_color') }}');
				    			$('#clockButtonBreakStart').addClass('{{ trans('lang.clock_button_disable_color') }}');
				    		}

				    		// BREAK END 
				    		if (data.clockLoggerButton.breakEnd) {
				    			//enable BREAK END
				    			{!! enableButton('clockButtonBreakEnd') !!}
				    			$('#clockButtonBreakEnd').removeClass('{{ trans('lang.clock_button_disable_color') }}');
				    			$('#clockButtonBreakEnd').addClass('{{ trans('lang.clock_button_enable_color') }}');
				    		}else {
				    			//disable BREAK END
				    			{!! disableButton('clockButtonBreakEnd') !!}
				    			$('#clockButtonBreakEnd').removeClass('{{ trans('lang.clock_button_enable_color') }}');
				    			$('#clockButtonBreakEnd').addClass('{{ trans('lang.clock_button_disable_color') }}');
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