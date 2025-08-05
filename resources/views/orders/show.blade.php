@extends('layouts.app')

@section('title', 'Pedido #' . $order->id . ' - DK Pandora')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('orders.index') }}" class="text-decoration-none">
                    <i class="fas fa-list me-1"></i>Meus Pedidos
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Pedido #{{ $order->id }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <!-- Order Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-receipt me-2"></i>Pedido #{{ $order->id }}
                        </h4>
                        @switch($order->status)
                            @case('pending')
                                <span class="badge bg-warning fs-6">
                                    <i class="fas fa-clock me-1"></i>Pendente
                                </span>
                                @break
                            @case('paid')
                                <span class="badge bg-info fs-6">
                                    <i class="fas fa-check me-1"></i>Pago
                                </span>
                                @break
                            @case('delivered')
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-truck me-1"></i>Entregue
                                </span>
                                @break
                            @case('cancelled')
                                <span class="badge bg-danger fs-6">
                                    <i class="fas fa-times me-1"></i>Cancelado
                                </span>
                                @break
                        @endswitch
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-2">
                                <i class="fas fa-user me-2"></i>Informações do Jogo
                            </h6>
                            <p class="mb-1"><strong>Personagem:</strong> {{ $order->game_username }}</p>
                            <p class="mb-1"><strong>Servidor:</strong> {{ $order->server_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-2">
                                <i class="fas fa-credit-card me-2"></i>Informações de Pagamento
                            </h6>
                            <p class="mb-1"><strong>Método:</strong>
                                @switch($order->payment_method)
                                    @case('stripe') Cartão de Crédito (Stripe) @break
                                    @case('pix') PIX @break
                                    @case('credit_card') Cartão de Crédito @break
                                    @case('bank_transfer') Transferência Bancária @break
                                    @default Cartão de Crédito
                                @endswitch
                            </p>
                            @if($order->transaction_id)
                                <p class="mb-1"><strong>Transação:</strong> {{ $order->transaction_id }}</p>
                            @endif
                            <p class="mb-1"><strong>Data:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Itens do Pedido
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($order->orderItems as $item)
                        <div class="row align-items-center mb-4 pb-4 border-bottom">
                            <div class="col-md-2">
                                @if($item->product && $item->product->image)
                                    <img src="{{ $item->product->image }}" class="img-fluid rounded" alt="{{ $item->product->name ?? 'Produto' }}">
                                @elseif($item->product && $item->product->name)
                                    <img src="https://via.placeholder.com/80x80/6a0dad/ffffff?text={{ urlencode($item->product->name) }}" class="img-fluid rounded" alt="{{ $item->product->name }}">
                                @elseif($item->product)
                                    <img src="https://via.placeholder.com/80x80/6a0dad/ffffff?text=Produto" class="img-fluid rounded" alt="Produto">
                                @else
                                    <img src="https://via.placeholder.com/80x80/FFD700/000000?text=Gold" class="img-fluid rounded" alt="{{ $item->product_name ?? 'Gold' }}">
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-1">{{ $item->product_name ?? ($item->product ? $item->product->name : 'Item do Pedido') }}</h6>
                                <p class="text-muted mb-1">
                                    @if($item->product && $item->product->description)
                                        {{ Str::limit($item->product->description, 80) }}
                                    @else
                                        {{ Str::limit($item->product_description ?? 'Gold personalizado para Grand Fantasia Violet', 80) }}
                                    @endif
                                </p>
                                @if($item->product && $item->product->rarity)
                                    <span class="badge badge-{{ $item->product->rarity }}">
                                        @switch($item->product->rarity)
                                            @case('common') Comum @break
                                            @case('rare') Raro @break
                                            @case('epic') Épico @break
                                            @case('legendary') Lendário @break
                                            @default {{ ucfirst($item->product->rarity) }}
                                        @endswitch
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-coins me-1"></i>Gold Personalizado
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-2 text-center">
                                <span class="text-muted">Qtd: {{ $item->quantity }}</span>
                            </div>
                            <div class="col-md-2 text-end">
                                <span class="price">R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator me-2"></i>Resumo do Pedido
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>R$ {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Taxa de entrega:</span>
                        <span class="text-success">Grátis</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong class="price">R$ {{ number_format($order->total_amount, 2, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Status do Pedido
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item {{ in_array($order->status, ['pending', 'paid', 'delivered']) ? 'active' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Pedido Realizado</h6>
                                <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>

                        <div class="timeline-item {{ in_array($order->status, ['paid', 'delivered']) ? 'active' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Pagamento Confirmado</h6>
                                <small class="text-muted">
                                    @if(in_array($order->status, ['paid', 'delivered']))
                                        Confirmado
                                    @else
                                        Aguardando
                                    @endif
                                </small>
                            </div>
                        </div>

                        <div class="timeline-item {{ $order->status == 'delivered' ? 'active' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Item Entregue</h6>
                                <small class="text-muted">
                                    @if($order->status == 'delivered')
                                        Entregue
                                    @else
                                        Aguardando
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Action -->
            @if($order->status === 'pending')
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-credit-card me-2"></i>Ação de Pagamento
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-3">
                            Seu pedido está aguardando pagamento. Escolha o método de pagamento:
                        </p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('payments.process', $order) }}" class="btn btn-primary">
                                <i class="fas fa-credit-card me-2"></i>Cartão de Crédito
                            </a>
                            <a href="{{ route('payments.pix', $order) }}" class="btn btn-success">
                                <i class="fas fa-qrcode me-2"></i>Pagar com PIX
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Support -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-headset me-2"></i>Precisa de Ajuda?
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">
                        Em caso de dúvidas sobre este pedido, entre em contato conosco.
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
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: rgba(106, 13, 173, 0.2);
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--text-muted);
    border: 2px solid var(--card-bg);
}

.timeline-item.active .timeline-marker {
    background: var(--primary-color);
}

.timeline-content h6 {
    margin: 0;
    font-size: 0.9rem;
}
</style>
@endsection
