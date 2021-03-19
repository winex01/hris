<!-- This file is used to store topbar (left) items -->

{{-- 
<li class="nav-item px-3"><a class="nav-link" href="#">Dashboard</a></li>
<li class="nav-item px-3"><a class="nav-link" href="#">Users</a></li>
--}}

{{-- TODO:: clock --}}
<li class="nav-item px-3 ml-2"><a class="nav-link text-white" href="#">{{ date('h : i A') }}</a></li> 

<li class="nav-item px-3 ml-n4">
	<a class="btn btn-info btn-sm" href="#"><i class="las la-clock"></i> IN &nbsp; &nbsp;</a>
	<a class="btn btn-warning btn-sm ml-1" href="#"><i class="las la-stopwatch"></i> OUT</a>
	<a class="btn btn-danger btn-sm ml-1" href="#"><i class="las la-hourglass-start"></i> BREAK START</a>
	<a class="btn btn-danger btn-sm ml-1" href="#"><i class="las la-hourglass-end"></i> BREAK END</a>
</li>