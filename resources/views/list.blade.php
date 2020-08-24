@extends('DashboardModule::dashboard.index')

@section('stylesheets')
    <link rel="stylesheet" href="{{ mix('vendor/css/HotelModule.css','') }}">
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header clearfix">
                        <h4 class="card-title float-left">Lista wszystkich Hoteli</h4>
                        <a href="{{ route('HotelModule::hotels.create') }}" class="btn btn-success float-right mr-2">
                            <i class="mdi mdi-plus-circle"></i> Dodaj
                        </a>
                        <a href="{{ route('HotelModule::hotels.sort') }}" class="btn btn-primary float-right mr-2">
                            <i class="mdi mdi-sort"></i> Sortuj
                        </a>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <td>Order</td>
                                    <td>Nazwa</td>
                                    <td>Data utworzenia</td>
                                    <td>Akcje</td>
                                    <td>Objekty</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hotels as $hotel)
                                    <tr>
                                        <td>{{ $hotel->order }}</td>
                                        <td>{{ $hotel->name }}</td>
                                        <td>{{ $hotel->created_at }}</td>
                                        <td>
                                            <div>
                                                <a class="btn btn-sm btn-primary" href="{{ route('HotelModule::hotels.edit', ['hotel' => $hotel->id ]) }}">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <a class="btn btn-sm btn-danger remove" href="{{ route('HotelModule::hotels.destroy', ['hotel' => $hotel->id ]) }}">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button" id="{{ $hotel->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Objekty
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="{{ $hotel->id }}">
                                                    <a class="dropdown-item" href="{{ route('HotelModule::apartments', ['hotel' => $hotel->id ]) }}" target="_blank">
                                                        Apartamenty
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('HotelModule::hotel.attraction', ['hotel' => $hotel->id ]) }}" target="_blank">
                                                        Atrakcje
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('HotelModule::kitchen', ['hotel' => $hotel->id ]) }}" target="_blank">
                                                        Kuchnia
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('HotelModule::conference.hall', ['hotel' => $hotel->id ]) }}" target="_blank">
                                                        Sale konferencyjne
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('HotelModule::wellness', ['hotel' => $hotel->id ]) }}" target="_blank">
                                                        Wellness
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('HotelModule::rent', ['hotel' => $hotel->id ]) }}" target="_blank">
                                                        Wypożyczalnia
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascripts')
    @parent
    <script>
        $(document).ready(function(){

            $('a.remove').click(function(e){
                e.preventDefault();
                let url = $(this).attr('href');

                Swal.fire({
                    title: 'Na pewno chcesz to zrobić?',
                    text: 'Nie będzie można tego przywrócić!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d53f3a',
                    confirmButtonText: 'Tak',
                    cancelButtonText: 'Powrót'
                }).then(result => {
                    if(!result.value) return;

                    $.ajax({
                        url: url,
                        method: "POST",
                        data: {
                            "_method": "DELETE",
                            "_token": $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function () {
                            Swal.fire('Usunięto!', 'Akcja zakończyła się sukcesem', 'success');
                            location.reload() ;
                        },
                        error: function () {
                            Swal.fire('Wystąpił błąd!', 'Wystąpił błąd po stronie serwera', 'error');
                        }
                    })
                })
            });
        });
    </script>
@endsection
