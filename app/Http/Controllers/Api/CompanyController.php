<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;


class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $companies,
        ], 200);
    }

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

    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $validated = $request->validate([
            'cnpj'          => 'required|digits:14|unique:companies,cnpj,' . $id,
            'razao_social'  => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'telefone'      => 'nullable|string|max:20',
            'logradouro'    => 'nullable|string|max:255',
            'numero'        => 'nullable|string|max:20',
            'estado'        => 'nullable|string|size:2',
            'cidade'        => 'nullable|string|max:255',
            'descricao'     => 'nullable|string',
        ]);

        $company->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Empresa atualizada com sucesso',
            'data'    => $company,
        ], 200);
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Empresa excluída com sucesso',
        ], 200);
    }
}
