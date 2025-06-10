<?php

namespace App\Http\Controllers;

use App\Models\HorarioNegocio;
use Illuminate\Http\Request;
use App\Models\Cita;
use Carbon\Carbon;
use App\Models\Services;

class HorarioNegocioController extends Controller
{
    public function show($negocio_id)
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
        $fecha = Carbon::parse($request->input('fecha'))->startOfDay(); 
        $diaSemana = $fecha->dayOfWeekIso;

        $horarios = HorarioNegocio::where('negocio_id', $negocio_id)
            ->where('dia_semana', $diaSemana)
            ->get();

        if ($horarios->isEmpty()) {
            return response()->json(['disponibles' => []]);
        }

        $citas = Cita::with('servicio')
            ->whereDate('fecha', $fecha->toDateString())
            ->whereHas('servicio', function($query) use ($negocio_id) {
                $query->where('negocio_id', $negocio_id);
            })
            ->get();

        $disponibles = collect(); 

        foreach ($horarios as $horario) {
            $inicio = Carbon::parse($fecha->format('Y-m-d') . ' ' . $horario->hora_inicio);
            $fin = Carbon::parse($fecha->format('Y-m-d') . ' ' . $horario->hora_fin);

            while ($inicio->lt($fin)) {
                $bloqueFin = $inicio->copy()->addMinutes(30);

                $disponible = true;
                foreach ($citas as $cita) {
                    $citaInicio = Carbon::parse($cita->fecha . ' ' . $cita->hora);
                    $duracion = $cita->servicio->duracion ?? 30;
                    $citaFin = $citaInicio->copy()->addMinutes($duracion);

                    if ($citaInicio < $bloqueFin && $citaFin > $inicio) {
                        $disponible = false;
                        break;
                    }
                }

                if ($disponible) {
                    $disponibles->push([
                        'inicio' => $inicio->format('H:i'),
                        'fin' => $bloqueFin->format('H:i'),
                        'hora_orden' => $inicio->format('Hi')
                    ]);
                }

                $inicio->addMinutes(30);
            }
        }

        $disponibles = $disponibles->sortBy('hora_orden')->values()->map(function($item) {
            return [
                'inicio' => $item['inicio'],
                'fin' => $item['fin']
            ];
        });

        return response()->json(['disponibles' => $disponibles]);
    }



}
