@extends(backpack_view('blank'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('lang.calendar') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
  <section class="container-fluid d-print-none">
      <a href="javascript:void(0)" onclick="printData()" class="btn btn-sm btn-success float-right"><i class="la la-print"></i> Print </a>
    <h2>
          <small>{!! $crud->getSubheading() ?? mb_ucfirst(trans('lang.calendar')).' '.$crud->entity_name !!}.</small>
          @if ($crud->hasAccess('list'))
            <small class=""><a href="{{ url($crud->route) }}" class="font-sm"><i class="la la-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
          @endif
      </h2>
    </section>
@endsection

@section('content')
<div class="row">
  <div id="print-div" class="{{ $crud->getShowContentClass() }}">

  <!-- Default box -->
    <div class="">
      @if ($crud->model->translationEnabled())
      <div class="row">
        <div class="col-md-12 mb-2">
        <!-- Change translation button group -->
        <div class="btn-group float-right">
          <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{trans('backpack::crud.language')}}: {{ $crud->model->getAvailableLocales()[request()->input('locale')?request()->input('locale'):App::getLocale()] }} &nbsp; <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            @foreach ($crud->model->getAvailableLocales() as $key => $locale)
              <a class="dropdown-item" href="{{ url($crud->route.'/'.$entry->getKey().'/show') }}?locale={{ $key }}">{{ $locale }}</a>
            @endforeach
          </ul>
        </div>
      </div>
      </div>
      @else
      @endif
      <div class="card">
        <div class="card-header with-border">
            {!! $calendar->calendar() !!}
        </div>
      </div>
    </div><!-- /.box -->

  </div>
</div>
@endsection


@section('after_styles')
  <link href="{{ asset('packages/fullcalendar/2.2.7/fullcalendar.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('after_scripts')
  <script src="{{ asset('packages/fullcalendar/2.2.7/moment.min.js') }}"></script>
  <script src="{{ asset('packages/fullcalendar/2.2.7/fullcalendar.min.js') }}"></script>
  {!! $calendar->script() !!}

  @include('crud::inc.custom_printData')
@endsection