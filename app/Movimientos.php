<?php

namespace App;

use App\Traits\SaldoScope;
use App\Traits\WithCategoriaScope;
use App\Traits\WithCuentaScope;
use Illuminate\Database\Eloquent\Model;

class Movimientos extends Model
{
    use WithCuentaScope, WithCategoriaScope, SaldoScope;

    protected $table = 'movimientos';
/*
    protected $appends= ['periodo'];

    function getPeriodoAttribute() {
        if(isset($this->created_at))
        {
            return $this->created_at->format('Y-m');
        }
    }*/

    function Cuenta()
    {
        return $this->hasOne('App\Cuentas', 'id', 'id_cuenta');
    }

    function Categoria()
    {
        return $this->hasOne('App\Categorias', 'id', 'id_categoria');
    }
}
