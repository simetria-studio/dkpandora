@extends('layouts.admin')

@section('title', 'Criar Recompensa - Painel Administrativo')
@section('page-title', 'Criar Nova Recompensa')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-gift me-2"></i>Informações da Recompensa
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.rewards.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome da Recompensa *</label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name') }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="required_amount" class="form-label">Valor Mínimo Gasto (R$) *</label>
                                <input type="number"
                                       class="form-control @error('required_amount') is-invalid @enderror"
                                       id="required_amount"
                                       name="required_amount"
                                       value="{{ old('required_amount') }}"
                                       step="0.01"
                                       min="0"
                                       required>
                                @error('required_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reward_type" class="form-label">Tipo de Recompensa *</label>
                                <select class="form-select @error('reward_type') is-invalid @enderror"
                                        id="reward_type"
                                        name="reward_type"
                                        required>
                                    <option value="">Selecione o tipo</option>
                                    <option value="discount" {{ old('reward_type') == 'discount' ? 'selected' : '' }}>
                                        Desconto
                                    </option>
                                    <option value="product" {{ old('reward_type') == 'product' ? 'selected' : '' }}>
                                        Produto Gratuito
                                    </option>
                                    <option value="bonus" {{ old('reward_type') == 'bonus' ? 'selected' : '' }}>
                                        Bônus Especial
                                    </option>
                                    <option value="cashback" {{ old('reward_type') == 'cashback' ? 'selected' : '' }}>
                                        Cashback
                                    </option>
                                </select>
                                @error('reward_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_redemptions" class="form-label">Limite de Resgates</label>
                                <input type="number"
                                       class="form-control @error('max_redemptions') is-invalid @enderror"
                                       id="max_redemptions"
                                       name="max_redemptions"
                                       value="{{ old('max_redemptions') }}"
                                       min="1">
                                <div class="form-text">Deixe em branco para resgates ilimitados</div>
                                @error('max_redemptions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Configurações específicas por tipo -->
                    <div id="reward-config" style="display: none;">
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">Configurações da Recompensa</h6>
                            </div>
                            <div class="card-body">
                                <div id="discount-config" class="reward-type-config" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="discount_percentage" class="form-label">Percentual de Desconto (%)</label>
                                                <input type="number"
                                                       class="form-control"
                                                       id="discount_percentage"
                                                       name="reward_data[discount_percentage]"
                                                       min="1"
                                                       max="100">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="discount_amount" class="form-label">Valor Fixo de Desconto (R$)</label>
                                                <input type="number"
                                                       class="form-control"
                                                       id="discount_amount"
                                                       name="reward_data[discount_amount]"
                                                       step="0.01"
                                                       min="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="valid_days" class="form-label">Válido por (dias)</label>
                                        <input type="number"
                                               class="form-control"
                                               id="valid_days"
                                               name="reward_data[valid_days]"
                                               value="30"
                                               min="1">
                                    </div>
                                </div>

                                <div id="product-config" class="reward-type-config" style="display: none;">
                                    <div class="mb-3">
                                        <label for="product_name" class="form-label">Nome do Produto</label>
                                        <input type="text"
                                               class="form-control"
                                               id="product_name"
                                               name="reward_data[product_name]">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="product_id" class="form-label">ID do Produto</label>
                                                <input type="number"
                                                       class="form-control"
                                                       id="product_id"
                                                       name="reward_data[product_id]"
                                                       min="1">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="quantity" class="form-label">Quantidade</label>
                                                <input type="number"
                                                       class="form-control"
                                                       id="quantity"
                                                       name="reward_data[quantity]"
                                                       value="1"
                                                       min="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="bonus-config" class="reward-type-config" style="display: none;">
                                    <div class="mb-3">
                                        <label for="bonus_type" class="form-label">Tipo de Bônus</label>
                                        <input type="text"
                                               class="form-control"
                                               id="bonus_type"
                                               name="reward_data[bonus_type]"
                                               placeholder="Ex: Moedas extras, XP bonus, etc.">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="bonus_value" class="form-label">Valor do Bônus</label>
                                                <input type="number"
                                                       class="form-control"
                                                       id="bonus_value"
                                                       name="reward_data[bonus_value]"
                                                       min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="bonus_description" class="form-label">Descrição do Bônus</label>
                                                <input type="text"
                                                       class="form-control"
                                                       id="bonus_description"
                                                       name="reward_data[description]">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="cashback-config" class="reward-type-config" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="cashback_percentage" class="form-label">Percentual de Cashback (%)</label>
                                                <input type="number"
                                                       class="form-control"
                                                       id="cashback_percentage"
                                                       name="reward_data[cashback_percentage]"
                                                       min="1"
                                                       max="100">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="cashback_amount" class="form-label">Valor Fixo de Cashback (R$)</label>
                                                <input type="number"
                                                       class="form-control"
                                                       id="cashback_amount"
                                                       name="reward_data[cashback_amount]"
                                                       step="0.01"
                                                       min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.rewards.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Criar Recompensa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Informações
                </h6>
            </div>
            <div class="card-body">
                <h6>Tipos de Recompensa:</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong>Desconto:</strong> Percentual ou valor fixo de desconto em compras futuras
                    </li>
                    <li class="mb-2">
                        <strong>Produto:</strong> Item gratuito do catálogo
                    </li>
                    <li class="mb-2">
                        <strong>Bônus:</strong> Benefícios especiais no jogo
                    </li>
                    <li class="mb-2">
                        <strong>Cashback:</strong> Retorno em dinheiro para próximas compras
                    </li>
                </ul>

                <hr>

                <h6>Dicas:</h6>
                <ul class="list-unstyled">
                    <li class="mb-1">• Defina valores progressivos (R$ 100, R$ 500, R$ 1000)</li>
                    <li class="mb-1">• Use limites para criar exclusividade</li>
                    <li class="mb-1">• Descreva claramente os benefícios</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rewardTypeSelect = document.getElementById('reward_type');
    const rewardConfig = document.getElementById('reward-config');
    const typeConfigs = document.querySelectorAll('.reward-type-config');

    rewardTypeSelect.addEventListener('change', function() {
        const selectedType = this.value;

        // Esconder todas as configurações
        typeConfigs.forEach(config => {
            config.style.display = 'none';
        });

        // Mostrar configuração do tipo selecionado
        if (selectedType) {
            rewardConfig.style.display = 'block';
            const targetConfig = document.getElementById(selectedType + '-config');
            if (targetConfig) {
                targetConfig.style.display = 'block';
            }
        } else {
            rewardConfig.style.display = 'none';
        }
    });

    // Mostrar configuração se já houver valor selecionado
    if (rewardTypeSelect.value) {
        rewardTypeSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection



