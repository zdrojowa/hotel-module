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
                        <b-nav-item active>Hotel</b-nav-item>
                    </b-navbar-nav>

                    @isset($hotel)
                        <!-- Right aligned nav items -->
                        <b-navbar-nav class="ml-auto">
                            <b-nav-item href="/dashboard/hotels-spa/{{ $hotel->_id }}">SPA</b-nav-item>
                            <b-nav-item href="/dashboard/hotels-wellness/{{ $hotel->_id }}">Wellness</b-nav-item>
                            <b-nav-item href="/dashboard/hotels-conference/{{ $hotel->_id }}">Konferencje</b-nav-item>
                            <b-nav-item href="/dashboard/hotels-suggestions/{{ $hotel->_id }}">Propozycje</b-nav-item>
                        </b-navbar-nav>
                    @endisset
                </b-collapse>
            </b-navbar>

            <b-card no-body>
                <b-tabs card>
                    <b-tab active>
                        <template v-slot:title>
                            <b-icon-pencil></b-icon-pencil> Hotel
                        </template>
                        @isset($hotel)
                            <hotel :_id=`{{ $hotel->_id }}`>
                                {{ csrf_field() }}
                            </hotel>
                        @else
                            <hotel :_id="0">
                                {{ csrf_field() }}
                            </hotel>
                        @endisset
                    </b-tab>
                    @isset($hotel)
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-info></b-icon-info> Informacja
                            </template>
                            <info :_id=`{{ $hotel->_id }}`>
                                {{ csrf_field() }}
                            </info>
                        </b-tab>
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-link></b-icon-link> Sociale
                            </template>
                            <socials :_id=`{{ $hotel->_id }}` url_get="/api/hotels" url_post="/dashboard/hotels/">
                                {{ csrf_field() }}
                            </socials>
                        </b-tab>
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-person></b-icon-person> Dzieci
                            </template>
                            <description :_id=`{{ $hotel->_id }}` url_get="/api/hotels" url_post="/dashboard/hotels/" field="children">
                                {{ csrf_field() }}
                            </description>
                        </b-tab>
                        <b-tab>
                            <template v-slot:title>
                                P Parking
                            </template>
                            <description :_id=`{{ $hotel->_id }}` url_get="/api/hotels" url_post="/dashboard/hotels/" field="parking">
                                {{ csrf_field() }}
                            </description>
                        </b-tab>
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-key></b-icon-key> Wypożyczalnia
                            </template>
                            <description :_id=`{{ $hotel->_id }}` url_get="/api/hotels" url_post="/dashboard/hotels/" field="rental">
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
