<?php

namespace App\Service;

use App\Repositories\PlayerRepositoryInterface;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class PlayerService 
{
    protected PlayerRepositoryInterface $repository;

    public function __construct(PlayerRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function create(Request $request): void {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|in:Goleiro,Zagueiro,Meio-campo,Atacante',
            'xp' => 'required|integer|min:0|max:255',
        ]);

        $player = $request->only(['name', 'position', 'xp']);

        $this->repository->create($player);
    }
    
    public function listAll() : JsonResponse {
        return $this->repository->listAll();
    }

    public function findPlayerByIds(array $playerIds): Collection {
        return $this->repository->findPlayerByIds($playerIds);
    }
    
}