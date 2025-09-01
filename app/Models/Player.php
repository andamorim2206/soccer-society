<?php

namespace App\Models;

use App\Models\MatchGame;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'xp',
    ];

    /**
     * Relacionamento com partidas
     */
    public function matches()
    {
        return $this->belongsToMany(MatchGame::class, 'match_player')
                    ->withPivot('confirmed')
                    ->withTimestamps();
    }

    public static function getPositions(): array
    {
        return ['Goleiro', 'Zagueiro', 'Meio-campo', 'Atacante'];
    }
}