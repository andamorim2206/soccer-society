<?php

use App\Http\Controllers\PlayerController;
use Illuminate\Support\Facades\Route;

Route::get('/api/players', [PlayerController::class, 'index']);
Route::post('/api/players', [PlayerController::class, 'store']);



Route::get('/', function () {
    return view('index');
});

Route::get('/player/create', function () {
    return view('playerform');
});