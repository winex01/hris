@extends(backpack_view('blank'))

{{-- TODO:: --}}
@php
    if (emp()->showTimeClock()) {
        $widgets['after_content'][] = [
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
                            <a href="#" class="mb-1 btn btn-info btn-sm"> '.trans('lang.clock_button_in').' </a>
                            <a href="#" class="mb-1 btn btn-danger btn-sm"> '.trans('lang.clock_button_out').' </a>
                            <a href="#" class="mb-1 btn btn-warning btn-sm"> '.trans('lang.clock_button_break_start').' </a>
                            <a href="#" class="mb-1 btn btn-success btn-sm"> '.trans('lang.clock_button_break_end').' </a>
                      ',
                  ]
                ],
          ]
        ];
    }
@endphp


@section('content')

@endsection