<li class="nav-item px-3 ml-2"><a class="nav-link text-white" href="#">
	<span class="btn btn-outline-secondary clock" id="clock" title="{{ __('Server Time') }}">{{ date('j. F  Y - g : i : s A') }}</span>
</a></li> 
@push('after_scripts')
<script src="{{ asset('packages/moment/min/moment.min.js') }}"></script>

<script type="text/javascript">
	var crClockInit1 = null;
	var crClockInterval = null;
	function crInitClock() {
	    crClockInit1 = setInterval(function() {
	        if (moment().format("SSS") <= 40) {
	            clearInterval(crClockInit1);
	            crStartClockNow();
	        }
	    }, 30);
	}

   	var serverOffset = moment('{{ serverDateTime() }}').diff(new Date());

	function currentServerDate() {
    	return moment().add(serverOffset, 'milliseconds');
	}

	function crStartClockNow() {
	    crClockInterval = setInterval(function() {
	        $('#clock').text(
	        	currentServerDate().format('D. MMMM YYYY - h : mm : ss A')
        	);
	    }, 1000);
	}

	crInitClock(); // init to sync seconds
	crStartClockNow();
</script>

<script type="text/javascript">
$('.clock').click(function() {

	$.ajax({
		url: '{{ route('employeetimeclock.show') }}',
		type: 'POST',
		data: {emp: '{{ emp()->id }}'},
		success: function (data) {
			console.log(data);
			if (data.show) {
				var buttonIn = '';
				var buttonOut = '';
				var buttonBreakStart = '';
				var buttonBreakEnd = '';

				if (data.in) {
					buttonIn = `<button id="buttonIn" value="1" class="mb-1 btn btn-info btn-sm"> {!! trans('lang.clock_button_in') !!} </button> `; 
				}

				if (data.out) {
					buttonOut = `<button id="buttonOut" value="2" class="mb-1 btn btn-danger btn-sm"> {!! trans('lang.clock_button_out') !!} </button> `;
				}

				if (data.breakStart) {
					buttonBreakStart = `<button id="buttonBreakStart" value="3" class="mb-1 btn btn-warning btn-sm"> {!! trans('lang.clock_button_break_start') !!} </button> `;
				}

				if (data.breakEnd) {
					buttonBreakEnd = `<button id="buttonBreakEnd" value="4" class="mb-1 btn btn-success btn-sm"> {!! trans('lang.clock_button_break_end') !!} </button> `;
				}

				Swal.fire({
				    position: 'top',
				    showConfirmButton: false,
				    width: '300px',
				    html: `<p> {!! trans('lang.clock_title') !!} </p>` + buttonIn + buttonOut + buttonBreakStart + buttonBreakEnd
			  	});
			  	
			}// end if data
		}// end success
	});

});
</script>
@endpush