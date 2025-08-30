<?php

namespace App\Service;

use App\Repositories\MatchPlayerRepositoryInterface;

class MatchPlayerService
{
    protected MatchPlayerRepositoryInterface $repository;

    public function __construct(MatchPlayerRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function updateOrCreateMatchGame(int $matchId, int $playerId): void {
        $this->repository->updateOrCreate($matchId, $playerId);
    }

    public function countPlayersByMatchId(int $matchId): int {
        return $this->repository->countPlayersByMatchId($matchId);
    }
}