<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CadastroController extends Controller
{
    /**
     * Mostrar o formulário de registro
     */
    public function showRegistrationForm()
    {
        return view('auth.cadastro');
    }

    /**
     * Processar o registro de um novo usuário
     */
    public function cadastro(Request $request)
    {
        // Validar os dados do formulário
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required'      => 'O campo nome é obrigatório.',
            'email.required'     => 'O campo email é obrigatório.',
            'email.email'        => 'Por favor, insira um email válido.',
            'email.unique'       => 'Este email já está cadastrado.',
            'password.required'  => 'O campo senha é obrigatório.',
            'password.min'       => 'A senha deve ter pelo menos 6 caracteres.',
            'password.confirmed' => 'A confirmação de senha não corresponde.',
        ]);

        // Criar o novo usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Fazer login do usuário após o registro (opcional)
        Auth::login($user);

        return redirect('/login')->with('success', 'Conta criada com sucesso!');
    }
}