@extends('DashboardModule::base')

@section('title','Dashboard')

@section('stylesheets')
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
                            <b-nav-item href="/dashboard/hotels-conference/{{ $hotel->_id }}">Konferencje</b-nav-item>
                            <b-nav-item active>Propozycje</b-nav-item>
                        </b-navbar-nav>
                    @endisset
                </b-collapse>
            </b-navbar>

            <b-card no-body>
                <b-tabs card>
                    <b-tab active>
                        <template v-slot:title>
                            <b-icon-brightness-high></b-icon-brightness-high> Propozycje
                        </template>
                        <suggestion _id="{{ $hotel->_id }}">
                            {{ csrf_field() }}
                        </suggestion>
                    </b-tab>
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
