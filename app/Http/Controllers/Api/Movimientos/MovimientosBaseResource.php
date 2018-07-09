<?php

namespace App\Http\Controllers\Api\Movimientos;

use App\Categorias;
use App\Cuentas;
use App\Http\Controllers\Controller;
use App\Movimientos;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class MovimientosBaseResource extends Controller
{
    public $mode;
    public $appendModeCredito;

    public function __construct($mode)
    {
        //$this->middleware('cors');

        $this->mode = $mode;
        if($mode=='egreso')
        {
            $this->appendModeCredito = 'credito';
        }
    }

    private function formatPeriodoFromRequest()
    {
        $periodo = Input::get('periodo');

        // Por defecto, es el periodo en curso
        $año = Carbon::now()->year;
        $mes = Carbon::now()->month;

        // Si se define el periodo...
        if($periodo!=null)
        {
            if($periodo=='all') {
                // Se visualizaran todos los meses del año en curso
                $mes = null;
            } else {
                // Solo muestro el periodo solicitado
                list($año,$mes) = explode('-',$periodo);
            }
        }

        return (object) compact('año','mes');
    }

    /*
     * Muestra los saldos por (cuenta|categoria)
     */
    public function index() {
        $periodo = $this->formatPeriodoFromRequest();

        $query = Movimientos::with(['Cuenta.TipoCuenta','Categoria'])
            ->filtrarPeriodo($periodo->año,$periodo->mes)
            ->whereOrWhere($this->mode,$this->appendModeCredito);

        return $query->get();
    }

    /*
     * Muestra los movimientos en detalle de una cuenta
     */
    public function show($nombre) {
        $periodo = $this->formatPeriodoFromRequest();

        $query = Movimientos::with(['Cuenta.TipoCuenta','Categoria'])
            ->filtrarCuentaNombre($nombre)
            ->filtrarPeriodo($periodo->año,$periodo->mes)
            ->whereOrWhere($this->mode,$this->appendModeCredito);

        return $query->get();
    }

    // Add
    public function store()
    {
        $validationRules = [
            'cuenta' => 'required|string',
            'categoria' => 'required|string',
            'concepto' => 'required|string',
            'cuotas' => 'required|numeric',
            'cuota_nro' => 'required|numeric',
            'monto' => 'required|numeric',
            'periodo' => 'required|date',
            'presentado_at' => 'required|date'
        ];

        // Se validan los parametros
        $validator = Validator::make(Input::all(), $validationRules);
        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        }

        $cuenta = Cuentas::where('nombre',Input::get('cuenta'))->first();
        if($cuenta==null) {
            $cuenta = new Cuentas();
            $cuenta->nombre = Input::get('cuenta');
            $cuenta->id_tipo_cuenta = 3;
            $cuenta->save();
        }

        $categoria = Categorias::where('nombre',Input::get('categoria'))->first();
        if($categoria==null) {
            $categoria = new Categorias();
            $categoria->nombre = Input::get('categoria');
            $categoria->save();
        }

        $periodo = Carbon::createFromFormat('Y-m', Input::get('periodo'));

        $output = [];

        if(Input::get('cuotas')>1) {
            $created_at = $periodo->subMonths(Input::get('cuota_nro'));

            for($i=1;$i <= Input::get('cuotas');$i++)
            {
                $created_at = $created_at->addMonth();
                $created_at_new = clone $created_at;

                $add = new Movimientos();
                $add->id_cuenta = $cuenta->id;
                $add->id_categoria = $categoria->id;
                $add->monto= Input::get('monto');
                $add->concepto= Input::get('concepto');
                $add->cuotas = Input::get('cuotas');
                $add->cuota_nro = $i;
                $add->created_at = $created_at_new;
                $add->updated_at = $created_at_new;
                $add->mode = $this->mode;
                $add->periodo = $created_at_new->format('Y-m');
                $add->presentado_at = Input::get('presentado_at');

                $add->save();

                $output[]=$add;
            }
        } else {
            $add = new Movimientos();
            $add->id_cuenta = $cuenta->id;
            $add->id_categoria = $categoria->id;
            $add->monto= Input::get('monto');
            $add->concepto= Input::get('concepto');
            $add->cuotas = Input::get('cuotas');
            $add->cuota_nro = Input::get('cuota_nro');
            $add->created_at = $periodo;
            $add->updated_at = $periodo;
            $add->mode = $this->mode;
            $add->periodo = $periodo->format('Y-m');
            $add->presentado_at = Input::get('presentado_at');


            $add->save();

            $output[]=$add;
        }

        return $output;
    }

    // Edit
    public function update($id)
    {
        $validationRules = [
            'cuenta' => 'required|string',
            'categoria' => 'required|string',
            'concepto' => 'required|string',
            'cuotas' => 'required|numeric',
            'cuota_nro' => 'required|numeric',
            'monto' => 'required|numeric',
            'fecha' => 'required|date',
        ];

        // Se validan los parametros
        $validator = Validator::make(Input::all(), $validationRules);
        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        }

        $cuenta = Cuentas::where('nombre',Input::get('cuenta'))->first();
        if($cuenta==null) {
            $cuenta = new Cuentas();
            $cuenta->nombre = Input::get('cuenta');
            $cuenta->id_tipo_cuenta = 3;
            $cuenta->save();
        }

        $categoria = Categorias::where('nombre',Input::get('categoria'))->first();
        if($categoria==null) {
            $categoria = new Categorias();
            $categoria->nombre = Input::get('categoria');
            $categoria->save();
        }

        $periodo = Carbon::createFromFormat('Y-m-d', Input::get('fecha'));

        $edit = Movimientos::where('id',$id)->first();
        $edit->id_cuenta = $cuenta->id;
        $edit->id_categoria = $categoria->id;
        $edit->monto= Input::get('monto');
        $edit->concepto= Input::get('concepto');
        $edit->cuotas = Input::get('cuotas');
        $edit->cuota_nro = Input::get('cuota_nro');
        $edit->created_at = $periodo;
        $edit->updated_at = $periodo;

        $edit->save();

        return $edit;
    }

    // Delete
    public function destroy($id)
    {
        $validationRules = [
            'id' => 'required|numeric'
        ];

        // Se validan los parametros
        $validator = Validator::make(['id'=>$id], $validationRules);
        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        }

        $egreso = Movimientos::where('id',$id)->first();

        if($egreso!=null)
        {
            $egreso->delete();

            return ['success'=>'Eliminado con exito'];
        }  else {
            return ['error'=>'El ID no existe'];
        }

    }

}
