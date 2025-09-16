<?php

namespace App\Services;

use App\Models\Reward;
use App\Models\User;
use App\Models\UserReward;
use Illuminate\Support\Facades\DB;

class RewardService
{
    /**
     * Verificar e criar recompensas elegíveis para um usuário
     */
    public function checkAndCreateEligibleRewards(User $user)
    {
        $totalSpent = $user->total_spent;

        $eligibleRewards = Reward::where('is_active', true)
            ->where('required_amount', '<=', $totalSpent)
            ->where(function($query) {
                $query->whereNull('max_redemptions')
                      ->orWhereRaw('current_redemptions < max_redemptions');
            })
            ->whereDoesntHave('userRewards', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();

        foreach ($eligibleRewards as $reward) {
            UserReward::create([
                'user_id' => $user->id,
                'reward_id' => $reward->id,
                'total_spent' => $totalSpent,
            ]);
        }

        return $eligibleRewards;
    }

    /**
     * Resgatar uma recompensa
     */
    public function redeemReward(UserReward $userReward)
    {
        if ($userReward->is_redeemed) {
            throw new \Exception('Esta recompensa já foi resgatada.');
        }

        if (!$userReward->reward->isAvailable()) {
            throw new \Exception('Esta recompensa não está mais disponível.');
        }

        DB::transaction(function () use ($userReward) {
            $userReward->redeem();

            // Aqui você pode adicionar lógica específica baseada no tipo de recompensa
            $this->processRewardRedemption($userReward);
        });

        return $userReward;
    }

    /**
     * Processar o resgate da recompensa baseado no tipo
     */
    protected function processRewardRedemption(UserReward $userReward)
    {
        $reward = $userReward->reward;

        switch ($reward->reward_type) {
            case 'discount':
                $this->processDiscountReward($userReward);
                break;
            case 'product':
                $this->processProductReward($userReward);
                break;
            case 'bonus':
                $this->processBonusReward($userReward);
                break;
            case 'cashback':
                $this->processCashbackReward($userReward);
                break;
        }
    }

    /**
     * Processar recompensa de desconto
     */
    protected function processDiscountReward(UserReward $userReward)
    {
        $rewardData = $userReward->reward->reward_data;

        // Salvar dados do desconto no redemption_data
        $userReward->update([
            'redemption_data' => [
                'discount_percentage' => $rewardData['discount_percentage'] ?? 0,
                'discount_amount' => $rewardData['discount_amount'] ?? 0,
                'expires_at' => now()->addDays($rewardData['valid_days'] ?? 30),
            ]
        ]);
    }

    /**
     * Processar recompensa de produto
     */
    protected function processProductReward(UserReward $userReward)
    {
        $rewardData = $userReward->reward->reward_data;

        // Salvar dados do produto no redemption_data
        $userReward->update([
            'redemption_data' => [
                'product_id' => $rewardData['product_id'] ?? null,
                'product_name' => $rewardData['product_name'] ?? '',
                'quantity' => $rewardData['quantity'] ?? 1,
            ]
        ]);
    }

    /**
     * Processar recompensa de bônus
     */
    protected function processBonusReward(UserReward $userReward)
    {
        $rewardData = $userReward->reward->reward_data;

        // Salvar dados do bônus no redemption_data
        $userReward->update([
            'redemption_data' => [
                'bonus_type' => $rewardData['bonus_type'] ?? '',
                'bonus_value' => $rewardData['bonus_value'] ?? 0,
                'description' => $rewardData['description'] ?? '',
            ]
        ]);
    }

    /**
     * Processar recompensa de cashback
     */
    protected function processCashbackReward(UserReward $userReward)
    {
        $rewardData = $userReward->reward->reward_data;

        // Salvar dados do cashback no redemption_data
        $userReward->update([
            'redemption_data' => [
                'cashback_amount' => $rewardData['cashback_amount'] ?? 0,
                'cashback_percentage' => $rewardData['cashback_percentage'] ?? 0,
            ]
        ]);
    }

    /**
     * Obter estatísticas das recompensas
     */
    public function getRewardStats()
    {
        return [
            'total_rewards' => Reward::count(),
            'active_rewards' => Reward::where('is_active', true)->count(),
            'total_redemptions' => UserReward::where('is_redeemed', true)->count(),
            'pending_redemptions' => UserReward::where('is_redeemed', false)->count(),
            'most_popular_reward' => Reward::withCount('userRewards')
                ->orderBy('user_rewards_count', 'desc')
                ->first(),
        ];
    }
}

