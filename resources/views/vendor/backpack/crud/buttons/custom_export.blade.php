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

			@php
				// override using dbColumns method at contorller setup method
				if (!empty($crud->dbColumns())) {
					$dbColumns = $crud->dbColumns();
				}else {
					$dbColumns = getTableColumns($crud->model->getTable());
				}
				$dontInclude = config('hris.dont_include_in_exports');

				$dbColumns = collect($dbColumns)->chunk(12);
				// dd(count($dbColumns));
			@endphp
			<div class="dropdown-menu multi-column columns-{{ count($dbColumns) }}">
				<div class="row">
					@foreach ($dbColumns as $dbColumn)
						<div class="col-sm-{{ 12 / count($dbColumns) }}">
				            <ul class="multi-column-dropdown">
								@foreach ($dbColumn as $column)
									@php
										if (in_array($column, $dontInclude)) {
											continue;
										}
										$label = ucfirst(str_replace('_', ' ', str_replace('_id', '', $column)));
									@endphp
									<li>
										<a href="javascript:void(0)" class="dropdown-item" data-value="{{ $column }}" tabIndex="-1">
											<input type="checkbox" checked/> 
											{{ $label }}
										</a>
									</li>
								@endforeach
				            </ul>
			            </div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
@endif

@push('after_scripts')
{{-- TODO:: add sweetalert2 for progress bar --}}
{{-- TODO:: fix lang/trans message --}}

<x-export-columns :exportColumns="$dbColumns->flatten()->toArray()" ></x-export-columns>

<script>
	if (typeof bulkEntries != 'function') {
		function bulkEntries(button) {
			var route = "{{ url($crud->route) }}/export";

			// console.log(crud.checkedItems); 
			// return;

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
					// console.log(result);

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

@push('after_styles')
	{{-- https://codepen.io/dustlilac/pen/Qwpxbp --}}
	<style type="text/css">
		.dropdown-menu {
			min-width: 200px;
		}
		.dropdown-menu.columns-2 {
			min-width: 400px;
		}
		.dropdown-menu.columns-3 {
			min-width: 600px;
		}
		.dropdown-menu li a {
			padding: 5px 15px;
			font-weight: 300;
		}
		.multi-column-dropdown {
			list-style: none;
		  margin: 0px;
		  padding: 0px;
		}
		.multi-column-dropdown li a {
			display: block;
			clear: both;
			line-height: 1.428571429;
			color: #333;
			white-space: normal;
		}
		.multi-column-dropdown li a:hover {
			text-decoration: none;
			color: #262626;
			background-color: #999;
		}
		 
		@media (max-width: 767px) {
			.dropdown-menu.multi-column {
				min-width: 240px !important;
				overflow-x: hidden;
			}
		}
	</style>
@endpush