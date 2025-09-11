@extends('layouts.admin')

@section('title', 'Gerenciar Pedidos - Painel Administrativo')
@section('page-title', 'Gerenciar Pedidos')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">
                    <i class="fas fa-shopping-cart me-3" style="color: #6a0dad;"></i>
                    Gerenciar Pedidos
                </h2>
                <p class="text-muted mb-0">Visualize e gerencie todos os pedidos do sistema</p>
            </div>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Voltar ao Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-filter me-2"></i>Filtros e Busca
                </h5>
            </div>
            <div class="card-body">
                <!-- Filtros -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="status_filter" class="form-label fw-bold">
                            <i class="fas fa-tag me-1"></i>Status do Pedido
                        </label>
                        <select id="status_filter" class="form-select">
                            <option value="">Todos os Status</option>
                            <option value="pending">Pendente</option>
                            <option value="paid">Pago</option>
                            <option value="delivered">Entregue</option>
                            <option value="cancelled">Cancelado</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date_filter" class="form-label fw-bold">
                            <i class="fas fa-calendar me-1"></i>Data do Pedido
                        </label>
                        <input type="date" id="date_filter" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="search" class="form-label fw-bold">
                            <i class="fas fa-search me-1"></i>Buscar Pedidos
                        </label>
                        <input type="text" id="search" class="form-control" placeholder="ID, usuário, personagem do jogo...">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" id="apply_filters" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>
                            Aplicar Filtros
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Lista de Pedidos
                </h5>
            </div>

            <div class="card-body">
                <!-- Tabela de Pedidos -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-1"></i>ID</th>
                                <th><i class="fas fa-user me-1"></i>Cliente</th>
                                <th><i class="fas fa-gamepad me-1"></i>Personagem</th>
                                <th><i class="fas fa-server me-1"></i>Servidor</th>
                                <th><i class="fas fa-dollar-sign me-1"></i>Total</th>
                                <th><i class="fas fa-tag me-1"></i>Status</th>
                                <th><i class="fas fa-credit-card me-1"></i>Pagamento</th>
                                <th><i class="fas fa-calendar me-1"></i>Data</th>
                                <th><i class="fas fa-cogs me-1"></i>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr style="background: #1a1a2e !important;">
                                <td>
                                    <span class="fw-bold text-primary">#{{ $order->id }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-light">
                                            {{ $order->user->name }}
                                        </span>
                                        <small class="text-light mt-1">{{ $order->user->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark fw-bold">
                                        <i class="fas fa-user me-1"></i>{{ $order->game_username }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-server me-1"></i>{{ $order->server_name }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success fs-6">
                                        R$ {{ number_format($order->total_amount, 2, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    @if($order->status === 'paid')
                                        <span class="badge badge-completed">
                                            <i class="fas fa-check me-1"></i>
                                            Pago
                                        </span>
                                    @elseif($order->status === 'delivered')
                                        <span class="badge badge-completed">
                                            <i class="fas fa-truck me-1"></i>
                                            Entregue
                                        </span>
                                    @elseif($order->status === 'pending')
                                        <span class="badge badge-pending">
                                            <i class="fas fa-clock me-1"></i>
                                            Pendente
                                        </span>
                                    @else
                                        <span class="badge badge-cancelled">
                                            <i class="fas fa-times me-1"></i>
                                            Cancelado
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        <i class="fas fa-credit-card me-1"></i>
                                        {{ ucfirst($order->payment_method) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-light">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $order->created_at->format('d/m/Y') }}
                                        <br>
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $order->created_at->format('H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                           class="btn btn-sm btn-primary"
                                           title="Ver detalhes">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($order->status === 'pending')
                                        <button type="button"
                                                class="btn btn-sm btn-success"
                                                title="Marcar como pago"
                                                onclick="markAsPaid({{ $order->id }})">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-danger"
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
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-light">
                                        <i class="fas fa-shopping-cart fa-4x mb-4" style="color: #6a0dad; opacity: 0.5;"></i>
                                        <h4 class="mb-3">Nenhum pedido encontrado</h4>
                                        <p class="mb-0">Não há pedidos para exibir no momento.</p>
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
                    <nav aria-label="Navegação de páginas">
                        {{ $orders->links() }}
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirmar Ação
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4 bg-dark">
                <i class="fas fa-question-circle fa-3x text-warning mb-3"></i>
                <p id="confirmMessage" class="fs-5 mb-0"></p>
            </div>
            <div class="modal-footer bg-dark">
                <button type="button" class="btn btn-secondary bg-dark text-white " data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="confirmAction">
                    <i class="fas fa-check me-2"></i>Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    /* Estilos específicos para a página de pedidos */
    .card {
        border: none;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-radius: 15px;
    }

    .card-header {
        background: linear-gradient(135deg, #6a0dad 0%, #8b5cf6 100%);
        color: white;
        border-radius: 15px 15px 0 0 !important;
        border: none;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #6a0dad;
        box-shadow: 0 0 0 0.2rem rgba(106, 13, 173, 0.25);
    }

    .btn-primary {
        background: linear-gradient(135deg, #6a0dad 0%, #8b5cf6 100%);
        border: none;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(106, 13, 173, 0.4);
    }

    .table thead th {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        color: white;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }

    .table tbody tr {
        background: #1a1a2e !important;
        transition: all 0.3s ease;
    }

    .table tbody tr:nth-child(even) {
        background: #16213e !important;
    }

    .table tbody tr:nth-child(odd) {
        background: #1a1a2e !important;
    }

    .table tbody tr:hover {
        background: rgba(106, 13, 173, 0.3) !important;
        transform: translateY(-1px);
    }

    .table tbody td {
        background: transparent !important;
        color: #ffffff !important;
    }

    .table.table-hover tbody tr {
        background: #1a1a2e !important;
    }

    .table.table-hover tbody tr:nth-child(even) {
        background: #16213e !important;
    }

    .table.table-hover tbody tr:nth-child(odd) {
        background: #1a1a2e !important;
    }

    /* Força o fundo escuro em todas as linhas */
    .card .table-responsive .table tbody tr {
        background: #1a1a2e !important;
    }

    .card .table-responsive .table tbody tr:nth-child(even) {
        background: #16213e !important;
    }

    .card .table-responsive .table tbody tr:nth-child(odd) {
        background: #1a1a2e !important;
    }

    .table tbody td h4 {
        color: #ffffff !important;
    }

    .table tbody td small {
        color: #a0a0a0 !important;
    }

    .table tbody td .text-success {
        color: #28a745 !important;
    }

    .table tbody td .text-muted {
        color: #a0a0a0 !important;
    }

    .table tbody td .fw-bold {
        color: #ffffff !important;
    }

    .table tbody td .text-primary {
        color: #00d4ff !important;
    }

    .badge {
        font-weight: 700;
        padding: 0.5rem 0.8rem;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-group .btn {
        border-radius: 8px;
        margin: 0 2px;
    }

    .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
        border-radius: 15px 15px 0 0;
        border: none;
    }
</style>
@endsection

@section('scripts')
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
        if (confirm('Tem certeza que deseja marcar este pedido como pago?')) {
            // Criar form diretamente
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/orders/${orderId}/status`;
            form.style.display = 'none';

            // CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            // Status
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = 'paid';
            form.appendChild(statusInput);

            document.body.appendChild(form);
            form.submit();
        }
    }

    // Cancelar pedido
    function cancelOrder(orderId) {
        if (confirm('Tem certeza que deseja cancelar este pedido?')) {
            // Criar form diretamente
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/orders/${orderId}/status`;
            form.style.display = 'none';

            // CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            // Status
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = 'cancelled';
            form.appendChild(statusInput);

            document.body.appendChild(form);
            form.submit();
        }
    }

    // Mostrar modal de confirmação
    function showConfirmModal(message, callback) {
        document.getElementById('confirmMessage').textContent = message;
        document.getElementById('confirmAction').onclick = callback;
        new bootstrap.Modal(document.getElementById('confirmModal')).show();
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

        // Forçar fundo escuro nas linhas da tabela
        const tableRows = document.querySelectorAll('.table tbody tr');
        tableRows.forEach((row, index) => {
            if (index % 2 === 0) {
                row.style.setProperty('background', '#1a1a2e', 'important');
            } else {
                row.style.setProperty('background', '#16213e', 'important');
            }
        });
    });
</script>
@endsection
