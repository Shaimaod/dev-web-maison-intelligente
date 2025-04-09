<?php

namespace App\Models;

use Illuminate\Auth\Notifications\VerifyEmail; // Correct import for VerifyEmail
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'surname',
        'username',
        'email',
        'password',
        'gender',
        'birthdate',
        'member_type',
        'photo',
        'level',
        'points',
        'email_verified_at',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birthdate' => 'date',
            'password' => 'hashed',
        ];
    }

    public function getAgeAttribute()
    {
        return $this->birthdate ? $this->birthdate->age : null;
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail()); // Use the built-in VerifyEmail notification
    }

    public function isAdmin()
    {
        return $this->role === 'admin'; // If you are using a 'role' column
    }

    public function updateLevel()
    {
        if ($this->points >= 0 && $this->points < 10) {
            $this->level = 'débutant';
        } elseif ($this->points >= 10 && $this->points < 20) {
            $this->level = 'intermédiaire';
        } elseif ($this->points >= 20 && $this->points < 30) {
            $this->level = 'avancé';
        } else {
            $this->level = 'expert';
        }

        $this->save();
    }
}
