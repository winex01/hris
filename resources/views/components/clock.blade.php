<li class="nav-item px-3 ml-2"><a class="nav-link text-white" href="#">
	<span class="btn btn-outline-secondary" id="clock" title="{{ __('Server Time') }}">{{ date('j. F  Y - g : i : s A') }}</span>
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
$('#clock').click(function() {
	// TODO:: here naku
	Swal.fire({
	    // icon: "info",
	    position: 'top',
	    showConfirmButton: false,
	    width: '300px',
	    html: `
	    	<p> {!! trans('lang.clock_title') !!} </p>
    		<button id="buttonIn" value="1" class="mb-1 btn btn-info btn-sm"> {!! trans('lang.clock_button_in') !!} </button>
			<button id="buttonOut" value="2" class="mb-1 btn btn-danger btn-sm"> {!! trans('lang.clock_button_out') !!} </button>
			<button id="buttonBreakStart" value="3" class="mb-1 btn btn-warning btn-sm"> {!! trans('lang.clock_button_break_start') !!} </button>
    		<button id="buttonBreakEnd" value="4" class="mb-1 btn btn-success btn-sm"> {!! trans('lang.clock_button_break_end') !!} </button>
	    `
  	});
	   
});
</script>
@endpush