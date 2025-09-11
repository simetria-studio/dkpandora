@extends('layouts.admin')

@section('title', 'Detalhes do Pedido #' . $order->id)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-dark border-0 d-flex justify-content-between align-items-center py-4">
                    <div class="d-flex align-items-center">
                        <div class="me-4">
                            @if($order->status === 'paid')
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 48px; height: 48px;">
                                    <i class="fas fa-check text-white"></i>
                                </div>
                            @elseif($order->status === 'pending')
                                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 48px; height: 48px;">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                            @else
                                <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 48px; height: 48px;">
                                    <i class="fas fa-times text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="mb-1 fw-bold text-white">Pedido #{{ $order->id }}</h3>
                            <p class="text-light mb-0">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>
                            Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Informações Principais -->
                    <div class="row mb-5">
                        <div class="col-md-8">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="border-start border-3 border-primary ps-4">
                                        <h6 class="text-light text-uppercase small fw-bold mb-2">Cliente</h6>
                                        <p class="mb-1 fw-semibold text-white">{{ $order->user->name }}</p>
                                        <p class="text-light small mb-0">{{ $order->user->email }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border-start border-3 border-info ps-4">
                                        <h6 class="text-light text-uppercase small fw-bold mb-2">Jogo</h6>
                                        <p class="mb-1 fw-semibold text-white">{{ $order->game_username }}</p>
                                        <p class="text-light small mb-0">{{ $order->server_name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-end">
                                <h6 class="text-light text-uppercase small fw-bold mb-2">Status</h6>
                                @if($order->status === 'paid')
                                    <span class="badge bg-success px-3 py-2 fs-6">Pago</span>
                                @elseif($order->status === 'pending')
                                    <span class="badge bg-warning text-dark px-3 py-2 fs-6">Pendente</span>
                                @elseif($order->status === 'delivered')
                                    <span class="badge bg-info px-3 py-2 fs-6">Entregue</span>
                                @else
                                    <span class="badge bg-danger px-3 py-2 fs-6">Cancelado</span>
                                @endif
                                <p class="text-light small mt-2 mb-0">{{ ucfirst($order->payment_method) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Itens do Pedido -->
                    <div class="mb-5">
                        <h5 class="fw-bold mb-4 text-white">Itens do Pedido</h5>
                        <div class="table-responsive">
                            <table class="table table-borderless table-dark">
                                <thead>
                                    <tr class="border-bottom border-secondary">
                                        <th class="text-light small fw-bold text-uppercase">Produto</th>
                                        <th class="text-light small fw-bold text-uppercase text-center">Qtd</th>
                                        <th class="text-light small fw-bold text-uppercase text-end">Preço Unit.</th>
                                        <th class="text-light small fw-bold text-uppercase text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                    <tr class="border-bottom border-secondary">
                                        <td class="py-3">
                                            <div>
                                                <p class="mb-1 fw-semibold text-white">{{ $item->product_name ?? $item->product->name }}</p>
                                                @if($item->product)
                                                <small class="text-light">{{ $item->product->description }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3 text-center">
                                            <span class="badge bg-secondary text-white">{{ $item->quantity }}</span>
                                        </td>
                                        <td class="py-3 text-end">
                                            <span class="fw-semibold text-white">R$ {{ number_format($item->price, 2, ',', '.') }}</span>
                                        </td>
                                        <td class="py-3 text-end">
                                            <span class="fw-bold text-success">R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="py-4 text-end border-0">
                                            <h5 class="mb-0 fw-bold text-white">Total</h5>
                                        </td>
                                        <td class="py-4 text-end border-0">
                                            <h4 class="mb-0 fw-bold text-primary">R$ {{ number_format($order->total_amount, 2, ',', '.') }}</h4>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Informações de Pagamento -->
                    @if($order->transaction_id)
                    <div class="mb-5">
                        <div class="border-start border-3 border-success ps-4">
                            <h6 class="text-light text-uppercase small fw-bold mb-2">Pagamento</h6>
                            <p class="mb-1 fw-semibold text-white">{{ $order->transaction_id }}</p>
                            <span class="badge bg-success">Confirmado</span>
                        </div>
                    </div>
                    @endif

                    <!-- Ações -->
                    <div class="border-top border-secondary pt-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="fw-bold text-white mb-0">
                                        <i class="fas fa-cogs me-2 text-primary"></i>
                                        Gerenciar Pedido
                                    </h5>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-light btn-sm" onclick="printOrder()">
                                            <i class="fas fa-print me-1"></i>
                                            Imprimir
                                        </button>
                                        <button type="button" class="btn btn-outline-info btn-sm" onclick="sendNotification()">
                                            <i class="fas fa-bell me-1"></i>
                                            Notificar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-dark border-secondary">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        @if($order->status === 'paid')
                                                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center"
                                                                 style="width: 40px; height: 40px;">
                                                                <i class="fas fa-check text-white"></i>
                                                            </div>
                                                        @elseif($order->status === 'pending')
                                                            <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center"
                                                                 style="width: 40px; height: 40px;">
                                                                <i class="fas fa-clock text-white"></i>
                                                            </div>
                                                        @elseif($order->status === 'delivered')
                                                            <div class="bg-info rounded-circle d-flex align-items-center justify-content-center"
                                                                 style="width: 40px; height: 40px;">
                                                                <i class="fas fa-truck text-white"></i>
                                                            </div>
                                                        @else
                                                            <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center"
                                                                 style="width: 40px; height: 40px;">
                                                                <i class="fas fa-times text-white"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="text-white mb-1">Status Atual</h6>
                                                        @if($order->status === 'paid')
                                                            <span class="badge bg-success fs-6">Pago</span>
                                                        @elseif($order->status === 'pending')
                                                            <span class="badge bg-warning text-dark fs-6">Pendente</span>
                                                        @elseif($order->status === 'delivered')
                                                            <span class="badge bg-info fs-6">Entregue</span>
                                                        @else
                                                            <span class="badge bg-danger fs-6">Cancelado</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="text-md-end">
                                                    <h6 class="text-light mb-3">Alterar Status</h6>
                                                    <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                                                        <button type="button"
                                                                class="btn btn-success {{ $order->status === 'paid' ? 'active' : '' }}"
                                                                onclick="changeStatus('paid')"
                                                                {{ $order->status === 'paid' ? 'disabled' : '' }}>
                                                            <i class="fas fa-check me-2"></i>
                                                            Pago
                                                        </button>
                                                        <button type="button"
                                                                class="btn btn-warning {{ $order->status === 'pending' ? 'active' : '' }}"
                                                                onclick="changeStatus('pending')"
                                                                {{ $order->status === 'pending' ? 'disabled' : '' }}>
                                                            <i class="fas fa-clock me-2"></i>
                                                            Pendente
                                                        </button>
                                                        <button type="button"
                                                                class="btn btn-info {{ $order->status === 'delivered' ? 'active' : '' }}"
                                                                onclick="changeStatus('delivered')"
                                                                {{ $order->status === 'delivered' ? 'disabled' : '' }}>
                                                            <i class="fas fa-truck me-2"></i>
                                                            Entregue
                                                        </button>
                                                        <button type="button"
                                                                class="btn btn-danger {{ $order->status === 'cancelled' ? 'active' : '' }}"
                                                                onclick="changeStatus('cancelled')"
                                                                {{ $order->status === 'cancelled' ? 'disabled' : '' }}>
                                                            <i class="fas fa-times me-2"></i>
                                                            Cancelado
                                                        </button>
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
        </div>
    </div>
</div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white">Confirmar Ação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-dark">
                <p id="confirmMessage"></p>
            </div>
            <div class="modal-footer bg-dark">
                <button type="button" class="btn btn-secondary bg-dark text-white" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmAction">Confirmar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    /* Tema escuro global */
    body {
        background-color: #1a1a1a !important;
    }

    .card {
        background-color: #2d2d2d !important;
        border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }

    .card-body {
        background-color: #2d2d2d !important;
    }

    /* Remover TODAS as animações e transições globalmente */
    *, *::before, *::after {
        animation: none !important;
        transition: none !important;
        transform: none !important;
    }

    /* Remover hover effects do Bootstrap */
    .btn:hover,
    .btn:focus,
    .btn:active,
    .btn:focus-visible {
        transform: none !important;
        box-shadow: none !important;
        animation: none !important;
        transition: none !important;
    }

    .card:hover {
        transform: none !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3) !important;
        animation: none !important;
        transition: none !important;
    }

    .table tbody tr:hover {
        background-color: rgba(255,255,255,0.05) !important;
        transform: none !important;
        animation: none !important;
        transition: none !important;
    }

    /* Estilos básicos sem animação */
    .btn-group .btn {
        border-radius: 4px;
        font-weight: 500;
    }

    .btn-group .btn:not(:last-child) {
        margin-right: 8px;
    }

    .btn-group .btn.active {
        box-shadow: none;
    }

    .btn-group .btn:disabled {
        opacity: 0.5;
    }

    .alert {
        border: none;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }

    .badge {
        font-weight: 500;
        padding: 6px 12px;
    }

    .border-start {
        border-left-width: 3px !important;
    }

    /* Forçar remoção de qualquer animação do Bootstrap */
    .fade {
        opacity: 1 !important;
    }

    .show {
        opacity: 1 !important;
    }

    /* Remover animações de focus */
    .btn:focus,
    .btn:focus-visible {
        outline: none !important;
        box-shadow: none !important;
    }

    /* Garantir que não há fundos brancos */
    .table {
        background-color: transparent !important;
    }

    .table tbody tr {
        background-color: transparent !important;
    }

    .table tbody tr td {
        background-color: transparent !important;
        border-color: #495057 !important;
    }

    .table tfoot tr {
        background-color: transparent !important;
    }

    .table tfoot tr td {
        background-color: transparent !important;
        border-color: #495057 !important;
    }

    /* Forçar remoção de qualquer fundo branco do Bootstrap */
    .table > :not(caption) > * > * {
        background-color: transparent !important;
    }

    .table-striped > tbody > tr:nth-of-type(odd) > td {
        background-color: transparent !important;
    }

    .table-striped > tbody > tr:nth-of-type(even) > td {
        background-color: transparent !important;
    }

    /* Forçar tema escuro em todas as células da tabela */
    .table-dark {
        background-color: transparent !important;
    }

    .table-dark tbody tr {
        background-color: transparent !important;
    }

    .table-dark tbody tr td {
        background-color: transparent !important;
        color: #ffffff !important;
    }

    .table-dark tfoot tr {
        background-color: transparent !important;
    }

    .table-dark tfoot tr td {
        background-color: transparent !important;
        color: #ffffff !important;
    }

    /* Remover qualquer fundo branco específico do Bootstrap */
    .table-dark > :not(caption) > * > * {
        background-color: transparent !important;
    }

    /* Estilos para botões de status */
    .btn.active {
        box-shadow: 0 0 0 2px rgba(255,255,255,0.3) !important;
        transform: scale(1.05) !important;
    }

    .btn:disabled {
        opacity: 0.4 !important;
        cursor: not-allowed !important;
    }

    .btn:not(:disabled):hover {
        transform: scale(1.02) !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3) !important;
    }

    /* Card de gerenciamento */
    .card.bg-dark {
        background-color: #2d2d2d !important;
        border: 1px solid #495057 !important;
    }

    .card.bg-dark .card-body {
        background-color: #2d2d2d !important;
    }
</style>
@endpush

@section('scripts')
<script>
    // Função principal para mudar status
    function changeStatus(newStatus) {
        const currentStatus = '{{ $order->status }}';
        const orderId = {{ $order->id }};

        if (currentStatus === newStatus) {
            return;
        }

        const statusMessages = {
            'paid': 'Tem certeza que deseja marcar este pedido como PAGO? Esta ação irá confirmar o pagamento.',
            'pending': 'Tem certeza que deseja marcar este pedido como PENDENTE? Esta ação irá reverter o status.',
            'delivered': 'Tem certeza que deseja marcar este pedido como ENTREGUE? Esta ação confirma a entrega.',
            'cancelled': 'Tem certeza que deseja CANCELAR este pedido? Esta ação não pode ser desfeita facilmente.'
        };

        const statusNames = {
            'paid': 'Pago',
            'pending': 'Pendente',
            'delivered': 'Entregue',
            'cancelled': 'Cancelado'
        };

        showConfirmModal(
            statusMessages[newStatus],
            () => updateOrderStatus(orderId, newStatus),
            `Alterar para: ${statusNames[newStatus]}`
        );
    }

    // Ações rápidas
    function quickAction(action) {
        const orderId = {{ $order->id }};

        switch(action) {
            case 'paid':
                changeStatus('paid');
                break;
            case 'processing':
                showConfirmModal(
                    'Deseja processar este pedido? Isso irá marcar como em processamento.',
                    () => updateOrderStatus(orderId, 'processing'),
                    'Processar Pedido'
                );
                break;
            default:
                console.log('Ação não implementada:', action);
        }
    }

    // Imprimir pedido
    function printOrder() {
        window.print();
    }

    // Enviar notificação
    function sendNotification() {
        const orderId = {{ $order->id }};

        showConfirmModal(
            'Deseja enviar uma notificação por email para o cliente sobre o status do pedido?',
            () => {
                // Implementar envio de notificação
                showAlert('Funcionalidade de notificação em desenvolvimento', 'info');
            },
            'Enviar Notificação'
        );
    }

    // Mostrar modal de confirmação melhorado
    function showConfirmModal(message, callback, title = 'Confirmar Ação') {
        document.getElementById('confirmMessage').innerHTML = message;
        document.getElementById('confirmAction').onclick = callback;

        // Atualizar título do modal
        const modalTitle = document.querySelector('#confirmModal .modal-title');
        if (modalTitle) {
            modalTitle.textContent = title;
        }

        new bootstrap.Modal(document.getElementById('confirmModal')).show();
    }

    // Mostrar alertas personalizados
    function showAlert(message, type = 'info') {
        const alertClass = type === 'success' ? 'alert-success' :
                          type === 'error' ? 'alert-danger' : 'alert-info';

        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible position-fixed"
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', alertHtml);

        // Remover automaticamente após 5 segundos
        setTimeout(() => {
            const alert = document.querySelector('.alert:last-of-type');
            if (alert) {
                alert.remove();
            }
        }, 5000);
    }

    // Atualizar status do pedido
    function updateOrderStatus(orderId, status) {
        // Mostrar loading
        const confirmBtn = document.getElementById('confirmAction');
        const originalText = confirmBtn.innerHTML;
        confirmBtn.innerHTML = 'Atualizando...';
        confirmBtn.disabled = true;

        // Criar form para enviar dados
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/orders/${orderId}/status`;
        form.style.display = 'none';

        // CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfInput);

        // Status
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        form.appendChild(statusInput);

        // Enviar form
        document.body.appendChild(form);
        form.submit();
    }

    // Funções legadas para compatibilidade
    function markAsPaid(orderId) {
        changeStatus('paid');
    }

    function markAsPending(orderId) {
        changeStatus('pending');
    }

    function cancelOrder(orderId) {
        changeStatus('cancelled');
    }
</script>
@endsection
