<x-rename-breadcrumbs></x-rename-breadcrumbs>

{{-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 --}}
@php
	$menus = \App\Models\Menu::whereNull('parent_id')->orderBy('lft')->get();
@endphp

@foreach ($menus as $menu)
	@if ($menu->url == 'audittrail')
		@can('admin_view')
			<li class="nav-title">
				@lang('lang.admin_only')
			</li>
		@endcan
	@endif

	@if ($menu->url != null) 
		@can($menu->permission)
			<li class="nav-item">
				<a class="nav-link" href="{{ backpack_url($menu->url) }}">
					{!! $menu->icon !!} 
					{{ $menu->label }} 
				</a>
			</li>
		@endcan
	@else
		@php
			$subMenus = \App\Models\Menu::where('parent_id', $menu->id)->orderBy('lft')->get();
			$subMenusPermissions = $subMenus->pluck('permission')->toArray();
		@endphp
		
		{{-- submenu --}}
		@foreach ($subMenus as $subMenu)
			@if ($loop->first && auth()->user()->can($subMenusPermissions))
					<li class="nav-item nav-dropdown">
						<a class="nav-link nav-dropdown-toggle" href="#">
							{!! $menu->icon !!} 
							{{ $menu->label }} 
						</a>
						<ul class="nav-dropdown-items">
			@endif
							@can('user_list')
							 	<li class="nav-item">
							 		<a class="nav-link" href="{{ backpack_url($subMenu->url) }}">
							 			{!! $subMenu->icon !!} 
										{{ $subMenu->label }} 
							 		</a>
						 		</li>
							@endcan

			@if ($loop->last && auth()->user()->can($subMenusPermissions))
						</ul>
					</li>
			@endif
		@endforeach
	@endif
@endforeach



@php
    // TODO:: generate seeder for menu
	// TODO:: app settings seeders
	// TODO:: schedule to run auto backup
	// TODO:: add theme
	// TODO:: wizard installer
@endphp

