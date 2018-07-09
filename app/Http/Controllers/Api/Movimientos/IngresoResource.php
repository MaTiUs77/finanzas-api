<?php

namespace App\Http\Controllers\Api\Movimientos;

use App\Categorias;
use App\Cuentas;
use App\Http\Controllers\Api\Movimientos\MovimientosBaseResource;
use App\Http\Controllers\Controller;
use App\Movimientos;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class IngresoResource extends MovimientosBaseResource
{
    public function __construct()
    {
        parent::__construct('ingreso');
    }
}
