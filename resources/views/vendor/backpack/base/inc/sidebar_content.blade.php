<x-rename-breadcrumbs></x-rename-breadcrumbs>

{{-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 --}}
<li class="nav-item">
	<a class="nav-link" href="{{ backpack_url('dashboard') }}">
		<i class="la la-home nav-icon"></i> @lang('lang.dashboard')
	</a>
</li>


{{-- Users, Roles, Permissions --}}
@canany(['user_view', 'role_view', 'permission_view'])
	<li class="nav-item nav-dropdown">
		<a class="nav-link nav-dropdown-toggle" href="#">
			<i class="nav-icon la la-users"></i> 
			@lang('lang.authentication')
		</a>

		{{-- sub menu --}}
		<ul class="nav-dropdown-items">
			@can('user_view')
			 	<li class="nav-item">
			 		<a class="nav-link" href="{{ backpack_url('user') }}">
			 			<i class="nav-icon la la-user"></i> 
			 			<span>@lang('lang.users')</span>
			 		</a>
		 		</li>
			@endcan

			@can('role_view')
			  	<li class="nav-item">
			  		<a class="nav-link" href="{{ backpack_url('role') }}">
			  			<i class="nav-icon la la-id-badge"></i> 
			  			<span>@lang('lang.roles')</span>
			  		</a>
			  	</li>
			@endcan

			@can('permission_view')
				<li class="nav-item">
					<a class="nav-link" href="{{ backpack_url('permission') }}">
						<i class="nav-icon la la-key"></i> 
						<span>@lang('lang.permissions')</span>
					</a>
				</li>
			@endcan
		</ul>
	</li>
@endcanany


{{-- Employee Records --}}
@canany([
	'employee_view', 
])
	<li class="nav-item nav-dropdown">
		<a class="nav-link nav-dropdown-toggle" href="#">
			<i class="nav-icon la la-users"></i> 
			@lang('lang.employee_records')
		</a>

		<ul class="nav-dropdown-items">
			{{-- sub menu --}}
			@can('employee_view')
				<li class='nav-item'>
					<a class='nav-link' href='{{ backpack_url('employee') }}'>
						<i class='nav-icon la la-user-plus'></i> 
						<span>@lang('lang.employees')</span>
					</a>
				</li>
			@endcan

		</ul>
	</li>
@endcanany