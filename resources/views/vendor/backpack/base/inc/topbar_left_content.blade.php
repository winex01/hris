<!-- This file is used to store topbar (left) items -->
<x-clock></x-clock>

@if (auth()->user()->employee_id)
	<li class="nav-item px-3 ml-n4">
		<button class="btn btn-info btn-sm"><i class="las la-clock"></i> IN &nbsp; &nbsp;</button>
		<button class="btn btn-secondary btn-sm ml-1"><i class="las la-stopwatch"></i> OUT</button>
		{{-- <button class="btn btn-danger btn-sm ml-1"><i class="las la-hourglass-start"></i> BREAK START</button> --}}
		{{-- <button class="btn btn-danger btn-sm ml-1"><i class="las la-hourglass-end"></i> BREAK END</button> --}}
	</li>
@endif

@php
	// TODO:: if employee current date shift schedule has dynamic break show Break start and break END
	// TODO:: if IN is enable OUT and others disable, if IN is disabled OUT and others is enable
@endphp


