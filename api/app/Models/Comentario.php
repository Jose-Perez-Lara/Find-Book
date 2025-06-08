<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $fillable = ['negocio_id', 'usuario_id', 'comentario', 'calificacion'];

    public function negocio()
    {
        return $this->belongsTo(Negocio::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
