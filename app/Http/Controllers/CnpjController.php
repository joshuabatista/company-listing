<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class CnpjController extends Controller
{
    public function buscar(string $cnpj)
    {
        $cnpj = preg_replace('/\D/', '', $cnpj);

        if (strlen($cnpj) !== 14) {
            return response()->json([
                'error' => 'CNPJ inválido.'
            ], 400);
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Laravel CNPJ Lookup'
                ])
                ->get("https://www.receitaws.com.br/v1/cnpj/{$cnpj}");

            if (!$response->successful()) {
                return response()->json([
                    'error' => 'Erro ao consultar CNPJ na Receita Federal.'
                ], 500);
            }

            $data = $response->json();

            if (isset($data['status']) && $data['status'] === 'ERROR') {
                return response()->json([
                    'error' => $data['message'] ?? 'CNPJ não encontrado'
                ], 404);
            }

            // dd( $data );

            return response()->json([
                'nome'       => $data['nome'] ?? null,
                'email'      => $data['email'] ?? null,
                'logradouro' => $data['logradouro'] ?? null,
                'municipio'  => $data['municipio'] ?? null,
                'uf'         => $data['uf'] ?? null,
            ]);

        } catch (\Throwable $e) {
    return response()->json([
        'error' => 'Erro ao consultar CNPJ',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ], 500);
}
    }
}
