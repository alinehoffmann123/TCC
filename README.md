
# 🥋 FightGym System

## 💻 Sistema de Gerenciamento de Academias de Jiu-Jitsu

---

## 📄 Descrição do Projeto

O **FightGym System** é um sistema desenvolvido para auxiliar na gestão administrativa de academias de **Jiu-Jitsu**, proporcionando maior organização, automação de tarefas e controle eficiente de alunos, mensalidades, agendamento de aulas e financeiro.

O projeto surgiu da necessidade observada nas academias, que geralmente utilizam métodos manuais ou terceirizados para controle de seus processos, gerando custos elevados e desorganização.  

Este sistema busca oferecer uma solução moderna, prática e escalável, utilizando tecnologias atuais como **Laravel**, **Alpine.js**, **MySQL**, **Tailwind CSS** e **Docker**.

---

## 🚀 Tecnologias Utilizadas

- 🧠 **Backend:** PHP 8 + Laravel
- 🎨 **Frontend:** Alpine.js + Tailwind CSS + Vite
- 🗄️ **Banco de Dados:** MySQL
- 🐳 **Containers:** Docker + Docker Compose

---

## 🗂️ Funcionalidades do Sistema

- ✅ Cadastro e gerenciamento de alunos
- ✅ Cadastro de mestres/professores
- ✅ Agendamento de aulas
- ✅ Acompanhamento de desempenho dos alunos
- ✅ Interface limpa, responsiva e amigável

---

## 🔧 Como Executar o Projeto (Local + Docker)

### 📦 Pré-requisitos

- Docker
- Docker Compose
- Composer

### 🐳 Rodando com Docker

1️⃣ Clone o projeto:

```bash
git clone https://github.com/seu-usuario/fightgym-system.git
cd fightgym-system
```

2️⃣ Configure o arquivo `.env`:

```bash
cp .env.example .env
```

3️⃣ Execute os containers:

```bash
docker-compose up -d --build
```

4️⃣ Instale as dependências PHP dentro do container (se necessário):

```bash
docker exec -it fightgym_app composer install
```

5️⃣ Rode o frontend com Vite:

```bash
npm install
npm run dev
```

## 🏗️ Comandos Utilizados na Criação do Projeto

### 📌 Instalação e Configuração do Laravel:

```bash
composer create-project laravel/laravel fightgym-system
composer install
npm install
php artisan serve
```

### 📌 Rodando Frontend:

```bash
npm run dev
```

### 📌 Comandos Docker:

```bash
docker-compose up -d --build
```
---

## 🏗️ Arquitetura Utilizada

- Arquitetura **MVC**
- Infraestrutura com Docker

---

## 👩‍💻 Autor(a)

- **Aline Fernanda Hoffmann**  
🎓 Bacharelanda em Sistemas de Informação — UNIDAVI  
📧 aline.hoffmann@unidavi.edu.br