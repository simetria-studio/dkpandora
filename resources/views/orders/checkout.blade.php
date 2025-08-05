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

                        <!-- Payment Information -->
                        <div class="mb-4">
                            <div class="alert alert-info">
                                <h6 class="mb-2">
                                    <i class="fas fa-credit-card me-2"></i>Informações de Pagamento
                                </h6>
                                <p class="mb-0 small">
                                    Após finalizar o pedido, você será redirecionado para a página de pagamento seguro
                                    onde poderá inserir os dados do seu cartão de crédito.
                                </p>
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
@endsection
