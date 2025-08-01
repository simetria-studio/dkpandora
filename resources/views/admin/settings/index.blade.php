@extends('layouts.admin')

@section('title', 'Configurações - Painel Administrativo')
@section('page-title', 'Configurações')

@section('content')
<div class="row">
    <!-- Configurações de Gold -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-coins me-2"></i>Configurações de Gold
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update-gold') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="available_gold" class="form-label">
                            <i class="fas fa-database me-1"></i>Gold Disponível
                        </label>
                        <input type="number" 
                               class="form-control @error('available_gold') is-invalid @enderror" 
                               id="available_gold" 
                               name="available_gold" 
                               value="{{ App\Models\Setting::get('available_gold', 1000000000) }}"
                               min="0" 
                               required>
                        <div class="form-text">
                            Quantidade total de gold disponível para venda
                        </div>
                        @error('available_gold')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="gold_price_per_1000" class="form-label">
                            <i class="fas fa-dollar-sign me-1"></i>Preço por 1000 Gold
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" 
                                   class="form-control @error('gold_price_per_1000') is-invalid @enderror" 
                                   id="gold_price_per_1000" 
                                   name="gold_price_per_1000" 
                                   value="{{ App\Models\Setting::get('gold_price_per_1000', '0.12') }}"
                                   step="0.01" 
                                   min="0" 
                                   required>
                        </div>
                        <div class="form-text">
                            Preço em reais por 1000 unidades de gold
                        </div>
                        @error('gold_price_per_1000')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gold_min_purchase" class="form-label">
                                    <i class="fas fa-arrow-down me-1"></i>Compra Mínima
                                </label>
                                <input type="number" 
                                       class="form-control @error('gold_min_purchase') is-invalid @enderror" 
                                       id="gold_min_purchase" 
                                       name="gold_min_purchase" 
                                       value="{{ App\Models\Setting::get('gold_min_purchase', 1000) }}"
                                       min="1000" 
                                       step="1000" 
                                       required>
                                <div class="form-text">Quantidade mínima</div>
                                @error('gold_min_purchase')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gold_max_purchase" class="form-label">
                                    <i class="fas fa-arrow-up me-1"></i>Compra Máxima
                                </label>
                                <input type="number" 
                                       class="form-control @error('gold_max_purchase') is-invalid @enderror" 
                                       id="gold_max_purchase" 
                                       name="gold_max_purchase" 
                                       value="{{ App\Models\Setting::get('gold_max_purchase', 1000000) }}"
                                       min="1000" 
                                       step="1000" 
                                       required>
                                <div class="form-text">Quantidade máxima</div>
                                @error('gold_max_purchase')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Salvar Configurações de Gold
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Configurações Gerais -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cog me-2"></i>Configurações Gerais
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="site_name" class="form-label">
                            <i class="fas fa-tag me-1"></i>Nome do Site
                        </label>
                        <input type="text" 
                               class="form-control @error('settings.site_name') is-invalid @enderror" 
                               id="site_name" 
                               name="settings[site_name]" 
                               value="{{ App\Models\Setting::get('site_name', 'DK Pandora') }}"
                               required>
                        @error('settings.site_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="site_description" class="form-label">
                            <i class="fas fa-info-circle me-1"></i>Descrição do Site
                        </label>
                        <textarea class="form-control @error('settings.site_description') is-invalid @enderror" 
                                  id="site_description" 
                                  name="settings[site_description]" 
                                  rows="3">{{ App\Models\Setting::get('site_description', 'Sua loja confiável para itens e gold do Grand Fantasia Violet') }}</textarea>
                        @error('settings.site_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="delivery_time" class="form-label">
                            <i class="fas fa-clock me-1"></i>Tempo de Entrega (minutos)
                        </label>
                        <input type="number" 
                               class="form-control @error('settings.delivery_time') is-invalid @enderror" 
                               id="delivery_time" 
                               name="settings[delivery_time]" 
                               value="{{ App\Models\Setting::get('delivery_time', 30) }}"
                               min="1" 
                               required>
                        @error('settings.delivery_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="support_whatsapp" class="form-label">
                            <i class="fab fa-whatsapp me-1"></i>WhatsApp de Suporte
                        </label>
                        <input type="text" 
                               class="form-control @error('settings.support_whatsapp') is-invalid @enderror" 
                               id="support_whatsapp" 
                               name="settings[support_whatsapp]" 
                               value="{{ App\Models\Setting::get('support_whatsapp', '+5511999999999') }}"
                               placeholder="+5511999999999">
                        @error('settings.support_whatsapp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Salvar Configurações Gerais
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Resumo das Configurações -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Resumo das Configurações
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <div class="fs-4 fw-bold text-primary">
                                {{ number_format(App\Models\Setting::get('available_gold', 0), 0, ',', '.') }}
                            </div>
                            <small class="text-muted">Gold Disponível</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <div class="fs-4 fw-bold text-success">
                                R$ {{ App\Models\Setting::get('gold_price_per_1000', '0.12') }}
                            </div>
                            <small class="text-muted">Preço por 1000 Gold</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <div class="fs-4 fw-bold text-info">
                                {{ number_format(App\Models\Setting::get('gold_min_purchase', 1000), 0, ',', '.') }}
                            </div>
                            <small class="text-muted">Compra Mínima</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <div class="fs-4 fw-bold text-warning">
                                {{ number_format(App\Models\Setting::get('gold_max_purchase', 1000000), 0, ',', '.') }}
                            </div>
                            <small class="text-muted">Compra Máxima</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 