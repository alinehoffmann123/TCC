/**
 * Alterna a visibilidade do campo de senha entre oculto (password) e visível (text).
 */
 function jVisualizarDadosSenhas(sSenha) {
    const sInputSenha      = document.getElementById(sSenha);
    const sVisualizarSenha = document.getElementById('eye-open-' + sSenha);
    const sEsconderSenha   = document.getElementById('eye-closed-' + sSenha);

    if (sInputSenha.type === 'password') {
        sInputSenha.type = 'text';
        sVisualizarSenha.classList.add('hidden');
        sEsconderSenha.classList.remove('hidden');
    } else {
        sInputSenha.type = 'password';
        sVisualizarSenha.classList.remove('hidden');
        sEsconderSenha.classList.add('hidden');
    }
}

function jRedirecionarPara(url) {
    if (url) {
       window.location.href = url;
    }
}
