<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait WithCategoriaScope
{
    public function scopeFiltrarCategoria($query,$categoria) {
        $query->whereHas('Categoria', function ($q) use($categoria) {
            return $q->where('categoria', $categoria);
        });
    }
}
