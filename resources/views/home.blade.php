@extends('layouts.adminlte')

@section('contenido')

<div class="row">

    <div id="app">
        <div class="col-sm-3">
            @verbatim
                <div v-cloak>
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-minus"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Egresos</span>
                            <span class="info-box-number">$ {{ egreso.saldo_periodo }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>

                    <div class="box box-danger" v-for="(items,tipo) in egreso.lista">
                        <div class="box-header">
                            <h3 class="box-title">{{ tipo }}</h3>
                        </div>
                        <div class="box-body no-padding">
                            <ul class="nav nav-stacked">
                                <li  v-for="item in items">
                                    <a href="#">{{ item.cuenta.nombre }}
                                        <span class="pull-right badge bg-red">$ {{ item.saldo }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="box-footer no-padding">
                            <ul class="nav nav-stacked">
                                <li class="bg-gray-light">
                                    <a href="#">Total a pagar:
                                        <span class="pull-right badge bg-default">
                                            $ {{ _.sumBy(items,'saldo') }}
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endverbatim
        </div>
    </div>

    <div class="col-sm-9">
        @include('widget.chart.resumen_anual',[
            'cuantitativo' => $cuantitativoAnual
        ])
    </div>

    <div class="col-sm-9">
        @include('widget.multidimencional',[
            'modo' => 'egreso',
            'multidimencional' => $egreso['multidimencional']
        ])
    </div>
</div>

@endsection

@section('footer')

    <script>
    $(function(){
        var app = new Vue({
            el: '#app',
            data: {
                egreso: {
                    periodo: 0,
                    saldo_periodo : 0,
                    lista: []
                },
                ingreso: {
                    periodo: 0,
                    saldo_periodo : 0,
                    lista: []
                }
            },
            mounted: function () {
                this.consumirApi(this.egreso,'{{ route('egreso.index')  }}?by=periodo');
            },
            methods: {
                consumirApi: function (element,url) {
                    element.lista = [];

                    this.$http.get(url).then(function(response){
                        response = response.body;

                        this.formatTipoCuenta(response,element);
                    }, function(err){
                        console.log("Error",err);
                    });
                },
                formatTipoCuenta: function(response,element) {
                    // Obtengo el primer key del array "2018-05"
                    element.periodo = _.first(_.keys(response));

                    // Array de movimientos del periodo
                    var movimientos = _.first(_.valuesIn(response));
                    element.saldo_periodo = _.sumBy(movimientos,'saldo');

                    // Formatea el array, genera un agrupamiento por tipo_cuenta.nombre
                    element.lista = _.transform(movimientos, function(result, item, key) {
                        (result[item.cuenta.tipo_cuenta.nombre] || (result[item.cuenta.tipo_cuenta.nombre] = [])).push(item);
                    }, {});
                }
            }
        })
    })
</script>
@append