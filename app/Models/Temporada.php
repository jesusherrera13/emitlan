<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Temporada extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "temporadas";
    protected $fillable = [
        'id_usuario', 
        'descripcion', 
        'f_inicio', 
        'f_fin', 
        'id_esquema',
        'id_modelo',
        'no_vueltas',
        'no_califican_por_grupo',
        'no_juegos_por_serie',
        'id_status',
        'id_sub_status',
        'no_entradas',
        'id_modo_encuentro',
        'id_usuario_sesion',
    ];
}
