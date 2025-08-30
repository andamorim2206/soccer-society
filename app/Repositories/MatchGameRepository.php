<?php

namespace App\Repositories;

use App\Models\MatchGame;
use Illuminate\Database\Eloquent\Collection;

class MatchGameRepository implements MatchGameRepositoryInterface 
{
    public function create(array $data): int {
        $matchGame = MatchGame::create([
            'name' => $data['name'],
            'status' => 'pendente'
        ]);

        return $matchGame->id;
    }

    public function load(): Collection {
       return MatchGame::orderBy('created_at', 'desc')->get();
    }

    public function findMatchById(int $matchId): MatchGame {
        return MatchGame::findOrFail($matchId);
    }

    public function updateToPrepared(MatchGame $matchGame): void {
        $matchGame->status = 'preparado';
        $matchGame->save();
    }

    public function finalized(int $matchId): bool {
        return MatchGame::where('id', $matchId)->update([
            'status' => 'finalizado',
        ]);
    }

    public function start(int $matchId): bool {
        return MatchGame::where('id', $matchId)->update([
            'status' => 'iniciado',
        ]);
    }
}