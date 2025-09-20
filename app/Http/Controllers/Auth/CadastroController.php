<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CadastroController extends Controller
{
    /** Exibe o formulário */
    public function showCadastroForm() {
        return view('auth.cadastro');
    }

    /** 
     * Processa o cadastro 
    */
    public function cadastro(Request $request) {
        $validator = Validator::make($request->all(), [
              'name'        => ['required', 'string', 'max:255']
            , 'email'       => ['required', 'string', 'email', 'max:255', 'unique:users,email']
            , 'password'    => ['required', 'string', 'min:8', 'confirmed']
            , 'role'        => ['required', 'in:aluno,professor']
            , 'invite_code' => ['required_if:role,professor','nullable','string']
        ], [
              'name.required'           => 'O nome é obrigatório.'
            , 'email.required'          => 'O email é obrigatório.'
            , 'email.email'             => 'Digite um email válido.'
            , 'email.unique'            => 'Este email já está cadastrado.'
            , 'password.required'       => 'A senha é obrigatória.'
            , 'password.min'            => 'A senha deve ter pelo menos 8 caracteres.'
            , 'password.confirmed'      => 'A confirmação da senha não confere.'
            , 'role.required'           => 'Selecione o tipo de acesso.'
            , 'role.in'                 => 'Tipo de acesso inválido.'
            , 'invite_code.required_if' => 'Informe o código para cadastro como professor.'
        ]);

        $validator->after(function ($v) use ($request) {
            if ($request->input('role') === 'professor') {
                $code = (string) config('auth.professor_invite_code', '');
                if (!strlen($code) || $request->input('invite_code') !== $code) {
                    $v->errors()->add('invite_code', 'Código de convite inválido.');
                }
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $role = $request->input('role'); 
            $alunoId = null;

            if ($role === 'aluno') {
                $aluno = Aluno::create([
                      'nome'      => $request->name
                    , 'email'     => $request->email ?? null   
                    , 'status'    => 'ativo'                    
                    , 'excluido'  => 'N'                    
                ]);

                $alunoId = $aluno->id;
            }

            $usuario = User::create([
                  'name'      => $request->name
                , 'email'     => $request->email
                , 'password'  => Hash::make($request->password)
                , 'role'      => $role
                , 'aluno_id'  => $alunoId
            ]);

            Auth::login($usuario);
            $request->session()->regenerate();

            return redirect()->route('dashboard')->with('success', 'Cadastro realizado com sucesso!');
        } catch (\Throwable $e) {
            return back()
                ->with('error', 'Erro ao realizar cadastro. Tente novamente.')
                ->withInput();
        }
    }
}
