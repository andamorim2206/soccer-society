<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MatchPlayer extends Pivot
{
    protected $table = 'match_player';

    protected $fillable = [
        'match_id',
        'player_id',
        'confirmed',
    ];
}