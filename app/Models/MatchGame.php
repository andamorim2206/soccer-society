<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchGame extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    /**
     * Relacionamento com jogadores
     */
    public function players()
    {
        return $this->belongsToMany(Player::class, 'match_player')
                    ->withPivot('confirmed')
                    ->withTimestamps();
    }

    /**
     * Jogadores confirmados
     */
    public function confirmedPlayers()
    {
        return $this->players()->wherePivot('confirmed', true);
    }

    public function canPrepare(): bool
    {
        $players = $this->confirmedPlayers()->get();
        $goleiros = $players->where('position', 'Goleiro')->count();
        return $players->count() >= 12 && $goleiros >= 2;
    }

    public function cancel()
    {
        $this->status = 'cancelado';
        $this->save();
    }
}