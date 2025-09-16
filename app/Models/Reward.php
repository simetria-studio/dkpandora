<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'required_amount',
        'reward_type',
        'reward_data',
        'is_active',
        'max_redemptions',
        'current_redemptions',
    ];

    protected $casts = [
        'required_amount' => 'decimal:2',
        'reward_data' => 'array',
        'is_active' => 'boolean',
        'current_redemptions' => 'integer',
        'max_redemptions' => 'integer',
    ];

    public function userRewards()
    {
        return $this->hasMany(UserReward::class);
    }

    public function isAvailable()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->max_redemptions && $this->current_redemptions >= $this->max_redemptions) {
            return false;
        }

        return true;
    }

    public function canBeRedeemedBy($user)
    {
        if (!$this->isAvailable()) {
            return false;
        }

        // Verificar se o usuário já resgatou esta recompensa
        $existingReward = UserReward::where('user_id', $user->id)
            ->where('reward_id', $this->id)
            ->first();

        return !$existingReward;
    }
}
