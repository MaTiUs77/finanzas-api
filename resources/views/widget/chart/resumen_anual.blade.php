<script src="https://code.highcharts.com/highcharts.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>


@section('footer')
    <script>
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Resumen anual'
            },
            xAxis: {
                categories: [
                    @foreach($cuantitativo as $año => $arr)
                        @foreach($arr as $mes => $info)
                            '{{ $año }}-{{ $mes }}',
                        @endforeach
                    @endforeach
                ]
            },
            yAxis: {
                title: {
                    text: 'Montos'
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: true
                }
            },
            @php
                $currentAño = collect($cuantitativo['2018']);
            @endphp
            series: [
                {
                    name: 'Ingreso',
                    color: '#81b53d',
                    data: [
                        {{ $currentAño->implode('ingreso',',')  }}
                    ]
                }, {
                    name: 'Egreso',
                    color: '#ea5252',
                    type: 'line',
                    data: [
                        {{ $currentAño->implode('egreso',',')  }}
                    ]
                }, {
                    name: 'Neto',
                    color: '#00c0ef',
                    visible: false,
                    data: [
                        {{ $currentAño->implode('neto',',')  }}
                    ]
                }
            ]
        });
    </script>
@append
