@extends(backpack_view('blank'))

@php
    $widgets['after_content'][] = [
        'type'        => 'jumbotron',
        'heading'     => trans('backpack::base.welcome'),
        'content'     => trans('backpack::base.use_sidebar'),
        // 'button_link' => backpack_url('logout'),
        'button_text' => trans('backpack::base.logout'),
    ];
    
@endphp

{{-- TODO:: --}}
@section('content')

    <button class="btn btn-info btn-sm"> {!! trans('lang.clock_button_in') !!} </button>

    <button class="btn btn-danger btn-sm ml-1"> {!! trans('lang.clock_button_out') !!} </button>

    <button class="btn btn-warning btn-sm ml-1"> {!! trans('lang.clock_button_break_start') !!} </button>

    <button class="btn btn-success btn-sm ml-1"> {!! trans('lang.clock_button_break_end') !!} </button> 

    <div class="mb-2"></div>

@endsection