@if ($crud->hasAccess('export') && $crud->get('list.bulkActions'))
	<div class="btn-group dropdown float-right ml-1">
		<button type="button" class="btn btn-sm btn-secondary dropdown-toggle" title="You can check rows to export specific items." data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="la la-download"></i>
			{{ __('Export') }}
		</button>
		<div class="dropdown-menu">
			{{-- TODO:: --}}
			{{-- <a href="javascript:void(0)" class="dropdown-item text-sm-left" onclick="bulkEntries(this)">Copy</a> --}}
			<a href="javascript:void(0)" class="dropdown-item text-sm-left" onclick="bulkEntries(this)">Excel</a>
			{{-- <a href="javascript:void(0)" class="dropdown-item text-sm-left" onclick="bulkEntries(this)">CSV</a> --}}
			{{-- <a href="javascript:void(0)" class="dropdown-item text-sm-left" onclick="bulkEntries(this)">PDF</a> --}}
			{{-- <a href="javascript:void(0)" class="dropdown-item text-sm-left" onclick="bulkEntries(this)">Print</a> --}}
		</div>

		<div class="dropdown ml-1">
			<button class="btn btn-sm btn-secondary dropdown-toggle" title="Export columns" type="button" 
			id="sampleDropdownMenu" data-toggle="dropdown">
				<i class="la la-columns"></i>
			</button>

			<div class="dropdown-menu">
				@php
					$dbColumns = getTableColumns($crud->model->getTable());
					$dontInclude = config('hris.dont_include_in_exports');
				@endphp
				@foreach ($dbColumns as $dbColumn)
					@php
						if (in_array($dbColumn, $dontInclude)) {
							continue;
						}
						$label = ucfirst(str_replace('_', ' ', str_replace('_id', '', $dbColumn)));
					@endphp
					<li>
						<a href="javascript:void(0)" class="dropdown-item" data-value="{{ $dbColumn }}" tabIndex="-1">
							<input type="checkbox" checked/> 
							{{ $label }}
						</a>
					</li>
				@endforeach
			</div>
		</div>

	</div>

	
@endif

@push('after_scripts')
{{-- TODO:: add sweetalert2 for progress bar --}}
{{-- TODO:: fix lang/trans message --}}

<x-export-columns :exportColumns="$dbColumns" ></x-export-columns>

<script>
	if (typeof bulkEntries != 'function') {
		function bulkEntries(button) {
			var route = "{{ url($crud->route) }}/export";

			// submit an AJAX delete call
			$.ajax({
				url: route,
				type: 'get',
				data: { 
					entries: crud.checkedItems, 
					model : "{{ $crud->model->model }}", 
					exportColumns : exportColumns,  
				},
				success: function(result) {
					console.log(result);

					if (result) {
					  window.location = result;
					  
					  // Show a success notification bubble
					  new Noty({
					    type: "success",
					    text: "<strong>{!! trans('lang.export_sucess_title') !!}</strong><br>{!! trans('lang.export_sucess_message') !!}"
					  }).show();
					} else {
					  	// Show a warning notification bubble
						new Noty({
							type: "warning",
							text: "<strong>{!! trans('lang.export_error_title') !!}</strong><br>{!! trans('lang.export_error_message') !!}"
						}).show();
					}

				  	crud.checkedItems = [];
				  	crud.table.ajax.reload();
				},
				error: function(result) {
					// Show an alert with the result
					new Noty({
						type: "warning",
						text: "<strong>{!! trans('lang.export_error_title') !!}</strong><br>{!! trans('lang.export_error_message') !!}"
					}).show();
				}
			});
		}
	}
</script>
@endpush
