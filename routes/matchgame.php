<?php

use App\Http\Controllers\MatchGameController;

Route::post('/api/matchgame/create', [MatchGameController::class, 'actionCreate']);

Route::get('/api/matchGame/{matchId}/confirm', [MatchGameController::class, 'actionConfirmPlayersForm'])->name('match.confirm.form');

Route::post('/api/matchGame/{matchId}/confirm', [MatchGameController::class, 'actionConfirmPlayers'])->name('match.confirm');

Route::get('/api/matchGame/list', [MatchGameController::class, 'actionListAllGames']);

Route::post('/api/matchGame/{matchId}/finalized', [MatchGameController::class, 'actionFinalized']);

Route::get('/matchgame/{matchId}/teams', [MatchGameController::class, 'generateTeams'])->name('matchgame.teams');

Route::post('/api/matchgame/{matchId}/start', [MatchGameController::class, 'startMatch'])->name('matchgame.start');


