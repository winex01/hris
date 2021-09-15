{{-- NOTE:: show / hide button, create showTheseLineButtons in entry model --}}
@if (in_array(str_replace('custom_', '', $buttonName), $entry->showTheseLineButtons())) 
	@include('crud::buttons.'.snake_case($buttonName))
@endif