<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CnpjController;

Route::get('/cnpj/{cnpj}', [CnpjController::class, 'buscar']);