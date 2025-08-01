@extends('layouts.app')

@section('title', 'Loja - DK Pandora')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    <i class="fas fa-dragon me-3"></i>DK Pandora
                </h1>
                <p class="lead mb-4">
                    Sua loja confiável para itens e gold do Grand Fantasia Violet.
                    Entrega rápida e segura para todos os servidores.
                </p>
                <div class="d-flex gap-3">
                    <a href="{{ route('products.index', ['type' => 'item']) }}" class="btn btn-light btn-lg">
                        <i class="fas fa-sword me-2"></i>Ver Itens
                    </a>
                    <a href="{{ route('products.index', ['type' => 'gold']) }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-coins me-2"></i>Comprar Gold
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="position-relative">
                    <div class="bg-dark bg-opacity-50 rounded-circle d-inline-block p-5">
                        <img src="{{ asset('img/violet_logo.png') }}" alt="Grand Fantasia Violet" style="max-width: 200px; height: auto;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gold Purchase Section -->
<section class="gold-purchase-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body text-center">
                        <h2 class="card-title mb-4">
                            <i class="fas fa-coins me-3" style="color: #FFD700;"></i>
                            Comprar Gold Personalizado
                        </h2>
                        <p class="card-text text-muted mb-4">
                            Escolha a quantidade de gold que deseja comprar. Preço: <strong>R$ {{ App\Models\Setting::get('gold_price_per_1000', '0.12') }} por 1000 gold</strong>
                        </p>

                        <div class="alert alert-info mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-database me-2"></i>
                                    <strong>Gold Disponível:</strong> {{ number_format(App\Models\Setting::get('available_gold', 0), 0, ',', '.') }} gold
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        Entrega em até {{ App\Models\Setting::get('delivery_time', 30) }} minutos
                                    </small>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('cart.add', ['product' => 'custom-gold']) }}" method="POST" id="goldPurchaseForm">
                            @csrf
                            <input type="hidden" name="custom_gold" value="1">

                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="goldAmount" class="form-label fw-bold">Quantidade de Gold</label>
                                        <div class="input-group input-group-lg">
                                                                                        <input type="number"
                                                   class="form-control"
                                                   id="goldAmount"
                                                   name="gold_amount"
                                                   min="{{ App\Models\Setting::get('gold_min_purchase', 1000) }}"
                                                   max="{{ App\Models\Setting::get('gold_max_purchase', 1000000) }}"
                                                   step="1000"
                                                   value="{{ App\Models\Setting::get('gold_min_purchase', 1000) }}"
                                                   required>
                                            <span class="input-group-text">gold</span>
                                        </div>
                                        <div class="form-text">
                                            Mínimo: {{ number_format(App\Models\Setting::get('gold_min_purchase', 1000), 0, ',', '.') }} gold |
                                            Máximo: {{ number_format(App\Models\Setting::get('gold_max_purchase', 1000000), 0, ',', '.') }} gold
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="price-display mb-4">
                                        <div class="card bg-dark">
                                            <div class="card-body">
                                                <h4 class="mb-2">
                                                    <i class="fas fa-calculator me-2"></i>
                                                    Preço Total
                                                </h4>
                                                <div class="display-6 fw-bold text-primary" id="totalPrice">
                                                    R$ 1,20
                                                </div>
                                                <small class="text-muted">
                                                    <span id="goldPerUnit">10.000 gold</span> × R$ 0,12 por 1000 gold
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center gap-3">
                                <button type="button" class="btn btn-outline-secondary btn-lg" onclick="setGoldAmount(5000)">
                                    <i class="fas fa-coins me-2"></i>5.000 Gold
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-lg" onclick="setGoldAmount(10000)">
                                    <i class="fas fa-coins me-2"></i>10.000 Gold
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-lg" onclick="setGoldAmount(50000)">
                                    <i class="fas fa-coins me-2"></i>50.000 Gold
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-lg" onclick="setGoldAmount(100000)">
                                    <i class="fas fa-coins me-2"></i>100.000 Gold
                                </button>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-cart-plus me-2"></i>
                                    Adicionar ao Carrinho
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Produtos em Destaque -->
@if($featuredProducts->count() > 0)
<section class="featured-products py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold text-white mb-3">
                    <i class="fas fa-star me-3" style="color: #FFD700;"></i>
                    Produtos em Destaque
                </h2>
                <p class="lead text-muted">
                    Os melhores itens selecionados especialmente para você
                </p>
            </div>
        </div>

        <div class="row">
            @foreach($featuredProducts as $product)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 product-card">
                    <div class="position-relative">
                        @if($product->hasImage())
                            <img src="{{ $product->image_url }}"
                                 class="card-img-top"
                                 alt="{{ $product->name }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center"
                                 style="height: 200px;">
                                <i class="fas fa-image fa-3x text-white"></i>
                            </div>
                        @endif

                        <!-- Badge de Destaque -->
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star me-1"></i>Destaque
                            </span>
                        </div>

                        <!-- Badge de Raridade -->
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-{{ $product->rarity_color }}">
                                {{ $product->rarity_label }}
                            </span>
                        </div>

                        <!-- Badge de Categoria -->
                        <div class="position-absolute bottom-0 start-0 m-2">
                            <span class="badge bg-info">
                                {{ $product->category_label }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-white">{{ $product->name }}</h5>
                        <p class="card-text text-muted flex-grow-1">
                            {{ Str::limit($product->description, 100) }}
                        </p>

                        @if($product->level_requirement)
                            <div class="mb-2">
                                <small class="text-warning">
                                    <i class="fas fa-star me-1"></i>Nível {{ $product->level_requirement }}+
                                </small>
                            </div>
                        @endif

                        @if($product->features && count($product->features) > 0)
                            <div class="mb-3">
                                @foreach(array_slice($product->features, 0, 2) as $feature)
                                    <small class="d-block text-success">
                                        <i class="fas fa-check me-1"></i>{{ trim($feature) }}
                                    </small>
                                @endforeach
                                @if(count($product->features) > 2)
                                    <small class="text-muted">
                                        <i class="fas fa-plus me-1"></i>+{{ count($product->features) - 2 }} mais
                                    </small>
                                @endif
                            </div>
                        @endif

                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="price-display">
                                    <span class="h4 fw-bold text-success mb-0">
                                        {{ $product->formatted_price }}
                                    </span>
                                </div>
                                <div class="stock-info">
                                    @if($product->stock > 0)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>{{ $product->stock }} disponível
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times me-1"></i>Sem estoque
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="d-grid">
                                @if($product->stock > 0)
                                    <form action="{{ route('cart.add', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-cart-plus me-2"></i>Adicionar ao Carrinho
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-secondary btn-lg" disabled>
                                        <i class="fas fa-times me-2"></i>Indisponível
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="#all-products" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-th-large me-2"></i>Ver Todos os Produtos
                </a>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Filters Section -->
<section id="all-products" class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-filter me-2"></i>Filtros
                        </h5>

                        <form method="GET" action="{{ route('products.index') }}">
                            <!-- Tipo -->
                            <div class="mb-3">
                                <label class="form-label">Tipo</label>
                                <select name="type" class="form-select">
                                    <option value="">Todos</option>
                                    <option value="item" {{ request('type') == 'item' ? 'selected' : '' }}>Itens</option>
                                    <option value="gold" {{ request('type') == 'gold' ? 'selected' : '' }}>Gold</option>
                                </select>
                            </div>

                            <!-- Categoria -->
                            <div class="mb-3">
                                <label class="form-label">Categoria</label>
                                <select name="category" class="form-select">
                                    <option value="">Todas</option>
                                    <option value="weapon" {{ request('category') == 'weapon' ? 'selected' : '' }}>Armas</option>
                                    <option value="armor" {{ request('category') == 'armor' ? 'selected' : '' }}>Armaduras</option>
                                    <option value="accessory" {{ request('category') == 'accessory' ? 'selected' : '' }}>Acessórios</option>
                                    <option value="consumable" {{ request('category') == 'consumable' ? 'selected' : '' }}>Consumíveis</option>
                                    <option value="currency" {{ request('category') == 'currency' ? 'selected' : '' }}>Moedas</option>
                                </select>
                            </div>

                            <!-- Raridade -->
                            <div class="mb-3">
                                <label class="form-label">Raridade</label>
                                <select name="rarity" class="form-select">
                                    <option value="">Todas</option>
                                    <option value="common" {{ request('rarity') == 'common' ? 'selected' : '' }}>Comum</option>
                                    <option value="rare" {{ request('rarity') == 'rare' ? 'selected' : '' }}>Raro</option>
                                    <option value="epic" {{ request('rarity') == 'epic' ? 'selected' : '' }}>Épico</option>
                                    <option value="legendary" {{ request('rarity') == 'legendary' ? 'selected' : '' }}>Lendário</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i>Filtrar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">
                        @if(request('type') == 'item')
                            <i class="fas fa-sword me-2"></i>Itens
                        @elseif(request('type') == 'gold')
                            <i class="fas fa-coins me-2"></i>Gold
                        @else
                            <i class="fas fa-store me-2"></i>Produtos
                        @endif
                    </h2>
                    <div class="text-muted">
                        {{ $products->total() }} produto(s) encontrado(s)
                    </div>
                </div>

                <!-- Products Grid -->
                @if($products->count() > 0)
                    <div class="row">
                        @foreach($products as $product)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100">
                                    <div class="position-relative">
                                        @if($product->hasImage())
                                            <img src="{{ $product->image_url }}"
                                                 class="card-img-top"
                                                 alt="{{ $product->name }}"
                                                 style="height: 200px; object-fit: cover;">
                                        @else
                                            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center"
                                                 style="height: 200px;">
                                                <i class="fas fa-image fa-3x text-white"></i>
                                            </div>
                                        @endif
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge badge-{{ $product->rarity }}">
                                                @switch($product->rarity)
                                                    @case('common') Comum @break
                                                    @case('rare') Raro @break
                                                    @case('epic') Épico @break
                                                    @case('legendary') Lendário @break
                                                @endswitch
                                            </span>
                                        </div>
                                    </div>

                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">{{ $product->name }}</h5>
                                        <p class="card-text text-muted flex-grow-1">
                                            {{ Str::limit($product->description, 100) }}
                                        </p>

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="price">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                                            @if($product->type == 'item')
                                                <small class="text-muted">
                                                    <i class="fas fa-level-up-alt me-1"></i>Nv. {{ $product->level_required }}
                                                </small>
                                            @endif
                                        </div>

                                        <div class="d-flex gap-2">
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-secondary flex-grow-1">
                                                <i class="fas fa-eye me-1"></i>Ver
                                            </a>
                                            <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1">
                                                @csrf
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="fas fa-cart-plus me-1"></i>Adicionar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Mostrando {{ $products->firstItem() }} a {{ $products->lastItem() }}
                                de {{ $products->total() }} resultados
                            </div>
                            <div class="pagination-wrapper">
                                {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-search" style="font-size: 4rem; color: var(--text-muted);"></i>
                        <h4 class="mt-3">Nenhum produto encontrado</h4>
                        <p class="text-muted">Tente ajustar os filtros ou voltar mais tarde.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>Ver Todos os Produtos
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const goldAmountInput = document.getElementById('goldAmount');
    const totalPriceElement = document.getElementById('totalPrice');
    const goldPerUnitElement = document.getElementById('goldPerUnit');

        // Função para calcular o preço
    function calculatePrice() {
        const goldAmount = parseInt(goldAmountInput.value) || 0;
        const pricePer1000Gold = {{ App\Models\Setting::get('gold_price_per_1000', '0.12') }};
        const totalPrice = (goldAmount / 1000) * pricePer1000Gold;

        totalPriceElement.textContent = `R$ ${totalPrice.toFixed(2).replace('.', ',')}`;
        goldPerUnitElement.textContent = `${goldAmount.toLocaleString('pt-BR')} gold`;
    }

    // Calcular preço quando o input mudar
    goldAmountInput.addEventListener('input', calculatePrice);

    // Calcular preço inicial
    calculatePrice();
});

// Função para definir quantidade de gold pelos botões
function setGoldAmount(amount) {
    document.getElementById('goldAmount').value = amount;
    document.getElementById('goldAmount').dispatchEvent(new Event('input'));
}
</script>
@endpush
