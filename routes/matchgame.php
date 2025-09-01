<?php

use App\Http\Controllers\MatchGameController;
use App\Http\Controllers\MatchPlayerController;

Route::post('/api/matchgame/create', [MatchGameController::class, 'actionCreate']);

Route::get('/api/matchGame/{matchId}/confirm', [MatchGameController::class, 'actionConfirmPlayersForm'])->name('match.confirm.form');

Route::post('/api/matchGame/{matchId}/confirm', [MatchGameController::class, 'actionConfirmPlayers'])->name('match.confirm');

Route::get('/api/matchGame/list', [MatchGameController::class, 'actionListAllGames']);

Route::post('/api/matchGame/{matchId}/finalized', [MatchGameController::class, 'actionFinalized']);

//Route::get('/matchgame/{matchId}/teams', [MatchGameController::class, 'actionGenerateTeams'])->name('matchgame.teams');

Route::post('/matchgame/{matchId}/teams/generate', [MatchGameController::class,'actionGenerateTeams']);

Route::post('/api/matchgame/{matchId}/start', [MatchGameController::class, 'actionStartMatch'])->name('matchgame.start');

Route::post('/api/matchgame/update', [MatchPlayerController::class, 'actionUpdate'])->name('matchgame.update');

Route::get('/api/matchgame/{matchId}/listMatchPlayers', [MatchPlayerController::class, 'actionListAllPlayersByMatchId']);





