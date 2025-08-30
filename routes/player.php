<?php

use App\Http\Controllers\PlayerController;

Route::post('/api/players', [PlayerController::class, 'actionCreate']);

route::get('/api/players/list', [PlayerController::class,'actionListAll']);