<?php

namespace App\Repositories;

use App\Models\Player;
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

    public function listAllPlayersAvailableToMatch(): JsonResponse {
        $record = DB::table("players as p")
             ->select(
                "p.id",
                "p.name",
                "p.position",
                "p.xp"
                )
            ->leftJoin("match_player as mp","p.id","=","mp.player_id")
            ->leftJoin("match_games as mg", "mg.id", "=", "mp.match_id")
            ->whereNull('mp.match_id')    
            ->orWhere('mp.status', 'encerrado')
            ->get()
        ;

        return response()->json($record);
    }

    public function findPlayerByIds(array $players): Collection {
        return Player::whereIn('id', $players)->get();
    }
}