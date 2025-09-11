<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $query = Product::query();

        // Filtros
        if (request('search')) {
            $query->where('name', 'like', '%' . request('search') . '%')
                  ->orWhere('description', 'like', '%' . request('search') . '%');
        }

        if (request('category')) {
            $query->where('category', request('category'));
        }

        if (request('status')) {
            if (request('status') === 'active') {
                $query->where('is_active', true);
            } elseif (request('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Ordenação
        $sort = request('sort', 'created_at');
        $direction = 'desc';

        if (in_array($sort, ['name', 'price', 'stock'])) {
            $direction = $sort === 'name' ? 'asc' : 'desc';
        }

        $query->orderBy($sort, $direction);

        $products = $query->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'rarity' => 'required|in:common,uncommon,rare,epic,legendary,mythic,divine,transcendent',
            'level_requirement' => 'nullable|integer|min:1|max:200',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'features' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();

        // Processar características especiais
        if ($request->filled('features')) {
            $features = array_map('trim', explode(',', $request->features));
            $data['features'] = array_filter($features);
        }

        // Processar checkboxes
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active');

        // Upload da imagem
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        // Definir tipo como 'item' por padrão
        $data['type'] = 'item';

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto criado com sucesso!');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'rarity' => 'required|in:common,uncommon,rare,epic,legendary,mythic,divine,transcendent',
            'level_requirement' => 'nullable|integer|min:1|max:200',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'features' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();

        // Processar características especiais
        if ($request->filled('features')) {
            $features = array_map('trim', explode(',', $request->features));
            $data['features'] = array_filter($features);
        }

        // Processar checkboxes
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active');

        // Upload da imagem
        if ($request->hasFile('image')) {
            // Deletar imagem antiga se existir
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto excluído com sucesso!');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'integer|exists:products,id'
        ]);

        $action = $request->action;
        $productIds = $request->product_ids;
        $count = count($productIds);

        // Debug: Log dos IDs recebidos
        Log::info('Bulk Action Debug', [
            'action' => $action,
            'product_ids' => $productIds,
            'count' => $count
        ]);

        switch ($action) {
            case 'activate':
                $updated = Product::whereIn('id', $productIds)->update(['is_active' => true]);
                Log::info('Activate result', ['updated_count' => $updated]);
                $message = "{$count} produto" . ($count > 1 ? 's' : '') . " ativado" . ($count > 1 ? 's' : '') . " com sucesso!";
                break;

            case 'deactivate':
                $updated = Product::whereIn('id', $productIds)->update(['is_active' => false]);
                Log::info('Deactivate result', ['updated_count' => $updated]);
                $message = "{$count} produto" . ($count > 1 ? 's' : '') . " desativado" . ($count > 1 ? 's' : '') . " com sucesso!";
                break;

            case 'delete':
                // Deletar imagens dos produtos antes de excluir
                $products = Product::whereIn('id', $productIds)->get();
                Log::info('Products to delete', ['products_count' => $products->count()]);

                foreach ($products as $product) {
                    if ($product->image) {
                        Storage::disk('public')->delete($product->image);
                    }
                }

                $deleted = Product::whereIn('id', $productIds)->delete();
                Log::info('Delete result', ['deleted_count' => $deleted]);
                $message = "{$count} produto" . ($count > 1 ? 's' : '') . " excluído" . ($count > 1 ? 's' : '') . " com sucesso!";
                break;
        }

        return redirect()->route('admin.products.index')
            ->with('success', $message);
    }
}
