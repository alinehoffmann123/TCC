<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller {
    public function showLoginForm() {
        return view('auth.login');
    }

    public function login(Request $oRequest) {
        $oValidador = Validator::make($oRequest->all(), [
              'email'    => ['required', 'email']
            , 'password' => ['required']
        ], [
              'email.required'    => 'O email é obrigatório.'
            , 'email.email'       => 'Digite um email válido.'
            , 'password.required' => 'A senha é obrigatória.'
        ]);

        if ($oValidador->fails()) {
            return back()->withErrors($oValidador)->withInput();
        }

        $bRelembrar = $oRequest->get('remember', false);
        $oUsuario = User::where('email', $oRequest->input('email'))->first();

        if (!$oUsuario) {
            return back()
                ->withErrors(['email' => 'Usuário não cadastrado. Crie uma conta para continuar.'])
                ->withInput($oRequest->only('email'));
        }

        if (!Hash::check($oRequest->input('password'), $oUsuario->password)) {
            return back()
                ->withErrors(['password' => 'Senha incorreta.'])
                ->withInput($oRequest->only('email'));
        }

        Auth::login($oUsuario, $bRelembrar);
        $oRequest->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $oRequest) {
        Auth::logout();
        $oRequest->session()->invalidate();
        $oRequest->session()->regenerateToken();
        return redirect()->route('login');
    }
}
