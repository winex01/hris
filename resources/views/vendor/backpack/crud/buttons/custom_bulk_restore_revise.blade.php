@if ($crud->hasAccess('bulkRestoreRevise') && $crud->get('list.bulkActions'))
	<a href="javascript:void(0)" onclick="bulkRestoreRevise(this)" class="btn btn-sm btn-secondary bulk-button btn-success" data-toggle="tooltip" title="{{ trans('lang.restore') }}"><i class="la la-undo-alt"></i></a>
@endif

@push('after_scripts')
<script>
	if (typeof bulkRestoreRevise != 'function') {
	  function bulkRestoreRevise(button) {

	      if (typeof crud.checkedItems === 'undefined' || crud.checkedItems.length == 0)
	      {
  	        new Noty({
	          type: "warning",
	          text: "<strong>{!! trans('backpack::crud.bulk_no_entries_selected_title') !!}</strong><br>{!! trans('backpack::crud.bulk_no_entries_selected_message') !!}"
	        }).show();

	      	return;
	      }

	      var message = "{!! trans('lang.bulk_restore_are_you_sure') !!}";
	      message = message.replace(":number", crud.checkedItems.length);

	      // show confirm message
	      Swal.fire({
			  title: "{!! trans('backpack::base.warning') !!}",
			  text: message,
			  icon: 'warning',
			  showCancelButton: true,
			  cancelButtonColor: '#d33',
			  cancelButtonText: "{!! trans('backpack::crud.cancel') !!}",
			  confirmButtonColor: 'bg-success',
			  confirmButtonText: "{!! trans('lang.restore') !!}",
			  reverseButtons: true,
			}).then((value) => {
				if (value.isConfirmed) {
					var ajax_calls = [];
		      		var route = "{{ url($crud->route) }}/bulkRestoreRevise";

					// submit an AJAX delete call
					$.ajax({
						url: route,
						type: 'POST',
						data: { entries: crud.checkedItems },
						success: function(result) {
						  // Show an alert with the result
		    	          new Noty({
				            type: "success",
				            text: "<strong>{!! trans('lang.bulk_restore_success_title') !!}</strong><br>"+crud.checkedItems.length+" {!! trans('lang.bulk_restore_sucess_message') !!}"
				          }).show();

						  crud.checkedItems = [];
						  crud.table.ajax.reload();
						},
						error: function(result) {
						  // Show an alert with the result
		    	          new Noty({
				            type: "danger",
				            text: "<strong>{!! trans('lang.crud.bulk_restore_error_title') !!}</strong><br>"+crud.checkedItems.length+" {!! trans('lang.bulk_restore_error_message') !!}"
				          }).show();
						}
					});
				}
			});
      }
	}
</script>
@endpush