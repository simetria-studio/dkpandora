@extends('layouts.app')

@section('title', 'Minhas Recompensas')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-gift me-2"></i>Minhas Recompensas
                </h2>
                <div class="text-end">
                    <div class="badge bg-primary fs-6">
                        Total Gasto: R$ {{ number_format($user->total_spent, 2, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Recompensas Disponíveis -->
            @if($availableRewards->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-star me-2"></i>Recompensas Disponíveis ({{ $availableRewards->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($availableRewards as $reward)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100 border-success">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-title mb-0">{{ $reward->name }}</h6>
                                                @switch($reward->reward_type)
                                                    @case('discount')
                                                        <span class="badge bg-primary">
                                                            <i class="fas fa-percentage"></i>
                                                        </span>
                                                        @break
                                                    @case('product')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-box"></i>
                                                        </span>
                                                        @break
                                                    @case('bonus')
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-star"></i>
                                                        </span>
                                                        @break
                                                    @case('cashback')
                                                        <span class="badge bg-info">
                                                            <i class="fas fa-money-bill-wave"></i>
                                                        </span>
                                                        @break
                                                @endswitch
                                            </div>

                                            @if($reward->description)
                                                <p class="card-text text-muted small">{{ $reward->description }}</p>
                                            @endif

                                            <div class="mb-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-dollar-sign me-1"></i>
                                                    Valor mínimo: R$ {{ number_format($reward->required_amount, 2, ',', '.') }}
                                                </small>
                                            </div>

                                            <!-- Detalhes da recompensa -->
                                            <div class="mb-3">
                                                @switch($reward->reward_type)
                                                    @case('discount')
                                                        @if(isset($reward->reward_data['discount_percentage']))
                                                            <div class="text-success fw-bold">
                                                                <i class="fas fa-percentage me-1"></i>
                                                                {{ $reward->reward_data['discount_percentage'] }}% de desconto
                                                            </div>
                                                        @endif
                                                        @if(isset($reward->reward_data['valid_days']))
                                                            <small class="text-muted">
                                                                Válido por {{ $reward->reward_data['valid_days'] }} dias
                                                            </small>
                                                        @endif
                                                        @break
                                                    @case('product')
                                                        @if(isset($reward->reward_data['product_name']))
                                                            <div class="text-success fw-bold">
                                                                <i class="fas fa-gift me-1"></i>
                                                                {{ $reward->reward_data['product_name'] }}
                                                            </div>
                                                        @endif
                                                        @break
                                                    @case('bonus')
                                                        @if(isset($reward->reward_data['bonus_type']))
                                                            <div class="text-warning fw-bold">
                                                                <i class="fas fa-star me-1"></i>
                                                                {{ $reward->reward_data['bonus_type'] }}
                                                            </div>
                                                        @endif
                                                        @if(isset($reward->reward_data['bonus_value']))
                                                            <small class="text-muted">
                                                                Valor: {{ $reward->reward_data['bonus_value'] }}
                                                            </small>
                                                        @endif
                                                        @break
                                                    @case('cashback')
                                                        @if(isset($reward->reward_data['cashback_percentage']))
                                                            <div class="text-info fw-bold">
                                                                <i class="fas fa-money-bill-wave me-1"></i>
                                                                {{ $reward->reward_data['cashback_percentage'] }}% de cashback
                                                            </div>
                                                        @endif
                                                        @break
                                                @endswitch
                                            </div>

                                            <form action="{{ route('rewards.redeem', $reward) }}" method="POST" class="d-grid">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-gift me-1"></i>Resgatar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="card mb-4">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-gift fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Nenhuma recompensa disponível</h5>
                        <p class="text-muted">Continue comprando para desbloquear novas recompensas!</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-cart me-2"></i>Ver Produtos
                        </a>
                    </div>
                </div>
            @endif

            <!-- Recompensas Resgatadas -->
            @if($redeemedRewards->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-check-circle me-2"></i>Recompensas Resgatadas ({{ $redeemedRewards->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($redeemedRewards as $userReward)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100 border-secondary">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-title mb-0">{{ $userReward->reward->name }}</h6>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Resgatado
                                                </span>
                                            </div>

                                            @if($userReward->reward->description)
                                                <p class="card-text text-muted small">{{ $userReward->reward->description }}</p>
                                            @endif

                                            <div class="mb-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    Resgatado em: {{ $userReward->redeemed_at->format('d/m/Y H:i') }}
                                                </small>
                                            </div>

                                            <div class="mb-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-dollar-sign me-1"></i>
                                                    Total gasto: R$ {{ number_format($userReward->total_spent, 2, ',', '.') }}
                                                </small>
                                            </div>

                                            <!-- Detalhes da recompensa resgatada -->
                                            @if($userReward->redemption_data)
                                                <div class="alert alert-info py-2">
                                                    <small>
                                                        <strong>Detalhes do resgate:</strong><br>
                                                        @foreach($userReward->redemption_data as $key => $value)
                                                            {{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}<br>
                                                        @endforeach
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .border-success {
        border-color: #28a745 !important;
    }

    .border-secondary {
        border-color: #6c757d !important;
    }
</style>
@endsection



