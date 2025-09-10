@extends('layouts.app')

@section('title', 'Checkout - DK Pandora')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>Informações do Pedido
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf

                        <!-- Game Information -->
                        <div class="mb-4">
                            <label for="game_username" class="form-label">
                                <i class="fas fa-user me-2"></i>Nome do Personagem
                            </label>
                            <input type="text" class="form-control @error('game_username') is-invalid @enderror"
                                   id="game_username" name="game_username"
                                   value="{{ old('game_username') }}" required>
                            @error('game_username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Servidor: <strong>Grand Fantasia Violet - Principal</strong>
                            </div>
                        </div>

                        <!-- Payment Method Selection -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-money-bill-wave me-2"></i>Método de Pagamento
                            </label>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card payment-method-card">
                                        <div class="card-body text-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_method"
                                                       id="stripe" value="stripe" checked>
                                                <label class="form-check-label" for="stripe">
                                                    <i class="fab fa-cc-stripe fa-2x text-primary mb-2"></i>
                                                    <div class="fw-bold">Cartão de Crédito</div>
                                                    <small class="text-muted">Via Stripe</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card payment-method-card">
                                        <div class="card-body text-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_method"
                                                       id="paypal" value="paypal">
                                                <label class="form-check-label" for="paypal">
                                                    <i class="fab fa-paypal fa-2x text-info mb-2"></i>
                                                    <div class="fw-bold">PayPal</div>
                                                    <small class="text-muted">Pagamento seguro</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card payment-method-card">
                                        <div class="card-body text-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_method"
                                                       id="pix" value="pix">
                                                <label class="form-check-label" for="pix">
                                                    <i class="fas fa-qrcode fa-2x text-success mb-2"></i>
                                                    <div class="fw-bold">PIX</div>
                                                    <small class="text-muted">Pagamento instantâneo</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="mb-4">
                            <h6 class="mb-3">
                                <i class="fas fa-list me-2"></i>Itens do Pedido
                            </h6>
                            <!-- Produtos normais -->
                            @foreach($products as $product)
                                @php
                                    $quantity = $cart[$product->id];
                                    $subtotal = $product->price * $quantity;
                                @endphp
                                <div class="row align-items-center mb-3 p-3 bg-dark bg-opacity-25 rounded">
                                    <div class="col-md-2">
                                        <img src="{{ $product->image ?: 'https://via.placeholder.com/60x60/6a0dad/ffffff?text=' . urlencode($product->name) }}"
                                             class="img-fluid rounded" alt="{{ $product->name }}">
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-1">{{ $product->name }}</h6>
                                        <span class="badge badge-{{ $product->rarity }}">
                                            @switch($product->rarity)
                                                @case('common') Comum @break
                                                @case('rare') Raro @break
                                                @case('epic') Épico @break
                                                @case('legendary') Lendário @break
                                            @endswitch
                                        </span>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <span class="text-muted">Qtd: {{ $quantity }}</span>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <span class="price">R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Gold personalizado -->
                            @if(isset($customGoldItems) && count($customGoldItems) > 0)
                                @foreach($customGoldItems as $item)
                                    @php
                                        $quantity = $item['quantity'];
                                        $subtotal = $item['price'] * $quantity;
                                    @endphp
                                    <div class="row align-items-center mb-3 p-3 bg-dark bg-opacity-25 rounded">
                                        <div class="col-md-2">
                                            <img src="{{ $item['image'] }}"
                                                 class="img-fluid rounded" alt="{{ $item['name'] }}">
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="mb-1">{{ $item['name'] }}</h6>
                                            <span class="badge badge-warning">
                                                <i class="fas fa-coins me-1"></i>Gold Personalizado
                                            </span>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <span class="text-muted">Qtd: {{ $quantity }}</span>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <span class="price">R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-lock me-2"></i>Finalizar Pedido
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator me-2"></i>Resumo do Pedido
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $total = 0;
                        // Calcular total dos produtos normais
                        foreach($products as $product) {
                            $total += $product->price * $cart[$product->id];
                        }
                        // Calcular total dos itens de gold personalizado
                        if(isset($customGoldItems)) {
                            foreach($customGoldItems as $item) {
                                $total += $item['price'] * $item['quantity'];
                            }
                        }
                    @endphp

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Taxa de entrega:</span>
                        <span class="text-success">Grátis</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong class="price">R$ {{ number_format($total, 2, ',', '.') }}</strong>
                    </div>

                    <div class="alert alert-info">
                        <h6 class="mb-2">
                            <i class="fas fa-info-circle me-2"></i>Informações Importantes
                        </h6>
                        <ul class="list-unstyled small mb-0">
                            <li class="mb-1">
                                <i class="fas fa-clock me-1"></i>
                                Entrega em até 30 minutos
                            </li>
                            <li class="mb-1">
                                <i class="fas fa-shield-alt me-1"></i>
                                Pagamento 100% seguro
                            </li>
                            <li class="mb-1">
                                <i class="fas fa-headset me-1"></i>
                                Suporte 24/7 disponível
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-headset me-2"></i>Precisa de Ajuda?
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">
                        Nossa equipe está disponível 24/7 para ajudar você.
                    </p>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp
                        </a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-comments me-2"></i>Chat Online
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.payment-method-card {
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
    cursor: pointer;
}

.payment-method-card:hover {
    border-color: #6a0dad;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.payment-method-card .form-check-input:checked + .form-check-label {
    color: #6a0dad;
}

.payment-method-card .form-check-input:checked ~ .card-body {
    background-color: #f8f9fa;
}

.payment-method-card .form-check-input {
    display: none;
}

.payment-method-card .form-check-label {
    cursor: pointer;
    width: 100%;
    margin: 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentCards = document.querySelectorAll('.payment-method-card');
    const radioInputs = document.querySelectorAll('input[name="payment_method"]');

    paymentCards.forEach(card => {
        card.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                
                // Remove active class from all cards
                paymentCards.forEach(c => c.classList.remove('active'));
                
                // Add active class to selected card
                this.classList.add('active');
            }
        });
    });

    // Handle radio button changes
    radioInputs.forEach(radio => {
        radio.addEventListener('change', function() {
            paymentCards.forEach(c => c.classList.remove('active'));
            if (this.checked) {
                const card = this.closest('.payment-method-card');
                if (card) {
                    card.classList.add('active');
                }
            }
        });
    });

    // Set initial active state
    const checkedRadio = document.querySelector('input[name="payment_method"]:checked');
    if (checkedRadio) {
        const card = checkedRadio.closest('.payment-method-card');
        if (card) {
            card.classList.add('active');
        }
    }
});
</script>
@endsection
