<?php

namespace App\Service;

use App\Repositories\MatchPlayerRepositoryInterface;
use DB;

class MatchPlayerService
{
    protected MatchPlayerRepositoryInterface $repository;

    private ?bool $isPlaying = false;

    private ?int $id;

    public function __construct(MatchPlayerRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function updateOrCreateMatchGame(int $matchId, int $playerId): void {
        $this->repository->updateOrCreate($matchId, $playerId);
    }

    public function countPlayersByMatchId(int $matchId): int {
        return $this->repository->countPlayersByMatchId($matchId);
    }

    public function updatePlayersMatch(array $data, int $matchId): void
    {
        $players = $data;

        DB::transaction(function () use ($players, $matchId) {
            foreach ($players as $playerData) {
                $playerId = $playerData['id'];
                $confirmed = $playerData['confirmed'] ?? false;
                $gaveUp    = $playerData['gaveUp'] ?? false;

                if ($confirmed) {
                    $this->repository->updateOrCreate($matchId, $playerId);
                }

                if ($gaveUp) {
                    $this->repository->cancelPlayerMatch($matchId, $playerId);
                }
            }
        });
    }

    public function findConfirmedPlayersByMatchId(int $matchId): array {
        return $this->repository->findConfirmedPlayersByMatchId($matchId);
    }

    public function setIsPlaying(?bool $isPlaying): self {
        $this->isPlaying = $isPlaying;

        return $this;
    }

    public function isPlaying(): bool { 
        return $this->isPlaying ?? false;
    }

     public function setId(?int $id): self {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int {
        return $this->id;
    }
}