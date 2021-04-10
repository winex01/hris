<li class="nav-item px-3 ml-2"><a class="nav-link text-white" href="#">
	<span style="cursor:auto;" class="btn btn-outline-secondary" id="clock" title="{{ __('Server Time') }}">{{ date('j. F  Y - g : i : s A') }}</span>
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
@endpush