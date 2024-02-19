<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function cliente() : BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'id');
    }
}
