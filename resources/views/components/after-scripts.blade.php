@push('after_scripts')
	<script type="text/javascript">
		var el = $('.breadcrumb-item.text-capitalize').first().find('a').html('Home');

		$(document).on('show.bs.modal', '.modal', function () {
	        $(this).appendTo('body');
	    });

	</script>
@endpush