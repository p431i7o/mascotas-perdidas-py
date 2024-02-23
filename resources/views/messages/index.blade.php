@extends('layouts.default')
@section('content')
<div class="container">
    <div class="row text-center">
        {{-- <h4 class="display-3 text-center">Inicio de Sesi&oacute;n</h4> --}}
        <h4 class="display-3 text-center">Mis mensajes</h4>

    </div>
</div>
<div class="container">
    @if(session('success'))
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
                <th>DE</th>
                <th>Fecha</th>
                <th>Reporte</th>
                <th></th>
            </tr>

        </thead>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_title">--</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal_body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="btn_delete_message" data-message-id="" onclick="deleteMessage(this)">Borrar mensaje</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar Ventana</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
    <script type="module">
        window.record_table = new Datatable('#theTable',{
            responsive: {
                details: {
                    type: 'column',
                    renderer: function ( api, rowIdx, columns ) {
                        var data = $.map( columns, function ( col, i ) {
                            return col.hidden ?
                                '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                    (col.title!=''?'<td><strong>'+col.title+':'+'</strong></td>'+'<td> '+col.data+'</td>'
                                    :'<td colspan="2">'+col.data+'</td>')+
                                '</tr>' :
                                '';
                        } ).join('');

                        return data ?
                            $('<table/>').append( data ) :
                            false;
                    }
                }
            },
            ajax:{
                url:'{{ route('messages.index')}}',
                type:'GET',
                data:function(d){
                    //Parametros adicionales a enviar
                    // d.only_active = $('#check_solo_activados')[0].checked;
                    //d._token = '{{ csrf_token() }}';
                },
            },
            columns:[
                { data:null,className:'dtr-control',render:function(){return '';}, width:'2%',orderable:false},
                { data: 'id',width:'2%' },
                {
                    data: 'from',
                    render:function(data,type,row){
                        return data.name + ' ['+row.from.email+']';
                    }
                },{
                    data:'created_at',
                    render:function(data,type,row){
                        return new Date(data).toLocaleString('es-PY', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })
                    }
                },{
                    data:null,
                    render:function(data,type,row){
                        return '<a target="_blank" href="{{ route('reports.show',['xx']) }}">Reporte</a>'.replace('xx',row.report_id);
                    }
                },{
                    data:null,
                    render:function(data,type,row){
                        return '<button data-action="view" data-row=\'' + JSON.stringify(row)
                                +'\' class="btn btn-sm btn-primary"><i class="fa-regular fa-eye"></i></button>';

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
            dom:"<'clearfix'<'ml-3 float-left'B><'float-right'f><'float-right mr-5'l>>" +
                    "<'clearfix'<'toolbar ml-3'><'mr-0 float-right'p>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: //Botones personalizados para la grilla
            [
                {
                    text:'<i class="fas fa-sync"></i> {{__('Reload') }}',
                    className: 'btn btn-sm',
                    action: function ( e, dt, node, config ) {

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
            }

        });

        function view(row){
            console.log('ver mas');
            $('#modal_body').html(row.message);
            $('#modal_title').html("De: "+row.from.name+", "+row.from.email );
            $('#exampleModalCenter').modal('show');
            $('#btn_delete_message').data('message-id',row.id);
            $.ajax({
                type: "post",
                url: "{{ route('messages.markAsRead',['xx']) }}".replace('xx',row.id),
                data: {
                    id:row.id,
                    report_id:row.report_id,
                    _token:"{{ csrf_token() }}"
                },
                dataType: "json",
                success: function (response) {
                    console.log('success');
                }
            });
        }

        window.deleteMessage =  function(button){
            var button = $(button);
            var data = button.data();
            Swal.fire({
              title: 'Confirmación',
              text: 'Confirma que desea borrar el mensaje?',
              icon: 'question',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Si borrar!',
              cancelButtonText:'Cancelar'
            }).then((result) => {
              if (result.isConfirmed) {
                $.ajax({
                    type: "delete",
                    url: "{{ route('messages.destroy',['xx']) }}".replace('xx',data.messageId),
                    data: {
                        id:data.messageId,
                        _token:"{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function (response) {
                        Swal.fire(
                          'Exito!',
                          'Borrado correctamente',
                          'success'
                        );
                        $('#exampleModalCenter').modal('hide');
                        record_table.ajax.reload();
                    }
                });
              }
            })

        }
    </script>
@endpush

@push('styles')
    <style>

    </style>
@endpush
