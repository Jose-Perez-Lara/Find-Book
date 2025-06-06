<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Services;

class Cita extends Model
{
    protected $fillable = [
        'cliente_id',
        'servicio_id',
        'fecha',
        'hora',
        'estado',
    ];

    public function cliente()
    {
        return $this->belongsTo(User::class);
    }

    public function servicio()
    {
        return $this->belongsTo(Services::class);
    }
}
