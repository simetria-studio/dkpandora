@extends('layouts.admin')

@section('title', 'Editar Produto - Painel Administrativo')
@section('page-title', 'Editar Produto')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Editar Produto: {{ $product->name }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-tag me-1"></i>Nome do Produto
                                </label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $product->name) }}"
                                       placeholder="Ex: Espada Lendária +15"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="price" class="form-label">
                                    <i class="fas fa-dollar-sign me-1"></i>Preço
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number"
                                           class="form-control @error('price') is-invalid @enderror"
                                           id="price"
                                           name="price"
                                           value="{{ old('price', $product->price) }}"
                                           step="0.01"
                                           min="0"
                                           placeholder="0.00"
                                           required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">
                            <i class="fas fa-info-circle me-1"></i>Descrição
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="4"
                                  placeholder="Descreva os detalhes do produto, benefícios, características especiais..."
                                  required>{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category" class="form-label">
                                    <i class="fas fa-folder me-1"></i>Categoria
                                </label>
                                <select class="form-select @error('category') is-invalid @enderror"
                                        id="category"
                                        name="category"
                                        required>
                                    <option value="">Selecione uma categoria</option>
                                    <option value="weapons" {{ old('category', $product->category) == 'weapons' ? 'selected' : '' }}>Armas</option>
                                    <option value="armor" {{ old('category', $product->category) == 'armor' ? 'selected' : '' }}>Armaduras</option>
                                    <option value="accessories" {{ old('category', $product->category) == 'accessories' ? 'selected' : '' }}>Acessórios</option>
                                    <option value="consumables" {{ old('category', $product->category) == 'consumables' ? 'selected' : '' }}>Consumíveis</option>
                                    <option value="materials" {{ old('category', $product->category) == 'materials' ? 'selected' : '' }}>Materiais</option>
                                    <option value="special" {{ old('category', $product->category) == 'special' ? 'selected' : '' }}>Itens Especiais</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stock" class="form-label">
                                    <i class="fas fa-boxes me-1"></i>Estoque Disponível
                                </label>
                                <input type="number"
                                       class="form-control @error('stock') is-invalid @enderror"
                                       id="stock"
                                       name="stock"
                                       value="{{ old('stock', $product->stock) }}"
                                       min="0"
                                       required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="level_requirement" class="form-label">
                                    <i class="fas fa-star me-1"></i>Nível Mínimo
                                </label>
                                <input type="number"
                                       class="form-control @error('level_requirement') is-invalid @enderror"
                                       id="level_requirement"
                                       name="level_requirement"
                                       value="{{ old('level_requirement', $product->level_requirement) }}"
                                       min="1"
                                       max="200">
                                <div class="form-text">Deixe em branco se não houver requisito</div>
                                @error('level_requirement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rarity" class="form-label">
                                    <i class="fas fa-gem me-1"></i>Raridade
                                </label>
                                <select class="form-select @error('rarity') is-invalid @enderror"
                                        id="rarity"
                                        name="rarity"
                                        required>
                                    <option value="common" {{ old('rarity', $product->rarity) == 'common' ? 'selected' : '' }}>🔘 Cinza</option>
                                    <option value="uncommon" {{ old('rarity', $product->rarity) == 'uncommon' ? 'selected' : '' }}>⚪ Branco</option>
                                    <option value="rare" {{ old('rarity', $product->rarity) == 'rare' ? 'selected' : '' }}>🟢 Verde</option>
                                    <option value="epic" {{ old('rarity', $product->rarity) == 'epic' ? 'selected' : '' }}>🔵 Azul</option>
                                    <option value="legendary" {{ old('rarity', $product->rarity) == 'legendary' ? 'selected' : '' }}>🟠 Laranja</option>
                                    <option value="mythic" {{ old('rarity', $product->rarity) == 'mythic' ? 'selected' : '' }}>🟡 Amarelo</option>
                                    <option value="divine" {{ old('rarity', $product->rarity) == 'divine' ? 'selected' : '' }}>🟣 Roxo</option>
                                    <option value="transcendent" {{ old('rarity', $product->rarity) == 'transcendent' ? 'selected' : '' }}>🔴 Vermelho</option>
                                </select>
                                @error('rarity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">
                            <i class="fas fa-image me-1"></i>Imagem do Produto
                        </label>

                        @if($product->image)
                            <div class="mb-3">
                                <p class="text-muted mb-2">Imagem atual:</p>
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="img-thumbnail"
                                     style="max-width: 200px; max-height: 200px;">
                            </div>
                        @endif

                        <input type="file"
                               class="form-control @error('image') is-invalid @enderror"
                               id="image"
                               name="image"
                               accept="image/*">
                        <div class="form-text">
                            Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB. Deixe em branco para manter a imagem atual.
                        </div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="features" class="form-label">
                            <i class="fas fa-list me-1"></i>Características Especiais
                        </label>
                        <textarea class="form-control @error('features') is-invalid @enderror"
                                  id="features"
                                  name="features"
                                  rows="3"
                                  placeholder="Liste as características especiais do item (opcional)">{{ old('features', $product->features ? implode(', ', $product->features) : '') }}</textarea>
                        <div class="form-text">
                            Separe cada característica com vírgula. Ex: +15 de Ataque, +10% Crítico, Resistência ao Fogo
                        </div>
                        @error('features')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="is_featured"
                                           name="is_featured"
                                           value="1"
                                           {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        <i class="fas fa-star me-1"></i>Produto em Destaque
                                    </label>
                                </div>
                                <div class="form-text">
                                    Produtos em destaque aparecem na seção principal
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                           {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <i class="fas fa-check me-1"></i>Produto Ativo
                                    </label>
                                </div>
                                <div class="form-text">
                                    Produtos inativos não aparecem no catálogo
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Atualizar Produto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview da imagem
    const imageInput = document.getElementById('image');
    const previewContainer = document.createElement('div');
    previewContainer.className = 'mt-2';
    previewContainer.style.display = 'none';

    imageInput.parentNode.appendChild(previewContainer);

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContainer.innerHTML = `
                    <p class="text-muted mb-2">Nova imagem:</p>
                    <img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                `;
                previewContainer.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
        }
    });
});
</script>
@endsection
