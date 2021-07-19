@extends(backpack_view('blank'))

{{-- TODO:: Employee Time Clock --}}
@if (emp()->timeClock()['show']) 
    @php
        $buttonIn = (emp()->timeClock()['in']) ? '' : 'display:none;';
        $buttonOut = (emp()->timeClock()['out']) ? ' ' : 'display:none;';
        $buttonBreakStart = (emp()->timeClock()['breakStart']) ? ' ' : 'display:none;';
        $buttonBreakEnd = (emp()->timeClock()['breakEnd']) ? ' ' : 'display:none;';

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
                      'body' => '
                        <button id="buttonIn" style="'.$buttonIn.'" value="1" class="timeClockButton mb-1 btn btn-info btn-sm"> '.trans('lang.clock_button_in').' </button>
                        <button id="buttonOut" style="'.$buttonOut.'" value="2" class="timeClockButton mb-1 btn btn-danger btn-sm"> '.trans('lang.clock_button_out').' </button>
                        <button id="buttonBreakStart" style="'.$buttonBreakStart.'" value="3" class="timeClockButton mb-1 btn btn-warning btn-sm"> '.trans('lang.clock_button_break_start').' </button>
                        <button id="buttonBreakEnd" style="'.$buttonBreakEnd.'" value="4" class="timeClockButton mb-1 btn btn-success btn-sm"> '.trans('lang.clock_button_break_end').' </button>
                      ',
                  ]
                ],
          ]
        ];
    @endphp

    @push('after_scripts')
        <script type="text/javascript">
            $('.timeClockButton').click(function() {
                var temp = $(this);
                $.ajax({
                    url: '{{ route('employeetimeclock.loggedTime') }}',
                    type: 'post',
                    data: {
                        empId : '{{ emp()->id }}',
                        type : temp.val()
                    },
                    success: function (data) {
                        if (data && data.timeClock.show) {
                            console.log(data);
                            if (data.error) {
                                window.swal({
                                  title: "Error!",
                                  text: data.msg,
                                  icon: "error",
                                });
                            }else {
                                temp.hide();
                                
                                if (data.timeClock.in) {
                                    $('#buttonIn').show();
                                }

                                if (data.timeClock.out) {
                                    $('#buttonOut').show();
                                }

                                if (data.timeClock.breakStart) {
                                    $('#buttonBreakStart').show();
                                }

                                if (data.timeClock.breakEnd) {
                                    $('#buttonBreakEnd').show();
                                }

                                window.swal({
                                  text: data.msg,
                                  icon: "success",
                                  timer: 2000,
                                });
                            }
                        }// end data && data.show
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