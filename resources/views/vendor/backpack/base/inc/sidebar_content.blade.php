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
			<i class="nav-icon la la-user"></i> 
			@lang('lang.employee_records')
		</a>

		{{-- sub menu --}}
		<ul class="nav-dropdown-items">
			@can('employee_view')
				<li class='nav-item'>
					<a class='nav-link' href='{{ backpack_url('employee') }}'>
						<i class='nav-icon la la-user-plus'></i> 
						<span>@lang('lang.employee')</span>
					</a>
				</li>
			@endcan

		</ul>
	</li>
@endcanany


{{-- Application Settings --}}
@canany([
	'religion_view', 
	'citizenship_view', 
	'gender_view', 
	'blood_type_view', 
	'civil_status_view', 
])
	<li class="nav-item nav-dropdown">
		<a class="nav-link nav-dropdown-toggle" href="#">
			<i class="nav-icon la la-cog"></i> 
			@lang('lang.app_settings')
		</a>

		{{-- sub menu --}}
		<ul class="nav-dropdown-items">
			@can('civil_status_view')
				<li class='nav-item'>
					<a class='nav-link' href='{{ backpack_url('civilstatus') }}'>
						<i class='nav-icon la la-neuter'></i> 
						<span>@lang('lang.civil_status')</span>
					</a>
				</li>
			@endcan

			@can('gender_view')
				<li class='nav-item'>
					<a class='nav-link' href='{{ backpack_url('gender') }}'>
						<i class='nav-icon la la-venus'></i> 
						<span>@lang('lang.gender')</span>
					</a>
				</li>
			@endcan
		</ul>
	</li>
@endcanany



{{-- TODO:: create config for seeders for easy edit and rerun --}}
{{-- TODO:: religion, citizenship, blood type,  --}}
