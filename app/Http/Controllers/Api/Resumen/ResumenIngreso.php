<?php
namespace App\Http\Controllers\Api\Resumen;

use App\Cuentas;
use App\Http\Controllers\Controller;
use App\Ingresos;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ResumenIngreso extends Controller
{
    public function cuantitativaPorCuenta($año,$mes=null)
    {
        $query = Ingresos::with(['Cuenta.TipoCuenta'])
            ->select(
                DB::raw("
                id_cuenta,
                SUM(`monto`) as saldo,
                DATE_FORMAT(created_at, '%Y-%m') as aniomes
            ")
            );

        if($mes!=null)
        {
            $query= $query->whereMonth('created_at', '=', $mes);
        }

        $query =  $query->whereYear('created_at', '=', $año)
            ->groupBy('id_cuenta')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->get();

        // Agrupa collection
        $output = $query->groupBy('aniomes');

        return $output;
    }

    public function cuantitativaPorAño($año=null,$mes=null)
    {
        $query = Ingresos::select(
            DB::raw('
                SUM(`monto`) as saldo,
                MONTH(`created_at`) as mes,
                YEAR(`created_at`) as anio
            ')
        );

        if($año!=null)
        {
            $query = $query->whereYear('created_at', '=', $año);
        }

        if($mes!=null)
        {
            $query = $query->whereMonth('created_at', '=', $mes);
        }

        $query = $query->groupBy(DB::raw('MONTH(`created_at`)'))
            ->groupBy(DB::raw('YEAR(`created_at`)'))
            ->get();

        $query = $query->groupBy('anio');

        return $query;
    }

    public function detalleCuenta($cuenta,$año,$mes=null)
    {
        $cu = Cuentas::select('id')->where('nombre',$cuenta)->first();

        $query = Ingresos::with(['Cuenta'])->where('id_cuenta', '=', $cu->id);

        if($mes!=null) {
            $query= $query->whereMonth('created_at', '=', $mes);
        }

        $output =  $query->whereYear('created_at', '=', $año)->get();

        return $output;
    }

}

