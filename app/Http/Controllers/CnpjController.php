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
                ->withOptions([
                    'verify' => false, 
                ])
                ->withHeaders([
                    'User-Agent' => 'Laravel CNPJ Lookup'
                ])
                ->get("https://www.receitaws.com.br/v1/cnpj/{$cnpj}");

            $data = $response->json();

            if (!$response->successful() || (isset($data['status']) && $data['status'] === 'ERROR')) {
                return response()->json([
                    'error' => $data['message'] ?? 'CNPJ não encontrado ou erro ao consultar a Receita Federal.',
                    'status_code' => $response->status(),
                    'response_data' => $data
                ], $response->status() === 429 ? 429 : 404);
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
