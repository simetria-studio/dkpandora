# 🔗 Configuração de Webhooks para Produção

## 📋 Webhooks Necessários

### 1. 🟦 Stripe Webhooks

**URL do Webhook:** `https://seudominio.com/webhook/stripe`

**Eventos a configurar:**
- `payment_intent.succeeded`
- `payment_intent.payment_failed`
- `payment_intent.canceled`
- `checkout.session.completed`
- `invoice.payment_succeeded`
- `invoice.payment_failed`

**Como configurar:**
1. Acesse: https://dashboard.stripe.com/webhooks
2. Clique em "Add endpoint"
3. Cole a URL do webhook
4. Selecione os eventos listados acima
5. Copie o "Signing secret" e adicione ao `.env` como `STRIPE_WEBHOOK_SECRET`

### 2. 🟨 PayPal Webhooks

**URL do Webhook:** `https://seudominio.com/webhook/paypal`

**Eventos a configurar:**
- `PAYMENT.CAPTURE.COMPLETED`
- `PAYMENT.CAPTURE.DENIED`
- `PAYMENT.CAPTURE.PENDING`
- `CHECKOUT.ORDER.APPROVED`
- `CHECKOUT.ORDER.COMPLETED`
- `CHECKOUT.ORDER.CANCELLED`

**Como configurar:**
1. Acesse: https://developer.paypal.com/
2. Vá em "My Apps & Credentials"
3. Selecione sua aplicação de produção
4. Clique em "Webhooks"
5. Adicione a URL do webhook
6. Selecione os eventos listados acima
7. Copie o "Webhook ID" e adicione ao `.env` como `PAYPAL_WEBHOOK_SECRET`

## 🔧 Configuração no Servidor

### Nginx (recomendado)
```nginx
location /webhook/stripe {
    try_files $uri $uri/ /index.php?$query_string;
}

location /webhook/paypal {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### Apache (.htaccess)
```apache
RewriteRule ^webhook/stripe$ /index.php [L,QSA]
RewriteRule ^webhook/paypal$ /index.php [L,QSA]
```

## 🧪 Testando Webhooks

### Teste Stripe
```bash
# Usar o Stripe CLI para testar
stripe listen --forward-to localhost:8000/webhook/stripe
```

### Teste PayPal
```bash
# Usar o PayPal Developer Dashboard
# Ir em "Webhooks" > "Test webhook"
```

## 📊 Monitoramento

### Logs de Webhook
```bash
# Verificar logs de webhook
tail -f storage/logs/laravel.log | grep webhook
```

### Status dos Webhooks
- Stripe: https://dashboard.stripe.com/webhooks
- PayPal: https://developer.paypal.com/developer/webhooks/

## ⚠️ Importante

1. **HTTPS obrigatório** - Webhooks só funcionam com HTTPS
2. **URLs públicas** - Os webhooks precisam ser acessíveis publicamente
3. **Timeout** - Configure timeout adequado (30s+)
4. **Retry** - Configure retry automático para falhas temporárias
5. **Logs** - Monitore logs para identificar problemas

## 🔍 Troubleshooting

### Erro 404
- Verificar se as rotas estão configuradas
- Verificar se o servidor web está redirecionando corretamente

### Erro 500
- Verificar logs do Laravel
- Verificar se as credenciais estão corretas
- Verificar se o banco de dados está acessível

### Webhook não dispara
- Verificar se o evento está configurado
- Verificar se a URL está correta
- Verificar se o webhook está ativo

### Assinatura inválida
- Verificar se o secret está correto
- Verificar se a URL está exatamente igual
- Verificar se não há caracteres extras
