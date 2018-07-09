<?php

namespace App\Traits;

trait WithCuentaScope
{
    public function scopeFiltrarCuenta($query,$cuenta) {
        $query->whereHas('Cuenta', function ($q) use($cuenta) {
            return $q->where('cuenta', $cuenta);
        });
    }

    public function scopeFiltrarCuentaNombre($query,$cuentaNombre) {
        $query->whereHas('Cuenta', function ($q) use($cuentaNombre) {
            return $q->where('nombre', $cuentaNombre);
        });
    }

    public function scopeWhereOrWhere($query,$where,$orWhere) {
        $query->where(function ($q) use($where, $orWhere) {
            $subquery = $q->where('mode',$where);
            if($orWhere)
            {
                $subquery->orWhere('mode', 'credito');
            }

            return $subquery;
        });
    }
}
