# 📦 Estoque Laravel

Sistema simples de CRUD de produtos com Laravel.

## 💡 O que faz

- ➕ Cadastrar produtos
- 📋 Listar produtos
- ✏️ Editar produtos
- 🗑️ Excluir produtos
- 🔐 Autenticação básica (Laravel padrão)

## 🚀 Como usar

### 1. Clone o repositório

```bash
git clone https://github.com/MatheusPrb/Estoque-Laravel.git
cd Estoque-Laravel
```

### 2. Configure o ambiente

```bash
cp .env.example .env
php artisan key:generate
```

### 2.5. Configure o .env 🔧

```bash
configure as variaveis do banco de dados
```

### 3. Suba o Docker 🐳

```bash
docker-compose up --build
```

### 4. Instale as dependências 📦

```bash
docker-compose exec app composer install
```

### 5. Acesse a aplicação 🌐

`http://localhost:81`

## 👨‍💻 Autor

[@MatheusPrb](https://github.com/MatheusPrb)
