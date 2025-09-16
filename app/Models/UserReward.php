<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reward_id',
        'total_spent',
        'is_redeemed',
        'redeemed_at',
        'redemption_data',
    ];

    protected $casts = [
        'total_spent' => 'decimal:2',
        'is_redeemed' => 'boolean',
        'redeemed_at' => 'datetime',
        'redemption_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }

    public function redeem()
    {
        $this->update([
            'is_redeemed' => true,
            'redeemed_at' => now(),
        ]);

        // Incrementar contador de resgates da recompensa
        $this->reward->increment('current_redemptions');
    }
}
