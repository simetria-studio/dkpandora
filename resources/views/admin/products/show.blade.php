@extends('layouts.admin')

@section('title', 'Visualizar Produto - Painel Administrativo')
@section('page-title', 'Detalhes do Produto')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-eye me-2"></i>{{ $product->name }}
                    </h5>
                    <div>
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>Editar
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Voltar
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Imagem do Produto -->
                    <div class="col-md-4 mb-4">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="img-fluid rounded shadow">
                        @else
                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                 style="height: 300px;">
                                <i class="fas fa-image fa-3x text-white"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Informações do Produto -->
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="mb-3">{{ $product->name }}</h4>
                                <p class="text-muted mb-4">{{ $product->description }}</p>

                                <div class="mb-3">
                                    <h5 class="text-success mb-2">{{ $product->formatted_price }}</h5>
                                    <div class="d-flex gap-2 mb-3">
                                        @if($product->is_featured)
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-star me-1"></i>Destaque
                                            </span>
                                        @endif
                                        <span class="badge bg-{{ $product->rarity_color }}">
                                            {{ $product->rarity_label }}
                                        </span>
                                        <span class="badge bg-info">
                                            {{ $product->category_label }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3">
                                            <i class="fas fa-info-circle me-2"></i>Informações Técnicas
                                        </h6>

                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted">Estoque</small>
                                                <div class="fw-bold">
                                                    @if($product->stock > 0)
                                                        <span class="text-success">{{ $product->stock }} disponível</span>
                                                    @else
                                                        <span class="text-danger">Sem estoque</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Status</small>
                                                <div class="fw-bold">
                                                    @if($product->is_active)
                                                        <span class="text-success">
                                                            <i class="fas fa-check me-1"></i>Ativo
                                                        </span>
                                                    @else
                                                        <span class="text-secondary">
                                                            <i class="fas fa-pause me-1"></i>Inativo
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        @if($product->level_requirement)
                                            <div class="mt-3">
                                                <small class="text-muted">Nível Mínimo</small>
                                                <div class="fw-bold">
                                                    <i class="fas fa-star me-1"></i>{{ $product->level_requirement }}+
                                                </div>
                                            </div>
                                        @endif

                                        <div class="mt-3">
                                            <small class="text-muted">Criado em</small>
                                            <div class="fw-bold">{{ $product->created_at->format('d/m/Y H:i') }}</div>
                                        </div>

                                        @if($product->updated_at != $product->created_at)
                                            <div class="mt-2">
                                                <small class="text-muted">Última atualização</small>
                                                <div class="fw-bold">{{ $product->updated_at->format('d/m/Y H:i') }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Características Especiais -->
                        @if($product->features && count($product->features) > 0)
                            <div class="mt-4">
                                <h6>
                                    <i class="fas fa-list me-2"></i>Características Especiais
                                </h6>
                                <div class="row">
                                    @foreach($product->features as $feature)
                                        <div class="col-md-6 mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                <span>{{ trim($feature) }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Estatísticas do Produto -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card bg-gradient-primary text-white">
                            <div class="card-body">
                                <h6 class="card-title mb-3">
                                    <i class="fas fa-chart-bar me-2"></i>Estatísticas do Produto
                                </h6>
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <div class="fs-4 fw-bold">{{ $product->stock }}</div>
                                        <small>Em Estoque</small>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="fs-4 fw-bold">{{ $product->formatted_price }}</div>
                                        <small>Preço Atual</small>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="fs-4 fw-bold">{{ $product->category_label }}</div>
                                        <small>Categoria</small>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="fs-4 fw-bold">{{ $product->rarity_label }}</div>
                                        <small>Raridade</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-2"></i>Editar Produto
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}"
                                      method="POST"
                                      class="d-inline ms-2"
                                      onsubmit="return confirm('Tem certeza que deseja excluir este produto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash me-2"></i>Excluir Produto
                                    </button>
                                </form>
                            </div>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Voltar à Lista
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
