@extends('layouts.admin')

@section('title', 'Criar Produto - Painel Administrativo')
@section('page-title', 'Criar Novo Produto')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>Informações do Produto
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

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
                                       value="{{ old('name') }}"
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
                                           value="{{ old('price') }}"
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
                                  required>{{ old('description') }}</textarea>
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
                                    <option value="weapons" {{ old('category') == 'weapons' ? 'selected' : '' }}>Armas</option>
                                    <option value="armor" {{ old('category') == 'armor' ? 'selected' : '' }}>Armaduras</option>
                                    <option value="accessories" {{ old('category') == 'accessories' ? 'selected' : '' }}>Acessórios</option>
                                    <option value="consumables" {{ old('category') == 'consumables' ? 'selected' : '' }}>Consumíveis</option>
                                    <option value="materials" {{ old('category') == 'materials' ? 'selected' : '' }}>Materiais</option>
                                    <option value="special" {{ old('category') == 'special' ? 'selected' : '' }}>Itens Especiais</option>
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
                                       value="{{ old('stock', 1) }}"
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
                                       value="{{ old('level_requirement', 1) }}"
                                       min="1"
                                       max="200">
                                <div class="form-text text-light">Deixe em branco se não houver requisito</div>
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
                                    <option value="common" {{ old('rarity') == 'common' ? 'selected' : '' }}>🔘 Cinza</option>
                                    <option value="uncommon" {{ old('rarity') == 'uncommon' ? 'selected' : '' }}>⚪ Branco</option>
                                    <option value="rare" {{ old('rarity') == 'rare' ? 'selected' : '' }}>🟢 Verde</option>
                                    <option value="epic" {{ old('rarity') == 'epic' ? 'selected' : '' }}>🔵 Azul</option>
                                    <option value="legendary" {{ old('rarity') == 'legendary' ? 'selected' : '' }}>🟠 Laranja</option>
                                    <option value="mythic" {{ old('rarity') == 'mythic' ? 'selected' : '' }}>🟡 Amarelo</option>
                                    <option value="divine" {{ old('rarity') == 'divine' ? 'selected' : '' }}>🟣 Roxo</option>
                                    <option value="transcendent" {{ old('rarity') == 'transcendent' ? 'selected' : '' }}>🔴 Vermelho</option>
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
                        <input type="file"
                               class="form-control @error('image') is-invalid @enderror"
                               id="image"
                               name="image"
                               accept="image/*">
                        <div class="form-text text-light">
                            Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB
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
                                  placeholder="Liste as características especiais do item (opcional)">{{ old('features') }}</textarea>
                        <div class="form-text text-light">
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
                                           {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        <i class="fas fa-star me-1"></i>Produto em Destaque
                                    </label>
                                </div>
                                <div class="form-text text-light">
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
                                           {{ old('is_active', '1') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <i class="fas fa-check me-1"></i>Produto Ativo
                                    </label>
                                </div>
                                <div class="form-text text-light">
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
                            <i class="fas fa-save me-2"></i>Criar Produto
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
