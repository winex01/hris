@include('crud::fields.custom_table')

@push('crud_fields_scripts')
<script src="{{ asset('packages/moment/min/moment.min.js') }}"></script>
<script type="text/javascript">
	$('input[name="working_hours"]').parent().find('tbody').on('keyup click input', function() {
		var tempWh = JSON.parse(
			$('input[name="working_hours"]').val()
		);
		tempWh = tempWh[0].start;
		
		if (tempWh){
			var deductHours = moment.duration('03:00', "HH:mm");
			tempWh = moment.duration(tempWh, "HH:mm");
			tempWh = tempWh.subtract(deductHours);
			tempWh = tempWh.hours() + ":" + tempWh.minutes();

			tempWh = moment(tempWh, "HH:mm").format("HH:mm");

			$('input[name="relative_day_start"]').val(tempWh);
		}
    });
</script>
@endpush