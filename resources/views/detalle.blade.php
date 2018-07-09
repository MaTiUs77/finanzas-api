@extends('layouts.adminlte')

@section('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection

@section('contenido')

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ $detalle->first()->created_at->format('F/Y') }} - {{ $detalle->first()->cuenta->nombre }} - Total: $ {{ $detalle->sum('monto') }}
            </h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#modal-{{ $modo }}"><i class="fa fa-share"></i> Agregar</button>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>

        </div>
        <div class="box-body">
            <a href="{{ url("detalle/$modo/{$detalle->first()->cuenta->nombre}/{$detalle->first()->created_at->subMonth()->format('Y-m')}") }}" class="btn btn-default">Ver periodo {{ $detalle->first()->created_at->subMonth()->format('F/Y') }}</a>
            <a href="{{ url("detalle/$modo/{$detalle->first()->cuenta->nombre}/{$detalle->first()->created_at->addMonth()->format('Y-m')}") }}" class="btn btn-default pull-right">Ver periodo {{ $detalle->first()->created_at->addMonth()->format('F/Y') }}</a>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th></th>
                    <th>Concepto</th>
                    <th>Monto</th>
                    <th>Cuota</th>
                    <th>Cuotas Restantes</th>
                    <th>Categoria</th>
                    <th>Creado</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($detalle->sortBy('cuotas') as $item)
                    @php
                        $restantes = $item->cuotas - $item->cuota_nro;
                    @endphp
                    <tr @if($item->cuotas > 1 && $restantes==0) class="success" @endif>
                        <td><a href="javascript:;" class="btnHide btn btn-default btn-xs"><i class="fa fa-eye"></i></a></td>
                        <td>{{ $item->concepto }}</td>
                        <td>$ {{ $item->monto }}</td>
                        <td>{{ $item->cuota_nro }}/{{ $item->cuotas }}</td>
                        <td>{{ $restantes }}</td>
                        <td>{{ $item->categoria->nombre }}</td>
                        <td>{{ $item->created_at->format('d-m-Y') }}</td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-edit-{{ $modo }}" data-item="{{ $item }}"><i class="fa fa-edit"></i></button>
                            <button type="button" class="btn btn-danger btn-sm itemDelete" data-url="{{ route("$modo.destroy",$item->id) }}"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            </table>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

    <div class="modal modal-default fade" id="modal-{{ $modo }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="formAdd" method="POST" action="{{ route("$modo.store") }}">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Nuevo {{ ucfirst($modo) }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-6">
                                <!-- Combo Cuentas -->
                                <select class="form-control uiselect2" name="cuenta" style="width: 100%">
                                    @foreach(\App\Cuentas::select('nombre')->orderBy('nombre')->get() as $item)
                                        <option value="{{ $item->nombre }}" @if($detalle->first()->cuenta->nombre==$item->nombre) selected="selected" @endif>{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xs-6">
                                <!-- Combo Categorias -->
                                <select class="form-control uiselect2" name="categoria" style="width: 100%">
                                    @foreach(\App\Categorias::select('nombre')->orderBy('nombre')->get() as $item)
                                        <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <br><br>

                            <div class="col-xs-8">
                                <input class="form-control" type="text" name="concepto" placeholder="Concepto">
                            </div>

                            <div class="col-xs-4">
                                <input class="form-control" type="date" name="fecha" value="{{ $periodo->format('Y-m-d') }}" placeholder="Fecha de ingreso de monto">
                            </div>

                            <br><br>

                            <div class="col-xs-4">
                                <input class="form-control" type="number" name="cuotas" placeholder="Cuotas">
                            </div>
                            <div class="col-xs-4">
                                <input class="form-control" type="number"  name="cuota_nro" placeholder="Cuota actual">
                            </div>
                            <div class="col-xs-4">
                                <input class="form-control" type="number"  name="monto" placeholder="Monto">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success ">Aceptar</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal modal-default fade" id="modal-edit-{{ $modo }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="formEdit" method="PUT" action="{{ route("$modo.update","replace-id") }}">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Editar {{ ucfirst($modo) }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-6">
                                <!-- Combo Cuentas -->
                                <select class="form-control uiselect2" name="cuenta" style="width: 100%">
                                    @foreach(\App\Cuentas::select('nombre')->orderBy('nombre')->get() as $item)
                                        <option value="{{ $item->nombre }}" @if($detalle->first()->cuenta->nombre==$item->nombre) selected="selected" @endif>{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xs-6">
                                <!-- Combo Categorias -->
                                <select class="form-control uiselect2" name="categoria" style="width: 100%">
                                    @foreach(\App\Categorias::select('nombre')->orderBy('nombre')->get() as $item)
                                        <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <br><br>

                            <div class="col-xs-8">
                                <input class="form-control" type="text" name="concepto" placeholder="Concepto">
                            </div>

                            <div class="col-xs-4">
                                <input class="form-control" type="date" name="fecha" placeholder="Fecha de ingreso de monto">
                            </div>

                            <br><br>

                            <div class="col-xs-4">
                                <input class="form-control" type="number" name="cuotas" placeholder="Cuotas">
                            </div>
                            <div class="col-xs-4">
                                <input class="form-control" type="number"  name="cuota_nro" placeholder="Cuota actual">
                            </div>
                            <div class="col-xs-4">
                                <input class="form-control" type="number"  name="monto" placeholder="Monto">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success ">Aceptar</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection


@section('footer')
    <script>
        function prepareFormAction(formElement,newId)
        {
            var oldAction = formElement.attr('action');
            var newAction = oldAction.split('/');
            newAction.pop();
            newAction.push(newId);
            newAction = newAction.join("/");

            formElement.attr('action',newAction);
        }

        $(function () {
            $(".btnHide").click(function(evt){
                var target = evt.target;

                var tr = target.closest('tr');
                $(tr).addClass('warning');
                evt.preventDefault();
            });

            // ADD
            $(".formAdd").submit(function(e){

                e.preventDefault();
                var form = $(e.target);

                $.ajax( {
                    type: "POST",
                    url: form.attr( 'action' ),
                    data: form.serialize(),
                    success: function( response ) {
                        console.log( response );
                        window.location.reload();
                    }
                });
            });

            // EDIT
            $('#modal-edit-{{ $modo }}').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var item = button.data('item');

                // Prepara la url para UPDATE
                prepareFormAction($(this).find('form'),item.id);

                $(this).find('[name="cuenta"]').val(item.cuenta.nombre);
                $(this).find('[name="categoria"]').val(item.categoria.nombre);
                $(this).find('[name="concepto"]').val(item.concepto);
                $(this).find('[name="fecha"]').val(item.created_at.split(" ")[0]);
                $(this).find('[name="cuotas"]').val(item.cuotas);
                $(this).find('[name="cuota_nro"]').val(item.cuota_nro);
                $(this).find('[name="monto"]').val(item.monto);
            });

            $(".formEdit").submit(function(e){
                e.preventDefault();
                var form = $(e.target);

                $.ajax( {
                    type: "PUT",
                    url: form.attr( 'action' ),
                    data: form.serialize(),
                    success: function( response ) {
                        if(response.error!=undefined)
                        {
                            alert(response.error);
                        } else {
                            window.location.reload();
                        }
                    }
                });
            });

            // DELETE
            $(".itemDelete").click(function(evt){
                var el = $(this);
                var url = el.data('url');

                $.ajax( {
                    type: "DELETE",
                    url: url,
                    success: function( response ) {
                        console.log( response );
                        window.location.reload();
                    }
                });
            });

        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.uiselect2').select2({
                tags: true
            });
        });
    </script>
@append
