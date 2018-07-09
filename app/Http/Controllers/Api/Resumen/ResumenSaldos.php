<?php
namespace App\Http\Controllers\Api\Resumen;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ResumenSaldos extends Controller
{
    public static function generar($egreso,$ingreso)
    {
        $output = compact('egreso','ingreso');

        return $output;
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
