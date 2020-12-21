@if ($crud->hasAccess('export') && $crud->get('list.bulkActions'))
	{{-- TODO::  --}}
	<div class="btn-group dropdown float-right text-right ml-1">
		<button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="la la-download"></i>
			{{ __('Export') }}
		</button>
		<div class="dropdown-menu">
			{{-- 
			TODO::
			'Copy', 
			'Excel',
			'CSV',
			'PDF',
			'Print', 
			--}}

			<a href="javascript:void(0)" class="dropdown-item text-sm-left" onclick="bulkEntries(this)">
				Excel
			</a>
		</div>
	</div>
@endif

{{-- <a href="javascript:void(0)" onclick="forceBulkDeleteEntries(this)" class="btn btn-sm btn-secondary bulk-button btn-danger"><i class="la la-trash"></i> {{ trans('lang.force_delete') }}</a> --}}


@push('after_scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
	if (typeof bulkEntries != 'function') {
	  function bulkEntries(button) {


	      var message = "{!! trans('backpack::crud.bulk_delete_are_you_sure') !!}";
	      var button = $(this);

	      // show confirm message
	      swal({
			  text: message,
			  buttons: {
			  	cancel: {
				  text: "{!! trans('backpack::crud.cancel') !!}",
				  value: null,
				  visible: true,
				  className: "bg-secondary",
				  closeModal: true,
				},
			  	delete: {
				  text: "{!! trans('lang.force_delete') !!}",
				  value: true,
				  visible: true,
				  className: "bg-danger",
				}
			  },
			}).then((value) => {
				if (value) {
					var ajax_calls = [];
					var route = "{{ url($crud->route) }}/export";

					// submit an AJAX delete call
					$.ajax({
						url: route,
						type: 'POST',
						data: { entries: crud.checkedItems },
						success: function(result) {
							if (result == true) {
							  // Show a success notification bubble
							  new Noty({
							    type: "success",
							    text: "<strong>{!! trans('backpack::crud.bulk_delete_sucess_title') !!}</strong><br>{!! trans('backpack::crud.bulk_delete_sucess_message') !!}"
							  }).show();
							} else {
							  	// Show a warning notification bubble
								new Noty({
									type: "warning",
									text: "<strong>{!! trans('backpack::crud.bulk_delete_error_title') !!}</strong><br>{!! trans('backpack::crud.bulk_delete_error_message') !!}"
								}).show();
							}

						  	crud.checkedItems = [];
						  	crud.table.ajax.reload();
						},
						error: function(result) {
							// Show an alert with the result
							new Noty({
								type: "warning",
								text: "<strong>{!! trans('backpack::crud.bulk_delete_error_title') !!}</strong><br>{!! trans('backpack::crud.bulk_delete_error_message') !!}"
							}).show();
						}
					});
				}
			});
      }
	}
</script>
@endpush
