@extends(backpack_view('blank'))

@php
    // $widgets['after_content'][] = [
    //     'type'        => 'jumbotron',
    //     'heading'     => trans('backpack::base.welcome'),
    //     'content'     => trans('backpack::base.use_sidebar'),
    //     'button_link' => backpack_url('logout'),
    //     'button_text' => trans('backpack::base.logout'),
    // ];
@endphp

@section('content')
  {{-- TODO:: --}}
    <div class="mb-2">
        <button 
        {{-- @if (!emp()->clockLoggerButton()['in']) style="display:none;" @endif --}}
            class="btn btn-info btn-md"> {!! trans('lang.clock_button_in') !!}
        </button>

        <button 
            {{-- @if (!emp()->clockLoggerButton()['out']) style="display:none;" @endif --}}
            class="btn btn-danger btn-md ml-1"> {!! trans('lang.clock_button_out') !!}
        </button>

        <button 
            {{-- @if (!emp()->clockLoggerButton()['breakStart']) style="display:none;" @endif --}}
            class="btn btn-success btn-md ml-1"> {!! trans('lang.clock_button_break_start') !!}
        </button>

        <button 
            {{-- @if (!emp()->clockLoggerButton()['breakEnd']) style="display:none;" @endif --}}
            class="btn btn-danger btn-md ml-1"> {!! trans('lang.clock_button_break_end') !!}
        </button> 
    </div>
@endsection