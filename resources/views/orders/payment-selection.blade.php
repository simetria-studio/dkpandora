@extends('layouts.app')

@section('title', 'Selecionar Método de Pagamento - DK Pandora')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>Escolha o Método de Pagamento
                    </h3>
                    <p class="text-muted mb-0 mt-2">Pedido #{{ $order->id }} - R$ {{ number_format($order->total_amount, 2, ',', '.') }}</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Stripe/Cartão de Crédito -->
                        <div class="col-md-4 mb-3">
                            <div class="card payment-option-card h-100" data-method="stripe">
                                <div class="card-body text-center">
                                    <i class="fab fa-cc-stripe fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title">Cartão de Crédito</h5>
                                    <p class="card-text text-muted">Via Stripe - Pagamento seguro</p>
                                    <div class="mt-3">
                                        <span class="badge bg-primary">Recomendado</span>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <a href="{{ route('payments.process', $order) }}" class="btn btn-primary w-100">
                                        <i class="fas fa-credit-card me-2"></i>Pagar com Cartão
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- PayPal -->
                        <div class="col-md-4 mb-3">
                            <div class="card payment-option-card h-100" data-method="paypal">
                                <div class="card-body text-center">
                                    <i class="fab fa-paypal fa-3x text-info mb-3"></i>
                                    <h5 class="card-title">PayPal</h5>
                                    <p class="card-text text-muted">Pagamento internacional seguro</p>
                                    <div class="mt-3">
                                        <span class="badge bg-info">Internacional</span>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <a href="{{ route('paypal.process', $order) }}" class="btn btn-info w-100">
                                        <i class="fab fa-paypal me-2"></i>Pagar com PayPal
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- PIX -->
                        <div class="col-md-4 mb-3">
                            <div class="card payment-option-card h-100" data-method="pix">
                                <div class="card-body text-center">
                                    <i class="fas fa-qrcode fa-3x text-success mb-3"></i>
                                    <h5 class="card-title">PIX</h5>
                                    <p class="card-text text-muted">Pagamento instantâneo brasileiro</p>
                                    <div class="mt-3">
                                        <span class="badge bg-success">Instantâneo</span>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <a href="{{ route('payments.pix', $order) }}" class="btn btn-success w-100">
                                        <i class="fas fa-qrcode me-2"></i>Pagar com PIX
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Voltar ao Pedido
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informações do Pedido -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Detalhes do Pedido
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Personagem:</strong> {{ $order->game_username }}</p>
                            <p><strong>Servidor:</strong> {{ $order->server_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Data:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-warning">Aguardando Pagamento</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.payment-option-card {
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
    cursor: pointer;
}

.payment-option-card:hover {
    border-color: #6a0dad;
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.payment-option-card .card-footer {
    border-top: 1px solid #e9ecef;
}

.payment-option-card:hover .card-footer {
    background-color: #f8f9fa;
}

.payment-option-card .btn {
    transition: all 0.3s ease;
}

.payment-option-card:hover .btn {
    transform: scale(1.05);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentCards = document.querySelectorAll('.payment-option-card');
    
    paymentCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove active class from all cards
            paymentCards.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked card
            this.classList.add('active');
        });
    });
});
</script>
@endsection
