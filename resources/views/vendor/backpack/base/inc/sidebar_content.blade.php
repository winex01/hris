<x-rename-breadcrumbs></x-rename-breadcrumbs>

{{-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 --}}
@php
	$menus = \App\Models\Menu::whereNull('parent_id')->orderBy('lft')->get();
@endphp

@foreach ($menus as $menu)
	@if ($menu->url == null && $menu->icon == null)
		{{-- show as label or title --}}
		@can($menu->permission)
			<li class="nav-title">
				{{ $menu->label }} 
			</li>
		@endcan
	@elseif ($menu->url != null) 
		{{-- normal menu --}}
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

			// dump($subMenusPermissions);
		@endphp
		
		{{-- sub menu --}}
		@foreach ($subMenus as $subMenu)
			@if ($loop->first && auth()->user()->canAny($subMenusPermissions))
					<li class="nav-item nav-dropdown">
						<a class="nav-link nav-dropdown-toggle" href="#">
							{!! $menu->icon !!} 
							{{ $menu->label }} 
						</a>
						<ul class="nav-dropdown-items">
			@endif
							@can($subMenu->permission)
							 	<li class="nav-item">
							 		<a class="nav-link" href="{{ backpack_url($subMenu->url) }}">
							 			{!! $subMenu->icon !!} 
										{{ $subMenu->label }} 
							 		</a>
						 		</li>
							@endcan

			@if ($loop->last && auth()->user()->canAny($subMenusPermissions))
						</ul>
					</li>
			@endif
		@endforeach
	@endif
@endforeach



@php


	// TODO:: educational background
            // Educational Level - string - Select an educational level.
            //      elementary
            //      high school
            //      vocational school
            //       tertiary / college
            //      Masters / Doctorate
            // Course / Major - string - Enter the course or major taken.
            // school - string- Enter the name of school.
            // Address - textarea - Enter the address of school.
            // date from - date - Enter the start of school year.
            // date to - Enter the end of school year.
            // attachment
	// TODO:: siblings
            // siblings - string - Enter the name of sibling.
            // birth date - date - Enter the birth of sibling.
            // birth place - string - Enter the birth place of sibling.
            // occupation - string - Enter the occupation of sibling.
            // company - enter the company of sibling.
	// TODO:: medical informations
            // Medical examination / History - string - Enter the type of medical information.
            // Date taken - date - Enter the date of medical examination.
            // Expiration date - date -Enter the expiration date of examination.
            // diagnosis - textarea
	// TODO:: professional orgs
            // organization - string - enter the name of organization.
            // position - string - enter the position in the organization.
            // membership date - date - enter the date of membership
	// TODO:: skills
            // skills and talents - textarea - enter the skill or talent
	// TODO:: benefeciary
            // first_name
            // last_name
            // middle_name
            // suffix_name
            // relationship
            // birth date
	// TODO:: dependents
            // first name
            // last name
            // middle name
            // suffix name
            // relationship
            // birth date
            // disability
            // date of birth
	// TODO:: character reference
            // strings
            // last name
            // first name
            // middle name
            // company
            // position
            // mobile number
            // telephone #
	// TODO:: employment information
            // company - select
            // location - select
                // ex. manila,
                // cebu
                // etc.
            // department - select
                // ex. Merchandising
                // store operation
                // etc.
            // division - select
            // section - select
            // position - select
            // level -select
            // rank - select
            // employment status - select
                // contractual
                // probationary
                // regular
                // special project 
                // trainee / intern
            // job status - select
                // active
                // end of contract
                // forced leave
                // inactive
                // resigned
                // retired
                // terminated
            // days per year - select
                // 262.0000/5.0000/8.0000
                // 312.0000/5.0000/8.0000
                // 313.0000/5.0000/8.0000
                // 313.0000/6.0000/8.0000
                // 314.0000/6.0000/8.0000
                // 360.0000/5.0000/8.0000
                // 365.0000/7.0000/8.0000
            // pay basis- select
                // monthly paid
                // pro-rated monthly maid
                // daily paid
                // hourly paid
            // basic rate - double - enter the basic rate amount.
            // ecola - double
            // basic - adjustment - double
            // tax code - select
            // grouping - (payroll group man siguro)
            // payment method - select
                // bank (ATM)
                // cash
                // check
            // effectivity date - date
	// TODO:: performance_appraisals
            // date evaluated -date
            // total rating - double
            // interpretation - textarea/string
	// TODO:: offences_and_sanctions
            // date issued - date
            // offence classification - select
                // attendance
                // working environment
                // insubordination
                // integrity
                // others
            // description - textarea
            // gravity of sanction - select
                // 1st offence
                // 2nd offence
                // 3rd offence
                // 4th offence
                // 5th offence
                // Dismissal
            // attachment
	// TODO:: shift schedules
            // shift descr - string
            // relative day start
            // open time - yes/no - boolean
            // working hours - time in - time out - dynamic input field
            // overtime hours
            // dynamic break - yes/no - boolean
            // break creadit: ex. 1:00 - 1 hour
            // shift credit - idk
	// TODO:: daily time records
		// TODO::
            //  date
            // shift
            // in
            // out
            // in
            // out
            // etc in
            // etc out
            // leave
            // reg hour
            // late
            // UT
            // OT
            // POT
	// TODO:: employee shift schedules
		// calendar
            // when date is click open modal
            // modal shows days of week from sunday to saturday - select
                // employee shift schedules
	// TODO:: change shift schedules:
		// table list
			// empployee id
			// last name
			// first name
			// shift schedule
			//  date filed
			// date granted	
			// credit date
			// reason
	



	// TODO:: add theme
	// TODO:: schedule to run auto backup
	// TODO:: wizard installer - https://github.com/rashidlaasri/LaravelInstaller
@endphp

