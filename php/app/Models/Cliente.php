<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'nome',
        'documento',
        'email',
        'senha',
        'telefone',
        'tipo'
    ];

    protected $hidden = [
        'senha'
    ];

    public function token() : HasOne
    {
        return $this->hasOne(TokenAcesso::class, 'cliente_id');
    }
}
