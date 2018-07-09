<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuentas extends Model
{
    protected $table = 'cuentas';
    public $timestamps = false;

    function TipoCuenta()
    {
        return $this->hasOne('App\TipoCuenta', 'id', 'id_tipo_cuenta');
    }
}
