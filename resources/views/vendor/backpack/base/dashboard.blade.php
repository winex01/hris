@extends(backpack_view('blank'))

{{-- TODO:: Employee Time Clock --}}
@if (emp()->timeClock()['show']) 
    @php
        $buttonIn = (emp()->timeClock()['in']) ? ' <button value="1" class="timeClockButton mb-1 btn btn-info btn-sm"> '.trans('lang.clock_button_in').' </button>' : null;
        $buttonOut = (emp()->timeClock()['out']) ? ' <button value="2" class="timeClockButton mb-1 btn btn-danger btn-sm"> '.trans('lang.clock_button_out').' </button>' : null;
        $buttonBreakStart = (emp()->timeClock()['breakStart']) ? ' <button value="3" class="timeClockButton mb-1 btn btn-warning btn-sm"> '.trans('lang.clock_button_break_start').' </button>' : null;
        $buttonBreakEnd = (emp()->timeClock()['breakEnd']) ? ' <button value="4" class="timeClockButton mb-1 btn btn-success btn-sm"> '.trans('lang.clock_button_break_end').' </button>' : null;

        $widgets['before_content'][] = [
          'type' => 'div',
          'class' => 'row',
          'content' => [ // widgets 
               [
                  'type' => 'card',
                  'wrapperClass' => 'col-sm-12 col-md-2', // optional
                  // 'class' => 'card bg-white text-center', // optional
                  'content' => [
                      'header' => 'Employee Time Clock', // optional
                      'body' => $buttonIn.$buttonOut.$buttonBreakStart.$buttonBreakEnd,
                  ]
                ],
          ]
        ];
    @endphp

    @push('after_scripts')
        <script type="text/javascript">
            $('.timeClockButton').click(function() {
                $.ajax({
                    url: '{{ route('employeetimeclock.loggedTime') }}',
                    type: 'post',
                    data: {
                        empId : '{{ emp()->id }}',
                        type : $(this).val()
                    },
                    success: function (data) {
                        if (data) {
                            console.log(data);
                            if (data.error) {
                                window.swal({
                                  title: "Error!",
                                  text: data.msg,
                                  icon: "error",
                                });
                            }else {
                                // TODO::success
                            }
                        }
                    },
                    error: function () {
                        {{-- if not authenticated then redirect --}}
                        window.location.href = "{{ request()->url() }}";
                    }
                });
            });
        </script>
    @endpush
@endif


@section('content')

@endsection