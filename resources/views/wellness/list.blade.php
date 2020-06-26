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
                        <table class="table table-striped"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascripts')
    @parent

    <script>
        $('.table').zdrojowaTable({
            ajax: {
                url: "{{route('HotelModule::wellness.ajax')}}",
                method: "POST",
                data: {
                    "_token": "{{csrf_token()}}"
                },
            },
            headers: [
                {
                    name: 'Order',
                    type: 'text',
                    ajax: 'order',
                    orderable: true
                },
                {
                    name: 'Nazwa',
                    type: 'text',
                    ajax: 'name',
                    orderable: true,
                },
                {
                    name: 'Data utworzenia',
                    orderable: true,
                    ajax: 'created_at'
                },
                {
                    name: 'Akcje',
                    ajax: 'key',
                    type: 'actions',
                    buttons: [
                        @permission('HotelModule.edit')
                        {
                            color: 'primary',
                            icon: 'mdi mdi-pencil',
                            class: 'remove',
                            url: "{{route('HotelModule::wellness.edit', ['wellness' => '%%id%%'])}}"
                        },
                        @endpermission
                        @permission('HotelModule.delete')
                        {
                            color: 'danger',
                            icon: 'mdi mdi-delete',
                            class: 'ZdrojowaTable--remove-action',
                            url: "{{route('HotelModule::wellness.destroy', ['wellness' => '%%id%%'])}}"
                        },
                        @endpermission
                    ]
                }
            ]
        });
    </script>
@endsection
