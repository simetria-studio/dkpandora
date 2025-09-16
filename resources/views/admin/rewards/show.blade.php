@extends('layouts.admin')

@section('title', 'Visualizar Recompensa - Painel Administrativo')
@section('page-title', 'Detalhes da Recompensa')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-gift me-2"></i>{{ $reward->name }}
                    </h5>
                    <div>
                        <a href="{{ route('admin.rewards.edit', $reward) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>Editar
                        </a>
                        <a href="{{ route('admin.rewards.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Voltar
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informações Básicas</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Nome:</strong></td>
                                <td>{{ $reward->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Valor Mínimo:</strong></td>
                                <td class="text-success fw-bold">R$ {{ number_format($reward->required_amount, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tipo:</strong></td>
                                <td>
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
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
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
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Estatísticas</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Resgates:</strong></td>
                                <td>
                                    {{ $reward->current_redemptions }}
                                    @if($reward->max_redemptions)
                                        / {{ $reward->max_redemptions }}
                                    @else
                                        (ilimitado)
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Criada em:</strong></td>
                                <td>{{ $reward->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Atualizada em:</strong></td>
                                <td>{{ $reward->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($reward->description)
                    <div class="mt-3">
                        <h6>Descrição</h6>
                        <p class="text-muted">{{ $reward->description }}</p>
                    </div>
                @endif

                @if($reward->reward_data)
                    <div class="mt-3">
                        <h6>Configurações da Recompensa</h6>
                        <div class="card">
                            <div class="card-body">
                                @switch($reward->reward_type)
                                    @case('discount')
                                        @if(isset($reward->reward_data['discount_percentage']))
                                            <p><strong>Desconto:</strong> {{ $reward->reward_data['discount_percentage'] }}%</p>
                                        @endif
                                        @if(isset($reward->reward_data['discount_amount']))
                                            <p><strong>Valor Fixo:</strong> R$ {{ number_format($reward->reward_data['discount_amount'], 2, ',', '.') }}</p>
                                        @endif
                                        @if(isset($reward->reward_data['valid_days']))
                                            <p><strong>Válido por:</strong> {{ $reward->reward_data['valid_days'] }} dias</p>
                                        @endif
                                        @break
                                    @case('product')
                                        @if(isset($reward->reward_data['product_name']))
                                            <p><strong>Produto:</strong> {{ $reward->reward_data['product_name'] }}</p>
                                        @endif
                                        @if(isset($reward->reward_data['product_id']))
                                            <p><strong>ID do Produto:</strong> {{ $reward->reward_data['product_id'] }}</p>
                                        @endif
                                        @if(isset($reward->reward_data['quantity']))
                                            <p><strong>Quantidade:</strong> {{ $reward->reward_data['quantity'] }}</p>
                                        @endif
                                        @break
                                    @case('bonus')
                                        @if(isset($reward->reward_data['bonus_type']))
                                            <p><strong>Tipo de Bônus:</strong> {{ $reward->reward_data['bonus_type'] }}</p>
                                        @endif
                                        @if(isset($reward->reward_data['bonus_value']))
                                            <p><strong>Valor:</strong> {{ $reward->reward_data['bonus_value'] }}</p>
                                        @endif
                                        @if(isset($reward->reward_data['description']))
                                            <p><strong>Descrição:</strong> {{ $reward->reward_data['description'] }}</p>
                                        @endif
                                        @break
                                    @case('cashback')
                                        @if(isset($reward->reward_data['cashback_percentage']))
                                            <p><strong>Cashback:</strong> {{ $reward->reward_data['cashback_percentage'] }}%</p>
                                        @endif
                                        @if(isset($reward->reward_data['cashback_amount']))
                                            <p><strong>Valor Fixo:</strong> R$ {{ number_format($reward->reward_data['cashback_amount'], 2, ',', '.') }}</p>
                                        @endif
                                        @break
                                @endswitch
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Lista de Resgates -->
        @if($reward->userRewards->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-users me-2"></i>Usuários que Resgataram ({{ $reward->userRewards->count() }})
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="border-0">Usuário</th>
                                    <th class="border-0 text-center">Total Gasto</th>
                                    <th class="border-0 text-center">Status</th>
                                    <th class="border-0 text-center">Data do Resgate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reward->userRewards as $userReward)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $userReward->user->name }}</strong><br>
                                            <small class="text-muted">{{ $userReward->user->email }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold text-success">
                                            R$ {{ number_format($userReward->total_spent, 2, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($userReward->is_redeemed)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Resgatado
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>Pendente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($userReward->redeemed_at)
                                            {{ $userReward->redeemed_at->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="card mt-4">
                <div class="card-body text-center py-4">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">Nenhum usuário resgatou esta recompensa ainda</h6>
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Resumo
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Resgates Totais:</span>
                        <strong>{{ $reward->current_redemptions }}</strong>
                    </div>
                </div>

                @if($reward->max_redemptions)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Limite:</span>
                            <strong>{{ $reward->max_redemptions }}</strong>
                        </div>
                        <div class="progress mt-1" style="height: 8px;">
                            <div class="progress-bar"
                                 style="width: {{ ($reward->current_redemptions / $reward->max_redemptions) * 100 }}%"></div>
                        </div>
                    </div>
                @endif

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Valor Mínimo:</span>
                        <strong class="text-success">R$ {{ number_format($reward->required_amount, 2, ',', '.') }}</strong>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Status:</span>
                        @if($reward->is_active)
                            <span class="badge bg-success">Ativa</span>
                        @else
                            <span class="badge bg-secondary">Inativa</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-cogs me-2"></i>Ações Rápidas
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.rewards.edit', $reward) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Editar Recompensa
                    </a>

                    <form action="{{ route('admin.rewards.toggle-active', $reward) }}" method="POST" class="d-grid">
                        @csrf
                        <button type="submit" class="btn btn-{{ $reward->is_active ? 'secondary' : 'success' }}">
                            <i class="fas fa-{{ $reward->is_active ? 'pause' : 'play' }} me-2"></i>
                            {{ $reward->is_active ? 'Desativar' : 'Ativar' }} Recompensa
                        </button>
                    </form>

                    <form action="{{ route('admin.rewards.destroy', $reward) }}"
                          method="POST"
                          onsubmit="return confirm('Tem certeza que deseja excluir esta recompensa?')"
                          class="d-grid">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Excluir Recompensa
                        </button>
                    </form>
                </div>
            </div>
        </div>
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

    .table tbody td strong {
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



