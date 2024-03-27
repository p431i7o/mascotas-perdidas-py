@extends('layouts.default')
@section('content')
    <div class="container">
        <div class="row text-center">
            {{-- <h4 class="display-3 text-center">Inicio de Sesi&oacute;n</h4> --}}
            <h4 class="display-3 text-center">Usuarios</h4>

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
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Ciudad</th>
                    <th>Direccion</th>
                    <th>Telefono</th>
                    <th>Verificado</th>
                    <th>Activo</th>
                    <th>Fecha creación</th>

                    <th></th>

                </tr>

            </thead>
        </table>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Permisos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal_body">
                    <input type="hidden" id="permission_user_id" value=""/>
                    @foreach ($permissions as $permission)
                        <div class="form-check form-check-inline">
                            <input  type="checkbox" id="permission{{ $permission->id }}" data-toggle="switchbutton" checked data-onstyle="success" data-offstyle="danger" name="permission[{{ $permission->id }}]">
                            <label for="permission[{{ $permission->id }}]" class="form-check-label">{{ $permission->name }}</label>
                        </div>
                        <script type="module">
                            $(function() {
                                $('#permission{{ $permission->id }}').change(function() {
                                    // $('#console-event').html('Checked?: ' + $(this).prop('checked'))
                                    console.log('chequeado',$(this).prop('checked'));
                                    updatePermission($('#permission_user_id').val(),'{{$permission->name}}',$(this).prop('checked'));

                                })
                            })
                        </script>
                    @endforeach
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
        window.permissions = @json($permissions);
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
                url: '{{ route('user.index') }}',
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
                    data: 'name',
                    width: '10%'
                },
                {
                    data: 'email',
                    width: '10%'
                },
                {
                    data: 'city',
                    width: '10%'
                },
                {
                    data: 'address',
                    width: '10%'
                },
                {
                    data: 'phone',
                    width: '10%'
                },
                {
                    data: 'email_verified_at',
                    width: '10%',
                    render: function(data) {
                        var tag = '<i class="fa-solid fa-xmark"></i>';
                        if (data != null) {
                            tag =
                                '<span class="badge badge-success"><i class="fa-solid fa-user-check"></i></span>';
                        }
                        return tag;
                    }
                },
                {
                    data: 'active',
                    width: '8%',
                    render: function(data) {
                        if (data == 1) {
                            return '<span class="badge badge-success"><i class="fa-regular fa-circle-check"></i></span>';
                        }
                        return 'No';
                    }
                },
                {
                    data: 'created_at',
                    width: '15%',
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
                    data: null,
                    width: '25%',
                    render: function(data, type, row) {
                        return ' <button data-row=\'' + JSON.stringify(row)
                            +'\' title="Permisos" data-action="permissions" class="btn btn-primary btn-sm"'
                            +' data-toggle="modal" data-target="#exampleModal"><i class="fa-solid fa-layer-group"></i></button>'
                            +' <button data-row=\'' + JSON.stringify(row)
                            +'\' title="Enviar mail de verificacion" data-action="sendRegisteredEmail" class="btn btn-info btn-sm">'
                            + '<i class="fa-solid fa-square-envelope"></i></button>'
                            +' <button data-row=\'' + JSON.stringify(row)
                            +'\' title="Enviar mail de restablecimiento de contraseña" data-action="sendResetPasswordEmail" class="btn btn-warning btn-sm">'
                            + '<i class="fa-regular fa-paper-plane"></i></button>'
                            +' <button data-row=\'' + JSON.stringify(row)
                            +'\' title="Borrar" data-action="delete" class="btn btn-danger btn-sm">'
                            +'<i class="fa-solid fa-trash"></i></button>';
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
                [
                    {
                        text: '<i class="fas fa-sync"></i> {{ __('Reload') }}',
                        className: 'btn btn-sm',
                        action: function(e, dt, node, config) {

                            record_table.ajax.reload();
                        }
                    },
                    'print',
                    'colvis',
                    'copy',
                    // 'excel',
                    // 'pdf',
                    // 'csv'

                ],
        });

        $('#theTable tbody').on('click', 'td > button', function(e) {
            var data = $(e.currentTarget).data();
            var row = data.row;
            var action = data.action;
            // console.log(row,action);
            switch (action) {
                case "permissions":
                    view(row);
                    break;
                case "delete":
                    deleteRecord(row);
                    break;
                case "sendRegisteredEmail":
                    sendRegisteredEmail(row);
                    break;
                case "sendResetPasswordEmail":
                    sendResetPasswordEmail(row);
                    break;
            }

        });

        function view(row) {
            // console.log('view', row);

            // var imgs = '@todo';
            var rol = null,
                item, found = false;
            $('#permission_user_id').val(row.id);
            for (var index in window.permissions) {
                item = window.permissions[index];
                found = false;
                for (var index2 in row.permissions) {

                    if (item.id == row.permissions[index2].id) {
                        found = true;
                        break;
                    }
                }

                $('#permission' + item.id)[0].switchButton(!found?'off':'on',true);
            }

        }

        window.updatePermission=function(user_id, permission, action){
            $.ajax({
                method: "POST",
                url: "{{ route('user.updatePermission', 'xx') }}".replace('xx', user_id),
                dataType: "json",
                data: {
                    _token: '{{ csrf_token() }}',
                    permission:permission,
                    action:action?'asign':'remove'
                },
                success: function(data, textStatus, jqXHR) {

                    if (data.success) {
                        Swal.fire("Guardado!", "", "success");
                        // window.record_table.ajax.reload();
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'No se ha podido enviar el mail',
                            icon: 'error'
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Error al comunicarse con el servidor',
                        icon: 'error'
                    });
                }

            });
        }

        function sendRegisteredEmail(row){
            if(row.email_verified_at != null){
                Swal.fire({text:'El usuario ya tiene verificado su email',icon:'success'});
                return;
            }
            Swal.fire({
                icon: "question",
                title: "Desea reenviar el mail?",
                text:"Confirme que desea enviar el mail de confirmación de registro nuevamente",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: "Si, enviar!",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('user.registered.mail', 'xx') }}".replace('xx', row.id),
                        dataType: "json",
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data, textStatus, jqXHR) {

                            if (data.success) {
                                Swal.fire("Enviado!", "", "success");
                                // window.record_table.ajax.reload();
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'No se ha podido enviar el mail',
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Error al comunicarse con el servidor',
                                icon: 'error'
                            });
                        }

                    });

                }
            });
        }

        function sendResetPasswordEmail(row){

            Swal.fire({
                icon: "question",
                title: "Desea enviar el mensaje de restablecer?",
                text:"Confirme que desea enviar el mail de restablecimiento de contraseña",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: "Si, enviar!",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('user.resetPassword.mail', 'xx') }}".replace('xx', row.id),
                        dataType: "json",
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data, textStatus, jqXHR) {

                            if (data.success) {
                                Swal.fire("Enviado!", "", "success");
                                // window.record_table.ajax.reload();
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'No se ha podido enviar el mail',
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Error al comunicarse con el servidor',
                                icon: 'error'
                            });
                        }

                    });

                }
            });
        }

        function deleteRecord(row) {
            Swal.fire({
                icon: "question",
                title: "Desea borrar el registro?",
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: "Si, borrar!",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        method: "DELETE",
                        url: "{{ route('user.destroy', 'xx') }}".replace('xx', row.id),
                        dataType: "json",
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data, textStatus, jqXHR) {

                            if (data.success) {
                                Swal.fire("Borrado!", "", "success");
                                window.record_table.ajax.reload();
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'No se ha podido actualizar el registro',
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Error al comunicarse con el servidor',
                                icon: 'error'
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
