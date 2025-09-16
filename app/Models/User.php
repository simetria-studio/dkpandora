<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function userRewards()
    {
        return $this->hasMany(UserReward::class);
    }

    public function getTotalSpentAttribute()
    {
        return $this->orders()
            ->where('status', 'completed')
            ->sum('total_amount');
    }

    public function getAvailableRewards()
    {
        $totalSpent = $this->total_spent;

        return Reward::where('is_active', true)
            ->where('required_amount', '<=', $totalSpent)
            ->where(function($query) {
                $query->whereNull('max_redemptions')
                      ->orWhereRaw('current_redemptions < max_redemptions');
            })
            ->whereDoesntHave('userRewards', function($query) {
                $query->where('user_id', $this->id);
            })
            ->orderBy('required_amount', 'asc')
            ->get();
    }

    public function getRedeemedRewards()
    {
        return $this->userRewards()
            ->with('reward')
            ->where('is_redeemed', true)
            ->get();
    }
}
