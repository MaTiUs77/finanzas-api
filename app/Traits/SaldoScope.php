<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait SaldoScope
{
    public function scopeFiltrarPeriodo($query,$año,$mes=null) {
        if($año!=null)
        {
            $query->whereYear('created_at', '=', $año);
        }

        if($mes!=null)
        {
            $query->whereMonth('created_at', '=', $mes);
        }

        return $query;
    }
    public function scopeSaldoPorCategoria($query,$año,$mes=null) {
        $query->select(
            DB::raw("
                id_categoria,
                SUM(`monto`) as saldo,
                DATE_FORMAT(created_at, '%Y-%m') as aniomes
            ")
        )
            ->filtrarPeriodo($año,$mes)
            ->groupBy('id_categoria')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"));

        return $query->get()->groupBy('aniomes');
    }
    public function scopeSaldoPorCuenta($query,$año,$mes=null) {
        $query->select(
            DB::raw("
                id_cuenta,
                SUM(`monto`) as saldo,
                DATE_FORMAT(created_at, '%Y-%m') as aniomes
            ")
        )
            ->filtrarPeriodo($año,$mes)
            ->groupBy('id_cuenta')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->get();

        return $query->get()->groupBy('aniomes');
    }
}
