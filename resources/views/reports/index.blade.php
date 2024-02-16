@extends('layouts.default')
@section('content')
    <div class="container">
        <div class="row text-center">
            {{-- <h4 class="display-3 text-center">Inicio de Sesi&oacute;n</h4> --}}
            <h4 class="display-3 text-center">Mis reportes</h4>

        </div>
    </div>
    <div class="container">
        @if (session('success'))
            <div class="row">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif

        <table id="theTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Nombre</th>
                    <th>Expira</th>
                    <th>Estado</th>
                    <th>Departamento</th>
                    <th>Distrito</th>
                    <th>Ciudad</th>
                    <th>Barrio</th>
                    <th></th>

                </tr>

            </thead>
        </table>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Imágenes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal_body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        window.record_table = new Datatable('#theTable', {
            responsive: {
                details: {
                    type: 'column',
                    renderer: function(api, rowIdx, columns) {
                        var data = $.map(columns, function(col, i) {
                            return col.hidden ?
                                '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col
                                .columnIndex + '">' +
                                (col.title != '' ? '<td><strong>' + col.title + ':' + '</strong></td>' +
                                    '<td> ' + col.data + '</td>' :
                                    '<td colspan="2">' + col.data + '</td>') +
                                '</tr>' :
                                '';
                        }).join('');

                        return data ?
                            $('<table/>').append(data) :
                            false;
                    }
                }
            },
            ajax: {
                url: '{{ route('reports.index') }}',
                type: 'GET',
                data: function(d) {
                    //Parametros adicionales a enviar
                    // d.only_active = $('#check_solo_activados')[0].checked;
                    //d._token = '{{ csrf_token() }}';
                },
            },
            columns: [{
                    data: null,
                    className: 'dtr-control',
                    render: function() {
                        return '';
                    },
                    width: '2%',
                    orderable: false
                },
                {
                    data: 'id',
                    width: '2%'
                },
                {
                    data: 'type',
                    width: '2%'
                },
                {
                    data: 'name',
                    width: '18%'
                },
                {
                    data: 'expiration',
                    width: '25%',
                    render: function(data, type, row) {
                        return new Date(data).toLocaleString('es-PY', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })
                    }
                },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        if (data == "{{ __('Rejected') }}") {
                            return data + ` (${row.observations})`;
                        }
                        return data;
                    }
                },
                {
                    data: 'department_name'
                },
                {
                    data: 'district_name'
                },
                {
                    data: 'city_name'
                },
                {
                    data: 'neighborhood_name'
                },
                {
                    data: null,
                    width: '20%',
                    render: function(data, type, row) {
                        return ' <button data-row=\'' + JSON.stringify(row) +
                            '\' title="Ver" data-action="view" class="btn btn-primary btn-sm"' +
                            ' data-toggle="modal" data-target="#exampleModal"><i class="fa-solid fa-eye"></i></button>' +
                            ' <button data-row=\'' + JSON.stringify(row) +
                            '\' title="Borrar" data-action="delete" class="btn btn-danger btn-sm">' +
                            '<i class="fa-solid fa-trash"></i></button>';
                    }

                }
            ],
            processing: true,
            serverSide: true,
            language: messages_es_mx,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'Todos'],
            ],
            dom: "<'clearfix'<'ml-3 float-left'B><'float-right'f><'float-right mr-5'l>>" +
                "<'clearfix'<'toolbar ml-3'><'mr-0 float-right'p>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: //Botones personalizados para la grilla
                [{
                        text: '<i class="fas fa-sync"></i> {{ __('Reload') }}',
                        className: 'btn btn-sm',
                        action: function(e, dt, node, config) {

                            record_table.ajax.reload();
                        }
                    },

                ],
        });

        $('#theTable tbody').on('click', 'td > button', function(e) {
            var data = $(e.currentTarget).data();
            var row = data.row;
            var action = data.action;
            // console.log(row,action);
            switch (action) {
                case "view":
                    view(row);
                    break;
                case "delete":
                    deleteReport(row);
                    break;
            }

        });

        function view(row) {
            // console.log('view',row);
            var attachments = JSON.parse(row.attachments);
            var imgs = '<div class="row">';
            for (var index in attachments) {
                imgs += `<div class="col-md-12">
                    <a target="_blank" href="{{ route('report.image.show', ['xx', 'yy']) }}">
                    <img class="col-12 mb-2"
                    src="{{ route('report.image.show', ['xx', 'yy']) }}"/>`
                    .replace(/xx/g, row.id)
                    .replace(/yy/g, index)
                    +`</a>
                    </div>`;
            }
            imgs += '</div>'
            $('#modal_body').html(`${imgs}`);
            // debugger;
        }

        function deleteReport(row) {
            Swal.fire({
                icon: "question",
                title: "Desea borrar el registro?",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: "Si, borrar!",
                cancelButtonText:"Cancelar"
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        method: "DELETE",
                        url: "{{ route('reports.delete','xx') }}".replace('xx',row.id),
                        dataType:"json",
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success:function(data, textStatus, jqXHR){

                            if(data.success){
                                Swal.fire("Borrado!", "", "success");
                                window.record_table.ajax.reload();
                            }else{
                                Swal.fire({
                                    title:'Error',
                                    text:'No se ha podido actualizar el registro',
                                    icon:'error'
                                });
                            }
                        },
                        error:function(jqXHR, textStatus, errorThrown){
                            Swal.fire({
                                    title:'Error',
                                    text:'Error al comunicarse con el servidor',
                                    icon:'error'
                                });
                        }

                    });

                }
            });

        }
    </script>
@endpush

@push('styles')
    <style>

    </style>
@endpush
