<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Models\UserReward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RewardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rewards = Reward::withCount('userRewards')->orderBy('required_amount', 'asc')->get();

        return view('admin.rewards.index', compact('rewards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rewards.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'required_amount' => 'required|numeric|min:0',
            'reward_type' => 'required|string|in:discount,product,bonus,cashback',
            'reward_data' => 'nullable|array',
            'max_redemptions' => 'nullable|integer|min:1',
        ]);

        Reward::create($request->all());

        return redirect()->route('admin.rewards.index')
            ->with('success', 'Recompensa criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reward $reward)
    {
        $reward->load(['userRewards.user']);

        return view('admin.rewards.show', compact('reward'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reward $reward)
    {
        return view('admin.rewards.edit', compact('reward'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reward $reward)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'required_amount' => 'required|numeric|min:0',
            'reward_type' => 'required|string|in:discount,product,bonus,cashback',
            'reward_data' => 'nullable|array',
            'max_redemptions' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $reward->update($request->all());

        return redirect()->route('admin.rewards.index')
            ->with('success', 'Recompensa atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reward $reward)
    {
        // Verificar se há resgates associados
        if ($reward->userRewards()->count() > 0) {
            return redirect()->route('admin.rewards.index')
                ->with('error', 'Não é possível excluir uma recompensa que já foi resgatada por usuários.');
        }

        $reward->delete();

        return redirect()->route('admin.rewards.index')
            ->with('success', 'Recompensa excluída com sucesso!');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Reward $reward)
    {
        $reward->update(['is_active' => !$reward->is_active]);

        $status = $reward->is_active ? 'ativada' : 'desativada';

        return redirect()->route('admin.rewards.index')
            ->with('success', "Recompensa {$status} com sucesso!");
    }
}
