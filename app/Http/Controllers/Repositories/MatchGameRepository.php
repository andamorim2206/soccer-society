<?php

namespace App\Http\Controllers\Repositories;

use App\Models\MatchGame;
use App\Models\Player;
use Illuminate\Http\JsonResponse;

class MatchGameRepository implements MatchGameRepositoryInterface 
{
    public function create(array $data): int {
        $matchGame = MatchGame::create([
            'name' => $data['name'],
            'status' => 'pendente'
        ]);

        return $matchGame->id;
    }

    public function findMatchById(int $matchId): MatchGame
    {
        return MatchGame::findOrFail($matchId);
    }

    public function finalized()
    {}

}