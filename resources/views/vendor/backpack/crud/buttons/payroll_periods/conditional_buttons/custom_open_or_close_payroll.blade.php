{{-- @include('crud::buttons.conditional_buttons.custom_conditional_button', [
	'buttonName' => 'custom_openOrClosePayroll'
]) --}}

{{-- NOTE:: show / hide button, create showTheseLineButtons in entry model --}}
@php
	$buttonName = 'custom_openOrClosePayroll';
@endphp
@if (in_array(str_replace('custom_', '', $buttonName), $entry->showTheseLineButtons())) 
	@include('crud::buttons.payroll_periods.'.snake_case($buttonName))
@endif