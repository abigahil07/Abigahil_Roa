<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Alumno;
use App\Models\Curso;

class AlumnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cursos = Curso::all();
        $alumnos = [
            ['nombre_alumno' => 'Juan Pérez', 'email' => 'juanperez@gmail.com'],
            ['nombre_alumno' => 'Ana Torres', 'email' => 'anatorres@gmail.com'],
            ['nombre_alumno' => 'Carlos Ruiz', 'email' => 'carlosruiz@gmail.com'],
            ['nombre_alumno' => 'Lucía Gómez', 'email' => 'luciagomez@gmail.com'],
            ['nombre_alumno' => 'Pedro Díaz', 'email' => 'pedrodiaz@gmail.com'],
        ];
        $i = 0;
        foreach ($alumnos as $alumno) {
            $curso = $cursos[$i % $cursos->count()];
            Alumno::create([
                'nombre_alumno' => $alumno['nombre_alumno'],
                'email' => $alumno['email'],
                'id_curso' => $curso->id,
            ]);
            $i++;
        }
    }
}
