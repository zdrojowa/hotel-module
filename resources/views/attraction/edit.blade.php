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
                            <b-icon-controller></b-icon-controller> Atrakcje
                        </template>
                        @if (isset($attraction))
                            <main-info :_id=`{{ $attraction->_id }}` _name="attraction">
                                {{ csrf_field() }}
                            </main-info>
                        @else
                            <main-info id="0" _name="attraction">
                                {{ csrf_field() }}
                            </main-info>
                        @endif
                    </b-tab>
                    @if(isset($attraction))
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-clock></b-icon-clock> Termin pracy
                            </template>
                            <work-time :_id=`{{ $attraction->_id }}` url_get="/api/hotels-attraction" url_post="/dashboard/hotels-attraction/">
                                {{ csrf_field() }}
                            </work-time>
                        </b-tab>
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-card-text></b-icon-card-text> Opis
                            </template>
                            <description :_id=`{{ $attraction->_id }}` url_get="/api/hotels-attraction" url_post="/dashboard/hotels-attraction/" field="descriptions">
                                {{ csrf_field() }}
                            </description>
                        </b-tab>
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-ui-checks></b-icon-ui-checks> Warunki
                            </template>
                            <attraction :_id=`{{ $attraction->_id }}`>
                                {{ csrf_field() }}
                            </attraction>
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
