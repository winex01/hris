@extends(backpack_view('blank'))

@php
    // $widgets['before_content'][] = [
    //     'type'        => 'jumbotron',
    //     'heading'     => trans('backpack::base.welcome'),
    //     'content'     => trans('backpack::base.use_sidebar'),
    //     // 'button_link' => backpack_url('logout'),
    //     // 'button_text' => trans('backpack::base.logout'),
    // ];

    $widgets['after_content'][] = [
      'type' => 'div',
      'class' => 'row',
      'content' => [ // widgets 
           [
              'type' => 'card',
              'wrapperClass' => 'col-sm-12 col-md-12', // optional
              // 'class' => 'card bg-white text-center', // optional
              'content' => [
                  'header' => 'Employee Time Clock', // optional
                  'body' => '
                        <button type="button" class="mb-1 btn btn-info btn-sm"> '.trans('lang.clock_button_in').' </button>
                        <button type="button" class="mb-1 btn btn-danger btn-sm"> '.trans('lang.clock_button_out').' </button>
                        <button type="button" class="mb-1 btn btn-warning btn-sm"> '.trans('lang.clock_button_break_start').' </button>
                        <button type="button" class="mb-1 btn btn-success btn-sm"> '.trans('lang.clock_button_break_end').' </button>
                  ',
              ]
            ],
      ]
    ];

@endphp

{{-- TODO:: --}}
@section('content')

@endsection