<?php
namespace App\Http\Controllers\Api\Forms;

use App\Categorias;
use App\Cuentas;
use App\Http\Controllers\Controller;

class Forms extends Controller
{
    public function cuentas() {
        return  Cuentas::all();
    }
    public function categorias() {
        return Categorias::all();
    }
}
