<?php

namespace App\Http\Controllers;

use App\Models\Caso;
use App\Models\Doctor;
use App\Models\Reception;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function getStatsDoc($id, Request $request)
    {
        $doctor = Doctor::find($id);
        if (!$doctor)
            return response()->json([
                'message' => 'El doctor no esta registrado en el sistema.'
            ], 404);
        try {
            $request->validate([
                'opciones' => 'required|string',
            ]);

            $fechaInicio = null;
            $fechaFin = null;
            switch ($request->opciones) {
                case 'Hoy':
                    $fechaInicio = Carbon::now()->startOfDay()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfDay()->format('Y-m-d');
                    break;
                case 'Esta semana':
                    $fechaInicio = Carbon::now()->startOfWeek()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'Los últimos 30 días':
                    $fechaInicio = Carbon::now()->subDays(30)->startOfDay()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'Todo el tiempo':
                    $fechaInicio = Carbon::createFromFormat('Y-m-d', '2000-01-01');
                    $fechaFin = Carbon::now()->endOfDay()->format('Y/m/d');
                    break;
                case 'Rango de fechas':
                    $fechaInicio = Carbon::createFromFormat('Y-m-d', $request->fecha_inicio)->format('Y/m/d');
                    $fechaFin = Carbon::createFromFormat('Y-m-d', $request->fecha_fin)->format('Y/m/d');
                    break;
                default:
                    return response()->json([
                        'message' => 'Opcion no reconocida'
                    ], 404);
                    break;
            }
            $casos = Caso::where('id_doctor', $id)->whereBetween('fecha', [$fechaInicio, $fechaFin])->get();
            $motivos = [
                ['cantidad' => 0, 'tipo' => 'Consulta'],
                ['cantidad' => 0, 'tipo' => 'Revision'],
                ['cantidad' => 0, 'tipo' => 'Ingreso'],
                ['cantidad' => 0, 'tipo' => 'Espontáneo'],
                ['cantidad' => 0, 'tipo' => 'Seguro']
            ];
            foreach ($casos as $caso) {
                switch ($caso->desglose) {
                    case "Consulta":
                        $motivos[0]['cantidad'] += $caso->cantidad;
                        break;
                    case "Revisión":
                        $motivos[1]['cantidad'] += $caso->cantidad;
                        break;
                    case "Ingreso":
                        $motivos[2]['cantidad'] += $caso->cantidad;
                        break;
                    case "Espontáneo":
                        $motivos[3]['cantidad'] += $caso->cantidad;
                        break;
                    case "Seguro":
                        $motivos[4]['cantidad'] += $caso->cantidad;
                        break;
                    default:
                }
            }
            return response()->json([
                'casos' => $motivos
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function getStatsGraphic($id, Request $request)
    {
        $doctor = Doctor::find($id);
        if (!$doctor)
            return response()->json([
                'message' => 'El doctor no esta registrado en el sistema.'
            ], 404);
        try {
            $request->validate([
                'opciones' => 'required|string',
            ]);

            $fechaInicio = null;
            $fechaFin = null;
            switch ($request->opciones) {
                case 'Hoy':
                    $fechaInicio = Carbon::now()->startOfDay()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfDay()->format('Y-m-d');
                    break;
                case 'Esta semana':
                    $fechaInicio = Carbon::now()->startOfWeek()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'Los últimos 30 días':
                    $fechaInicio = Carbon::now()->subDays(30)->startOfDay()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'Todo el tiempo':
                    $fechaInicio = Carbon::createFromFormat('Y-m-d', '2000-01-01');
                    $fechaFin = Carbon::now()->endOfDay()->format('Y/m/d');
                    break;
                case 'Rango de fechas':
                    $fechaInicio = Carbon::createFromFormat('Y-m-d', $request->fecha_inicio)->format('Y/m/d');
                    $fechaFin = Carbon::createFromFormat('Y-m-d', $request->fecha_fin)->format('Y/m/d');
                    break;
                default:
                    return response()->json([
                        'message' => 'Opcion no reconocida'
                    ], 404);
                    break;
            }

            $casos = Caso::where('id_doctor', $id)->whereBetween('fecha', [$fechaInicio, $fechaFin])->get();
            $motivos = [0, 0, 0, 0, 0];
            foreach ($casos as $caso) {
                switch ($caso->desglose) {
                    case "Consulta":
                        $motivos[0] += $caso->cantidad;
                        break;
                    case "Revisión":
                        $motivos[1] += $caso->cantidad;
                        break;
                    case "Ingreso":
                        $motivos[2] += $caso->cantidad;
                        break;
                    case "Espontáneo":
                        $motivos[3] += $caso->cantidad;
                        break;
                    case "Seguro":
                        $motivos[4] += $caso->cantidad;
                        break;
                    default:
                }
            }
            return response()->json([
                'casos' => $motivos
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function statsReception($id, Request $request)
    {
        $recepcion = Reception::find($id);
        if (!$recepcion)
            return response()->json([
                'message' => 'La recepcion no esta registrada en el sistema.'
            ], 404);
        try {
            $request->validate([
                'opciones' => 'required|string',
            ]);
            $fechaInicio = null;
            $fechaFin = null;
            switch ($request->opciones) {
                case 'Hoy':
                    $fechaInicio = Carbon::now()->startOfDay()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfDay()->format('Y-m-d');
                    break;
                case 'Esta semana':
                    $fechaInicio = Carbon::now()->startOfWeek()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'Los últimos 30 días':
                    $fechaInicio = Carbon::now()->subDays(30)->startOfDay()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'Todo el tiempo':
                    $fechaInicio = Carbon::createFromFormat('Y-m-d', '2000-01-01');
                    $fechaFin = Carbon::now()->endOfDay()->format('Y/m/d');
                    break;
                case 'Rango de fechas':
                    $fechaInicio = Carbon::createFromFormat('Y-m-d', $request->fecha_inicio)->format('Y/m/d');
                    $fechaFin = Carbon::createFromFormat('Y-m-d', $request->fecha_fin)->format('Y/m/d');
                    break;
                default:
                    return response()->json([
                        'message' => 'Opcion no reconocida'
                    ], 404);
                    break;
            }
            $casos = Caso::where('id_reception', $id)->whereBetween('fecha', [$fechaInicio, $fechaFin])->get();
            $motivos = [
                ['cantidad' => 0, 'tipo' => 'Consulta'],
                ['cantidad' => 0, 'tipo' => 'Revision'],
                ['cantidad' => 0, 'tipo' => 'Ingreso'],
                ['cantidad' => 0, 'tipo' => 'Espontáneo'],
                ['cantidad' => 0, 'tipo' => 'Seguro']
            ];
            foreach ($casos as $caso) {
                switch ($caso->desglose) {
                    case "Consulta":
                        $motivos[0]['cantidad'] += $caso->cantidad;
                        break;
                    case "Revisión":
                        $motivos[1]['cantidad'] += $caso->cantidad;
                        break;
                    case "Ingreso":
                        $motivos[2]['cantidad'] += $caso->cantidad;
                        break;
                    case "Espontáneo":
                        $motivos[3]['cantidad'] += $caso->cantidad;
                        break;
                    case "Seguro":
                        $motivos[4]['cantidad'] += $caso->cantidad;
                        break;
                    default:
                }
            }
            return response()->json([
                'casos' => $motivos
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function statsReceptionGraphic($id, Request $request)
    {
        $recepcion = Reception::find($id);
        if (!$recepcion)
            return response()->json([
                'message' => 'La recepcion no esta registrada en el sistema.'
            ], 404);
        try {
            $request->validate([
                'opciones' => 'required|string',
            ]);
            $fechaInicio = null;
            $fechaFin = null;
            switch ($request->opciones) {
                case 'Hoy':
                    $fechaInicio = Carbon::now()->startOfDay()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfDay()->format('Y-m-d');
                    break;
                case 'Esta semana':
                    $fechaInicio = Carbon::now()->startOfWeek()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'Los últimos 30 días':
                    $fechaInicio = Carbon::now()->subDays(30)->startOfDay()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'Todo el tiempo':
                    $fechaInicio = Carbon::createFromFormat('Y-m-d', '2000-01-01');
                    $fechaFin = Carbon::now()->endOfDay()->format('Y/m/d');
                    break;
                case 'Rango de fechas':
                    $fechaInicio = Carbon::createFromFormat('Y-m-d', $request->fecha_inicio)->format('Y/m/d');
                    $fechaFin = Carbon::createFromFormat('Y-m-d', $request->fecha_fin)->format('Y/m/d');
                    break;
                default:
                    return response()->json([
                        'message' => 'Opcion no reconocida'
                    ], 404);
                    break;
            }
            $casos = Caso::where('id_reception', $id)->whereBetween('fecha', [$fechaInicio, $fechaFin])->get();
            $motivos = [0, 0, 0, 0, 0];
            foreach ($casos as $caso) {
                switch ($caso->desglose) {
                    case "Consulta":
                        $motivos[0] += $caso->cantidad;
                        break;
                    case "Revisión":
                        $motivos[1] += $caso->cantidad;
                        break;
                    case "Ingreso":
                        $motivos[2] += $caso->cantidad;
                        break;
                    case "Espontáneo":
                        $motivos[3] += $caso->cantidad;
                        break;
                    case "Seguro":
                        $motivos[4] += $caso->cantidad;
                        break;
                    default:
                }
            }
            return response()->json([
                'casos' => $motivos
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }
    }
    //----------------------------------------------------------------------------------------------------
    public function statsGenerales(Request $request)
    {
        try {
            $request->validate([
                'opciones' => 'required|string',
            ]);
            $fechaInicio = null;
            $fechaFin = null;
            switch ($request->opciones) {
                case 'Hoy':
                    $fechaInicio = Carbon::now()->startOfDay()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfDay()->format('Y-m-d');
                    break;
                case 'Esta semana':
                    $fechaInicio = Carbon::now()->startOfWeek()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'Los últimos 30 días':
                    $fechaInicio = Carbon::now()->subDays(30)->startOfDay()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'Todo el tiempo':
                    $fechaInicio = Carbon::createFromFormat('Y-m-d', '2000-01-01');
                    $fechaFin = Carbon::now()->endOfDay()->format('Y/m/d');
                    break;
                case 'Rango de fechas':
                    $fechaInicio = Carbon::createFromFormat('Y-m-d', $request->fecha_inicio)->format('Y/m/d');
                    $fechaFin = Carbon::createFromFormat('Y-m-d', $request->fecha_fin)->format('Y/m/d');
                    break;
                default:
                    return response()->json([
                        'message' => 'Opcion no reconocida'
                    ], 404);
                    break;
            }
            $casos = Caso::whereBetween('fecha', [$fechaInicio, $fechaFin])->get();
            $motivos = [
                ['cantidad' => 0, 'tipo' => 'Consulta'],
                ['cantidad' => 0, 'tipo' => 'Revision'],
                ['cantidad' => 0, 'tipo' => 'Ingreso'],
                ['cantidad' => 0, 'tipo' => 'Espontáneo'],
                ['cantidad' => 0, 'tipo' => 'Seguro']
            ];
            foreach ($casos as $caso) {
                switch ($caso->desglose) {
                    case "Consulta":
                        $motivos[0]['cantidad'] += $caso->cantidad;
                        break;
                    case "Revisión":
                        $motivos[1]['cantidad'] += $caso->cantidad;
                        break;
                    case "Ingreso":
                        $motivos[2]['cantidad'] += $caso->cantidad;
                        break;
                    case "Espontáneo":
                        $motivos[3]['cantidad'] += $caso->cantidad;
                        break;
                    case "Seguro":
                        $motivos[4]['cantidad'] += $caso->cantidad;
                        break;
                    default:
                }
            }
            return response()->json([
                'casos' => $motivos
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function statsGeneralesGraphic(Request $request)
    {
        try {
            $request->validate([
                'opciones' => 'required|string',
            ]);
            $fechaInicio = null;
            $fechaFin = null;
            switch ($request->opciones) {
                case 'Hoy':
                    $fechaInicio = Carbon::now()->startOfDay()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfDay()->format('Y-m-d');
                    break;
                case 'Esta semana':
                    $fechaInicio = Carbon::now()->startOfWeek()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'Los últimos 30 días':
                    $fechaInicio = Carbon::now()->subDays(30)->startOfDay()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'Todo el tiempo':
                    $fechaInicio = Carbon::createFromFormat('Y-m-d', '2000-01-01');
                    $fechaFin = Carbon::now()->endOfDay()->format('Y/m/d');
                    break;
                case 'Rango de fechas':
                    $fechaInicio = Carbon::createFromFormat('Y-m-d', $request->fecha_inicio)->format('Y/m/d');
                    $fechaFin = Carbon::createFromFormat('Y-m-d', $request->fecha_fin)->format('Y/m/d');
                    break;
                default:
                    return response()->json([
                        'message' => 'Opcion no reconocida'
                    ], 404);
                    break;
            }
            $casos = Caso::whereBetween('fecha', [$fechaInicio, $fechaFin])->get();
            $motivos = [0, 0, 0, 0, 0];
            foreach ($casos as $caso) {
                switch ($caso->desglose) {
                    case "Consulta":
                        $motivos[0] += $caso->cantidad;
                        break;
                    case "Revisión":
                        $motivos[1] += $caso->cantidad;
                        break;
                    case "Ingreso":
                        $motivos[2] += $caso->cantidad;
                        break;
                    case "Espontáneo":
                        $motivos[3] += $caso->cantidad;
                        break;
                    case "Seguro":
                        $motivos[4] += $caso->cantidad;
                        break;
                    default:
                }
            }
            return response()->json([
                'casos' => $motivos
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function statsDoctorTable($id, Request $request)
    {
        $recepcion = Reception::find($id);
        if (!$recepcion)
            return response()->json([
                'message' => 'La recepcion no esta registrada en el sistema.'
            ], 404);
        try {
            $request->validate([
                'opciones' => 'required|string',
            ]);
            $fechaInicio = null;
            $fechaFin = null;
            switch ($request->opciones) {
                case 'Hoy':
                    $fechaInicio = Carbon::now()->startOfDay()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfDay()->format('Y-m-d');
                    break;
                case 'Esta semana':
                    $fechaInicio = Carbon::now()->startOfWeek()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'Los últimos 30 días':
                    $fechaInicio = Carbon::now()->subDays(30)->startOfDay()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'Todo el tiempo':
                    $fechaInicio = Carbon::createFromFormat('Y-m-d', '2000-01-01')->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfDay()->format('Y/m/d');
                    break;
                case 'Rango de fechas':
                    $fechaInicio = Carbon::createFromFormat('Y-m-d', $request->fecha_inicio)->format('Y/m/d');
                    $fechaFin = Carbon::createFromFormat('Y-m-d', $request->fecha_fin)->format('Y/m/d');
                    break;
                default:
                    return response()->json([
                        'message' => 'Opcion no reconocida'
                    ], 404);
                    break;
            }
            $doctors = Doctor::where('reception_id', $id)->get();
            $casos = Caso::where('id_reception', $id)->whereBetween('fecha', [$fechaInicio, $fechaFin])->get();
            $response = [];
            foreach ($doctors as $doctor) {
                $motivos = [0, 0, 0, 0, 0];
                foreach ($casos as $caso) {
                    if ($caso->id_doctor == $doctor->id) {
                        switch ($caso->desglose) {
                            case "Consulta":
                                $motivos[0] += $caso->cantidad;
                                break;
                            case "Revisión":
                                $motivos[1] += $caso->cantidad;
                                break;
                            case "Ingreso":
                                $motivos[2] += $caso->cantidad;
                                break;
                            case "Espontáneo":
                                $motivos[3] += $caso->cantidad;
                                break;
                            case "Seguro":
                                $motivos[4] += $caso->cantidad;
                                break;
                            default:
                        }
                    }
                }
                $totalCasos = array_sum($motivos);
                $response[] = [
                    'doctor' => $doctor,
                    'casos' => $motivos,
                    'total' => $totalCasos
                ];
            }
            return response()->json([
                'informacion' => $response,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function statsGeneralesTable(Request $request)
    {
        try {
            // Validar el parámetro 'opciones'
            $request->validate([
                'opciones' => 'required|string',
            ]);

            // Establecer las fechas de inicio y fin basadas en la opción seleccionada
            $fechaInicio = null;
            $fechaFin = null;
            switch ($request->opciones) {
                case 'Hoy':
                    $fechaInicio = Carbon::now()->startOfDay()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfDay()->format('Y-m-d');
                    break;
                case 'Esta semana':
                    $fechaInicio = Carbon::now()->startOfWeek()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'Los últimos 30 días':
                    $fechaInicio = Carbon::now()->subDays(30)->startOfDay()->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfDay()->format('Y-m-d');
                    break;
                case 'Todo el tiempo':
                    $fechaInicio = Carbon::createFromFormat('Y-m-d', '2000-01-01')->format('Y-m-d');
                    $fechaFin = Carbon::now()->endOfDay()->format('Y-m-d');
                    break;
                case 'Rango de fechas':
                    $fechaInicio = Carbon::createFromFormat('Y-m-d', $request->fecha_inicio)->format('Y-m-d');
                    $fechaFin = Carbon::createFromFormat('Y-m-d', $request->fecha_fin)->format('Y-m-d');
                    break;
                default:
                    return response()->json([
                        'message' => 'Opción no reconocida'
                    ], 404);
            }

            // Obtener los casos en el rango de fechas seleccionadas
            $casos = Caso::whereBetween('fecha', [$fechaInicio, $fechaFin])->get();

            // Agrupar los casos por fecha y desglose
            $response = [];
            foreach ($casos as $caso) {
                $fecha = Carbon::parse($caso->fecha)->format('d-m-Y');
                if (!isset($response[$fecha])) {
                    $response[$fecha] = [
                        'Consulta' => 0,
                        'Revision' => 0,
                        'Ingreso' => 0,
                        'Espontaneo' => 0,
                        'Seguro' => 0,
                        'Total' => 0
                    ];
                }
                switch ($caso->desglose) {
                    case "Consulta":
                        $response[$fecha]['Consulta'] += $caso->cantidad;
                        break;
                    case "Revisión":
                        $response[$fecha]['Revision'] += $caso->cantidad;
                        break;
                    case "Ingreso":
                        $response[$fecha]['Ingreso'] += $caso->cantidad;
                        break;
                    case "Espontáneo":
                        $response[$fecha]['Espontaneo'] += $caso->cantidad;
                        break;
                    case "Seguro":
                        $response[$fecha]['Seguro'] += $caso->cantidad;
                        break;
                    default:
                }
                $response[$fecha]['Total'] += $caso->cantidad;
            }

            // Formatear la respuesta para enviarla como JSON
            $formattedResponse = [];
            foreach ($response as $fecha => $motivos) {
                $formattedResponse[] = [
                    'fecha' => $fecha,
                    'Consulta' => $motivos['Consulta'],
                    'Revision' => $motivos['Revision'],
                    'Ingreso' => $motivos['Ingreso'],
                    'Espontaneo' => $motivos['Espontaneo'],
                    'Seguro' => $motivos['Seguro'],
                    'Total' => $motivos['Total']
                ];
            }

            // Devolver la respuesta en formato JSON
            return response()->json([
                'informacion' => $formattedResponse,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
