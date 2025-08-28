<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'xp',
        'confirmed',
        'team_id',
    ];

    /**
     * Jogador pertence a um time
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}