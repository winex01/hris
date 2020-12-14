<!-- This file is used to store topbar (left) items -->

{{-- <li class="nav-item px-3"><a class="nav-link" href="{{ backpack_url('dashboard') }}">@lang('lang.dashboard')</a></li> --}}

@if (config('web-tinker.enabled'))
	<li class="nav-item px-3">
		<a href="{{ url(config('web-tinker.path')) }}" target="_blank" class="nav-link" href="#">
			{{ __('Tinker') }}
		</a>
	</li>
@endif
