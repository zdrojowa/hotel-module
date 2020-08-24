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
                            <b-icon-cup></b-icon-cup> Kuchnia
                        </template>
                        @if (isset($kitchen))
                            <kitchen :_id=`{{ $kitchen->_id }}`>
                                {{ csrf_field() }}
                            </kitchen>
                        @else
                            <kitchen :_hotel=`{{ $hotel }}`>
                                {{ csrf_field() }}
                            </kitchen>
                        @endif
                    </b-tab>
                    @if(isset($kitchen))
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-clock></b-icon-clock> Termin pracy
                            </template>
                            <work-time :_id=`{{ $kitchen->_id }}` url_get="/api/hotels/kitchen" url_post="/dashboard/hotels-kitchen/">
                                {{ csrf_field() }}
                            </work-time>
                        </b-tab>
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-card-text></b-icon-card-text> Opis
                            </template>
                            <description :_id=`{{ $kitchen->_id }}` url_get="/api/hotels/kitchen" url_post="/dashboard/hotels-kitchen/" field="descriptions">
                                {{ csrf_field() }}
                            </description>
                        </b-tab>
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-images></b-icon-images> Galeria
                            </template>
                            <gallery :_id=`{{ $kitchen->_id }}` url_get="/api/hotels/kitchen" url_post="/dashboard/hotels-kitchen/" field="images">
                                {{ csrf_field() }}
                            </gallery>
                        </b-tab>
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-files></b-icon-files> Pliki
                            </template>
                            <files :_id=`{{ $kitchen->_id }}` url_get="/api/hotels/kitchen" url_post="/dashboard/hotels-kitchen/">
                                {{ csrf_field() }}
                            </files>
                        </b-tab>
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-trophy></b-icon-trophy> Nagrody
                            </template>
                            <icon :_id=`{{ $kitchen->_id }}` url_get="/api/hotels/kitchen" url_post="/dashboard/hotels-kitchen/" field="awards">
                                {{ csrf_field() }}
                            </icon>
                        </b-tab>
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-person></b-icon-person> Sociale
                            </template>
                            <socials :_id=`{{ $kitchen->_id }}` url_get="/api/hotels/kitchen" url_post="/dashboard/hotels-kitchen/">
                                {{ csrf_field() }}
                            </socials>
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
