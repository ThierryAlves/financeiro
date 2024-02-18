<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransacaoCliente extends Model
{
    use HasFactory;

    const UPDATED_AT = null;
    protected $table = 'transacoes_clientes';

    protected $fillable = [
        'cliente_pagante_id',
        'cliente_recebedor_id',
        'valor'
    ];
}
