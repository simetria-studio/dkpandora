#!/bin/bash

# ===========================================
# SCRIPT DE DEPLOY PARA PRODUÇÃO - PANDORA
# ===========================================

echo "🚀 Iniciando deploy para produção..."

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Função para exibir mensagens coloridas
print_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Verificar se está no ambiente correto
if [ "$APP_ENV" != "production" ]; then
    print_warning "APP_ENV não está definido como 'production'"
    print_warning "Certifique-se de que o arquivo .env está configurado para produção"
fi

# 1. Backup do banco de dados
print_status "Fazendo backup do banco de dados..."
php artisan backup:run --only-db

# 2. Atualizar dependências
print_status "Atualizando dependências do Composer..."
composer install --optimize-autoloader --no-dev --no-interaction

# 3. Limpar caches
print_status "Limpando caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# 4. Executar migrações
print_status "Executando migrações do banco de dados..."
php artisan migrate --force

# 5. Criar caches de produção
print_status "Criando caches de produção..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Otimizar autoloader
print_status "Otimizando autoloader..."
composer dump-autoload --optimize

# 7. Verificar permissões
print_status "Verificando permissões..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 8. Verificar configurações
print_status "Verificando configurações..."
php artisan config:show services.paypal
php artisan config:show services.stripe

# 9. Testar conectividade
print_status "Testando conectividade com APIs..."
php artisan tinker --execute="
try {
    echo 'Testando Stripe...';
    \$stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
    echo 'Stripe: OK';
} catch (Exception \$e) {
    echo 'Stripe: ERRO - ' . \$e->getMessage();
}

try {
    echo 'Testando PayPal...';
    \$paypal = new \App\Services\PayPalDirectService();
    echo 'PayPal: OK';
} catch (Exception \$e) {
    echo 'PayPal: ERRO - ' . \$e->getMessage();
}
"

print_status "Deploy concluído com sucesso!"
print_warning "Lembre-se de:"
print_warning "1. Configurar webhooks do PayPal e Stripe"
print_warning "2. Configurar SSL/HTTPS"
print_warning "3. Configurar backup automático"
print_warning "4. Monitorar logs de erro"
