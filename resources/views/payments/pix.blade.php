@extends('layouts.app')

@section('title', 'Pagamento PIX - Pedido #' . $order->id)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-qrcode me-2"></i>
                        Pagamento PIX - Pedido #{{ $order->id }}
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Resumo do Pedido -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Resumo do Pedido</h5>
                            <p><strong>Usuário do Jogo:</strong> {{ $order->game_username }}</p>
                            <p><strong>Servidor:</strong> {{ $order->server_name }}</p>
                            <p><strong>Total:</strong> <span class="text-success fw-bold">{{ $order->formatted_total_amount }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <h5>Itens do Pedido</h5>
                            @foreach($order->orderItems as $item)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>{{ $item->product_name ?? ($item->product ? $item->product->name : 'Item do Pedido') }}</span>
                                    <span>R$ {{ number_format($item->price, 2, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- QR Code PIX -->
                    <div class="text-center mb-4">
                        <h5 class="mb-3">
                            <i class="fas fa-qrcode me-2"></i>Escaneie o QR Code
                        </h5>
                        
                        @if($pixData['qr_code'])
                            <div class="qr-code-container p-4 bg-light rounded">
                                <img src="{{ $pixData['qr_code'] }}" 
                                     alt="QR Code PIX" 
                                     class="img-fluid mb-3"
                                     style="max-width: 300px;">
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Use o app do seu banco para escanear o QR Code acima
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                QR Code não disponível no momento. Tente novamente.
                            </div>
                        @endif
                    </div>

                    <!-- Código PIX -->
                    @if($pixData['pix_code'])
                        <div class="mb-4">
                            <h6 class="mb-2">
                                <i class="fas fa-copy me-2"></i>Código PIX (Copie e Cole)
                            </h6>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       value="{{ $pixData['pix_code'] }}" 
                                       id="pix-code" 
                                       readonly>
                                <button class="btn btn-outline-primary" 
                                        type="button" 
                                        onclick="copyPixCode()">
                                    <i class="fas fa-copy"></i> Copiar
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Status do Pagamento -->
                    <div class="payment-status mb-4">
                        <h6 class="mb-2">
                            <i class="fas fa-clock me-2"></i>Status do Pagamento
                        </h6>
                        <div class="alert alert-warning" id="payment-status">
                            <i class="fas fa-clock me-2"></i>
                            Aguardando pagamento...
                        </div>
                    </div>

                    <!-- Informações Importantes -->
                    <div class="alert alert-info">
                        <h6 class="mb-2">
                            <i class="fas fa-info-circle me-2"></i>Informações Importantes
                        </h6>
                        <ul class="mb-0 small">
                            <li>O pagamento será processado automaticamente após a confirmação</li>
                            <li>Você receberá uma notificação quando o pagamento for confirmado</li>
                            <li>Em caso de dúvidas, entre em contato com o suporte</li>
                        </ul>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Voltar ao Pedido
                        </a>
                        <button type="button" class="btn btn-primary" onclick="checkPaymentStatus()">
                            <i class="fas fa-sync-alt me-2"></i>Verificar Status
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let checkStatusInterval;

// Copiar código PIX
function copyPixCode() {
    const pixCode = document.getElementById('pix-code');
    pixCode.select();
    pixCode.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    // Mostrar feedback
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-check"></i> Copiado!';
    button.classList.remove('btn-outline-primary');
    button.classList.add('btn-success');
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.classList.remove('btn-success');
        button.classList.add('btn-outline-primary');
    }, 2000);
}

// Verificar status do pagamento
function checkPaymentStatus() {
    const statusElement = document.getElementById('payment-status');
    const button = event.target;
    
    // Desabilitar botão
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Verificando...';
    
    fetch('{{ route("payments.pix.status", $order) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            payment_intent_id: '{{ $paymentIntent->id }}'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.paid) {
            statusElement.className = 'alert alert-success';
            statusElement.innerHTML = '<i class="fas fa-check me-2"></i>Pagamento confirmado! Redirecionando...';
            
            // Parar verificação automática
            if (checkStatusInterval) {
                clearInterval(checkStatusInterval);
            }
            
            // Redirecionar após 2 segundos
            setTimeout(() => {
                window.location.href = '{{ route("orders.show", $order) }}';
            }, 2000);
        } else {
            statusElement.className = 'alert alert-warning';
            statusElement.innerHTML = '<i class="fas fa-clock me-2"></i>Aguardando pagamento...';
        }
    })
    .catch(error => {
        statusElement.className = 'alert alert-danger';
        statusElement.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Erro ao verificar status';
        console.error('Erro:', error);
    })
    .finally(() => {
        // Reabilitar botão
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-sync-alt me-2"></i>Verificar Status';
    });
}

// Verificar status automaticamente a cada 10 segundos
document.addEventListener('DOMContentLoaded', function() {
    checkStatusInterval = setInterval(checkPaymentStatus, 10000);
});

// Parar verificação quando a página for fechada
window.addEventListener('beforeunload', function() {
    if (checkStatusInterval) {
        clearInterval(checkStatusInterval);
    }
});
</script>
@endsection 