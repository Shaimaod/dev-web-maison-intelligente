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
}
