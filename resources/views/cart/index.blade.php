@extends('layouts.app')

@section('title', 'Carrinho - DK Pandora')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            <i class="fas fa-shopping-cart me-2"></i>Seu Carrinho
                        </h3>
                        <span class="badge bg-light text-dark fs-6">
                            {{ count($cart) }} item(s)
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if(count($cart) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="border-0">Produto</th>
                                        <th class="border-0 text-center">Preço Unit.</th>
                                        <th class="border-0 text-center">Quantidade</th>
                                        <th class="border-0 text-center">Total</th>
                                        <th class="border-0 text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Produtos normais -->
                                    @foreach($products as $product)
                                        @php
                                            $quantity = $cart[$product->id];
                                            $subtotal = $product->price * $quantity;
                                        @endphp
                                        <tr class="border-bottom">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $product->image ?: 'https://via.placeholder.com/60x60/6a0dad/ffffff?text=' . urlencode($product->name) }}"
                                                         class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;" alt="{{ $product->name }}">
                                                    <div>
                                                        <h4 class="mb-1 fw-bold name-product">{{ $product->name }}</h4>
                                                        <p class="text-muted mb-1 small">{{ Str::limit($product->description, 50) }}</p>
                                                        <span class="badge badge-{{ $product->rarity }} badge-sm">
                                                            @switch($product->rarity)
                                                                @case('common') Comum @break
                                                                @case('rare') Raro @break
                                                                @case('epic') Épico @break
                                                                @case('legendary') Lendário @break
                                                            @endswitch
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="fw-bold text-primary">R$ {{ number_format($product->price, 2, ',', '.') }}</div>
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('cart.update', $product) }}" method="POST" class="d-flex align-items-center justify-content-center">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" name="quantity" value="{{ $quantity }}"
                                                           min="1" max="{{ $product->stock }}"
                                                           class="form-control form-control-sm" style="width: 80px;">
                                                    <button type="submit" class="btn btn-sm btn-outline-primary ms-2">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <div class="fw-bold text-success fs-6">R$ {{ number_format($subtotal, 2, ',', '.') }}</div>
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('cart.remove', $product) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Remover item">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach

                                    <!-- Gold personalizado -->
                                    @if(isset($customGoldItems) && count($customGoldItems) > 0)
                                        @foreach($customGoldItems as $key => $item)
                                            @php
                                                $quantity = $item['quantity'];
                                                $subtotal = $item['price'] * $quantity;
                                            @endphp
                                            <tr class="border-bottom">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $item['image'] }}"
                                                             class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;" alt="{{ $item['name'] }}">
                                                        <div>
                                                            <h4 class="mb-1 fw-bold name-product">{{ $item['name'] }}</h4>
                                                            <p class="text-muted mb-1 small">{{ $item['description'] }}</p>
                                                            <span class="badge badge-warning badge-sm name-product">
                                                                <i class="fas fa-coins me-1"></i>Gold Personalizado
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="fw-bold text-primary">R$ {{ number_format($item['price'], 2, ',', '.') }}</div>
                                                </td>
                                                <td class="text-center">
                                                    <form action="{{ route('cart.update', $key) }}" method="POST" class="d-flex align-items-center justify-content-center">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="number" name="quantity" value="{{ $quantity }}"
                                                               min="1" class="form-control form-control-sm" style="width: 80px;">
                                                        <button type="submit" class="btn btn-sm btn-outline-primary ms-2">
                                                            <i class="fas fa-sync-alt"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                                <td class="text-center">
                                                    <div class="fw-bold text-success fs-6">R$ {{ number_format($subtotal, 2, ',', '.') }}</div>
                                                </td>
                                                <td class="text-center">
                                                    <form action="{{ route('cart.remove', $key) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Remover item">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart" style="font-size: 4rem; color: var(--text-muted);"></i>
                            <h4 class="mt-3">Seu carrinho está vazio</h4>
                            <p class="text-muted">Adicione alguns produtos para começar suas compras.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">
                                <i class="fas fa-store me-2"></i>Continuar Comprando
                            </a>
                        </div>
                    @endif
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
                    @if(count($cart) > 0)
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
                            <span class="fw-bold">R$ {{ number_format($total, 2, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Taxa de entrega:</span>
                            <span class="text-success">Grátis</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong class="fs-4 text-success">R$ {{ number_format($total, 2, ',', '.') }}</strong>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('orders.checkout') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-credit-card me-2"></i>Finalizar Compra
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Continuar Comprando
                            </a>
                        </div>

                        <div class="mt-4">
                            <h6 class="mb-3">
                                <i class="fas fa-shield-alt me-2"></i>Garantias
                            </h6>
                            <ul class="list-unstyled small">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Entrega garantida
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Reembolso em 24h
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Suporte 24/7
                                </li>
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <p class="text-muted">Adicione produtos ao carrinho para ver o resumo.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
