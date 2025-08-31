<?php

use App\Http\Controllers\PlayerController;

Route::post('/api/players', [PlayerController::class, 'actionCreate']);

Route::get('/api/players/list', [PlayerController::class, 'actionListAll']);

Route::get('/api/players/listAllPlayersAvailableToMatch', [PlayerController::class, 'actionListAllPlayersAvailableToMatch']);
