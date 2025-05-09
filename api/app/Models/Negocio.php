<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Negocio extends Model
{
    /** @use HasFactory<\Database\Factories\NegocioFactory> */
    use HasFactory;
    
    protected $fillable = [
        'usuario_id',
        'nombre',
        'descripcion',
        'categoria',
        'imagen_portada',
        'color_tema',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
