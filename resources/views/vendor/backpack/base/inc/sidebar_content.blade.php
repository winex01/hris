<x-rename-breadcrumbs></x-rename-breadcrumbs>

{{-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 --}}

<li class="nav-item">
	<a class="nav-link" href="{{ backpack_url('dashboard') }}">
		<i class="la la-home nav-icon"></i> @lang('lang.dashboard')
	</a>
</li>


{{-- Employee Records --}}
@canany([
	'employee_list', 
])
	<li class="nav-item nav-dropdown">
		<a class="nav-link nav-dropdown-toggle" href="#">
			<i class="nav-icon la la-user"></i> 
			@lang('lang.employee_records')
		</a>

		{{-- sub menu --}}
		<ul class="nav-dropdown-items">

			@can('employee_list')
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


{{-- App Settings --}}
@canany([
	'blood_type_list', 
	'citizenship_list', 
	'civil_status_list', 
	'gender_list', 
	'religion_list', 
])
	<li class="nav-item nav-dropdown">
		<a class="nav-link nav-dropdown-toggle" href="#">
			<i class="nav-icon la la-cog"></i> 
			@lang('lang.app_settings')
		</a>

		{{-- sub menu --}}
		<ul class="nav-dropdown-items">

			@can('blood_type_list')
				<li class='nav-item'>
					<a class='nav-link' href='{{ backpack_url('bloodtype') }}'>
						<i class='nav-icon la la-eyedropper'></i> 
						<span>@lang('lang.blood_type')</span>
					</a>
				</li>
			@endcan

			@can('citizenship_list')
				<li class='nav-item'>
					<a class='nav-link' href='{{ backpack_url('citizenship') }}'>
						<i class='nav-icon la la-flag-o'></i> 
						<span>@lang('lang.citizenship')</span>
					</a>
				</li>
			@endcan
			
			@can('civil_status_list')
				<li class='nav-item'>
					<a class='nav-link' href='{{ backpack_url('civilstatus') }}'>
						<i class='nav-icon la la-neuter'></i> 
						<span>@lang('lang.civil_status')</span>
					</a>
				</li>
			@endcan

			@can('gender_list')
				<li class='nav-item'>
					<a class='nav-link' href='{{ backpack_url('gender') }}'>
						<i class='nav-icon la la-venus'></i> 
						<span>@lang('lang.gender')</span>
					</a>
				</li>
			@endcan

			@can('religion_list')
				<li class='nav-item'>
					<a class='nav-link' href='{{ backpack_url('religion') }}'>
						<i class='nav-icon la la-eye'></i> 
						<span>@lang('lang.religion')</span>
					</a>
				</li>
			@endcan
			
		</ul>
	</li>
@endcanany


@can('admin_view')
	<li class="nav-title">
		@lang('lang.admin_only')
	</li>
@endcan

{{-- Users, Roles, Permissions --}}
@canany(['user_list', 'role_list', 'permission_list'])
	<li class="nav-item nav-dropdown">
		<a class="nav-link nav-dropdown-toggle" href="#">
			<i class="nav-icon la la-users"></i> 
			@lang('lang.authentication')
		</a>

		{{-- sub menu --}}
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
@endcanany


@can('audit_trail_list')
	<li class='nav-item'>
		<a class='nav-link' href='{{ backpack_url('audittrail') }}'>
			<i class='nav-icon la la-history'></i> 
			<span>@lang('lang.audit_trail')</span>
		</a>
	</li>
@endcan


{{-- 
	TODO:: app settings seeders
	TODO:: create audit trail base on revision package
 --}}

