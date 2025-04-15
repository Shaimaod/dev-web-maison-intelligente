<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'object_id',
        'energy_consumption',
        'duration',
        'status',
        'efficiency_score',
        'maintenance_needed',
        'notes',
        'recorded_at',
    ];

    protected $casts = [
        'energy_consumption' => 'float',
        'duration' => 'integer',
        'efficiency_score' => 'float',
        'maintenance_needed' => 'boolean',
        'recorded_at' => 'datetime',
    ];

    /**
     * Relation avec l'objet connecté
     */
    public function object()
    {
        return $this->belongsTo(ConnectedObject::class, 'object_id');
    }
} 