<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'daily_goal',
        'weekly_goal',
        'monthly_goal'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
