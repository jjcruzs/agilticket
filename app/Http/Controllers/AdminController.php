<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $pendientes = Ticket::where('estado_id', 1)->count();
        $proceso = Ticket::where('estado_id', 2)->count();
        $resueltos = Ticket::where('estado_id', 3)->count();
        $total = Ticket::count();

        $recientes = Ticket::orderBy('fecha_creacion', 'desc')->take(5)->get();

        return view('autenticacion.admin', compact('pendientes', 'proceso', 'resueltos', 'total', 'recientes'));
    }

    public function tickets()
    {
        $tickets = Ticket::with(['estado', 'solicitante', 'responsable'])->get();

        return view('tickets.index', compact('tickets'));
    }

    public function nuevoTicket()
    {
        $usuarios = User::all();

        return view('autenticacion.nuevo_ticket', compact('usuarios'));
    }

    public function usuarios()
    {
        $usuarios = User::with('rol')->get();

        return view('admin.usuarios', compact('usuarios'));
    }

    public function crearUsuario()
    {
        $roles = Rol::all();

        return view('admin.crear_usuario', compact('roles'));
    }

    public function guardarUsuario(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|unique:usuarios,correo',
            'password' => 'required|string|min:6|confirmed',
            'rol_id' => 'required|integer',
        ]);

        User::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'password' => bcrypt($request->password),
            'rol_id' => $request->rol_id,
        ]);

        return redirect()
            ->route('admin.usuarios')
            ->with('success', '✅ Usuario creado correctamente.');
    }

    public function editarUsuario($id)
    {
        $usuario = User::findOrFail($id);
        $roles = Rol::all();

        return view('admin.editar_usuario', compact('usuario', 'roles'));
    }

    public function actualizarUsuario(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|unique:usuarios,correo,'.$usuario->id,
            'rol_id' => 'required|integer',
        ]);

        $usuario->update([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'rol_id' => $request->rol_id,
        ]);

        return redirect()->route('admin.usuarios')->with('success', '✅ Usuario actualizado correctamente.');
    }

    public function eliminarUsuario($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('admin.usuarios')->with('success', 'Usuario eliminado correctamente.');
    }

    public function reportForm()
    {
        return view('admin.reportes', [
            'tickets' => collect(),
            'desde' => null,
            'hasta' => null,
            'totales' => null,
        ]);
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'desde' => 'required|date',
            'hasta' => 'required|date|after_or_equal:desde',
            'export' => 'nullable|in:csv',
        ]);

        $desde = Carbon::parse($request->desde)->startOfDay();
        $hasta = Carbon::parse($request->hasta)->endOfDay();

        $tickets = Ticket::with(['estado', 'solicitante', 'responsable'])
            ->whereBetween('fecha_creacion', [$desde, $hasta])
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        $totales = [
            'pendientes' => $tickets->where('estado_id', 1)->count(),
            'enProceso' => $tickets->where('estado_id', 2)->count(),
            'resueltos' => $tickets->where('estado_id', 3)->count(),
            'total' => $tickets->count(),
        ];

        if ($request->input('export') === 'csv') {
            $filename = 'reporte_tickets_'.$desde->format('Ymd').'_'.$hasta->format('Ymd').'.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $columns = ['ID', 'Título', 'Solicitante', 'Responsable', 'Prioridad', 'Estado', 'Fecha Creación'];

            $callback = function () use ($tickets, $columns) {
                $fh = fopen('php://output', 'w');
                fputcsv($fh, $columns);
                foreach ($tickets as $t) {
                    fputcsv($fh, [
                        $t->id,
                        $t->titulo,
                        $t->solicitante->nombre ?? '',
                        $t->responsable->nombre ?? '',
                        $t->prioridad,
                        $t->estado->nombre ?? '',
                        $t->fecha_creacion,
                    ]);
                }
                fclose($fh);
            };

            return response()->stream($callback, 200, $headers);
        }

        return view('admin.reportes', [
            'tickets' => $tickets,
            'desde' => $desde->toDateString(),
            'hasta' => $hasta->toDateString(),
            'totales' => $totales,
        ]);
    }
}
