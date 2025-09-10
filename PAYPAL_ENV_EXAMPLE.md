# Configuração do PayPal - Variáveis de Ambiente

Adicione as seguintes variáveis ao seu arquivo `.env` para configurar o PayPal:

## 🔑 **Variáveis Obrigatórias**

```env
# PayPal Configuration
PAYPAL_CLIENT_ID=AZBw00QrvG...your_client_id_here
PAYPAL_CLIENT_SECRET=your_client_secret_here
PAYPAL_ENVIRONMENT=sandbox
```

## 🌍 **Configurações de Ambiente**

### **Sandbox (Desenvolvimento/Teste)**
```env
PAYPAL_ENVIRONMENT=sandbox
```
- Use para testes e desenvolvimento
- Não processa pagamentos reais
- Use contas de sandbox do PayPal

### **Production (Produção)**
```env
PAYPAL_ENVIRONMENT=production
```
- Use apenas em produção
- Processa pagamentos reais
- Use contas reais do PayPal

## 🔒 **Variáveis Opcionais**

```env
# Webhook Secret (recomendado para produção)
PAYPAL_WEBHOOK_SECRET=your_webhook_secret_here
```

## 📝 **Exemplo Completo**

```env
# ===========================================
# PAYPAL CONFIGURATION
# ===========================================

# Credenciais Obrigatórias
PAYPAL_CLIENT_ID=AZBw00QrvG...your_client_id_here
PAYPAL_CLIENT_SECRET=your_client_secret_here

# Ambiente (sandbox ou production)
PAYPAL_ENVIRONMENT=sandbox

# Webhook Secret (opcional)
PAYPAL_WEBHOOK_SECRET=your_webhook_secret_here
```

## 🧪 **Como Testar**

Após configurar as variáveis, teste a conexão com:

```bash
php artisan paypal:test
```

## ⚠️ **Importante**

- **NUNCA** commite suas credenciais reais no Git
- Use `.env.example` para documentar as variáveis necessárias
- Mantenha suas credenciais seguras
- Use ambiente sandbox para desenvolvimento

## 🔗 **Onde Obter as Credenciais**

1. Acesse [PayPal Developer Dashboard](https://developer.paypal.com/)
2. Faça login com sua conta PayPal
3. Vá para "My Apps & Credentials"
4. Crie uma nova aplicação ou use uma existente
5. Copie o Client ID e Client Secret

## 🚀 **Próximos Passos**

1. Configure as variáveis no `.env`
2. Teste a conexão com `php artisan paypal:test`
3. Configure webhooks no PayPal Developer Dashboard
4. Teste o fluxo completo de pagamento
