<!-- This file is used to store topbar (left) items -->
<x-clock></x-clock>

{{-- TODO:: --}}
@if (emp() && emp()->clockLoggerButton()['show'])
	<li class="nav-item px-3 ml-n4">
		<button 
			@if (!emp()->clockLoggerButton()['in']) style="display:none;" @endif
			id="clockButtonIn" class="btn btn-info btn-md" onclick="loggedClock(1)"> {!! trans('lang.clock_button_in') !!}
		</button>

		<button 
			@if (!emp()->clockLoggerButton()['out']) style="display:none;" @endif
			id="clockButtonOut" class="btn-blink btn btn-danger btn-md ml-1" onclick="loggedClock(2)"> {!! trans('lang.clock_button_out') !!}
		</button>

		<button 
			@if (!emp()->clockLoggerButton()['breakStart']) style="display:none;" @endif
			id="clockButtonBreakStart" class="btn btn-success btn-md ml-1" onclick="loggedClock(3)"> {!! trans('lang.clock_button_break_start') !!}
		</button>

		<button 
			@if (!emp()->clockLoggerButton()['breakEnd']) style="display:none;" @endif
			id="clockButtonBreakEnd" class="btn-blink btn btn-danger btn-md ml-1" onclick="loggedClock(4)"> {!! trans('lang.clock_button_break_end') !!}
		</button> 
	</li>

	@push('after_scripts')
	{{-- NOTE:: blinking button --}}
	<style>
      @keyframes glowing {
		  0% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
		  50% { background-color: #FF0000; box-shadow: 0 0 10px #FF0000; }
		  100% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
		}
      .btn-blink {
        animation: glowing 1300ms infinite;
      }
    </style>
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
				    	// console.log(data);
			    		$('#clockButtonIn').hide();
			    		$('#clockButtonOut').hide();
			    		$('#clockButtonBreakStart').hide();
			    		$('#clockButtonBreakEnd').hide();

				    	if (data.clockLoggerButton.in) {
				    		$('#clockButtonIn').show();
				    	}

				    	if (data.clockLoggerButton.out) {
				    		$('#clockButtonOut').show();
				    	}

				    	if (data.clockLoggerButton.breakStart) {
				    		$('#clockButtonBreakStart').show();
				    	}

				    	if (data.clockLoggerButton.breakEnd) {
				    		$('#clockButtonBreakEnd').show();
				    	}

	           
				     Swal.fire({
						  icon: 'success',
						  text: data.text,
						  timer: 2000
						});
						
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