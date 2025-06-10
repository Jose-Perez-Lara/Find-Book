<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favoritos extends Model
{
    protected $fillable = ['negocio_id', 'usuario_id'];
    
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function negocio()
    {
        return $this->belongsTo(Negocio::class);
    }
}
