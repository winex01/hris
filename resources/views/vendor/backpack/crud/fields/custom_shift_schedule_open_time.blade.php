@include('crud::fields.radio')

@push('crud_fields_scripts')
<script type="text/javascript">

	if ( $('input[name="open_time"]').val() == 1 ) {
		$('.group-hiddenable').hide();
	}

	$('input[name="open_time_radio_button"]').change(function(event) {
		if (this.value == 0) {
			$('.group-hiddenable').show();
		}else if (this.value == 1) {
			$('.group-hiddenable').hide();
		} 
	});
</script>
@endpush