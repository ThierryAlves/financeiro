<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenAcesso extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $table = 'tokens_acesso';

    protected $fillable = [
        'cliente_id',
        'token',
        'expires_at'
    ];
}
