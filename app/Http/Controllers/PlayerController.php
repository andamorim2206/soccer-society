<?php

namespace App\Http\Controllers;

use App\Repositories\PlayerRepository;
use App\Service\PlayerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlayerController extends Controller
{

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

    public function actionListAllPlayersAvailableToMatch(): JsonResponse {
        $player = new PlayerService(new PlayerRepository());

        return $player->listAllPlayersAvailableToMatch();
    }
}