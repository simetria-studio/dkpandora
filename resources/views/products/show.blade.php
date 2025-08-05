@extends('layouts.app')

@section('title', $product->name . ' - DK Pandora')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('products.index') }}" class="text-decoration-none">
                    <i class="fas fa-home me-1"></i>Loja
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('products.index', ['type' => $product->type]) }}" class="text-decoration-none">
                    {{ $product->type == 'item' ? 'Itens' : 'Gold' }}
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Image -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="position-relative">
                    <img src="{{ $product->image ?: 'https://via.placeholder.com/600x400/6a0dad/ffffff?text=' . urlencode($product->name ?? 'Produto') }}"
                         class="card-img-top" alt="{{ $product->name }}" style="height: 400px; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge badge-{{ $product->rarity }} fs-6">
                            @switch($product->rarity)
                                @case('common') Comum @break
                                @case('rare') Raro @break
                                @case('epic') Épico @break
                                @case('legendary') Lendário @break
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title mb-3">{{ $product->name }}</h1>

                    <div class="mb-4">
                        <span class="price fs-1">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                    </div>

                    <div class="mb-4">
                        <p class="card-text">{{ $product->description }}</p>
                    </div>

                    <!-- Product Info -->
                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-tag me-2 text-muted"></i>
                                <span class="text-muted">Categoria:</span>
                            </div>
                            <strong>{{ ucfirst($product->category) }}</strong>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-cube me-2 text-muted"></i>
                                <span class="text-muted">Tipo:</span>
                            </div>
                            <strong>{{ $product->type == 'item' ? 'Item' : 'Gold' }}</strong>
                        </div>
                        @if($product->type == 'item')
                            <div class="col-6 mt-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-level-up-alt me-2 text-muted"></i>
                                    <span class="text-muted">Nível Requerido:</span>
                                </div>
                                <strong>{{ $product->level_required }}</strong>
                            </div>
                        @endif
                        <div class="col-6 mt-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-boxes me-2 text-muted"></i>
                                <span class="text-muted">Estoque:</span>
                            </div>
                            <strong>{{ $product->stock > 0 ? $product->stock : 'Indisponível' }}</strong>
                        </div>
                    </div>

                    <!-- Add to Cart Form -->
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <label for="quantity" class="form-label">Quantidade</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity"
                                           value="1" min="1" max="{{ $product->stock }}">
                                </div>
                                <div class="col-md-8">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-cart-plus me-2"></i>Adicionar ao Carrinho
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Este produto está temporariamente indisponível.
                        </div>
                    @endif

                    <!-- Additional Info -->
                    <div class="border-top pt-4">
                        <h6 class="mb-3">
                            <i class="fas fa-info-circle me-2"></i>Informações Importantes
                        </h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Entrega automática após confirmação do pagamento
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Suporte 24/7 via chat e WhatsApp
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Garantia de entrega ou reembolso
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Compatível com todos os servidores
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="mt-5">
        <h3 class="mb-4">
            <i class="fas fa-star me-2"></i>Produtos Relacionados
        </h3>
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
                    <div class="card h-100">
                        <div class="position-relative">
                            <img src="{{ $relatedProduct->image ?: 'https://via.placeholder.com/300x200/6a0dad/ffffff?text=' . urlencode($relatedProduct->name) }}"
                                 class="card-img-top" alt="{{ $relatedProduct->name }}">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge badge-{{ $relatedProduct->rarity }}">
                                    @switch($relatedProduct->rarity)
                                        @case('common') Comum @break
                                        @case('rare') Raro @break
                                        @case('epic') Épico @break
                                        @case('legendary') Lendário @break
                                    @endswitch
                                </span>
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                            <p class="card-text text-muted flex-grow-1">
                                {{ Str::limit($relatedProduct->description, 80) }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="price">R$ {{ number_format($relatedProduct->price, 2, ',', '.') }}</span>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-secondary flex-grow-1">
                                    <i class="fas fa-eye me-1"></i>Ver
                                </a>
                                <form action="{{ route('cart.add', $relatedProduct) }}" method="POST" class="flex-grow-1">
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
    </div>
</div>
@endsection
