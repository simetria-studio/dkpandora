@extends('layouts.app')

@section('title', 'Detalhes do Pedido #' . $order->id)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Detalhes do Pedido #{{ $order->id }}
                    </h4>
                    <div>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Status do Pedido -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Informações do Pedido
                                    </h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>ID:</strong><br>
                                            <span class="text-muted">#{{ $order->id }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Status:</strong><br>
                                            @if($order->status === 'paid')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>
                                                    Pago
                                                </span>
                                            @elseif($order->status === 'pending')
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock me-1"></i>
                                                    Pendente
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times me-1"></i>
                                                    Cancelado
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>Data:</strong><br>
                                            <span class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Método:</strong><br>
                                            <span class="badge bg-secondary">{{ ucfirst($order->payment_method) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-user me-2"></i>
                                        Informações do Cliente
                                    </h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>Nome:</strong><br>
                                            <span class="text-muted">{{ $order->user->name }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Email:</strong><br>
                                            <span class="text-muted">{{ $order->user->email }}</span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>Jogo:</strong><br>
                                            <span class="badge bg-info">{{ $order->game_username }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Servidor:</strong><br>
                                            <span class="text-muted">{{ $order->server_name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Itens do Pedido -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-list me-2"></i>
                                        Itens do Pedido
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Produto</th>
                                                    <th>Quantidade</th>
                                                    <th>Preço Unitário</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($order->orderItems as $item)
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <strong>{{ $item->product_name ?? $item->product->name }}</strong>
                                                            @if($item->product)
                                                            <br>
                                                            <small class="text-muted">{{ $item->product->description }}</small>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                                                    <td>
                                                        <strong>R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</strong>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <td colspan="3" class="text-end">
                                                        <strong>Total:</strong>
                                                    </td>
                                                    <td>
                                                        <strong class="text-primary fs-5">
                                                            R$ {{ number_format($order->total_amount, 2, ',', '.') }}
                                                        </strong>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informações de Pagamento -->
                    @if($order->transaction_id)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-credit-card me-2"></i>
                                        Informações de Pagamento
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Transaction ID:</strong><br>
                                            <code>{{ $order->transaction_id }}</code>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Status:</strong><br>
                                            <span class="badge bg-success">Confirmado</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Ações -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-cogs me-2"></i>
                                        Ações
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="btn-group" role="group">
                                        @if($order->status === 'pending')
                                        <button type="button"
                                                class="btn btn-success"
                                                onclick="markAsPaid({{ $order->id }})">
                                            <i class="fas fa-check me-1"></i>
                                            Marcar como Pago
                                        </button>
                                        <button type="button"
                                                class="btn btn-danger"
                                                onclick="cancelOrder({{ $order->id }})">
                                            <i class="fas fa-times me-1"></i>
                                            Cancelar Pedido
                                        </button>
                                        @endif

                                        @if($order->status === 'paid')
                                        <button type="button"
                                                class="btn btn-warning"
                                                onclick="markAsPending({{ $order->id }})">
                                            <i class="fas fa-clock me-1"></i>
                                            Marcar como Pendente
                                        </button>
                                        @endif

                                        @if($order->status === 'cancelled')
                                        <button type="button"
                                                class="btn btn-warning"
                                                onclick="markAsPending({{ $order->id }})">
                                            <i class="fas fa-clock me-1"></i>
                                            Marcar como Pendente
                                        </button>
                                        @endif
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

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Ação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="confirmMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmAction">Confirmar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Marcar como pago
    function markAsPaid(orderId) {
        showConfirmModal(
            'Tem certeza que deseja marcar este pedido como pago?',
            () => updateOrderStatus(orderId, 'paid')
        );
    }

    // Marcar como pendente
    function markAsPending(orderId) {
        showConfirmModal(
            'Tem certeza que deseja marcar este pedido como pendente?',
            () => updateOrderStatus(orderId, 'pending')
        );
    }

    // Cancelar pedido
    function cancelOrder(orderId) {
        showConfirmModal(
            'Tem certeza que deseja cancelar este pedido?',
            () => updateOrderStatus(orderId, 'cancelled')
        );
    }

    // Mostrar modal de confirmação
    function showConfirmModal(message, callback) {
        document.getElementById('confirmMessage').textContent = message;
        document.getElementById('confirmAction').onclick = callback;
        new bootstrap.Modal(document.getElementById('confirmModal')).show();
    }

    // Atualizar status do pedido
    function updateOrderStatus(orderId, status) {
        fetch(`/admin/orders/${orderId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao atualizar status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao atualizar status do pedido');
        });
    }
</script>
@endpush
