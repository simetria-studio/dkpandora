@extends('layouts.app')

@section('title', 'Gerenciar Pedidos')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Gerenciar Pedidos
                    </h4>
                    <div>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="status_filter" class="form-label">Status</label>
                            <select id="status_filter" class="form-select">
                                <option value="">Todos</option>
                                <option value="pending">Pendente</option>
                                <option value="paid">Pago</option>
                                <option value="cancelled">Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_filter" class="form-label">Data</label>
                            <input type="date" id="date_filter" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="search" class="form-label">Buscar</label>
                            <input type="text" id="search" class="form-control" placeholder="ID, usuário, jogo...">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" id="apply_filters" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>
                                Filtrar
                            </button>
                        </div>
                    </div>

                    <!-- Tabela de Pedidos -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Usuário</th>
                                    <th>Jogo</th>
                                    <th>Servidor</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Método</th>
                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <strong>#{{ $order->id }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $order->user->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $order->user->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $order->game_username }}</span>
                                    </td>
                                    <td>{{ $order->server_name }}</td>
                                    <td>
                                        <span class="fw-bold text-primary">
                                            R$ {{ number_format($order->total_amount, 2, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($order->payment_method) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.orders.show', $order) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($order->status === 'pending')
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-success"
                                                    title="Marcar como pago"
                                                    onclick="markAsPaid({{ $order->id }})">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Cancelar pedido"
                                                    onclick="cancelOrder({{ $order->id }})">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                                            <h5>Nenhum pedido encontrado</h5>
                                            <p>Não há pedidos para exibir.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    @if($orders->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->links() }}
                    </div>
                    @endif
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
    // Filtros
    document.getElementById('apply_filters').addEventListener('click', function() {
        const status = document.getElementById('status_filter').value;
        const date = document.getElementById('date_filter').value;
        const search = document.getElementById('search').value;

        let url = new URL(window.location);
        if (status) url.searchParams.set('status', status);
        if (date) url.searchParams.set('date', date);
        if (search) url.searchParams.set('search', search);

        window.location.href = url.toString();
    });

    // Marcar como pago
    function markAsPaid(orderId) {
        showConfirmModal(
            'Tem certeza que deseja marcar este pedido como pago?',
            () => updateOrderStatus(orderId, 'paid')
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

    // Aplicar filtros ao carregar a página
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.get('status')) {
            document.getElementById('status_filter').value = urlParams.get('status');
        }
        if (urlParams.get('date')) {
            document.getElementById('date_filter').value = urlParams.get('date');
        }
        if (urlParams.get('search')) {
            document.getElementById('search').value = urlParams.get('search');
        }
    });
</script>
@endpush
