<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\CadastroController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlunosController;
use App\Http\Controllers\EvolucaoAlunoController;
use App\Http\Controllers\TurmasController;
use App\Http\Controllers\VideoAulasController;
use App\Http\Controllers\FaixaController;
use App\Http\Controllers\GraduacaoController;

/*
|--------------------------------------------------------------------------
| Rotas públicas (guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', fn () => redirect()->route('login'));

    Route::get('/login',   [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login',  [LoginController::class, 'login']);

    Route::get('/cadastro',  [CadastroController::class, 'showCadastroForm'])->name('cadastro');
    Route::post('/cadastro', [CadastroController::class, 'cadastro']);
});

/*
|--------------------------------------------------------------------------
| Rotas autenticadas (auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', fn () => redirect()->route('dashboard'))->name('home');

    Route::get('/alunos', [AlunosController::class, 'index'])
        ->name('alunos.index')->middleware('role:admin,professor,aluno');
    Route::get('/alunos/{aluno}', [AlunosController::class, 'show'])
        ->name('alunos.show')->middleware('role:admin,professor,aluno')->whereNumber('aluno');

    Route::middleware('role:admin,professor')->group(function () {
        Route::get('/alunos/create',       [AlunosController::class, 'create'])->name('alunos.create');
        Route::post('/alunos',             [AlunosController::class, 'store'])->name('alunos.store');
        Route::get('/alunos/{aluno}/edit', [AlunosController::class, 'edit'])->name('alunos.edit')->whereNumber('aluno');
        Route::put('/alunos/{aluno}',      [AlunosController::class, 'update'])->name('alunos.update')->whereNumber('aluno');
        Route::delete('/alunos/{aluno}',   [AlunosController::class, 'destroy'])->name('alunos.destroy')->whereNumber('aluno');
    });

    Route::get('/alunos/{aluno}/evolucao', [EvolucaoAlunoController::class, 'show'])
        ->name('alunos.evolucao')->middleware('role:admin,professor,aluno')->whereNumber('aluno');
    Route::post('/alunos/{aluno}/evolucao/grau', [EvolucaoAlunoController::class, 'storeGrau'])
        ->name('alunos.evolucao.grau.store')->middleware('role:admin,professor')->whereNumber('aluno');
    Route::post('/alunos/{aluno}/evolucao/promover', [EvolucaoAlunoController::class, 'promover'])
        ->name('alunos.evolucao.promover')->middleware('role:admin,professor')->whereNumber('aluno');

    Route::get('/turmas', [TurmasController::class, 'index'])
        ->name('turmas.index')->middleware('role:admin,professor,aluno');
    Route::get('/turmas/{turma}', [TurmasController::class, 'show'])
        ->name('turmas.show')->middleware('role:admin,professor,aluno')->whereNumber('turma');

    Route::middleware('role:admin,professor')->group(function () {
        Route::get('/turmas/create',       [TurmasController::class, 'create'])->name('turmas.create');
        Route::post('/turmas',             [TurmasController::class, 'store'])->name('turmas.store');
        Route::get('/turmas/{turma}/edit', [TurmasController::class, 'edit'])->name('turmas.edit')->whereNumber('turma');
        Route::put('/turmas/{turma}',      [TurmasController::class, 'update'])->name('turmas.update')->whereNumber('turma');
        Route::delete('/turmas/{turma}',   [TurmasController::class, 'destroy'])->name('turmas.destroy')->whereNumber('turma');

        Route::post('/turmas/{turma}/restaurar',       [TurmasController::class, 'restore'])->name('turmas.restore')->whereNumber('turma');
        Route::get('/turmas/{turma}/alunos',           [TurmasController::class, 'alunos'])->name('turmas.alunos')->whereNumber('turma');
        Route::post('/turmas/{turma}/matricular',      [TurmasController::class, 'matricularAluno'])->name('turmas.matricular')->whereNumber('turma');
        Route::delete('/turmas/{turma}/alunos/{aluno}',[TurmasController::class, 'removerAluno'])->name('turmas.remover-aluno')->whereNumber('turma')->whereNumber('aluno');
    });

    Route::get('/video-aulas', [VideoAulasController::class, 'index'])
        ->name('video-aulas.index')->middleware('role:admin,professor,aluno');
    Route::get('/video-aulas/{video_aula}', [VideoAulasController::class, 'show'])
        ->name('video-aulas.show')->middleware('role:admin,professor,aluno')->whereNumber('video_aula');

    Route::middleware('role:admin,professor')->group(function () {
        Route::get('/video-aulas/create',            [VideoAulasController::class, 'create'])->name('video-aulas.create');
        Route::post('/video-aulas',                  [VideoAulasController::class, 'store'])->name('video-aulas.store');
        Route::get('/video-aulas/{video_aula}/edit', [VideoAulasController::class, 'edit'])->name('video-aulas.edit')->whereNumber('video_aula');
        Route::put('/video-aulas/{video_aula}',      [VideoAulasController::class, 'update'])->name('video-aulas.update')->whereNumber('video_aula');
        Route::delete('/video-aulas/{video_aula}',   [VideoAulasController::class, 'destroy'])->name('video-aulas.destroy')->whereNumber('video_aula');
    });

    Route::middleware('role:admin,professor')->group(function () {
        Route::get('/faixas',              [FaixaController::class, 'index'])->name('faixas.index');
        Route::get('/faixas/create',       [FaixaController::class, 'create'])->name('faixas.create');
        Route::post('/faixas',             [FaixaController::class, 'store'])->name('faixas.store');
        Route::get('/faixas/{faixa}/edit', [FaixaController::class, 'edit'])->name('faixas.edit')->whereNumber('faixa');
        Route::put('/faixas/{faixa}',      [FaixaController::class, 'update'])->name('faixas.update')->whereNumber('faixa');
        Route::delete('/faixas/{faixa}',   [FaixaController::class, 'destroy'])->name('faixas.destroy')->whereNumber('faixa');
    });

    Route::prefix('alunos/{aluno}')->whereNumber('aluno')->middleware('role:admin,professor')->group(function () {
        Route::get('graduacoes',        [GraduacaoController::class, 'index'])->name('graduacoes.index');
        Route::get('graduacoes/create', [GraduacaoController::class, 'create'])->name('graduacoes.create');
        Route::post('graduacoes',       [GraduacaoController::class, 'store'])->name('graduacoes.store');
    });
});
