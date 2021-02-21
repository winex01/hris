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
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </div>
      </div>
    </div><!-- /.box -->

  </div>
</div>
@endsection


@section('after_styles')
@endsection

@section('after_scripts')
@endsection