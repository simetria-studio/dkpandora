# DK Pandora - Sistema de E-commerce para Jogos

<p align="center">
<img src="public/img/violet_logo.png" width="200" alt="DK Pandora Logo">
</p>

## 🎮 Sobre o Projeto

DK Pandora é um sistema de e-commerce especializado na venda de itens para jogos, especificamente desenvolvido para Grand Fantasia Violet. O sistema oferece uma plataforma completa para compra e venda de itens de jogo, gold personalizado e gerenciamento de pedidos.

## ✨ Funcionalidades

### 🛒 Sistema de Loja
- Catálogo de produtos com categorias e raridades
- Sistema de carrinho com sessão
- Compra de gold personalizado
- Filtros por tipo, categoria e raridade

### 💳 Sistema de Pagamentos
- **Integração com Stripe** para processamento seguro
- Formulário de cartão de crédito com validação em tempo real
- **Pagamento PIX** com QR Code e código copia e cola
- Webhooks para atualização automática de status
- Suporte a múltiplos métodos de pagamento

### 👨‍💼 Painel Administrativo
- Dashboard com relatórios de vendas
- Gestão completa de produtos
- Controle de pedidos e usuários
- Sistema de configurações

### 🔐 Autenticação e Autorização
- Sistema de login/registro
- Controle de acesso administrativo
- Middleware de segurança

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## 🚀 Tecnologias

- **Backend**: Laravel 12
- **Frontend**: Bootstrap 5, Blade Templates
- **Pagamentos**: Stripe
- **Banco de Dados**: MySQL/PostgreSQL
- **Autenticação**: Laravel Breeze

## 📦 Instalação

### Pré-requisitos
- PHP 8.2+
- Composer
- MySQL/PostgreSQL
- Node.js (para assets)

### Passos

1. **Clone o repositório**
```bash
git clone <repository-url>
cd pandora
```

2. **Instale as dependências**
```bash
composer install
npm install
```

3. **Configure o ambiente**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure o banco de dados**
```bash
# Edite o arquivo .env com suas configurações de banco
php artisan migrate
php artisan db:seed
```

5. **Configure o Stripe** (Opcional)
```bash
# Adicione as variáveis do Stripe no .env
STRIPE_KEY=pk_test_your_key
STRIPE_SECRET=sk_test_your_secret
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret

# Teste a conexão
php artisan stripe:test
```

6. **Compile os assets**
```bash
npm run dev
```

7. **Inicie o servidor**
```bash
php artisan serve
```

## 🔧 Configuração do Stripe

Para ativar o processamento de pagamentos, siga o guia completo em [STRIPE_SETUP.md](STRIPE_SETUP.md).

### Variáveis de Ambiente Necessárias
```env
STRIPE_KEY=pk_test_your_publishable_key
STRIPE_SECRET=sk_test_your_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
```

## 📊 Estrutura do Projeto

```
pandora/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Controllers administrativos
│   │   │   ├── Auth/           # Controllers de autenticação
│   │   │   ├── PaymentController.php  # Integração Stripe
│   │   │   └── ...
│   │   └── Middleware/
│   ├── Models/                  # Modelos Eloquent
│   ├── Services/
│   │   └── StripeService.php   # Serviço do Stripe
│   └── Providers/
├── resources/
│   └── views/
│       ├── admin/              # Views administrativas
│       ├── payments/           # Views de pagamento
│       └── ...
└── routes/
    ├── web.php                 # Rotas principais
    ├── admin.php               # Rotas administrativas
    └── auth.php                # Rotas de autenticação
```

## 🎯 Funcionalidades Principais

### Sistema de Produtos
- ✅ Gestão de produtos com categorias
- ✅ Sistema de raridades (Comum, Raro, Épico, Lendário)
- ✅ Suporte a imagens de produtos
- ✅ Produtos em destaque

### Sistema de Pedidos
- ✅ Carrinho com sessão
- ✅ Checkout integrado
- ✅ Processamento de pagamentos
- ✅ Histórico de pedidos

### Sistema de Pagamentos
- ✅ Integração completa com Stripe
- ✅ Formulário seguro de cartão
- ✅ **Pagamento PIX** com QR Code
- ✅ Webhooks para atualização automática
- ✅ Tratamento de erros

### Painel Administrativo
- ✅ Dashboard com métricas
- ✅ Gestão de produtos
- ✅ Controle de pedidos
- ✅ Gestão de usuários

## 🧪 Testando

### Cartões de Teste do Stripe
```bash
# Cartão de sucesso
4242 4242 4242 4242

# Cartão de falha
4000 0000 0000 0002
```

### Comandos Úteis
```bash
# Testar conexão Stripe
php artisan stripe:test

# Testar integração PIX
php artisan test:pix-integration

# Verificar rotas
php artisan route:list

# Limpar cache
php artisan config:clear
```

## 🤝 Contribuindo

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 📞 Suporte

Para suporte técnico ou dúvidas:
- Email: suporte@dkpandora.com
- WhatsApp: (11) 99999-9999
- Discord: [Link do servidor]

---

**Desenvolvido com ❤️ para a comunidade de Grand Fantasia Violet**


