@extends('DashboardModule::dashboard.index')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header clearfix">
                        <h4 class="card-title float-left">Lista wszystkich Wellness</h4>
                        <a href="{{route('HotelModule::wellness.create')}}" class="btn btn-success float-right mr-2">
                            <i class="mdi mdi-plus-circle"></i> Dodaj
                        </a>
                        <a href="{{route('HotelModule::wellness.sort')}}" class="btn btn-primary float-right mr-2">
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
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($wellnesses as $wellness)
                                <tr>
                                    <td>{{ $wellness->order }}</td>
                                    <td>{{ $wellness->name }}</td>
                                    <td>{{ $wellness->created_at }}</td>
                                    <td>
                                        <a class="btn btn-primary" href="{{ route('HotelModule::wellness.edit', ['wellness' => $wellness->id ]) }}">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <a class="btn btn-danger remove" href="{{ route('HotelModule::wellness.destroy', ['wellness' => $wellness->id ]) }}">
                                            <i class="mdi mdi-delete"></i>
                                        </a>
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