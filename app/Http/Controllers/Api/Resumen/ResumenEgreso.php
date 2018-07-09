<?php
namespace App\Http\Controllers\Api\Resumen;

use App\Egresos;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ResumenEgreso extends Controller
{
    public function detalleCuenta($cuenta,$año,$mes=null)
    {
        $query = Egresos::with(['Cuenta'])
            ->filtrarCuentaNombre(Input::get('cuenta'))
            ->filtrarPeriodo($año,$mes);

        return $query->get();
    }
 }
