<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, softDeletes;

    const CLIENTE_TIPO_PESSOA_FISICA = 1;
    const CLIENTE_TIPO_PESSOA_JURIDICA = 2;

    protected $fillable = [
        'nome',
        'documento',
        'email',
        'password',
    ];

    protected $hidden = [
        'senha'
    ];

    public function tokens() : HasMany
    {
        return $this->hasMany(TokenAcesso::class, 'cliente_id');
    }
}
