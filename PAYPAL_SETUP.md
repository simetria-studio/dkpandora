# Configuração do PayPal

Este documento explica como configurar a integração com PayPal no projeto Laravel.

## 1. Configuração do Ambiente

### Variáveis de Ambiente

Adicione as seguintes variáveis ao seu arquivo `.env`:

```env
# PayPal Configuration
PAYPAL_CLIENT_ID=seu_client_id_aqui
PAYPAL_CLIENT_SECRET=seu_client_secret_aqui
PAYPAL_ENVIRONMENT=sandbox
PAYPAL_WEBHOOK_SECRET=seu_webhook_secret_aqui
```

### Configurações

- **PAYPAL_CLIENT_ID**: ID do cliente da sua aplicação PayPal
- **PAYPAL_CLIENT_SECRET**: Chave secreta da sua aplicação PayPal
- **PAYPAL_ENVIRONMENT**: Ambiente (`sandbox` para testes, `production` para produção)
- **PAYPAL_WEBHOOK_SECRET**: Chave secreta para validar webhooks (opcional)

## 2. Configuração da Conta PayPal

### 1. Acesse o [PayPal Developer Dashboard](https://developer.paypal.com/)
### 2. Faça login com sua conta PayPal
### 3. Vá para "My Apps & Credentials"
### 4. Clique em "Create App"
### 5. Dê um nome para sua aplicação
### 6. Selecione "Business" como tipo de conta
### 7. Clique em "Create App"
### 8. Copie o Client ID e Client Secret

## 3. Configuração de Webhooks

### 1. No Developer Dashboard, vá para "Webhooks"
### 2. Clique em "Add Webhook"
### 3. Configure a URL do webhook: `https://seudominio.com/webhook/paypal`
### 4. Selecione os eventos:
   - `PAYMENT.CAPTURE.COMPLETED`
   - `PAYMENT.CAPTURE.DENIED`
   - `PAYMENT.CAPTURE.PENDING`
   - `CHECKOUT.ORDER.APPROVED`
   - `CHECKOUT.ORDER.COMPLETED`
### 5. Salve o webhook

## 4. Testando a Integração

### Ambiente Sandbox

1. Use as credenciais de sandbox do PayPal
2. Teste com contas de sandbox do PayPal
3. Verifique os logs para debug

### Ambiente de Produção

1. Use as credenciais de produção do PayPal
2. Configure webhooks com URLs de produção
3. Teste com contas reais do PayPal

## 5. Fluxo de Pagamento

### 1. Usuário seleciona pagamento via PayPal
### 2. Sistema cria pedido no PayPal
### 3. Usuário é redirecionado para o PayPal
### 4. Usuário aprova o pagamento
### 5. PayPal redireciona para URL de sucesso
### 6. Sistema captura o pagamento
### 7. Status do pedido é atualizado

## 6. Tratamento de Erros

### Logs

Todos os erros são logados no sistema de logs do Laravel:
- `storage/logs/laravel.log`

### Webhooks

Webhooks são processados automaticamente e podem ser monitorados nos logs.

## 7. Segurança

### Validação de Webhooks

- Implemente validação de assinatura dos webhooks
- Use HTTPS em produção
- Valide o payload recebido

### Dados Sensíveis

- Nunca exponha Client ID e Secret no código
- Use variáveis de ambiente
- Revogue credenciais comprometidas

## 8. Monitoramento

### Status dos Pagamentos

- Verifique o status dos pedidos na base de dados
- Monitore webhooks recebidos
- Configure alertas para falhas

### Métricas

- Taxa de sucesso dos pagamentos
- Tempo de processamento
- Erros frequentes

## 9. Suporte

### Documentação Oficial

- [PayPal Developer Documentation](https://developer.paypal.com/docs/)
- [API Reference](https://developer.paypal.com/docs/api/)

### Problemas Comuns

1. **Webhook não recebido**: Verifique URL e configurações
2. **Erro de autenticação**: Verifique credenciais
3. **Pagamento não capturado**: Verifique fluxo de redirecionamento

## 10. Atualizações

### SDK

- Mantenha o SDK do PayPal atualizado
- Verifique changelog para mudanças importantes
- Teste em ambiente de desenvolvimento antes de atualizar produção
