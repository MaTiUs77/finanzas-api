<?php
namespace App\Http\Controllers\Api\Resumen;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ResumenAnual extends Controller
{
    public static function init($egresos,$ingresos)
    {
        $output = [];

        // Años en los que se declararon ingresos y egresos
        $añosDeclarados = $ingresos->keys()
            ->merge($egresos->keys())
            ->unique();

        // Recorro los años declarados
        foreach($añosDeclarados as $año)
        {
            // Genero los meses del año
            for($mes=1;$mes<=12;$mes++)
            {
                $fecha = Carbon::createFromFormat('Y-m', "$año-$mes");

                $saldoIngreso = self::buscarPeriodo($fecha->year,$fecha->month,$ingresos);
                $saldoEgreso = self::buscarPeriodo($fecha->year,$fecha->month,$egresos);

                $output[$fecha->year][$fecha->month] = [
                    'egreso' => $saldoEgreso,
                    'ingreso' => $saldoIngreso,
                    'neto' => $saldoIngreso - $saldoEgreso,
                    'saldo' => null
                ];

                $saldo = self::calcularSaldo($output,$fecha->year,$fecha->month);
            }
        }

        return $output;
    }
    
    private static function buscarPeriodo($año,$mes,$arr) {
        $data = $arr->get($año)->where('mes',$mes)->first();
        if($data!=null) {
            return $data->saldo;
        } else {
            return 0;
        }
    }

    private static function calcularSaldo($periodos,$año,$mes) {
        $data = $periodos[$año][$mes];
    }

}
