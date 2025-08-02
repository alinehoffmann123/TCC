<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Mostrar o formulário de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Processar o login
     */
    public function login(Request $request)
    {
        // Validar os dados do formulário
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'Por favor, insira um email válido.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
        ]);

        // Tentar fazer login
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Login bem-sucedido
            $request->session()->regenerate();
            
            return redirect()->intended('/dashboard')->with('success', 'Login realizado com sucesso!');
        }

        // Login falhou
        return back()->withErrors([
            'email' => 'As credenciais fornecidas não conferem com nossos registros.',
        ])->withInput($request->except('password'));
    }

    /**
     * Fazer logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Logout realizado com sucesso!');
    }
}