<?php

use App\Http\Controllers\MatchGameController;

Route::post('/api/matchgame/create', [MatchGameController::class, 'create']);

Route::get('/api/matchGame/{matchId}/confirm', [MatchGameController::class, 'confirmPlayersForm'])->name('match.confirm.form');

Route::post('/api/matchGame/{matchId}/confirm', [MatchGameController::class, 'confirmPlayers'])->name('match.confirm');

Route::get('/api/matchGame/list', [MatchGameController::class, 'listAllGames']);

Route::post('/api/matchGame/{matchId}/finalized', [MatchGameController::class, 'finalized']);
