<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;

class PlayerController extends Controller
{
    public function store(Request $request)
    {
    $request->validate([
        'name' => 'required|string|max:255',
        'position' => 'required|in:Goleiro,Zagueiro,Meio-campo,Atacante',
        'xp' => 'required|integer|min:0|max:255',
    ]);

    Player::create($request->only(['name', 'position', 'xp']));

    return response()->json(['message' => 'Player criado com sucesso!'], 201);  
    }

    public function index()
    {
        $players = Player::with('team')->get();
        return response()->json($players);
    }
}