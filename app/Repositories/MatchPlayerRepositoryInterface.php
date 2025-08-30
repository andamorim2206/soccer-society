<?php

namespace App\Repositories;


interface MatchPlayerRepositoryInterface
{
   public function updateOrCreate(int $matchId, int $playerId): void;

   public function countPlayersByMatchId(int $matchId): int;

}