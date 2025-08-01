@extends('layouts.app')

@section('title', 'Meus Pedidos - DK Pandora')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">
                        <i class="fas fa-list me-2"></i>Meus Pedidos
                    </h3>
                </div>
                <div class="card-body">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Pedido #</th>
                                        <th>Data</th>
                                        <th>Personagem</th>
                                        <th>Servidor</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>
                                                <strong>#{{ $order->id }}</strong>
                                            </td>
                                            <td>
                                                {{ $order->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                <i class="fas fa-user me-1"></i>{{ $order->game_username }}
                                            </td>
                                            <td>
                                                <i class="fas fa-server me-1"></i>{{ $order->server_name }}
                                            </td>
                                            <td>
                                                <span class="price">R$ {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                                            </td>
                                            <td>
                                                @switch($order->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-clock me-1"></i>Pendente
                                                        </span>
                                                        @break
                                                    @case('paid')
                                                        <span class="badge bg-info">
                                                            <i class="fas fa-check me-1"></i>Pago
                                                        </span>
                                                        @break
                                                    @case('delivered')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-truck me-1"></i>Entregue
                                                        </span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times me-1"></i>Cancelado
                                                        </span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-secondary">
                                                    <i class="fas fa-eye me-1"></i>Ver
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-bag" style="font-size: 4rem; color: var(--text-muted);"></i>
                            <h4 class="mt-3">Você ainda não fez nenhum pedido</h4>
                            <p class="text-muted">Comece a comprar itens e gold para o seu personagem!</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">
                                <i class="fas fa-store me-2"></i>Ir para a Loja
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 