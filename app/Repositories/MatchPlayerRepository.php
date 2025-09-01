<?php

namespace App\Repositories;


use App\Models\MatchPlayer;
use App\Service\MatchGameService;
use App\Service\MatchPlayerService;
use App\Service\PlayerService;
use DB;

class MatchPlayerRepository implements MatchPlayerRepositoryInterface 
{
   public function updateOrCreate(int $matchId, int $playerId): void{
         MatchPlayer::updateOrCreate( 
                ['match_id' => $matchId, 'player_id' => $playerId],
                [
                  'status' => 'reserva',
                  'confirmed' => true
               ],
            );
   }

   public function cancelPlayerMatch(int $matchId, int $playerId): void {
      MatchPlayer::updateOrCreate( 
               ['match_id' => $matchId, 'player_id' => $playerId],
               [
               'status' => 'reserva',
               'confirmed' => false
            ],
         );
   }

   public function countPlayersByMatchId(int $matchId): int {
        return MatchPlayer::where('match_id', $matchId)->count();
   }

   public function findConfirmedPlayersByMatchId(int $matchId): array
   {
      $records = DB::table("match_player as mp")
             ->select(
                   "p.id as player_id",
                "p.name",
                "p.position",
                "p.xp",
                "mp.id as match_player_id",
                "mp.confirmed",
                "mg.id as match_id"
                )
            ->leftJoin("players as p","mp.player_id","=","p.id")
            ->leftJoin("match_games as mg", "mg.id", "=", "mp.match_id")
            ->where([
               "mp.match_id" => $matchId,
               "mp.confirmed" => true
            ])
            ->get()
        ;

        $recordsArray = [];

        foreach ($records as $record) {
            $matchPlayer = (new MatchPlayerService(new MatchPlayerRepository()))
                ->setIsPlaying($record->confirmed)
            ;

            $matchGame = (new MatchGameService(new MatchGameRepository()))
                ->setId(($record->match_id))
                ->setMatchPlayer($matchPlayer)
            ;

            $player = (new PlayerService(new PlayerRepository()))
                ->setId($record->player_id)
                ->setName($record->name)
                ->setPosition($record->position)
                ->setXP($record->xp)
                ->setMatchGameService($matchGame)
            ;

            $recordsArray[] = $player;
        }

        return $recordsArray;
   }
}