<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Aluno;
use App\Policies\AlunoPolicy;

class AuthServiceProvider extends ServiceProvider {
    /**
     * Mapeia os models para suas respectivas policies.
     * 
     * Aqui estamos dizendo ao Laravel que para o model Aluno,
     * será usada a policy AlunoPolicy.
     */
    protected $policies = [
        Aluno::class => AlunoPolicy::class,
    ];

    /**
     * Registra as policies da aplicação.
     * 
     * O método boot é chamado durante o carregamento do provedor de serviço.
     * A função registerPolicies conecta as policies mapeadas acima com o sistema
     * de autorização do Laravel (Gates, @can, etc).
     */
    public function boot(): void {
        $this->registerPolicies();
    }
}