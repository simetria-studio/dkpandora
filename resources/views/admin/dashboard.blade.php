@extends('layouts.admin')

@section('title', 'Dashboard - Painel Administrativo')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="number">{{ $stats['total_orders'] }}</div>
            <div class="label">Total de Pedidos</div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
            <div class="icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="number">R$ {{ number_format($stats['total_revenue'], 2, ',', '.') }}</div>
            <div class="label">Receita Total</div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #17a2b8 0%, #00d4ff 100%);">
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="number">{{ $stats['total_products'] }}</div>
            <div class="label">Produtos</div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #ff6b35 0%, #ff8c42 100%);">
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="number">{{ $stats['total_users'] }}</div>
            <div class="label">Usuários</div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Orders -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>Pedidos Recentes
                </h5>
            </div>
            <div class="card-body">
                @if($recent_orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_orders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>R$ {{ number_format($order->total_amount, 2, ',', '.') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $order->status }}">
                                                @switch($order->status)
                                                    @case('pending') Pendente @break
                                                    @case('processing') Processando @break
                                                    @case('completed') Concluído @break
                                                    @case('cancelled') Cancelado @break
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Nenhum pedido encontrado.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Status dos Pedidos
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Pendentes</span>
                    <span class="badge badge-pending">{{ $stats['pending_orders'] }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Concluídos</span>
                    <span class="badge badge-completed">{{ $stats['completed_orders'] }}</span>
                </div>
                <div class="progress mb-3" style="height: 8px;">
                    @php
                        $total = $stats['total_orders'] > 0 ? $stats['total_orders'] : 1;
                        $completed_percentage = ($stats['completed_orders'] / $total) * 100;
                    @endphp
                    <div class="progress-bar bg-success" style="width: {{ $completed_percentage }}%"></div>
                </div>
                <small class="text-muted">
                    {{ number_format($completed_percentage, 1) }}% dos pedidos foram concluídos
                </small>
            </div>
        </div>

        <!-- Top Products -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-star me-2"></i>Produtos Mais Vendidos
                </h5>
            </div>
            <div class="card-body">
                @if($top_products->count() > 0)
                    @foreach($top_products as $product)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-truncate">{{ $product->name }}</span>
                            <span class="badge bg-primary">{{ $product->total_sold }}</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">Nenhum produto vendido ainda.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Monthly Sales Chart -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Vendas Mensais ({{ date('Y') }})
                </h5>
            </div>
            <div class="card-body">
                @if($monthly_sales->count() > 0)
                    <div class="row">
                        @foreach($monthly_sales as $sale)
                            <div class="col-md-2 mb-3">
                                <div class="text-center">
                                    <div class="fw-bold text-primary">
                                        R$ {{ number_format($sale->total, 2, ',', '.') }}
                                    </div>
                                    <small class="text-muted">
                                        @switch($sale->month)
                                            @case(1) Janeiro @break
                                            @case(2) Fevereiro @break
                                            @case(3) Março @break
                                            @case(4) Abril @break
                                            @case(5) Maio @break
                                            @case(6) Junho @break
                                            @case(7) Julho @break
                                            @case(8) Agosto @break
                                            @case(9) Setembro @break
                                            @case(10) Outubro @break
                                            @case(11) Novembro @break
                                            @case(12) Dezembro @break
                                        @endswitch
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Nenhuma venda registrada este ano.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
