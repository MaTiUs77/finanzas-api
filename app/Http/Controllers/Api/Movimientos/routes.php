<?php
Route::prefix('movimiento',['middleware'=>'cors'])->group(function () {
    Route::resource('/credito', 'Api\Movimientos\CreditoResource');
    Route::resource('/egreso', 'Api\Movimientos\EgresoResource');
    Route::resource('/ingreso', 'Api\Movimientos\IngresoResource');
});

