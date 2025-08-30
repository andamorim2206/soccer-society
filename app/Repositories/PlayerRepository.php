<?php

namespace App\Repositories;

use App\Models\Player;
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

    public function findPlayerByIds(array $players): Collection {
        return Player::whereIn('id', $players)->get();
    }
}