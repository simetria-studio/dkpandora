@extends('layouts.admin')

@section('title', 'Recompensas - Painel Administrativo')
@section('page-title', 'Gerenciar Recompensas')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <div class="d-flex align-items-center">
            <h4 class="mb-0 me-3">
                <i class="fas fa-gift me-2"></i>Recompensas
            </h4>
            <span class="badge bg-primary fs-6">{{ $rewards->count() }} recompensas</span>
        </div>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('admin.rewards.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nova Recompensa
        </a>
    </div>
</div>

<!-- Estatísticas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $rewards->count() }}</h4>
                        <p class="mb-0">Total de Recompensas</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-gift fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $rewards->where('is_active', true)->count() }}</h4>
                        <p class="mb-0">Recompensas Ativas</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $rewards->sum('user_rewards_count') }}</h4>
                        <p class="mb-0">Total de Resgates</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-trophy fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">R$ {{ number_format($rewards->min('required_amount'), 2, ',', '.') }}</h4>
                        <p class="mb-0">Menor Valor</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lista de Recompensas -->
<div class="card">
    <div class="card-body p-0">
        @if($rewards->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="border-0">Recompensa</th>
                            <th class="border-0 text-center">Valor Mínimo</th>
                            <th class="border-0 text-center">Tipo</th>
                            <th class="border-0 text-center">Resgates</th>
                            <th class="border-0 text-center">Status</th>
                            <th class="border-0 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rewards as $reward)
                        <tr>
                            <td>
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ $reward->name }}</h6>
                                    <small class="text-muted">
                                        {{ Str::limit($reward->description, 60) }}
                                    </small>
                                    @if($reward->max_redemptions)
                                        <div class="mt-1">
                                            <span class="badge bg-info">
                                                Limite: {{ $reward->max_redemptions }} resgates
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="fw-bold text-success">
                                    R$ {{ number_format($reward->required_amount, 2, ',', '.') }}
                                </div>
                            </td>
                            <td class="text-center">
                                @switch($reward->reward_type)
                                    @case('discount')
                                        <span class="badge bg-primary">
                                            <i class="fas fa-percentage me-1"></i>Desconto
                                        </span>
                                        @break
                                    @case('product')
                                        <span class="badge bg-success">
                                            <i class="fas fa-box me-1"></i>Produto
                                        </span>
                                        @break
                                    @case('bonus')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-star me-1"></i>Bônus
                                        </span>
                                        @break
                                    @case('cashback')
                                        <span class="badge bg-info">
                                            <i class="fas fa-money-bill-wave me-1"></i>Cashback
                                        </span>
                                        @break
                                @endswitch
                            </td>
                            <td class="text-center">
                                <div class="fw-bold">{{ $reward->user_rewards_count }}</div>
                                @if($reward->max_redemptions)
                                    <small class="text-muted">
                                        / {{ $reward->max_redemptions }}
                                    </small>
                                @else
                                    <small class="text-muted">Ilimitado</small>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($reward->is_active)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Ativa
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-pause me-1"></i>Inativa
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.rewards.show', $reward) }}"
                                       class="btn btn-sm btn-outline-info"
                                       title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.rewards.edit', $reward) }}"
                                       class="btn btn-sm btn-outline-warning"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.rewards.toggle-active', $reward) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-{{ $reward->is_active ? 'secondary' : 'success' }}"
                                                title="{{ $reward->is_active ? 'Desativar' : 'Ativar' }}">
                                            <i class="fas fa-{{ $reward->is_active ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.rewards.destroy', $reward) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Tem certeza que deseja excluir esta recompensa?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-gift fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Nenhuma recompensa encontrada</h5>
                <p class="text-muted">Comece criando sua primeira recompensa</p>
                <a href="{{ route('admin.rewards.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Criar Recompensa
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    .table tbody tr {
        background: #1a1a2e !important;
    }

    .table tbody tr:nth-child(even) {
        background: #16213e !important;
    }

    .table tbody tr:hover {
        background: rgba(106, 13, 173, 0.3) !important;
    }

    .table tbody td {
        background: transparent !important;
        color: #ffffff !important;
    }

    .table tbody td h6 {
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
</style>
@endsection



