<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectedObject extends Model
{
    use HasFactory;

    protected $table = 'connected_objects';

    protected $fillable = [
        'name',
        'description',
        'category',
    ];
}

