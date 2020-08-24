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
                        <b-nav-item active>Konfiguracja</b-nav-item>
                    </b-navbar-nav>

                    <!-- Right aligned nav items -->
                    <b-navbar-nav class="ml-auto">
                        @isset($hall)
                            <b-nav-item href="/dashboard/hotels-conference-configuration/{{ $hall->id }}">
                                Lista konfiguracji
                            </b-nav-item>
                        @else
                            @isset($configuration)
                                <b-nav-item href="/dashboard/hotels-conference-configuration/{{ $configuration->hall }}">
                                    Lista konfiguracji
                                </b-nav-item>
                            @endisset
                        @endisset
                    </b-navbar-nav>
                </b-collapse>
            </b-navbar>

            <b-card no-body>
                <b-tabs card>
                    <b-tab active>
                        <template v-slot:title>
                            <b-icon-diagram2></b-icon-diagram2> Konfiguracja
                        </template>
                        @if (isset($configuration))
                            <configuration :_id=`{{ $configuration->_id }}`>
                                {{ csrf_field() }}
                            </configuration>
                        @else
                            <configuration _id="0">
                                {{ csrf_field() }}
                            </configuration>
                        @endif
                    </b-tab>
                    @if (isset($configuration))
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-card-text></b-icon-card-text> Opis
                            </template>
                            <description :_id=`{{ $configuration->_id }}` url_get="/api/hotels-conference-configuration" url_post="/dashboard/hotels-conference-configuration/" field="descriptions">
                                {{ csrf_field() }}
                            </description>
                        </b-tab>
                    @endisset
                </b-tabs>
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
