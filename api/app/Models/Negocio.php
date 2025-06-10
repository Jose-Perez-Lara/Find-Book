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
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categorias::class);
    }

    public function favoritos()
    {
        return $this->hasMany(Favoritos::class, 'negocio_id');
    }
}
