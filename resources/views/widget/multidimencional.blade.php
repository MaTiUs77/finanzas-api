@php
    $periodoActual = \Carbon\Carbon::now()->format('Y-m');
@endphp

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Resumen anual de {{ ucfirst($modo) }}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#modal-{{ $modo }}"><i class="fa fa-share"></i> Agregar</button>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
            <div class="table-responsive">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th>Cuenta</th>
                        @foreach($multidimencional->get('aniomes') as $aniomes => $anioData)
                            <th @if($periodoActual==$aniomes) class="danger" @endif style="text-align: center;">{{ $aniomes }}</th>
                        @endforeach
                    </tr>
                    @foreach($multidimencional->get('cuentas') as $cuenta => $item)
                        <tr>
                            <td>
                                {{ $cuenta }}
                            </td>

                            @foreach($multidimencional->get('aniomes') as $aniomes => $anioData)
                                <td @if($periodoActual==$aniomes) class="danger" @endif style="text-align: center;">
                                    @if(isset($item[$aniomes]))
                                        <a href="{{ url("detalle/$modo/$cuenta/$aniomes") }}">$ {{ $item[$aniomes]->saldo }}</a>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    <tr>
                        <th>
                            Total
                        </th>
                        @foreach($multidimencional->get('aniomes') as $aniomes => $anioData)
                            <th>
                                @if(isset($anioData['total']))
                                    $ {{ $anioData['total'] }}
                                @else
                                    $ 0
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </tbody>
                </table>
            </div>
        </div>
        <!-- /.box-body -->
    </div>

    <div class="modal modal-default fade" id="modal-{{ $modo }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form{{$modo}}Add" action="{{ url("api/movimiento/$modo/add") }}">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Nuevo {{ ucfirst($modo) }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <!-- Combo Cuentas -->
                                <select class="form-control" name="cuenta">
                                    <option value="">- Seleccionar cuenta -</option>
                                    @foreach(\App\Cuentas::select('nombre')->orderBy('nombre')->get() as $item)
                                        <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <!-- Combo Categorias -->
                                <select class="form-control" name="categoria">
                                    <option value="">- Seleccionar categorias -</option>
                                    @foreach(\App\Categorias::select('nombre')->orderBy('nombre')->get() as $item)
                                        <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <br><br>

                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="concepto" placeholder="Concepto">
                            </div>

                            <div class="col-sm-4">
                                <input class="form-control" type="date" name="fecha" placeholder="Fecha de ingreso de monto">
                            </div>

                            <br><br>

                            <div class="col-sm-4">
                                <input class="form-control" type="number" name="cuotas" placeholder="Cuotas">
                            </div>
                            <div class="col-sm-4">
                                <input class="form-control" type="number"  name="cuota_nro" placeholder="Cuota actual">
                            </div>
                            <div class="col-sm-4">
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

@section('footer')
    <script>
        $(function () {
            $("#form{{$modo}}Add").submit(function(e){

                e.preventDefault();
                var form = $(e.target);

                $.ajax( {
                    type: "POST",
                    url: form.attr( 'action' ),
                    data: form.serialize(),
                    success: function( response ) {
                        console.log( response );
                        window.location.reload();
                        //$('#modal-egreso').modal('hide');
                    }
                });
            });
        });
    </script>
@append