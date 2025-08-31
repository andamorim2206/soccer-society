<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

interface PlayerRepositoryInterface
{
    public function create(array $player): void;
    public function listAll(): JsonResponse;

    public function listAllPlayersAvailableToMatch(): JsonResponse;  
    public function findPlayerByIds(array $players):Collection;

}