<?php

namespace App\Http\Controllers\Repositories;
use App\Models\MatchGame;

interface MatchGameRepositoryInterface
{
    public function create(array $data): int;
    public function findMatchById(int $matchId): MatchGame; 
    public function finalized();
}