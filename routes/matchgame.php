<?php

use App\Http\Controllers\MatchGameController;

Route::post('/api/matchgame/create', [MatchGameController::class, 'create']);