<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CursoController;

Route::get('/', function () {
    return Inertia::render('Alumnos');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/alumnos', function () {
        return Inertia::render('Alumnos');
    })->name('alumnos');
});

// Rutas tipo API para alumnos y cursos
Route::get('/api/alumnos', [AlumnoController::class, 'index']);
Route::post('/api/alumnos', [AlumnoController::class, 'store']);
Route::get('/api/alumnos/{id}', [AlumnoController::class, 'show']);
Route::put('/api/alumnos/{id}', [AlumnoController::class, 'update']);
Route::delete('/api/alumnos/{id}', [AlumnoController::class, 'destroy']);

Route::get('/api/cursos', [CursoController::class, 'index']);

require __DIR__.'/auth.php';
