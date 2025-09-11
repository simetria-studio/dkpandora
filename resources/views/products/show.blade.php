@extends('layouts.app')

@section('title', $product->name . ' - DK Pandora')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb melhorado -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-dark rounded-pill px-3 py-2">
            <li class="breadcrumb-item">
                <a href="{{ route('products.index') }}" class="text-decoration-none text-light">
                    <i class="fas fa-home me-1"></i>Loja
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('products.index', ['type' => $product->type]) }}" class="text-decoration-none text-light">
                    {{ $product->type == 'item' ? 'Itens' : 'Gold' }}
                </a>
            </li>
            <li class="breadcrumb-item active text-light" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Image -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-lg">
                <div class="position-relative overflow-hidden rounded-top">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                             class="card-img-top" alt="{{ $product->name }}"
                             style="height: 500px; object-fit: cover; transition: transform 0.3s ease;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                             onmouseover="this.style.transform='scale(1.05)'"
                             onmouseout="this.style.transform='scale(1)'">
                        <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center"
                             style="height: 500px; display: none; background: linear-gradient(135deg, #6a0dad, #4a0e4a);">
                            <div class="text-center">
                                <i class="fas fa-image fa-4x text-white mb-3"></i>
                                <p class="text-white mb-0">Imagem não disponível</p>
                            </div>
                        </div>
                    @else
                        <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center"
                             style="height: 500px; background: linear-gradient(135deg, #6a0dad, #4a0e4a);">
                            <div class="text-center">
                                <i class="fas fa-image fa-4x text-white mb-3"></i>
                                <p class="text-white mb-0">Imagem não disponível</p>
                            </div>
                        </div>
                    @endif
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-{{ $product->rarity_color ?? 'secondary' }} fs-6 px-3 py-2 shadow">
                            <i class="fas fa-gem me-1"></i>{{ $product->rarity_label ?? 'Cinza' }}
                        </span>
                    </div>
                    @if($product->is_featured)
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge bg-warning text-dark fs-6 px-3 py-2 shadow">
                                <i class="fas fa-star me-1"></i>Destaque
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h1 class="card-title mb-0 text-primary">{{ $product->name }}</h1>
                        <div class="text-end">
                            <div class="h2 text-success mb-0">R$ {{ number_format($product->price, 2, ',', '.') }}</div>
                            <small class="text-muted">Preço unitário</small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="card-text text-muted fs-5">{{ $product->description }}</p>
                    </div>

                    <!-- Product Info -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="bg-dark rounded p-3 h-100 border border-secondary">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-tag me-2 text-primary"></i>
                                    <span class="text-light fw-bold">Categoria</span>
                                </div>
                                <div class="h5 mb-0 text-white">{{ ucfirst($product->category) }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="bg-dark rounded p-3 h-100 border border-secondary">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-cube me-2 text-info"></i>
                                    <span class="text-light fw-bold">Tipo</span>
                                </div>
                                <div class="h5 mb-0 text-white">{{ $product->type == 'item' ? 'Item' : 'Gold' }}</div>
                            </div>
                        </div>
                        @if($product->type == 'item')
                            <div class="col-md-6 mb-3">
                                <div class="bg-dark rounded p-3 h-100 border border-secondary">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-level-up-alt me-2 text-warning"></i>
                                        <span class="text-light fw-bold">Nível Requerido</span>
                                    </div>
                                    <div class="h5 mb-0 text-white">{{ $product->level_required }}</div>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <div class="bg-dark rounded p-3 h-100 border border-secondary">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-boxes me-2 {{ $product->stock > 0 ? 'text-success' : 'text-danger' }}"></i>
                                    <span class="text-light fw-bold">Estoque</span>
                                </div>
                                <div class="h5 mb-0 {{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $product->stock > 0 ? $product->stock : 'Indisponível' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add to Cart Form -->
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="bg-dark rounded p-4 border border-secondary">
                                <h5 class="mb-3 text-white">
                                    <i class="fas fa-shopping-cart me-2 text-primary"></i>Adicionar ao Carrinho
                                </h5>
                                <div class="row align-items-end">
                                    <div class="col-md-4 mb-3">
                                        <label for="quantity" class="form-label fw-bold text-light">Quantidade</label>
                                        <input type="number" class="form-control form-control-lg bg-dark text-white border-secondary" id="quantity" name="quantity"
                                               value="1" min="1" max="{{ $product->stock }}" onchange="updateTotal()">
                                    </div>
                                    <div class="col-md-8 mb-3">
                                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3">
                                            <i class="fas fa-cart-plus me-2"></i>Adicionar ao Carrinho
                                        </button>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <small class="text-light">Total: <span id="total-price" class="fw-bold text-success">R$ {{ number_format($product->price, 2, ',', '.') }}</span></small>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-danger border-0 shadow bg-dark border border-danger">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-3 fa-2x text-danger"></i>
                                <div>
                                    <h5 class="alert-heading mb-1 text-white">Produto Indisponível</h5>
                                    <p class="mb-0 text-light">Este produto está temporariamente fora de estoque.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Additional Info -->
                    <div class="mt-4">
                        <div class="card border-0 bg-gradient text-white" style="background: linear-gradient(135deg, #6a0dad, #4a0e4a);">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-3">
                                    <i class="fas fa-shield-alt me-2"></i>Garantias e Suporte
                                </h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-bolt text-warning me-3 fa-lg"></i>
                                            <div>
                                                <h6 class="mb-1">Entrega Instantânea</h6>
                                                <small class="opacity-75">Após confirmação do pagamento</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-headset text-info me-3 fa-lg"></i>
                                            <div>
                                                <h6 class="mb-1">Suporte 24/7</h6>
                                                <small class="opacity-75">Chat e WhatsApp</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-shield-alt text-success me-3 fa-lg"></i>
                                            <div>
                                                <h6 class="mb-1">Garantia Total</h6>
                                                <small class="opacity-75">Entrega ou reembolso</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-server text-primary me-3 fa-lg"></i>
                                            <div>
                                                <h6 class="mb-1">Compatibilidade</h6>
                                                <small class="opacity-75">Todos os servidores</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">
                <i class="fas fa-star me-2 text-warning"></i>Produtos Relacionados
            </h3>
            <a href="{{ route('products.index', ['type' => $product->type]) }}" class="btn btn-outline-primary">
                Ver Todos <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row">
            @php
                $relatedProducts = App\Models\Product::where('type', $product->type)
                    ->where('id', '!=', $product->id)
                    ->where('is_active', true)
                    ->limit(3)
                    ->get();
            @endphp

            @foreach($relatedProducts as $relatedProduct)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-shadow">
                        <div class="position-relative overflow-hidden">
                            @if($relatedProduct->image)
                                <img src="{{ asset('storage/' . $relatedProduct->image) }}"
                                     class="card-img-top" alt="{{ $relatedProduct->name }}"
                                     style="height: 200px; object-fit: cover; transition: transform 0.3s ease;"
                                     onmouseover="this.style.transform='scale(1.05)'"
                                     onmouseout="this.style.transform='scale(1)'">
                            @else
                                <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center"
                                     style="height: 200px; background: linear-gradient(135deg, #6a0dad, #4a0e4a);">
                                    <i class="fas fa-image fa-2x text-white"></i>
                                </div>
                            @endif
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-{{ $relatedProduct->rarity_color ?? 'secondary' }} px-2 py-1 shadow">
                                    <i class="fas fa-gem me-1"></i>{{ $relatedProduct->rarity_label ?? 'Cinza' }}
                                </span>
                            </div>
                            @if($relatedProduct->is_featured)
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-warning text-dark px-2 py-1 shadow">
                                        <i class="fas fa-star me-1"></i>Destaque
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="card-body d-flex flex-column p-3">
                            <h5 class="card-title text-primary mb-2">{{ $relatedProduct->name }}</h5>
                            <p class="card-text text-muted flex-grow-1 mb-3">
                                {{ Str::limit($relatedProduct->description, 80) }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h5 text-success mb-0">R$ {{ number_format($relatedProduct->price, 2, ',', '.') }}</span>
                                <small class="text-muted">
                                    <i class="fas fa-boxes me-1"></i>{{ $relatedProduct->stock }} em estoque
                                </small>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-outline-primary flex-grow-1">
                                    <i class="fas fa-eye me-1"></i>Ver Detalhes
                                </a>
                                @if($relatedProduct->stock > 0)
                                    <form action="{{ route('cart.add', $relatedProduct) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-cart-plus me-1"></i>Adicionar
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-secondary w-100" disabled>
                                        <i class="fas fa-times me-1"></i>Indisponível
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .hover-shadow {
        transition: box-shadow 0.3s ease;
    }
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .card {
        transition: transform 0.2s ease;
        background: #1a1a2e !important;
        border: 1px solid #16213e !important;
    }
    .card:hover {
        transform: translateY(-2px);
    }
    .bg-gradient {
        background: linear-gradient(135deg, #6a0dad, #4a0e4a);
    }
    .form-control {
        background-color: #1a1a2e !important;
        border-color: #6c757d !important;
        color: #ffffff !important;
    }
    .form-control:focus {
        background-color: #1a1a2e !important;
        border-color: #6a0dad !important;
        color: #ffffff !important;
        box-shadow: 0 0 0 0.2rem rgba(106, 13, 173, 0.25) !important;
    }
    .form-label {
        color: #ffffff !important;
    }
    .alert {
        background-color: #1a1a2e !important;
        border-color: #dc3545 !important;
    }
    .bg-dark {
        background-color: #1a1a2e !important;
    }
    .border-secondary {
        border-color: #6c757d !important;
    }
</style>
@endpush

@push('scripts')
<script>
    function updateTotal() {
        const quantity = document.getElementById('quantity').value;
        const price = {{ $product->price }};
        const total = quantity * price;
        document.getElementById('total-price').textContent = 'R$ ' + total.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // Inicializar total ao carregar a página
    document.addEventListener('DOMContentLoaded', function() {
        updateTotal();
    });
</script>
@endpush
