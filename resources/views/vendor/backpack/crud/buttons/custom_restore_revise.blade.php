 @if ($crud->hasAccess('restoreRevise'))
	<a href="javascript:void(0)" onclick="restoreEntry(this)" data-route="{{ url($crud->route.'/'.$entry->getKey().'/restoreRevise') }}" class="btn btn-sm btn-link text-success" data-button-type="restore-revise"><i class="la la-undo"></i> {{ trans('lang.restore') }}</a>
@endif

{{-- @dump(url($crud->route)) --}}

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>

	if (typeof restoreEntry != 'function') {
	  $("[data-button-type=restore-revise]").unbind('click');

	  function restoreEntry(button) {
		// ask for confirmation before deleting an item
		// e.preventDefault();
		var button = $(button);
		var route = button.attr('data-route');
		var row = $("#crudTable a[data-route='"+route+"']").closest('tr');

		swal({
		  title: "{!! trans('backpack::base.warning') !!}",
		  text: "{!! trans('lang.restore_confirm') !!}",
		  icon: "warning",
		  buttons: {
		  	cancel: {
			  text: "{!! trans('backpack::crud.cancel') !!}",
			  value: null,
			  visible: true,
			  className: "bg-secondary",
			  closeModal: true,
			},
		  	delete: {
			  text: "{!! trans('lang.restore') !!}",
			  value: true,
			  visible: true,
			  className: "bg-success",
			}
		  },
		}).then((value) => {
			if (value) {
				$.ajax({
			      url: route,
			      type: 'POST',
			      success: function(result) {
			          if (result == 1) {
			          	  // Show a success notification bubble
			              new Noty({
		                    type: "success",
		                    text: "{!! '<strong>'.trans('lang.restore_confirmation_title').'</strong><br>'.trans('lang.restore_confirmation_message') !!}"
		                  }).show();

			              // Hide the modal, if any
			              $('.modal').modal('hide');

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
				              swal({
				              	title: "{!! trans('lang.restore_confirmation_not_title') !!}",
	                            text: "{!! trans('lang.restore_confirmation_not_message') !!}",
				              	icon: "error",
				              	timer: 4000,
				              	buttons: false,
				              });
			          	  }			          	  
			          }

			          // if operation is show then redirect
			          @include('crud::buttons.custom_redirect_to_crud_route')
			      },
			      error: function(result) {
			          // Show an alert with the result
			          swal({
		              	title: "{!! trans('lang.restore_confirmation_not_title') !!}",
                        text: "{!! trans('lang.restore_confirmation_not_message') !!}",
		              	icon: "error",
		              	timer: 4000,
		              	buttons: false,
		              });
			      }
			  });
			}
		});

      }
	}

	// make it so that the function above is run after each DataTable draw event
	// crud.addFunctionToDataTablesDrawEventQueue('restoreEntry');
</script>
@if (!request()->ajax()) @endpush @endif

