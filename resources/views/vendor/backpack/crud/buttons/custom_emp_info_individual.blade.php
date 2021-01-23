@push('custom_export_dropdown')
	{{-- push to custom_export_blade.php dropdown as button --}}	
	<a href="javascript:void(0)" class="dropdown-item text-sm-left" data-export-type="html" onclick="printIndividual(this)">Print Individual</a>
@endpush

@push('custom_export_js')
<script type="text/javascript">
	
	if (typeof printIndividual != 'function') {
		function printIndividual(button) {

			// TODO:: here
			alert('all right');
			
		}
	}


</script>	
@endpush