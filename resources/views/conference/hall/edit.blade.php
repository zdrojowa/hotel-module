@extends('DashboardModule::base')

@section('title','Dashboard')

@section('stylesheets')
    <link rel="stylesheet" href="{{ mix('vendor/css/MediaManager.css','') }}">
    <link rel="stylesheet" href="{{ mix('vendor/css/HotelModule.css','') }}">
@endsection

@section('sidebar')
    @include('DashboardModule::sidebar.index', ['menu' => Selene\Support\Facades\MenuRepository::getPresences()])
@endsection

@section('content')
    <div class="content-wrapper">
        <div id="app">
            <b-navbar toggleable="lg" type="dark" variant="dark">
                <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

                <b-collapse id="nav-collapse" is-nav>
                    <b-navbar-nav>
                        <b-nav-item active>Sala</b-nav-item>
                    </b-navbar-nav>

                    <!-- Right aligned nav items -->
                    <b-navbar-nav class="ml-auto">
                        @isset($hotel)
                            <b-nav-item href="/dashboard/hotels-conference-hall/{{ $hotel->_id }}">Lista sal</b-nav-item>
                        @else
                            @isset($hall)
                                <b-nav-item href="/dashboard/hotels-conference-hall/{{ $hall->hotel }}">Lista sal</b-nav-item>
                            @endisset
                        @endisset
                    </b-navbar-nav>
                </b-collapse>
            </b-navbar>

            <b-card no-body class="px-5 py-5">
                @if (isset($hall))
                    <hall _id="{{ $hall->_id }}">
                        {{ csrf_field() }}
                    </hall>
                @else
                    <hall _id="0">
                        {{ csrf_field() }}
                    </hall>
                @endif
            </b-card>
        </div>
    </div>
@endsection

@section('javascripts')
    @parent
    @javascript('csrf', csrf_token())
    @javascript('ajaxUpload', route('MediaManager::media.upload.ajax'))
    @javascript('infoUrl', route('MediaManager::media.image.info', ['media' => '%%id%%']))
    <script src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@13.0.1/dist/lazyload.min.js"></script>
    <script src="{{ mix('vendor/js/MediaManager.js') }}"></script>
    <script src="{{ mix('vendor/js/HotelModule.js') }}"></script>
@endsection
