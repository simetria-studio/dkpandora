@extends('layouts.app')

@section('title', 'DK Pandora - Sua Loja de Jogos')

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

<!-- Gold Purchase Section (copiado da página de produtos) -->
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

<!-- Featured Products Section -->
<div class="featured-products py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-5">
                    <i class="fas fa-star me-2"></i>Produtos em Destaque
                </h2>
            </div>
        </div>
        <div class="row">
            @forelse($featuredProducts as $product)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card product-card h-100">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="card-img-top">
                        @else
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-secondary">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="price">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                                    <span class="badge bg-{{ $product->rarity_color ?? 'secondary' }}">
                                        {{ $product->rarity_label ?? 'Cinza' }}
                                    </span>
                                </div>
                                <a href="{{ route('products.show', $product) }}" class="btn btn-primary w-100">
                                    <i class="fas fa-eye me-2"></i>Ver Detalhes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="card">
                        <div class="card-body">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Nenhum produto em destaque disponível no momento.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Popular Products Section -->
<div class="py-5" style="background: linear-gradient(135deg, rgba(15, 52, 96, 0.1) 0%, rgba(26, 26, 46, 0.1) 100%);">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-5">
                    <i class="fas fa-fire me-2"></i>Mais Populares
                </h2>
            </div>
        </div>
        <div class="row">
            @forelse($popularProducts as $product)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card product-card h-100">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-secondary" style="height: 200px;">
                                <i class="fas fa-image fa-2x text-muted"></i>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title">{{ $product->name }}</h6>
                            <p class="card-text small">{{ Str::limit($product->description, 80) }}</p>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="price">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                                </div>
                                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="fas fa-eye me-1"></i>Ver
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4">
                    <p class="text-muted">Nenhum produto popular disponível no momento.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-primary bg-opacity-10 w-16 h-16 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                        <i class="fas fa-dollar-sign fa-2x text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Preços Competitivos</h5>
                    <p class="text-muted">Os melhores preços do mercado para seus itens favoritos</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-success bg-opacity-10 w-16 h-16 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                        <i class="fas fa-shield-alt fa-2x text-success"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Garantia de Qualidade</h5>
                    <p class="text-muted">Produtos originais com garantia total de funcionamento</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="text-center">
                    <div class="bg-warning bg-opacity-10 w-16 h-16 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                        <i class="fas fa-bolt fa-2x text-warning"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Entrega Rápida</h5>
                    <p class="text-muted">Receba seus itens rapidamente após a compra</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="py-5" style="background: var(--gradient-primary);">
    <div class="container text-center">
        <h2 class="display-6 fw-bold mb-3">Pronto para começar?</h2>
        <p class="lead mb-4">Explore nossa coleção de itens e gold do Grand Fantasia Violet</p>
        <a href="{{ route('products.index') }}" class="btn btn-light btn-lg me-3">
            <i class="fas fa-store me-2"></i>Começar a Comprar
        </a>
        <a href="{{ route('products.index', ['type' => 'gold']) }}" class="btn btn-outline-light btn-lg">
            <i class="fas fa-coins me-2"></i>Comprar Gold
        </a>
    </div>
</div>

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
@endsection
