<x-rename-breadcrumbs></x-rename-breadcrumbs>

{{-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 --}}

{{-- <li class="nav-item">
	<a class="nav-link" href="{{ backpack_url('dashboard') }}">
		<i class="la la-home nav-icon"></i> @lang('lang.dashboard')
	</a>
</li> --}}

@php
	

	$menus = \App\Models\Menu::all();

	// dd($menus);

@endphp


{{-- Users, Roles, Permissions --}}
{{-- @canany(['user_list', 'role_list', 'permission_list'])
	<li class="nav-item nav-dropdown">
		<a class="nav-link nav-dropdown-toggle" href="#">
			<i class="nav-icon la la-users"></i> 
			@lang('lang.authentication')
		</a>

		<ul class="nav-dropdown-items">

			@can('user_list')
			 	<li class="nav-item">
			 		<a class="nav-link" href="{{ backpack_url('user') }}">
			 			<i class="nav-icon la la-user"></i> 
			 			<span>@lang('lang.users')</span>
			 		</a>
		 		</li>
			@endcan

			@can('role_list')
			  	<li class="nav-item">
			  		<a class="nav-link" href="{{ backpack_url('role') }}">
			  			<i class="nav-icon la la-id-badge"></i> 
			  			<span>@lang('lang.roles')</span>
			  		</a>
			  	</li>
			@endcan

			@can('permission_list')
				<li class="nav-item">
					<a class="nav-link" href="{{ backpack_url('permission') }}">
						<i class="nav-icon la la-key"></i> 
						<span>@lang('lang.permissions')</span>
					</a>
				</li>
			@endcan

		</ul>
	</li>
@endcanany --}}


{{-- @can('admin_view')
	<li class="nav-title">
		@lang('lang.admin_only')
	</li>
@endcan --}}




@php
	// TODO:: select menu from DB
	// TODO:: refactor and clean
    // TODO:: generate seeder
	// TODO:: app settings seeders
	// TODO:: schedule to run auto backup
	// TODO:: add theme
	// TODO:: wizard installer
@endphp

