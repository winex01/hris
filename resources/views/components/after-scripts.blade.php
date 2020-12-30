@push('after_scripts')
	<script type="text/javascript">
		{{-- fix modal tab-index --}}
		$(document).on('show.bs.modal', '.modal', function () {
	        $(this).appendTo('body');
	    });

	</script>
@endpush