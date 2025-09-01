<?php

namespace App\Repositories;

use App\Models\Player;
use App\Service\MatchGameService;
use App\Service\MatchPlayerService;
use App\Service\PlayerService;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class PlayerRepository implements PlayerRepositoryInterface 
{
    public function create(array $player): void {
        Player::create($player);
    }

    public function listAll(): JsonResponse {
       $players = Player::all();

       return response()->json($players);
    }

   public function listAllPlayersAvailableToMatch(): array {
    $records = DB::table("players as p")
        ->select(
            "p.id as player_id",
            "p.name",
            "p.position",
            "p.xp",
            "mp.confirmed",
            "mg.id as match_id"
        )
        ->leftJoin("match_player as mp", "p.id", "=", "mp.player_id")
        ->leftJoin("match_games as mg", "mg.id", "=", "mp.match_id")
        ->distinct('p.id') // garante que não haja duplicados
        ->get();

    $recordsArray = [];

    foreach ($records as $record) {
        $matchPlayer = (new MatchPlayerService(new MatchPlayerRepository()))
            ->setIsPlaying($record->confirmed);

        $matchGame = (new MatchGameService(new MatchGameRepository()))
            ->setId($record->match_id)
            ->setMatchPlayer($matchPlayer);

        $player = (new PlayerService(new PlayerRepository()))
            ->setId($record->player_id)
            ->setName($record->name)
            ->setPosition($record->position)
            ->setXP($record->xp)
            ->setMatchGameService($matchGame);

        $recordsArray[] = $player;
    }

    return $recordsArray;
}


    public function findPlayerByIds(array $players): Collection {
        return Player::whereIn('id', $players)->get();
    }
}