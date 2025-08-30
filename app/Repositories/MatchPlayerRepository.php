<?php

namespace App\Repositories;


use App\Models\MatchPlayer;

class MatchPlayerRepository implements MatchPlayerRepositoryInterface 
{
   public function updateOrCreate(int $matchId, int $playerId): void{
         MatchPlayer::updateOrCreate( 
                ['match_id' => $matchId, 'player_id' => $playerId],
                ['confirmed' => true]
            );
   }

   public function countPlayersByMatchId(int $matchId): int {
        return MatchPlayer::where('match_id', $matchId)->count();
   }
}