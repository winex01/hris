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
            [
              'type' => 'card',
              // 'wrapperClass' => 'col-sm-6 col-md-4', // optional
              // 'class' => 'card bg-dark text-white', // optional
              'content' => [
                  'header' => 'Another card title', // optional
                  'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis non mi nec orci euismod venenatis. Integer quis sapien et diam facilisis facilisis ultricies quis justo. Phasellus sem <b>turpis</b>, ornare quis aliquet ut, volutpat et lectus. Aliquam a egestas elit. <i>Nulla posuere</i>, sem et porttitor mollis, massa nibh sagittis nibh, id porttitor nibh turpis sed arcu.',
              ]
            ],
            [
              'type' => 'card',
              // 'wrapperClass' => 'col-sm-6 col-md-4', // optional
              // 'class' => 'card bg-dark text-white', // optional
              'content' => [
                  'header' => 'Yet another card title', // optional
                  'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis non mi nec orci euismod venenatis. Integer quis sapien et diam facilisis facilisis ultricies quis justo. Phasellus sem <b>turpis</b>, ornare quis aliquet ut, volutpat et lectus. Aliquam a egestas elit. <i>Nulla posuere</i>, sem et porttitor mollis, massa nibh sagittis nibh, id porttitor nibh turpis sed arcu.',
              ]
            ],

              [
              'type' => 'card',
              // 'wrapperClass' => 'col-sm-6 col-md-4', // optional
              // 'class' => 'card bg-dark text-white', // optional
              'content' => [
                  'header' => 'Lorem Ipsum', // optional
                  'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis non mi nec orci euismod venenatis. Integer quis sapien et diam facilisis facilisis ultricies quis justo. Phasellus sem <b>turpis</b>, ornare quis aliquet ut, volutpat et lectus. Aliquam a egestas elit. <i>Nulla posuere</i>, sem et porttitor mollis, massa nibh sagittis nibh, id porttitor nibh turpis sed arcu.',
              ]
            ],

              [
              'type' => 'card',
              // 'wrapperClass' => 'col-sm-6 col-md-4', // optional
              // 'class' => 'card bg-dark text-white', // optional
              'content' => [
                  'header' => 'Test Card Title', // optional
                  'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis non mi nec orci euismod venenatis. Integer quis sapien et diam facilisis facilisis ultricies quis justo. Phasellus sem <b>turpis</b>, ornare quis aliquet ut, volutpat et lectus. Aliquam a egestas elit. <i>Nulla posuere</i>, sem et porttitor mollis, massa nibh sagittis nibh, id porttitor nibh turpis sed arcu.',
              ]
            ],
      ]
    ];

@endphp

{{-- TODO:: --}}
@section('content')

    {{-- <button type="button" class="btn btn-info btn-sm"> {!! trans('lang.clock_button_in') !!} </button> --}}
    {{-- <button type="button" class="btn btn-danger btn-sm"> {!! trans('lang.clock_button_out') !!} </button> --}}
    {{-- <button type="button" class="btn btn-warning btn-sm"> {!! trans('lang.clock_button_break_start') !!} </button> --}}
    {{-- <button type="button" class="btn btn-success btn-sm"> {!! trans('lang.clock_button_break_end') !!} </button> --}}

    <div class="mb-2"></div>
@endsection