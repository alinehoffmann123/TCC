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

// Função para gerenciar pessoas (alunos e professores)
function pessoasManager() {
  return {
    activeTab: "alunos",
    showDeleteModal: false,
    personId: null,
    personName: "",

    // Alternar entre abas
    switchTab(tab) {
      this.activeTab = tab
    },

    // Confirmar exclusão
    confirmDelete(id, name) {
      this.personId = id
      this.personName = name
      this.showDeleteModal = true
    },

    // Executar exclusão
    executeDelete() {
      if (this.personId) {
        const form = document.getElementById(`delete-form-${this.personId}`)
        if (form) {
          form.submit()
        }
      }
      this.showDeleteModal = false
    },
  }
}

// Função para formatar telefone
function formatPhone(input) {
  let value = input.value.replace(/\D/g, "")

  if (value.length >= 11) {
    value = value.replace(/(\d{2})(\d{5})(\d{4})/, "($1) $2-$3")
  } else if (value.length >= 7) {
    value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, "($1) $2-$3")
  } else if (value.length >= 3) {
    value = value.replace(/(\d{2})(\d{0,5})/, "($1) $2")
  }

  input.value = value
}

// Função para validar email
function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return re.test(email)
}

// Função para mostrar notificação
function showNotification(message, type = "success") {
  const notification = document.createElement("div")
  notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 animate-fade-in ${
    type === "success"
      ? "bg-green-100 border border-green-400 text-green-700"
      : type === "error"
        ? "bg-red-100 border border-red-400 text-red-700"
        : "bg-blue-100 border border-blue-400 text-blue-700"
  }`
  notification.textContent = message

  document.body.appendChild(notification)

  setTimeout(() => {
    notification.remove()
  }, 5000)
}

// Função para confirmar ação
function confirmAction(message, callback) {
  if (confirm(message)) {
    callback()
  }
}

// Inicializar quando o DOM estiver carregado
document.addEventListener("DOMContentLoaded", () => {
  // Aplicar máscara de telefone
  const phoneInputs = document.querySelectorAll('input[type="tel"], input[name="telefone"]')
  phoneInputs.forEach((input) => {
    input.addEventListener("input", function () {
      formatPhone(this)
    })
  })

  // Validação de formulários
  const forms = document.querySelectorAll("form")
  forms.forEach((form) => {
    form.addEventListener("submit", (e) => {
      const emailInputs = form.querySelectorAll('input[type="email"]')
      let isValid = true

      emailInputs.forEach((input) => {
        if (input.value && !validateEmail(input.value)) {
          e.preventDefault()
          input.classList.add("border-red-500")
          showNotification("Por favor, insira um email válido.", "error")
          isValid = false
        }
      })

      return isValid
    })
  })
})

// Exportar funções para uso global
window.pessoasManager = pessoasManager
window.formatPhone = formatPhone
window.validateEmail = validateEmail
window.showNotification = showNotification
window.confirmAction = confirmAction


