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
                            <b-icon-key></b-icon-key> Wypożyczalnia
                        </template>
                        @if (isset($rent))
                            <rent :_id=`{{ $rent->_id }}`>
                                {{ csrf_field() }}
                            </rent>
                        @else
                            <rent :_id="0">
                                {{ csrf_field() }}
                            </rent>
                        @endif
                    </b-tab>
                    @if(isset($rent))
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-credit-card></b-icon-credit-card> Ceny
                            </template>
                            <price :_id=`{{ $rent->_id }}`>
                                {{ csrf_field() }}
                            </price>
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
