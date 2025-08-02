<?php

use App\Http\Controllers\AlunosController;
use App\Http\Controllers\TurmasController;
use App\Http\Controllers\VideoAulasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\CadastroController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);


    Route::get('/cadastro', [CadastroController::class, 'showRegistrationForm'])->name('cadastro');
    Route::post('/cadastro', [CadastroController::class, 'cadastro']);

    Route::get('/password/reset', function () {
        return view('auth.passwords.email');
    })->name('password.request');
    
    Route::post('/password/email', function () {
        return back()->with('status', 'Funcionalidade em desenvolvimento.');
    })->name('password.email');
});


Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    Route::resource('alunos', AlunosController::class);
    Route::get('/alunos/lixeira', [AlunosController::class, 'trash'])->name('alunos.trash');
    Route::post('/alunos/{aluno}/restaurar', [AlunosController::class, 'restore'])->name('alunos.restore');


    Route::resource('turmas', TurmasController::class);
    Route::get('/turmas/lixeira', [TurmasController::class, 'trash'])->name('turmas.trash');
    Route::post('/turmas/{turma}/restaurar', [TurmasController::class, 'restore'])->name('turmas.restore');
    
    Route::get('/turmas/{turma}/alunos', [TurmasController::class, 'alunos'])->name('turmas.alunos');
    Route::post('/turmas/{turma}/matricular', [TurmasController::class, 'matricularAluno'])->name('turmas.matricular');
    Route::delete('/turmas/{turma}/alunos/{aluno}', [TurmasController::class, 'removerAluno'])->name('turmas.remover-aluno');

    Route::resource('video-aulas', VideoAulasController::class);
    Route::get('/video-aulas/lixeira', [VideoAulasController::class, 'trash'])->name('video-aulas.trash');
    Route::post('/video-aulas/{videoAula}/restaurar', [VideoAulasController::class, 'restore'])->name('video-aulas.restore');
});


