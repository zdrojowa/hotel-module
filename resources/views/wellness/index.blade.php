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
                        <b-nav-item href="/dashboard/hotels/{{ $hotel->_id }}/edit">Hotel</b-nav-item>
                    </b-navbar-nav>

                @isset($hotel)
                    <!-- Right aligned nav items -->
                        <b-navbar-nav class="ml-auto">
                            <b-nav-item href="/dashboard/hotels-spa/{{ $hotel->_id }}">SPA</b-nav-item>
                            <b-nav-item active>Wellness</b-nav-item>
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
                            <b-icon-heart></b-icon-heart> Wellness
                        </template>
                        <wellness _id="{{ $hotel->_id }}">
                            {{ csrf_field() }}
                        </wellness>
                    </b-tab>
                    <b-tab>
                        <template v-slot:title>
                            <b-icon-info></b-icon-info> Informacja
                        </template>
                        <contact _id="{{ $hotel->_id }}" url_get="/api/hotels" url_post="/dashboard/hotels/" prefix="wellness_">
                            {{ csrf_field() }}
                        </contact>
                    </b-tab>
                    <b-tab>
                        <template v-slot:title>
                            <b-icon-clock></b-icon-clock> Termin pracy
                        </template>
                        <work-time :_id=`{{ $hotel->_id }}` url_get="/api/hotels" url_post="/dashboard/hotels/" prefix="wellness_">
                            {{ csrf_field() }}
                        </work-time>
                    </b-tab>
                    <b-tab>
                        <template v-slot:title>
                            <b-icon-files></b-icon-files> Pliki
                        </template>
                        <files :_id=`{{ $hotel->_id }}` url_get="/api/hotels" url_post="/dashboard/hotels/" prefix="wellness_">
                            {{ csrf_field() }}
                        </files>
                    </b-tab>
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
