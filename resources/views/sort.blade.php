@extends('DashboardModule::base')

@section('title','Dashboard: Hotels sort')

@section('stylesheets')
    <link rel="stylesheet" href="{{ mix('vendor/css/HotelModule.css','') }}">
@endsection

@section('sidebar')
    @include('DashboardModule::sidebar.index', ['menu' => Selene\Support\Facades\MenuRepository::getPresences()])
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card" id="app">
                    <div class="card-header clearfix">
                        <h4 class="card-title float-left">Sortuj Hotele</h4>
                        <a href="{{route('HotelModule::hotels')}}" class="text-warning float-right">
                            <i class="mdi mdi-view-list"></i> Do listy
                        </a>
                    </div>
                    <div class="card-body">
                        <list>
                            {{ csrf_field() }}
                        </list>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascripts')
    @parent
    @javascript('csrf', csrf_token())
    <script src="{{ mix('vendor/js/HotelModule.js') }}"></script>
@endsection