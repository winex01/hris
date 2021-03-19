<!-- This file is used to store topbar (left) items -->

<li class="nav-item px-3 ml-2"><a class="nav-link text-white" href="#">
	<span class="btn btn-outline-secondary" id="clock" title="{{ __('Server Time') }}">{{ date('d. F  Y - g : i A') }}</span>
</a></li> 

<li class="nav-item px-3 ml-n4">
	<button class="btn btn-info btn-sm"><i class="las la-clock"></i> IN &nbsp; &nbsp;</button>
	<button class="btn btn-secondary btn-sm ml-1"><i class="las la-stopwatch"></i> OUT</button>
	{{-- <button class="btn btn-danger btn-sm ml-1"><i class="las la-hourglass-start"></i> BREAK START</button> --}}
	{{-- <button class="btn btn-danger btn-sm ml-1"><i class="las la-hourglass-end"></i> BREAK END</button> --}}
</li>

@php
	// TODO:: create clock retrieved from backend and animete using jquery setInterval
	// TODO:: if employee current date shift schedule has dynamic break show Break start and break END
	// TODO:: if IN is enable OUT and others disable, if IN is disabled OUT and others is enable
@endphp


@push('after_scripts')
<script src="{{ asset('packages/fullcalendar/2.2.7/moment.min.js') }}"></script>
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

	function crStartClockNow() {
	    crClockInterval = setInterval(function() {
	        $('#clock').text(moment().format('D. MMMM YYYY - h : mm A'));
	    }, 1000);
	}

	crInitClock(); // init to sync seconds
	crStartClockNow();

</script>
@endpush