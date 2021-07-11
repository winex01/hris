<!-- This file is used to store topbar (left) items -->
<x-clock></x-clock>

{{-- TODO:: --}}
@if (emp()->clockLoggerButton()['show'])
	<li class="nav-item px-3 ml-n4">
		<button 
			{{ emp()->clockLoggerButton()['in'] }}
			onclick="loggedClock(1)" class="btn btn-info btn-sm"><i class="las la-clock"></i> IN &nbsp; &nbsp;
		</button>
		<button 
			{{ emp()->clockLoggerButton()['out'] }}
			onclick="loggedClock(2)" class="btn btn-secondary btn-sm ml-1"><i class="las la-stopwatch"></i> OUT
		</button>
		<button 
			{{ emp()->clockLoggerButton()['breakStart'] }}
			onclick="loggedClock(3)" class="btn btn-danger btn-sm ml-1"><i class="las la-hourglass-start"></i> BREAK START
		</button>
		<button 
			{{ emp()->clockLoggerButton()['breakEnd'] }}
			onclick="loggedClock(4)" class="btn btn-danger btn-sm ml-1"><i class="las la-hourglass-end"></i> BREAK END
		</button> 
	</li>

	@push('after_scripts')
	<script type="text/javascript">
		function loggedClock(type) {
			$.ajax({
				url: '{{ route('dtrlogs.loggedClock') }}',
				type: 'post',
				data: {
					empId : '{{ emp()->id }}',
					type : type
				},
				success: function (data) {
					if (data) {
	                    window.swal({
				          title: data.text,
				          icon: "success",
				          timer: 2000,
				        });
					}
				},
				error: function () {
					window.location.href = "{{ request()->url() }}";
				}
			});
		}
	</script>
	@endpush
@endif

