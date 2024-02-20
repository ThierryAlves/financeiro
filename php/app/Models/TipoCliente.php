<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCliente extends Model
{
    use HasFactory;

    const TIPO_PESSOAFISICA = 1;
    const TIPO_PESSOAJURIDICA = 2;
}
