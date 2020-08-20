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
            <b-card no-body>
                <b-tabs card>
                    <b-tab active>
                        <template v-slot:title>
                            <b-icon-flower1></b-icon-flower1> SPA
                        </template>
                        @if (isset($spa))
                            <spa :_id=`{{ $spa->_id }}`>
                                {{ csrf_field() }}
                            </spa>
                        @else
                            <spa :_hotel=`{{ $hotel }}`>
                                {{ csrf_field() }}
                            </spa>
                        @endif
                    </b-tab>
                    @if(isset($spa))
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-clock></b-icon-clock> Termin pracy
                            </template>
                            <work-time :_id=`{{ $spa->_id }}` url_get="/api/hotels/spa" url_post="/dashboard/hotels-spa/">
                                {{ csrf_field() }}
                            </work-time>
                        </b-tab>
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-card-text></b-icon-card-text> Opis
                            </template>
                            <description :_id=`{{ $spa->_id }}` url_get="/api/hotels/spa" url_post="/dashboard/hotels-spa/" field="descriptions">
                                {{ csrf_field() }}
                            </description>
                        </b-tab>
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-images></b-icon-images> Galeria
                            </template>
                            <spa-gallery :id=`{{ $spa->_id }}`>
                                {{ csrf_field() }}
                            </spa-gallery>
                        </b-tab>
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-lightning></b-icon-lightning> Highlights
                            </template>
                            <spa-highlights :id=`{{ $spa->_id }}`>
                                {{ csrf_field() }}
                            </spa-highlights>
                        </b-tab>
                    @endif
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
