<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CnpjController;
use App\Http\Controllers\Api\CompanyController;

Route::get('/cnpj/{cnpj}', [CnpjController::class, 'buscar']);
Route::post('/companies', [CompanyController::class, 'store']);
