<?php

namespace App\Http\Controllers;

use App\Repositories\PlayerRepository;
use App\Repositories\PlayerRepositoryInterface;
use App\Service\PlayerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Player;

class PlayerController extends Controller
{
    protected PlayerRepositoryInterface $repository;
    public function __construct(PlayerRepositoryInterface $repository){
        $this->repository = $repository;
    }

    public function actionCreate(Request $request): JsonResponse
    {
       $player = new PlayerService(new PlayerRepository());
       
       $player->create($request);

        return response()->json(['message' => 'Player criado com sucesso!'], 201);  
    }

    public function actionListAll(): JsonResponse {
        $player = new PlayerService(new PlayerRepository());

        return $player->listAll();
    }
}