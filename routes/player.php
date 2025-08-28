<?php

use App\Http\Controllers\PlayerController;

Route::get('/api/players', [PlayerController::class, 'index']);
Route::post('/api/players', [PlayerController::class, 'store']);
route::get('/api/players/list', [PlayerController::class,'listAll']);