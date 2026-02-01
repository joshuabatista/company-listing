<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CnpjController;
use App\Http\Controllers\Api\CompanyController;

Route::get('/cnpj/{cnpj}', [CnpjController::class, 'buscar']);
Route::get('/companies', [CompanyController::class, 'index']);
Route::post('/companies', [CompanyController::class, 'store']);
Route::put('/companies/{id}', [CompanyController::class, 'update']);
