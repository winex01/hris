@if ($crud->hasAccess('export') && $crud->get('list.bulkActions'))
	<div class="btn-group dropdown float-right ml-1">
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

			{{-- TODO:: --}}
			<a href="javascript:void(0)" class="dropdown-item text-sm-left" onclick="bulkEntries(this)">Excel</a>
			{{-- <a href="{{ url($crud->route.'/export') }}" class="dropdown-item text-sm-left">Excel</a> --}}
		</div>
	</div>

@endif

@push('after_scripts')
{{-- TODO:: add sweetalert2 for progress bar --}}
<script>
	if (typeof bulkEntries != 'function') {
		function bulkEntries(button) {
			var route = "{{ url($crud->route) }}/export";

			// submit an AJAX delete call
			$.ajax({
				url: route,
				type: 'get',
				data: { entries: crud.checkedItems, model : "{{ $crud->model->model }}" },
				success: function(result) {
					console.log(result);

					if (result) {
					  window.location = result;
					  
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

				  	// crud.checkedItems = [];
				  	// crud.table.ajax.reload();
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
	}
</script>
@endpush
