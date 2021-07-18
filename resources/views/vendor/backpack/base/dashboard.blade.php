@extends(backpack_view('blank'))

{{-- TODO:: --}}
@php
    if (emp()->timeClock()['show']) {
        $buttonIn = (emp()->timeClock()['in']) ? ' <a href="#" class="mb-1 btn btn-info btn-sm"> '.trans('lang.clock_button_in').' </a>' : null;
        $buttonOut = (emp()->timeClock()['out']) ? ' <a href="#" class="mb-1 btn btn-danger btn-sm"> '.trans('lang.clock_button_out').' </a>' : null;
        $buttonBreakStart = (emp()->timeClock()['breakStart']) ? ' <a href="#" class="mb-1 btn btn-warning btn-sm"> '.trans('lang.clock_button_break_start').' </a>' : null;
        $buttonBreakEnd = (emp()->timeClock()['breakEnd']) ? ' <a href="#" class="mb-1 btn btn-success btn-sm"> '.trans('lang.clock_button_break_end').' </a>' : null;

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
    }
@endphp


@section('content')

@endsection