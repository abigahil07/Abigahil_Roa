<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    protected $fillable = [
        'nombre_curso',
        'turno'
    ];

    public function alumnos(): HasMany
    {
        return $this->hasMany(Alumno::class, 'id_curso');
    }
}
