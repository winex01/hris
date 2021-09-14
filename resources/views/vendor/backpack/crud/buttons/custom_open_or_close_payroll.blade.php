@if ($crud->hasAccessToAny(['openPayroll', 'closePayroll']))

	@php
		$showButton = false;
		$icon = null;
		$title = null;
		$confirmText = null;
		$confirmButtonText = null;
		$confirmationTitle = null;
		$confirmationNotTitle = null;
		$confirmationNotMessage = null;

		if ($entry->status == 1 && $crud->hasAccess('closePayroll')) { // if payroll is open then
			// close
			$icon = 'las la-folder';
			$title = trans('lang.close_payroll');
			$confirmText = trans('lang.close_payroll_confirm');
			$confirmButtonText = trans('lang.close_payroll_button');
			$confirmationTitle = '<strong>'.trans('lang.close_payroll_confirmation_title').'</strong><br>'.trans('lang.close_payroll_confirmation_message');
			$confirmationNotTitle = trans('lang.close_payroll_confirmation_not_title');
			$confirmationNotMessage = trans('lang.close_payroll_confirmation_not_message');
			$showButton = true;
		}elseif ($entry->status == 0 && $crud->hasAccess('openPayroll')) { // if payroll is close then
			// open
			$icon = 'las la-folder-open';
			$title = trans('lang.open_payroll');
			$confirmText = trans('lang.open_payroll_confirm');
			$confirmButtonText = trans('lang.open_payroll_button');
			$confirmationTitle = '<strong>'.trans('lang.open_payroll_confirmation_title').'</strong><br>'.trans('lang.open_payroll_confirmation_message');
			$confirmationNotTitle = trans('lang.open_payroll_confirmation_not_title');
			$confirmationNotMessage = trans('lang.open_payroll_confirmation_not_message');
			$showButton = true;	
		}else {
			// 
		}
	@endphp

	@if ($showButton == true)
		<a href="javascript:void(0)" onclick="openOrClosePayroll(this)" 
			class="btn btn-sm btn-link" 
			data-status="{{ $entry->status }}"  
			data-route="{{ url($crud->route.'/'.$entry->getKey().'/openOrClosePayroll') }}" 
			data-button-type="openOrClosePayroll" 
			data-toggle="tooltip" 
			title="{{ $title }}"
			data-confirmText="{{ $confirmText }}"
			data-confirmButtonText="{{ $confirmButtonText }}"
			data-confirmationTitle="{{ $confirmationTitle }}"
			data-confirmationNotTitle="{{ $confirmationNotTitle }}"
			data-confirmationNotMessage="{{ $confirmationNotMessage }}"
		>
			<i class="{{ $icon }}"></i>
		</a>
	@endif
	

{{-- @dump(url($crud->route)) --}}

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>

	if (typeof openOrClosePayroll != 'function') {
	  $("[data-button-type=openOrClosePayroll]").unbind('click');

	  function openOrClosePayroll(button) {
		// ask for confirmation before deleting an item
		// e.preventDefault();
		var button = $(button);
		var route = button.attr('data-route');
		var status = button.attr('data-status');

		var confirmText = button.attr('data-confirmText');
		var confirmButtonText = button.attr('data-confirmButtonText');
		var confirmationTitle = button.attr('data-confirmationTitle');
		var confirmationNotTitle = button.attr('data-confirmationNotTitle');
		var confirmationNotMessage = button.attr('data-confirmationNotMessage');
		
		var row = $("#crudTable a[data-route='"+route+"']").closest('tr');

		const swalWithBootstrapButtons = Swal.mixin({
		  customClass: {
		    confirmButton: 'btn btn-success ml-1',
		    cancelButton: 'btn btn-secondary'
		  },
		  buttonsStyling: false
		});

      	// show confirm message
		swalWithBootstrapButtons.fire({
		  title: "{!! trans('backpack::base.warning') !!}",
		  text: confirmText,
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonText: confirmButtonText,
		  cancelButtonText: "{!! trans('backpack::crud.cancel') !!}",
		  reverseButtons: true,
		}).then((value) => {
			if (value.isConfirmed) {
				$.ajax({
			      url: route,
			      type: 'POST',
			      data: {
			      	status : status
			      },
			      success: function(result) {
			          if (result == 1) {
			          	  // Show a success notification bubble
			              new Noty({
		                    type: "success",
		                    text: confirmationTitle
		                  }).show();

			              // Hide the modal, if any
			              $('.modal').modal('hide');

			              // reload table
			              if (typeof crud !== 'undefined') {
		                    crud.table.ajax.reload();
		                  }

			          } else {
			              // if the result is an array, it means 
			              // we have notification bubbles to show
			          	  if (result instanceof Object) {
			          	  	// trigger one or more bubble notifications 
			          	  	Object.entries(result).forEach(function(entry, index) {
			          	  	  var type = entry[0];
			          	  	  entry[1].forEach(function(message, i) {
					          	  new Noty({
				                    type: type,
				                    text: message
				                  }).show();
			          	  	  });
			          	  	});
			          	  } else {// Show an error alert
				              Swal.fire({
				              	title: confirmationNotTitle,
	                            text: confirmationNotMessage,
				              	icon: "error",
				              	timer: 4000,
				              	showConfirmButton: false,
				              });
			          	  }			          	  
			          }

			          // if operation is show then redirect
			          @include('crud::inc.custom_redirect_to_crud_route')
			      },
			      error: function(result) {
			          // Show an alert with the result
			          Swal.fire({
		              	title: confirmationNotTitle,
                        text: confirmationNotMessage,
		              	icon: "error",
		              	timer: 4000,
		              	showConfirmButton: false,
		              });
			      }
			  });
			}
		});

      }
	}

	// make it so that the function above is run after each DataTable draw event
	// crud.addFunctionToDataTablesDrawEventQueue('openOrClosePayroll');
</script>
@if (!request()->ajax()) @endpush @endif

@endif {{-- end of if ($crud->hasAccessToAny(['openPayroll', 'closePayroll'])) --}}