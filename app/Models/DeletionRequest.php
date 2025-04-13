<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeletionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'object_id',
        'user_id',
        'reason',
        'status'
    ];

    /**
     * Relation avec l'objet connecté
     */
    public function object()
    {
        return $this->belongsTo(ConnectedObject::class, 'object_id');
    }

    /**
     * Relation avec l'utilisateur qui a fait la demande
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
