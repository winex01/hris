<!-- This file is used to store topbar (left) items -->

@can('admin_web_tinker')
	<li class="nav-item px-3">
		<a href="{{ url(config('web-tinker.path')) }}" target="_blank" class="nav-link" href="#">
			{{ __('Tinker') }}
		</a>
	</li>
@endcan
