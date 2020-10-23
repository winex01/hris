<!-- This file is used to store topbar (left) items -->

<li class="nav-item px-3"><a class="nav-link" href="{{ backpack_url('dashboard') }}">@lang('lang.dashboard')</a></li>
@can('user_view')
	<li class="nav-item px-3"><a class="nav-link" href="{{ backpack_url('user') }}">@lang('lang.users')</a></li>
@endcan

{{-- TODO:: settings --}}
@can('setting_view', )	
	<li class="nav-item px-3"><a class="nav-link" href="#">@lang('lang.settings')</a></li>
@endcan
