<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Repositories\PlayerRepository;
use App\Http\Controllers\Repositories\PlayerRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Player;

class PlayerController extends Controller
{
    protected PlayerRepositoryInterface $repository;
    public function __construct(PlayerRepositoryInterface $repository){
        $this->repository = $repository;
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|in:Goleiro,Zagueiro,Meio-campo,Atacante',
            'xp' => 'required|integer|min:0|max:255',
        ]);

        $player = $request->only(['name', 'position', 'xp']);

        $this->repository->create($player);

        return response()->json(['message' => 'Player criado com sucesso!'], 201);  
    }

    public function listAll(): JsonResponse {
        return $this->repository->listAll();
    }
}