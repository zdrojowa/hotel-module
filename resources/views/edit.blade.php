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
                            <b-icon-pencil></b-icon-pencil> Hotel
                        </template>
                        @if (isset($hotel))
                            <hotel :_id=`{{ $hotel->_id }}`>
                                {{ csrf_field() }}
                            </hotel>
                        @else
                            <hotel :_id="0">
                                {{ csrf_field() }}
                            </hotel>
                        @endif
                    </b-tab>
                    @if(isset($hotel))
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
                                <b-icon-person></b-icon-person> Sociale
                            </template>
                            <socials :_id=`{{ $hotel->_id }}`>
                                {{ csrf_field() }}
                            </socials>
                        </b-tab>
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-person></b-icon-person> Dzieci
                            </template>
                            <children :_id=`{{ $hotel->_id }}`>
                                {{ csrf_field() }}
                            </children>
                        </b-tab>
                        <b-tab>
                            <template v-slot:title>
                                P Parking
                            </template>
                            <parking :_id=`{{ $hotel->_id }}`>
                                {{ csrf_field() }}
                            </parking>
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
