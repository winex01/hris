<x-rename-breadcrumbs></x-rename-breadcrumbs>

{{-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 --}}

<li class="nav-item">
	<a class="nav-link" href="{{ backpack_url('dashboard') }}">
		<i class="la la-home nav-icon"></i> @lang('lang.dashboard')
	</a>
</li>


{{-- Employee Records --}}
@canany([
	'award_and_recog_list', 
	'employee_list', 
	'gov_exam_list', 
	'supporting_docs_list', 
	'train_and_seminar_list', 
	'work_exp_list', 
])
	<li class="nav-item nav-dropdown">
		<a class="nav-link nav-dropdown-toggle" href="#">
			<i class="nav-icon la la-user"></i> 
			@lang('lang.employee_records')
		</a>

		{{-- sub menu --}}
		<ul class="nav-dropdown-items">

			@can('award_and_recog_list')
				<li class='nav-item'>
					<a class='nav-link' href='{{ backpack_url('awardandrecognition') }}'>
						<i class='nav-icon la la-trophy'></i> 
						@lang('lang.award_and_recognitions_shorten')
					</a>
				</li>
			@endcan

			@can('employee_list')
				<li class='nav-item'>
					<a class='nav-link' href='{{ backpack_url('employee') }}'>
						<i class='nav-icon la la-user-plus'></i> 
						<span>@lang('lang.employee')</span>
					</a>
				</li>
			@endcan

			@can('gov_exam_list')
				<li class='nav-item'>
					<a class='nav-link' href='{{ backpack_url('governmentexamination') }}'>
						<i class='nav-icon la la-industry'></i> 
						@lang('lang.government_examinations_shorten')
					</a>
				</li>
			@endcan

			@can('supporting_docs_list')
				<li class='nav-item'>
					<a class='nav-link' href='{{ backpack_url('supportingdocument') }}'>
						<i class='nav-icon la la-file-o'></i> 
						@lang('lang.supporting_documents_shorten')
					</a>
				</li>
			@endcan

			@can('train_and_seminar_list')
				<li class='nav-item'>
					<a class='nav-link' href='{{ backpack_url('trainingsandseminar') }}'>
						<i class='nav-icon la la-bicycle'></i> 
						@lang('lang.trainings_and_seminars_shorten')
					</a>
				</li>
			@endcan

			@can('work_exp_list')
				<li class='nav-item'>
					<a class='nav-link' href='{{ backpack_url('workexperience') }}'>
						<i class='nav-icon la la-bolt'></i> 
						@lang('lang.work_experiences')
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

{{-- audit trail --}}
@can('audit_trail_list')
	<li class='nav-item'>
		<a class='nav-link' href='{{ backpack_url('audittrail') }}'>
			<i class='nav-icon la la-history'></i> 
			<span>@lang('lang.audit_trail')</span>
		</a>
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


{{-- Advanced --}}
@canany([
	'advanced_file_manager', 
	'advanced_backups', 
	'advanced_logs', 
	'advanced_settings', 
])
	<li class="nav-item nav-dropdown">
		<a class="nav-link nav-dropdown-toggle" href="#">
			<i class="nav-icon la la-cogs"></i> 
			@lang('lang.advanced')
		</a>

		{{-- sub menu --}}
		<ul class="nav-dropdown-items">
		
			@can('advanced_file_manager')
				<li class="nav-item">
					<a class="nav-link" href="{{ backpack_url('elfinder') }}">
						<i class="nav-icon la la-files-o"></i> 
						<span>{{ trans('backpack::crud.file_manager') }}</span>
					</a>
				</li>
			@endcan

			@can('advanced_backups')
				<li class='nav-item'>
					<a class='nav-link' href='{{ backpack_url('backup') }}'>
						<i class='nav-icon la la-hdd-o'></i> 
						<span>@lang('lang.backups')</span>
					</a>
				</li>
			@endcan

			@can('advanced_logs')
				<li class='nav-item'>
					<a class='nav-link' href='{{ backpack_url('log') }}'>
						<i class='nav-icon la la-terminal'></i> 
						<span>@lang('lang.logs')</span>
					</a>
				</li>
			@endcan

			@can('advanced_settings')
				<li class='nav-item'>
					<a class='nav-link' href='{{ backpack_url('setting') }}'>
						<i class='nav-icon la la-cog'></i> 
						<span>@lang('lang.settings')</span>
					</a>
				</li>
			@endcan

		</ul>
	</li>
@endcanany



@php
	// TODO:: app settings seeders
	// TODO:: schedule to run auto backup
	// TODO:: add theme
	// TODO:: wizard installer
@endphp
