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
                <div class="card-header clearfix">
                    <h4 class="card-title float-left">Plan zajęć</h4>
                    @if(isset($schedule_item))
                        <a href="{{route('HotelModule::schedule-item', ['schedule' => $schedule_item->kids_club_schedule])}}" class="btn btn-primary float-right">
                            <i class="mdi mdi-keyboard-backspace"></i> Do listy
                        </a>
                    @endif
                </div>
                <b-tabs card>
                    <b-tab active>
                        <template v-slot:title>
                            <b-icon-key></b-icon-key> Rozkład godzin
                        </template>
                        @if (isset($schedule_item))
                            <kids-club-schedule-item :_id=`{{ $schedule_item->id }}` _name="schedule-item">
                                {{ csrf_field() }}
                            </kids-club-schedule-item>
                        @else
                            <kids-club-schedule-item _schedule_id="{{$schedule}}" _name="schedule-item">
                                {{ csrf_field() }}
                            </kids-club-schedule-item>
                        @endif
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
