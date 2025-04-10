<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;

    protected $table = 'house';

    protected $fillable = [
        'name',
        'address',
        'description'
    ];

    /**
     * Relation avec les utilisateurs de la maison
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relation avec les objets connectés de la maison
     */
    public function connectedObjects()
    {
        return $this->hasMany(ConnectedObject::class);
    }

    /**
     * Récupère les propriétaires de la maison
     */
    public function owners()
    {
        return $this->users()->where('house_role', 'owner');
    }

    /**
     * Récupère les administrateurs de la maison
     */
    public function admins()
    {
        return $this->users()->where('house_role', 'admin');
    }

    /**
     * Récupère les invités de la maison
     */
    public function guests()
    {
        return $this->users()->where('house_role', 'guest');
    }
} 