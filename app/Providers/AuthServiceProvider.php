<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Aluno;
use App\Policies\AlunoPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Aluno::class => AlunoPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}