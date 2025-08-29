<?php

namespace App\Http\Controllers\Repositories;
use Illuminate\Http\JsonResponse;
interface PlayerRepositoryInterface
{
    public function create(array $player): void;
    public function listAll(): JsonResponse;

}