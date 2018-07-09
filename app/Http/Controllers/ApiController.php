<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{

    public function home()
    {
        $motor = 'laravel';
        $version = app()::VERSION;
        $server_time = Carbon::now();

        return compact('motor','version','server_time');
    }
}
