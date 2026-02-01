<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;


class CompanyController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cnpj'          => 'required|digits:14|unique:companies,cnpj',
            'razao_social'  => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'telefone'      => 'nullable|string|max:20',
            'logradouro'    => 'nullable|string|max:255',
            'numero'        => 'nullable|string|max:20',
            'estado'        => 'nullable|string|size:2',
            'cidade'        => 'nullable|string|max:255',
            'descricao'     => 'nullable|string',
        ]);

        $company = Company::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Empresa cadastrada com sucesso',
            'data'    => $company,
        ], 201);
    }
}
