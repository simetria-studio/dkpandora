# Configuração do Stripe - DK Pandora

## 📋 Pré-requisitos

1. Conta no Stripe (https://stripe.com)
2. Chaves de API do Stripe
3. Laravel configurado

## 🔧 Configuração

### 1. Variáveis de Ambiente

Adicione as seguintes variáveis ao seu arquivo `.env`:

```env
# Stripe Configuration
STRIPE_KEY=pk_test_your_publishable_key_here
STRIPE_SECRET=sk_test_your_secret_key_here
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret_here
```

### 2. Obter as Chaves do Stripe

1. Acesse o [Dashboard do Stripe](https://dashboard.stripe.com)
2. Vá para **Developers > API keys**
3. Copie as chaves:
   - **Publishable key** → `STRIPE_KEY`
   - **Secret key** → `STRIPE_SECRET`

### 3. Configurar Webhook

1. No Dashboard do Stripe, vá para **Developers > Webhooks**
2. Clique em **Add endpoint**
3. Configure:
   - **Endpoint URL**: `https://seudominio.com/webhook/stripe`
   - **Events to send**: 
     - `payment_intent.succeeded`
     - `payment_intent.payment_failed`
     - `payment_intent.processing`
4. Copie o **Signing secret** → `STRIPE_WEBHOOK_SECRET`

### 4. Ativar PIX (Opcional)

1. No Dashboard do Stripe, vá para **Settings > Payment methods**
2. Procure por **PIX** na lista de métodos de pagamento
3. Clique em **Activate** para habilitar o PIX
4. Configure as informações necessárias (CNPJ, chave PIX, etc.)
5. Aguarde a aprovação do Stripe (pode levar alguns dias)

### 4. Testar a Integração

Use os cartões de teste do Stripe:

**Cartão de Sucesso:**
- Número: `4242 4242 4242 4242`
- Data: Qualquer data futura
- CVC: Qualquer 3 dígitos

**Cartão de Falha:**
- Número: `4000 0000 0000 0002`
- Data: Qualquer data futura
- CVC: Qualquer 3 dígitos

## 🚀 Funcionalidades Implementadas

### ✅ Processamento de Pagamentos
- Integração completa com Stripe
- Formulário seguro de cartão de crédito
- **Pagamento PIX** com QR Code e código copia e cola
- Validação em tempo real
- Tratamento de erros

### ✅ Webhooks
- Processamento automático de pagamentos
- Atualização de status dos pedidos
- Logs de transações

### ✅ Segurança
- Dados de cartão não são armazenados
- Comunicação criptografada
- Validação de assinatura de webhooks

## 📁 Arquivos Criados/Modificados

### Novos Arquivos:
- `app/Providers/StripeServiceProvider.php`
- `app/Services/StripeService.php`
- `app/Http/Controllers/PaymentController.php`
- `resources/views/payments/process.blade.php`
- `STRIPE_SETUP.md`

### Arquivos Modificados:
- `composer.json` - Adicionado pacote Stripe
- `config/services.php` - Configuração do Stripe
- `app/Providers/AppServiceProvider.php` - Registro do Service Provider
- `app/Models/Order.php` - Adicionado accessor para formatação
- `app/Http/Controllers/OrderController.php` - Integração com Stripe
- `routes/web.php` - Rotas de pagamento
- `resources/views/orders/checkout.blade.php` - Removida seleção de método

## 🔄 Fluxo de Pagamento

1. **Checkout**: Usuário finaliza pedido
2. **Criação do Pedido**: Sistema cria pedido com status "pending"
3. **Redirecionamento**: Usuário é redirecionado para página de pagamento
4. **Payment Intent**: Sistema cria Payment Intent no Stripe
5. **Formulário de Pagamento**: Usuário insere dados do cartão
6. **Processamento**: Stripe processa o pagamento
7. **Webhook**: Stripe notifica o sistema sobre o resultado
8. **Atualização**: Sistema atualiza status do pedido

## 🛠️ Comandos Úteis

```bash
# Instalar dependências
composer install

# Limpar cache de configuração
php artisan config:clear

# Verificar rotas
php artisan route:list

# Testar webhook localmente (usando ngrok)
ngrok http 8000

# Testar integração do Stripe
php artisan test:stripe-integration

# Testar integração PIX
php artisan test:pix-integration

# Testar estrutura de pedidos
php artisan test:order-items

# Testar view de pedidos
php artisan test:order-view
```

## 🔍 Troubleshooting

### Erro: "No such payment_intent"
- Verifique se a chave secreta está correta
- Confirme se está usando ambiente de teste/produção correto

### Erro: "Invalid signature"
- Verifique se o webhook secret está correto
- Confirme se a URL do webhook está acessível

### Erro: "Card declined"
- Use cartões de teste válidos
- Verifique se o cartão tem saldo suficiente

### Erro: "trim(): Argument #1 ($string) must be of type string, array given"
- **RESOLVIDO**: Adicionada validação de dados no StripeService
- Todos os valores enviados para o Stripe são convertidos para string
- Validação de IDs e parâmetros antes do envio

### Erro: "Amount must be at least R$ 0.50 brl"
- Valor mínimo para pagamentos no Stripe é R$ 0,50
- Sistema automaticamente ajusta valores baixos para teste
- Em produção, configure valores mínimos adequados

### Erro: "The payment method type 'pix' is invalid"
- **RESOLVIDO**: PIX precisa ser ativado no dashboard do Stripe
- Vá em **Settings > Payment methods** no Stripe Dashboard
- Procure por **PIX** e clique em **Activate**
- Configure as informações necessárias (CNPJ, chave PIX)
- Aguarde a aprovação do Stripe (pode levar alguns dias)

## 📞 Suporte

Para dúvidas sobre a integração:
- [Documentação do Stripe](https://stripe.com/docs)
- [Laravel Documentation](https://laravel.com/docs)
- [Stripe PHP SDK](https://github.com/stripe/stripe-php) 
