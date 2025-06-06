<?php

namespace App\Http\Controllers;

use App\Models\HorarioNegocio;
use Illuminate\Http\Request;
use App\Models\Cita;
use Carbon\Carbon;
use App\Models\Servicio;

class HorarioNegocioController extends Controller
{
    public function index($negocio_id)
    {
        return HorarioNegocio::where('negocio_id', $negocio_id)->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'negocio_id' => 'required|exists:negocios,id',
            'dia_semana' => 'required|integer|min:1|max:7',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        ]);

        return HorarioNegocio::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $horario = HorarioNegocio::findOrFail($id);

        $request->validate([
            'dia_semana' => 'sometimes|integer|min:1|max:7',
            'hora_inicio' => 'sometimes|date_format:H:i',
            'hora_fin' => 'sometimes|date_format:H:i|after:hora_inicio',
        ]);

        $horario->update($request->all());

        return $horario;
    }

    public function destroy($id)
    {
        $horario = HorarioNegocio::findOrFail($id);
        $horario->delete();

        return response()->json(['message' => 'Horario eliminado']);
    }


    public function huecosDisponibles(Request $request, $negocio_id)
    {
        //formato YYYY-MM-DD
        $fecha = Carbon::parse($request->input('fecha')); 
        $servicioId = $request->input('servicio_id');

        $servicio = Servicio::findOrFail($servicioId);
        //minutos
        $duracion = $servicio->duracion; 

        //1 (lunes) - 7 (domingo)
        $diaSemana = $fecha->dayOfWeekIso;

        $horarios = HorarioNegocio::where('negocio_id', $negocio_id)
            ->where('dia_semana', $diaSemana)
            ->get();

        if ($horarios->isEmpty()) {
            return response()->json(['disponibles' => []]);
        }

        $disponibles = [];

        foreach ($horarios as $horario) {
            $inicio = Carbon::parse($fecha->toDateString() . ' ' . $horario->hora_inicio);
            $fin = Carbon::parse($fecha->toDateString() . ' ' . $horario->hora_fin);

            while ($inicio->copy()->addMinutes($duracion)->lte($fin)) {
                $bloqueFin = $inicio->copy()->addMinutes($duracion);

                //verificar si hay citas que se solapen con este hueco
                $haySolape = Cita::where('negocio_id', $negocio_id)
                    ->whereDate('fecha', $fecha->toDateString())
                    ->where(function ($query) use ($inicio, $bloqueFin) {
                        $query->where(function ($q) use ($inicio, $bloqueFin) {
                            $q->whereTime('hora', '<', $bloqueFin->format('H:i:s'))
                            ->whereRaw("ADDTIME(hora, SEC_TO_TIME(duracion * 60)) > ?", [$inicio->format('H:i:s')]);
                        });
                    })
                    ->exists();

                if (!$haySolape) {
                    $disponibles[] = [
                        'inicio' => $inicio->format('H:i'),
                        'fin' => $bloqueFin->format('H:i'),
                    ];
                }

                $inicio->addMinutes(15);
            }
        }

        return response()->json(['disponibles' => $disponibles]);
    }


}
