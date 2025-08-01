@extends('layouts.admin')

@section('title', 'Produtos - Painel Administrativo')
@section('page-title', 'Gerenciar Produtos')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <div class="d-flex align-items-center">
            <h4 class="mb-0 me-3">
                <i class="fas fa-box me-2"></i>Produtos
            </h4>
            <span class="badge bg-primary fs-6">{{ $products->total() }} produtos</span>
        </div>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Novo Produto
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Buscar</label>
                <input type="text"
                       class="form-control"
                       id="search"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Nome do produto...">
            </div>
            <div class="col-md-2">
                <label for="category" class="form-label">Categoria</label>
                <select class="form-select" id="category" name="category">
                    <option value="">Todas</option>
                    <option value="weapons" {{ request('category') == 'weapons' ? 'selected' : '' }}>Armas</option>
                    <option value="armor" {{ request('category') == 'armor' ? 'selected' : '' }}>Armaduras</option>
                    <option value="accessories" {{ request('category') == 'accessories' ? 'selected' : '' }}>Acessórios</option>
                    <option value="consumables" {{ request('category') == 'consumables' ? 'selected' : '' }}>Consumíveis</option>
                    <option value="materials" {{ request('category') == 'materials' ? 'selected' : '' }}>Materiais</option>
                    <option value="special" {{ request('category') == 'special' ? 'selected' : '' }}>Itens Especiais</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Todos</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativos</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativos</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="sort" class="form-label">Ordenar</label>
                <select class="form-select" id="sort" name="sort">
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Mais Recentes</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nome A-Z</option>
                    <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Preço</option>
                    <option value="stock" {{ request('sort') == 'stock' ? 'selected' : '' }}>Estoque</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="fas fa-search me-1"></i>Filtrar
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Limpar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Lista de Produtos -->
<div class="card">
    <div class="card-body p-0">
        @if($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="border-0">Produto</th>
                            <th class="border-0 text-center">Categoria</th>
                            <th class="border-0 text-center">Preço</th>
                            <th class="border-0 text-center">Estoque</th>
                            <th class="border-0 text-center">Status</th>
                            <th class="border-0 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                 alt="{{ $product->name }}"
                                                 class="rounded"
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-image text-white"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold">{{ $product->name }}</h6>
                                        <small class="text-muted">
                                            {{ Str::limit($product->description, 50) }}
                                        </small>
                                        <div class="mt-1">
                                            @if($product->is_featured)
                                                <span class="badge bg-warning text-dark me-1">
                                                    <i class="fas fa-star me-1"></i>Destaque
                                                </span>
                                            @endif
                                            <span class="badge bg-{{ $product->rarity_color }}">
                                                {{ $product->rarity_label }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $product->category_label }}</span>
                                @if($product->level_requirement)
                                    <br><small class="text-muted">Nível {{ $product->level_requirement }}+</small>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="fw-bold text-success">R$ {{ number_format($product->price, 2, ',', '.') }}</div>
                            </td>
                            <td class="text-center">
                                @if($product->stock > 0)
                                    <span class="badge bg-success">{{ $product->stock }} disponível</span>
                                @else
                                    <span class="badge bg-danger">Sem estoque</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($product->is_active)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Ativo
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-pause me-1"></i>Inativo
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.products.show', $product) }}"
                                       class="btn btn-sm btn-outline-info"
                                       title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="btn btn-sm btn-outline-warning"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Tem certeza que deseja excluir este produto?')">
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

            <!-- Paginação -->
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        Mostrando {{ $products->firstItem() }} a {{ $products->lastItem() }}
                        de {{ $products->total() }} produtos
                    </div>
                    <div>
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Nenhum produto encontrado</h5>
                <p class="text-muted">Comece criando seu primeiro produto</p>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Criar Produto
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
