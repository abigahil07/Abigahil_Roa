<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;

class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cursos = [
            ['nombre_curso' => 'Matemática', 'turno' => 'Mañana'],
            ['nombre_curso' => 'Lengua', 'turno' => 'Tarde'],
            ['nombre_curso' => 'Historia', 'turno' => 'Noche'],
            ['nombre_curso' => 'Inglés', 'turno' => 'Mañana'],
            ['nombre_curso' => 'Física', 'turno' => 'Tarde'],
        ];
        foreach ($cursos as $curso) {
            Curso::create($curso);
        }
    }
}
