<x-after-scripts></x-after-scripts>

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

	// TODO:: performance_appraisals
            // date evaluated -date
            // total rating - double
            // interpretation - textarea/string
	
	// TODO:: update backpack to version 4.1.32 and fix showEmployeeNameColumnUnsortable column
	// TODO:: check all crud export if orderby datatable is working and no error
	// TODO:: refactor validation request all select must be integer instead of using numeric
	// TODO:: modify emp select and use inline_create type but disable add to have ajax with pagination by 10 and improve performance
	// TODO:: check all crud with employee_id validation message
	// TODO:: find away to order all crud that has employee to order by full name with badge
	// TODO:: make relation select default to -
    // TODO:: fix select2 in employee app settings such as: gender etc. to use select2_from_array instead
    // of select2 which is relationship to allow nulls nad delete dash in app settings crud seeder

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
	// TODO:: daily time records table list:
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
	// TODO:: leave credits
			// employee - select
			// leave - select
				// compassionate leave
				// emergency leave
				// maternity leave
				// paternity leave
				// SL-without pay
				// sick leave
				// Solo parent leave
				// VL-without pay
				// vacatioon leave
			// applicable year - select - years
			// leave credit - numeric input field steps .5
	// TODO:: leave application 
			// employee id
			// last name
			// first name
			// leave
			// leave credit
			// date start
			// end date
	// TODO:: overtime application 
			// employee id
			// last name
			// first name
			// credit date
			// time start
			// time end
	// TODO:: leave adjustment 
			// employee id
			// last name
			// first name
			// leave
			// leave credit
			// date start
			// end date
	// TODO:: late allowance 
			// employee id
			// last name
			// first name
			// credit date
			// late allowance
			// reason
			// status
	// TODO:: undertime applications 
			// employee id
			// last name
			// first name
			// credit date
			// time start
			// time end
			// reason
	// TODO:: log adjustments
			// employee id
			// last name
			// first name
			// credit date
			// trasaction
			// time log
			// reason
			// date filed
			// date granted
	// TODO:: holiday
			// date - 
			// description - textarea
			// holiday - radio - Legal/Special
			// location: checkbox: NOTE:: use for payroll
					// ex. manila		
					// ex. cebu
	// TODO:: labor hours
			// description - string- code
			// multiplier(daily)
			// multiplier(monthly)
			// is taxable -yes/no -boolean
	// TODO:: benefit summaries 3 tabs = 3 tables = employee/incomes/deductions
			// employee
				// employee id
				// last name
				// first name
				// income
				// deduciton
			// incomes 
				// code
				// description
				// amount
			// deduction 
				// code
				// description
				// amount
	// TODO:: deduction amortization
			// employee id, 
			// name
			// deduction
			// amount deducted
			// date start
			// end date
	// TODO:: deduction balance
			// employee id
			// name
			// deduction
			// total
			// amount to deduct
			// amount deducted
			// date start
	// TODO:: deduction onetime
	// TODO:: salary advance / post cash advance
			// employee id
			// first name
			// last name
			// last transaction
			// date
			// outstanding balance
			// amount to deduct
			// amount deducted
			// actions
	// TODO:: statutory deductions
			// tabs: pagibig contribution, philhealth cont, sss, cont, witholding tax cont
				// pagibig tab
					// emp id
					// emp name
					// EE share
					// ER share
					// actions
				// philhealth
					// emp id
					// emp name
					// EE share
					// ER share
					// actions
				// sss
					// emp id
					// emp name
					// EE share
					// ER share
					// EC share
					// actions
				// Witholding Tax
					// emp id
					// emp name
					// witholding tax
					// action
	// TODO:: government loans
			// employee id
			// employee name
			// government agency
			// total
			// amount to deduct
			// amount deducted
	// TODO:: income balance
			// emp id
			// emp name
			// income
			// total
			// amount to give
			// amount given
			// date start
			// reflect to
	// TODO:: income  onetime
			// employee id
			// employee name
			// income
			// amount to give
			// amount given
			// date start
			// reflect to
	// TODO:: income recurring
			// employee id
			// employee name
			// income
			// amount to give
			// amount given
			// date start
			// end date
			// reflect to
	// TODO:: benefits settings
			// deduction tab
				// code 
				// desc
				// is government type - boolean - radio yes/no - default is yes
				// if gov type is = yes 
					// show government agency -select
						// pagibig, philhealth, sss
			// income
				// code
				// desc
				// 13 month - radio -yes/no - Is the income included when computing 13th month?
				// BIR 2316 item - select item # on 2316
				// BIR 2316 excess - select item # on 2316
				// ceiling amount - enter the ceiling amount
	// TODO:: payroll inputs
			// DTR summaries tab
				// emp id
				// emp name
				// absences
				// holidays
				// leaves
				// payroll days
				// regular hours
				// late
				// undertime
			// labor hours
				// emp id
				// name
			// incomes
				// emp id
				// name
			// statutory deductions
				// emp id
				// name
				// witholding tax
			// other deductions
				// emp id
				// name
			// employment information
	// TODO:: bank accounts
		// employee - select
		// bank - select bank
		// account #
		// enter account name
		// type - select -
			// savings  
			// others
		// status - select
			// active
			// inactive
	// TODO:: tax calculator
		// tax code - select
		// witholding tax basis - select
			// daily
			// weekly
			// semi-monthly
			// monthly
			// annualized
	// TODO:: process payrolls
		// employee id
		// emp name
		// gross
		// total deduction
		// net pay
		// payroll alert -message ni siya
	// TODO:: payroll period
		// payroll code / name sa ako
		// payroll description
		// year - select
		// month - select
		// is off payroll - yes/no- radio - Select Yes if payroll period is off-payroll, otherwise No
		// payroll start - date
		// payroll end - date
		// company - select company
		// groupiing - select payroll group
		// payroll count - select payroll count
		// witholding tax basis - select
				// No deduction
				// annualized
				// modified annualized
				// daily
				// weekly
				// semi-monthly
				// monthly
		// deduct pagibig - radio - yes/no
		// deduct philhealth - radio - yes/no
		// deduct sss - radio - yes/no
		// is last pay - radio - yes/no - Check Yes if payroll is the last for the year, otherwise No.
	// TODO:: payroll settings - company
		// code / name
		// desc
		// address - Enter the company address.
		// contact person - Enter the contact person of the company.
		// position - Enter the position of the contact person.
		// fax number - Enter the fax number of the company.
		// mobile number - Enter the mobile number of the company.
		// mobile # - Enter the telephone number of the company.
		// pagibig - Enter the pagibig number of the company.
		// philhealth - Enter the philhealth number of the company.
		// sss - Enter the sss number of the company.
		// tax id # - Enter the tax id number of the company.
		// logo - company logo
	// TODO:: payroll settings - location
		// code / name
		// desc
		// minimum wage - double - Enter the minimum wage of the location.
	// TODO:: payroll settings - department
		// code / name
		// desc
		// ideal head count - Enter the ideal head count of the department.
	// TODO:: payroll settings - division
		// code / name
		// desc
	// TODO:: payroll settings - position
		// code / name
		// desc
	// TODO:: payroll settings - level
		// code / name - ex. EXAD, R&F/S
		// desc 
	// TODO:: payroll settings - rank
		// code / name
		// desc
	// TODO:: payroll settings - employment status
		//code
		// desc
	// TODO:: payroll settings - job status
	// TODO:: payroll settings - days per year
		// days per year
		// days per week
		// hours per day
	// TODO:: payroll settings - grouping
		// code / name
		// desc
	// TODO:: payroll settings - bank
		// code / name
		// desc
		// company code
		// branch code
		// address
		// account number
	
	// TODO:: add div / mini info for the remaining col-4, that can be change using CRUD, in preview and etc
	// TODO:: bulk upload in crud
	// TODO:: position seeders, see image
	// TODO:: employee bank / ATM  acc. details
	// TODO:: company crud add column logo
	// TODO:: benefits ecola - double -
    // TODO:: benefits cola - double - (is added to basic rate)
	// TODO:: add user crud for non admin user
	// TODO:: add app settings company logo/img
	// TODO:: time in(click) at navbar
	// TODO:: date and time in nav bar
	// TODO:: export history for review purposes
	// TODO:: app permission dir: https://stackoverflow.com/questions/23411520/how-to-fix-error-laravel-log-could-not-be-opened
	// TODO:: add theme
	// TODO:: schedule to run auto backup
	// TODO:: wizard installer - https://github.com/rashidlaasri/LaravelInstaller
	// NOTE:: how to uery json obj $query->whereRaw('field_value->"$.id" = "'.$value.'"');
@endphp