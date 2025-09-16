<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\UserReward;
use App\Services\RewardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    protected $rewardService;

    public function __construct(RewardService $rewardService)
    {
        $this->rewardService = $rewardService;
    }

    /**
     * Exibir todas as recompensas disponíveis
     */
    public function index()
    {
        $user = Auth::user();

        // Verificar e criar recompensas elegíveis
        $this->rewardService->checkAndCreateEligibleRewards($user);

        // Buscar recompensas do usuário (não resgatadas)
        $userRewards = UserReward::with('reward')
            ->where('user_id', $user->id)
            ->where('is_redeemed', false)
            ->orderBy('created_at', 'desc')
            ->get();

        // Buscar recompensas resgatadas
        $redeemedRewards = UserReward::with('reward')
            ->where('user_id', $user->id)
            ->where('is_redeemed', true)
            ->orderBy('redeemed_at', 'desc')
            ->get();

        // Buscar todas as recompensas ativas para exibição
        $availableRewards = Reward::where('is_active', true)
            ->orderBy('required_amount', 'asc')
            ->get();

        return view('rewards.index', compact('userRewards', 'redeemedRewards', 'availableRewards', 'user'));
    }

    /**
     * Resgatar uma recompensa
     */
    public function redeem(Request $request, Reward $reward)
    {
        $user = Auth::user();

        try {
            // Verificar se o usuário tem esta recompensa
            $userReward = UserReward::where('user_id', $user->id)
                ->where('reward_id', $reward->id)
                ->where('is_redeemed', false)
                ->first();

            if (!$userReward) {
                return redirect()->back()->with('error', 'Você não possui esta recompensa ou ela já foi resgatada.');
            }

            // Resgatar a recompensa
            $this->rewardService->redeemReward($userReward);

            return redirect()->back()->with('success', 'Recompensa resgatada com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Verificar recompensas elegíveis via AJAX
     */
    public function checkEligibleRewards()
    {
        $user = Auth::user();

        try {
            $eligibleRewards = $this->rewardService->checkAndCreateEligibleRewards($user);

            return response()->json([
                'success' => true,
                'message' => 'Recompensas verificadas com sucesso.',
                'new_rewards_count' => $eligibleRewards->count(),
                'rewards' => $eligibleRewards->map(function($reward) {
                    return [
                        'id' => $reward->id,
                        'name' => $reward->name,
                        'description' => $reward->description,
                        'reward_type' => $reward->reward_type,
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao verificar recompensas: ' . $e->getMessage()
            ], 500);
        }
    }
}
