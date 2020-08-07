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
            <b-card no-body>
                <b-tabs card>
                    <b-tab active>
                        <template v-slot:title>
                            <b-icon-pencil></b-icon-pencil> Hotel
                        </template>
                        @include('DashboardModule::partials.alerts')
                        <form method="POST" action="{{ isset($hotel) ? route('HotelModule::hotels.update', ['hotel' => $hotel]) : route('HotelModule::hotels.store') }}" enctype="multipart/form-data">
                            @csrf
                            @if (isset($hotel))
                                @method('PUT')
                            @endif

                            <button type="submit" class="btn btn-primary float-right">Zapisz</button>

                            <div class="d-flex justify-content-center">
                                <div class="form-group @error('name') has-danger @enderror col-6">
                                    <label for="">Nazwa</label>
                                    <input type="text" class="form-control" name="name" placeholder="Wpisz nazwe" value="{{ isset($hotel) ? $hotel->name : old('name') }}">
                                    @error('name')
                                    <small class="error mt-1 text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group @error('full_name') has-danger @enderror col-6">
                                    <label for="">Pełna Nazwa</label>
                                    <input type="text" class="form-control" name="full_name" placeholder="Wpisz pełną nazwę" value="{{ isset($hotel) ? $hotel->full_name : old('full_name') }}">
                                    @error('full_name')
                                    <small class="error mt-1 text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-center">

                                <div class="form-group col-lg-4">
                                    <label for="logo">Logo</label>
                                    <input id="logo" type="file" name="logo_file" class="dropify" data-height="100" data-allowed-file-extensions="svg png jpg jpeg"
                                           @if(isset($hotel) && isset($hotel->logo))
                                           data-default-file="{{ asset($hotel->logo) }}"
                                           @endif
                                           data-max-file-size="1M">

                                    @error('logo_file')
                                    <small class="error mt-1 text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-4">
                                    <label for="photo">Zdjęcie obiektu</label>
                                    <input id="photo" type="file" name="photo_file" class="dropify" data-height="100" data-allowed-file-extensions="svg png jpg jpeg"
                                           @if(isset($hotel) && isset($hotel->photo))
                                           data-default-file="{{ asset($hotel->photo_file) }}"
                                           @endif
                                           data-max-file-size="1M">

                                    @error('photo_file')
                                    <small class="error mt-1 text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-4">
                                    <label for="marker">Marker</label>
                                    <input id="marker" type="file" name="marker_file" class="dropify" data-height="100" data-allowed-file-extensions="svg png jpg jpeg"
                                           @if(isset($hotel) && isset($hotel->marker))
                                           data-default-file="{{ asset($hotel->marker) }}"
                                           @endif
                                           data-max-file-size="1M">

                                    @error('marker_file')
                                    <small class="error mt-1 text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-center">

                                <div class="form-group @error('star') has-danger @enderror">
                                    @php
                                        $stars = isset($hotel) ? $hotel->star : (old('star') >> 0);
                                    @endphp
                                    <input type="hidden" name="star" value="{{ $stars }}">

                                    @for($i = 1; $i < 6; $i++)
                                        @if ($i <= $stars)
                                            <span class="mdi mdi-star checked" id="{{ $i }}"></span>
                                        @else
                                            <span class="mdi mdi-star" id="{{ $i }}"></span>
                                        @endif
                                    @endfor

                                    @error('star')
                                    <small class="error mt-1 text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-center">
                                <div class="form-group @error('reservation') has-danger @enderror col-6">
                                    <label for="">Rezerwacja</label>
                                    <input type="text" class="form-control" name="reservation" value="{{ isset($hotel) ? $hotel->reservation : old('reservation')}}">
                                    @error('reservation')
                                    <small class="error mt-1 text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group @error('reception') has-danger @enderror col-6">
                                    <label for="">Recepcja</label>
                                    <input type="text" class="form-control" name="reception" value="{{ isset($hotel) ? $hotel->reception : old('reception')}}">
                                    @error('reception')
                                    <small class="error mt-1 text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-center">
                                <div class="form-group @error('coordinates.latitude') has-danger @enderror col-6">
                                    <label for="">Latitude</label>
                                    <input type="text" class="form-control" name="coordinates[latitude]" value="{{ isset($hotel) ? $hotel->coordinates['latitude'] : old('coordinates.latitude') }}">
                                    @error('coordinates.latitude')
                                    <small class="error mt-1 text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group @error('coordinates.longitude') has-danger @enderror col-6">
                                    <label for="">Longitude</label>
                                    <input type="text" class="form-control" name="coordinates[longitude]" value="{{ isset($hotel) ? $hotel->coordinates['longitude'] : old('coordinates.longitude')}}">
                                    @error('coordinates.longitude')
                                    <small class="error mt-1 text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-center">
                                <div class="form-group @error('city') has-danger @enderror col-6">
                                    <label for="">Miasto</label>
                                    @php
                                        $currentCity = isset($hotel) ? $hotel->city : old('city');
                                    @endphp
                                    <select name="city" class="form-control">
                                        @foreach($cities as $city)
                                            <option value="{{ $city->_id }}" @if($currentCity === $city->_id) selected @endif>{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('city')
                                    <small class="error mt-1 text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group @error('street') has-danger @enderror col-6">
                                    <label for="">Ulica</label>
                                    <input type="text" class="form-control" name="street" value="{{ isset($hotel) ? $hotel->street : old('street') }}">
                                    @error('street')
                                    <small class="error mt-1 text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group @error('address') has-danger @enderror col-12">
                                <label for="">Adres</label>
                                <input type="text" class="form-control" name="address" value="{{ isset($hotel) ? $hotel->address : old('address') }}">
                                @error('address')
                                <small class="error mt-1 text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group @error('arrive') has-danger @enderror col-12">
                                <label for="">Jak dojade</label>
                                <input type="text" class="form-control" name="arrive" value="{{ isset($hotel) ? $hotel->arrive : old('arrive') }}">
                                @error('arrive')
                                <small class="error mt-1 text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-center">

                                <div class="form-group @error('mail') has-danger @enderror col-6">
                                    <label for="">E-mail</label>
                                    <input type="text" class="form-control" name="mail" value="{{ isset($hotel) ? $hotel->mail : old('mail') }}">
                                    @error('mail')
                                    <small class="error mt-1 text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group @error('copyright') has-danger @enderror col-6">
                                    <label for="">Copyright</label>
                                    <input type="text" class="form-control" name="copyright" value="{{ isset($hotel) ? $hotel->copyright : old('copyright') }}">
                                    @error('copyright')
                                    <small class="error mt-1 text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </form>
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
    <script src="{{ mix('vendor/js/HotelModule.js') }}"></script>
    <script>
        $('.dropify').dropify({});

        let $stars = $('.mdi-star');

        $stars.click(function() {

            let id = $(this).attr('id');
            $('input[name="star"]').val(id);

            $stars.removeClass('checked');

            for (let i = 1; i <= id; i++) {
                $('#' + i).addClass('checked');
            }
        });
    </script>
@endsection
