<?php
Route::prefix('forms',['middleware'=>'cors'])->group(function () {
    Route::get('/cuentas', 'Api\Forms\Forms@cuentas');
    Route::get('/categorias', 'Api\Forms\Forms@categorias');
});

