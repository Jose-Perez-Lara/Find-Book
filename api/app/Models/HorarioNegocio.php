<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioNegocio extends Model
{
    use HasFactory;

    protected $fillable = [
        'negocio_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
    ];

    public function negocio()
    {
        return $this->belongsTo(Negocio::class);
    }
}
