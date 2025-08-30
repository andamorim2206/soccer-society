<?php

namespace App\Http\Controllers\Repositories;
use App\Models\MatchGame;
use Illuminate\Database\Eloquent\Collection;

interface MatchGameRepositoryInterface
{
    public function load(): Collection;
    public function create(array $data): int;
    public function findMatchById(int $matchId): MatchGame; 
    public function finalized(int $matchId ): bool;
    public function start(int $matchId): bool;
}