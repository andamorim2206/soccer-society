<?php

namespace App\Http\Controllers\Repositories;

use App\Models\Player;
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
}