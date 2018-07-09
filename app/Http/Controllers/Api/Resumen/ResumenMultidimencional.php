<?php
namespace App\Http\Controllers\Api\Resumen;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ResumenMultidimencional extends Controller
{
    public static function generar($resumen)
    {
        $año = 2018;

        $multidimencional = [];
        // Genero todos los meses del año
        $multidimencional['aniomes'] = self::generarMesesDelAño($año);
        $columnas = count($multidimencional['aniomes']);
        $columna = 0;

        // Recorro las cuentas de el resumen
        foreach ($resumen as $aniomes => $item)
        {
            foreach ($item as $movimiento)
            {
                $multidimencional['cuentas'][$movimiento->cuenta->nombre][$aniomes] = $movimiento;
                if(isset($multidimencional['aniomes'][$aniomes]['total']))
                {
                    $multidimencional['aniomes'][$aniomes]['total'] = $multidimencional['aniomes'][$aniomes]['total'] + $movimiento->saldo;
                } else {
                    $multidimencional['aniomes'][$aniomes]['total'] = $movimiento->saldo;
                }
            }
        }

        $multidimencional = collect($multidimencional);

        return $multidimencional;
    }

    public static function generarMesesDelAño($año) {
        $output = [];

        for($mes=1;$mes<=12;$mes++)
        {
            $aniomes = Carbon::createFromFormat('Y-m', "$año-$mes")->format("Y-m");
            $output[$aniomes]['aniomes'] = $aniomes;
        }

        return $output;
    }
 }
