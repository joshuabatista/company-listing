<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
     protected $fillable = [
        'cnpj',
        'razao_social',
        'email',
        'telefone',
        'logradouro',
        'numero',
        'estado',
        'cidade',
        'descricao',
    ];
}
