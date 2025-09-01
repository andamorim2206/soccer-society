<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/player.php';
require __DIR__.'/matchgame.php';

Route::get('/', function () {
    return view('index');
});

Route::get('/player/create', function () {
    return view('Player.playerform');
});

Route::get('/player/list', function () {
    return view('Player.listAll');
});

Route::get('/matchgame/create', function () {
    return view('Matchgame.matchgameform');
})->name('matchgame.create');

Route::get('/matchgame/{id}/list/confirmed', function () {
    return view('Matchgame.matchgameconfirmed');
})->name('matchgame.confirmed');

Route::get('/matchgame/{id}/list/edit', function () {
    return view('Matchgame.matchgameedit');
})->name('matchgame.edit');

Route::get('/matchgame/{id}/generate/teams', function () {
    return view('Matchgame.matchgamegenerationteamsv2');
})->name('matchgame.generateteamsv2');

