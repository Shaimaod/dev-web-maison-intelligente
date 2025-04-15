<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectedObject extends Model
{
    use HasFactory;

    protected $table = 'connected_objects';

    protected $fillable = [
        'house_id',
        'name',
        'description',
        'category',
        'room',
        'brand',
        'type',
        'status',
        'connectivity',
        'battery',
        'mode',
        'is_automated',
        'image',
    ];

    protected $casts = [
        'settings' => 'array',
        'schedule' => 'array',
        'is_automated' => 'boolean'
    ];

    /**
     * Relation avec la maison à laquelle l'objet appartient
     */
    public function house()
    {
        return $this->belongsTo(House::class);
    }

    /**
     * Relation avec les utilisateurs qui peuvent contrôler l'objet
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_connected_object')
            ->withPivot('permissions')
            ->withTimestamps();
    }
}

