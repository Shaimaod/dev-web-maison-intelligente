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
        $levels = config('levels.levels');
        $currentLevel = null;
        $nextLevel = null;

        // Trouver le niveau actuel et le prochain niveau
        foreach ($levels as $level => $config) {
            if ($this->points >= $config['min_points'] && 
                ($config['max_points'] === null || $this->points <= $config['max_points'])) {
                $currentLevel = $level;
                $nextLevel = $config['next_level'];
                break;
            }
        }

        // Mettre à jour le niveau
        $this->level = $currentLevel;

        // Si l'utilisateur atteint le niveau expert, le promouvoir en admin
        if ($currentLevel === 'expert') {
            $this->role = 'admin';
        }
    }

    /**
     * Ajoute des points à l'utilisateur et met à jour son niveau
     */
    public function addPoints($action)
    {
        $points = config('levels.points.' . $action, 0);
        if ($points > 0) {
            $this->points += $points;
            $this->updateLevel();
            $this->save();
        }
        return $this;
    }

    /**
     * Récupère les informations sur le prochain niveau
     */
    public function getNextLevelInfo()
    {
        $levels = config('levels.levels');
        $currentLevel = $this->level ?? 'débutant'; // Si pas de niveau, on considère débutant
        
        if (isset($levels[$currentLevel])) {
            $nextLevel = $levels[$currentLevel]['next_level'];
            if ($nextLevel) {
                return [
                    'name' => $nextLevel,
                    'points_needed' => $levels[$currentLevel]['points_needed'],
                    'current_points' => $this->points,
                    'points_remaining' => $levels[$currentLevel]['points_needed'] - $this->points,
                    'progress_percent' => min(($this->points / $levels[$currentLevel]['points_needed']) * 100, 100)
                ];
            }
        }
        
        // Si on est au niveau expert ou si le niveau n'est pas trouvé
        return [
            'name' => null,
            'points_needed' => null,
            'current_points' => $this->points,
            'points_remaining' => 0,
            'progress_percent' => 100
        ];
    }

    /**
     * Vérifie si l'utilisateur peut passer au niveau suivant
     */
    public function canLevelUp()
    {
        $nextLevelInfo = $this->getNextLevelInfo();
        return $nextLevelInfo && $this->points >= $nextLevelInfo['points_needed'];
    }

    /**
     * Récupère les objets connectés de l'utilisateur
     */
    public function connectedObjects()
    {
        return $this->hasMany(ConnectedObject::class, 'user_id');
    }

    /**
     * Récupère les logs d'activité de l'utilisateur
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Relation avec la maison de l'utilisateur
     */
    public function house()
    {
        return $this->belongsTo(House::class);
    }

    /**
     * Vérifie si l'utilisateur est propriétaire de la maison
     */
    public function isHouseOwner()
    {
        return $this->house_role === 'owner';
    }

    /**
     * Vérifie si l'utilisateur est administrateur de la maison
     */
    public function isHouseAdmin()
    {
        return $this->house_role === 'admin';
    }

    /**
     * Vérifie si l'utilisateur est invité de la maison
     */
    public function isHouseGuest()
    {
        return $this->house_role === 'guest';
    }
}
