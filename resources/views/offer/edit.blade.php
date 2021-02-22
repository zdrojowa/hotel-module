@extends('DashboardModule::base')

@section('title','Dashboard')

@section('stylesheets')
    <link rel="stylesheet" href="{{ mix('vendor/css/MediaManager.css') }}">
    <link rel="stylesheet" href="{{ mix('vendor/css/HotelModule.css') }}">
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
                            <b-icon-calendar></b-icon-calendar> Oferta
                        </template>
                        @if (isset($offer))
                            <editor :_id=`{{ $offer->_id }}`>
                                {{ csrf_field() }}
                            </editor>
                        @else
                            <editor :_id="0">
                                {{ csrf_field() }}
                            </editor>
                        @endif
                    </b-tab>
                    @if(isset($offer))
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-file></b-icon-file> Program
                            </template>
                            <program :_id=`{{ $offer->_id }}`>
                                {{ csrf_field() }}
                            </program>
                        </b-tab>
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-file></b-icon-file> Warunki
                            </template>
                            <conditions :_id=`{{ $offer->_id }}`>
                                {{ csrf_field() }}
                            </conditions>
                        </b-tab>
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-bag></b-icon-bag> Pakiet
                            </template>
                            <pack :_id=`{{ $offer->_id }}`>
                                {{ csrf_field() }}
                            </pack>
                        </b-tab>
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-files></b-icon-files> Pliki
                            </template>
                            <files :_id=`{{ $offer->_id }}` url_get="/api/offers" url_post="/dashboard/offers/">
                                {{ csrf_field() }}
                            </files>
                        </b-tab>
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-plus></b-icon-plus> Udogodnienia
                            </template>
                            <convenience :_id=`{{ $offer->_id }}`>
                                {{ csrf_field() }}
                            </convenience>
                        </b-tab>
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-bookmarks></b-icon-bookmarks> Obekty
                            </template>
                            <objects :_id=`{{ $offer->_id }}`>
                                {{ csrf_field() }}
                            </objects>
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
