@extends(backpack_view('blank'))

@php
  // dump($crud);
@endphp

@section('header')
  <div class="container-fluid">
    <h2>
        <span class="text-capitalize">{!! $title !!}</span>
        {{-- <small>{!! $title !!}.</small> --}}

        @if ($crud->hasAccess('calendar'))
          <small><a href="{{ url($crud->route) }}" class="hidden-print font-sm"><i class="la la-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
        @endif
    </h2>
  </div>
@endsection

@section('content')
  <!-- Default box -->
  <div class="row">

    <!-- THE ACTUAL CONTENT -->
    <div class="{{ $crud->getListContentClass() }}">
      <div class="card">
        <div class="card-header with-border">

          <div class="form-group">
            <select class="form-control col-md-3 calendar">
              {{-- TODO:: populate with employee --}}
              <option value="1">One</option>
              <option value="2">Two</option>
              <option value="3">Three</option>
              <option value="4">Four</option>
            </select>
          </div>

        </div>
      </div>
    </div>
  </div>{{-- end row --}}

@endsection


@section('after_styles')
<!-- include select2 css-->
<link href="{{ asset('packages/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('after_scripts')
<!-- include select2 js-->
<script src="{{ asset('packages/select2/dist/js/select2.full.min.js') }}"></script>
@if (app()->getLocale() !== 'en')
<script src="{{ asset('packages/select2/dist/js/i18n/' . app()->getLocale() . '.js') }}"></script>
@endif
<script>
$(document).ready(function() {
    $('.calendar').select2({
      placeholder: "Select employee",
      allowClear: true
    });
});
</script>
@endsection