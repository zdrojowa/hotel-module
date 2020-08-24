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
                            <b-icon-building></b-icon-building> Apartament
                        </template>
                        @if (isset($apartment))
                            <apartment :_id=`{{ $apartment->_id }}`>
                                {{ csrf_field() }}
                            </apartment>
                        @else
                            <apartment :_hotel=`{{ $hotel }}`>
                                {{ csrf_field() }}
                            </apartment>
                        @endif
                    </b-tab>
                    @if(isset($apartment))
                        <b-tab>
                            <template v-slot:title>
                                <b-icon-plus></b-icon-plus> Udogodnienia
                            </template>
                            <icon :_id=`{{ $apartment->_id }}` url_get="/api/hotels/apartments" url_post="/dashboard/hotels-apartments/" field="conveniences">
                                {{ csrf_field() }}
                            </icon>
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
    <script src="{{ mix('vendor/js/HotelModule.js') }}"></script>
@endsection
