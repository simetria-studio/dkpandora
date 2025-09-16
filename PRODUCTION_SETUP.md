# 🚀 Guia de Configuração para Produção - Pandora

## ⚠️ ATENÇÃO: Configurações Críticas para Produção

### 1. 🔐 Credenciais do PayPal (OBRIGATÓRIO)

**Você PRECISA obter credenciais de PRODUÇÃO do PayPal:**

1. Acesse: https://developer.paypal.com/
2. Faça login com sua conta PayPal Business
3. Vá em "My Apps & Credentials"
4. Crie uma nova aplicação ou use uma existente
5. Copie as credenciais de **PRODUÇÃO** (não sandbox!)

**Configurações necessárias no .env:**
```env
PAYPAL_CLIENT_ID=seu_client_id_de_producao
PAYPAL_CLIENT_SECRET=seu_client_secret_de_producao
PAYPAL_ENVIRONMENT=production
PAYPAL_WEBHOOK_SECRET=seu_webhook_secret_de_producao
```

### 2. 💳 Credenciais do Stripe (JÁ CONFIGURADAS)

✅ **Stripe já está configurado para produção:**
- STRIPE_KEY: pk_live_... (chave de produção)
- STRIPE_SECRET: sk_live_... (chave secreta de produção)
- STRIPE_WEBHOOK_SECRET: whsec_... (webhook de produção)

### 3. 🌐 Configurações de Ambiente

**Alterações necessárias no .env:**

```env
# Aplicação
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com

# Banco de dados (produção)
DB_CONNECTION=mysql
DB_HOST=seu_host_de_producao
DB_PORT=3306
DB_DATABASE=nome_do_banco_producao
DB_USERNAME=usuario_producao
DB_PASSWORD=senha_forte_producao

# Cache e Sessões
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Email (configurar SMTP real)
MAIL_MAILER=smtp
MAIL_HOST=seu_smtp_host
MAIL_PORT=587
MAIL_USERNAME=seu_email
MAIL_PASSWORD=sua_senha_email
MAIL_FROM_ADDRESS=noreply@seudominio.com
MAIL_FROM_NAME="Pandora Store"

# Logs
LOG_CHANNEL=stack
LOG_LEVEL=error
```

### 4. 🔒 Segurança

**Configurações de segurança obrigatórias:**

```env
# Gerar nova chave de aplicação
APP_KEY=base64:SUA_NOVA_CHAVE_DE_PRODUCAO

# Desabilitar debug
APP_DEBUG=false

# Configurar domínio correto
SESSION_DOMAIN=seudominio.com

# Configurar HTTPS
FORCE_HTTPS=true
```

### 5. 📁 Estrutura de Arquivos

**Arquivos que NÃO devem ir para produção:**
- `.env` (usar `.env.production`)
- `storage/logs/*.log`
- `node_modules/`
- `vendor/` (será reinstalado no servidor)

### 6. 🗄️ Banco de Dados

**Comandos para produção:**
```bash
# Executar migrações
php artisan migrate --force

# Limpar cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Otimizar autoloader
composer install --optimize-autoloader --no-dev
```

### 7. 🌐 Servidor Web

**Configurações recomendadas:**
- PHP 8.1+
- MySQL 8.0+
- Redis (para cache e sessões)
- SSL/HTTPS obrigatório
- Firewall configurado

### 8. 📊 Monitoramento

**Configurar:**
- Logs de erro
- Monitoramento de performance
- Backup automático do banco
- Alertas de falhas

### 9. 🚀 Deploy

**Processo de deploy:**
1. Fazer backup do banco atual
2. Fazer upload dos arquivos
3. Executar migrações
4. Limpar caches
5. Testar funcionalidades
6. Configurar webhooks

### 10. ✅ Checklist Final

- [ ] Credenciais PayPal de produção
- [ ] Banco de dados de produção
- [ ] SSL/HTTPS configurado
- [ ] Email SMTP configurado
- [ ] Cache Redis configurado
- [ ] Logs configurados
- [ ] Backup configurado
- [ ] Testes realizados

## 🆘 Suporte

Em caso de problemas:
1. Verificar logs: `storage/logs/laravel.log`
2. Verificar configurações: `php artisan config:show`
3. Testar conectividade com APIs
4. Verificar permissões de arquivos

---
**⚠️ IMPORTANTE: Nunca use credenciais de sandbox em produção!**
