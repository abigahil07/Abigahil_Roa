<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlumnoController extends Controller
{
    /**
     * Muestra todos los alumnos
     */
    public function index(Request $request)
    {
        $query = Alumno::with('curso');

        if ($request->filled('nombre')) {
            $query->where('nombre_alumno', 'like', '%'.$request->nombre.'%');
        }
        if ($request->filled('curso')) {
            $query->where('id_curso', $request->curso);
        }
        if ($request->filled('turno')) {
            $query->whereHas('curso', function($q) use ($request) {
                $q->where('turno', $request->turno);
            });
        }
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        $alumnos = $query->orderBy('created_at', 'desc')->get();
        return response()->json(['alumnos' => $alumnos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_alumno' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:alumnos,email',
            'id_curso' => 'required|exists:cursos,id',
        ]);
        $alumno = Alumno::create($validated);
        return response()->json(['message' => 'Alumno registrado correctamente', 'alumno' => $alumno], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $alumno = Alumno::with('curso')->findOrFail($id);
        return response()->json(['alumno' => $alumno]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $alumno = Alumno::findOrFail($id);
        $validated = $request->validate([
            'nombre_alumno' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('alumnos')->ignore($alumno->id),
            ],
            'id_curso' => 'required|exists:cursos,id',
        ]);
        $alumno->update($validated);
        return response()->json(['message' => 'Alumno actualizado correctamente', 'alumno' => $alumno]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $alumno = Alumno::findOrFail($id);
        $alumno->delete();
        return response()->json(['message' => 'Alumno eliminado correctamente']);
    }
}
