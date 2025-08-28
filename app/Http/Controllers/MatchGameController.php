<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MatchGame;

class MatchGameController extends Controller
{
    public function create(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $match = MatchGame::create([
            'name' => $request->name,
            'status' => 'pendente', // padrão inicial
        ]);

        return response()->json([
            'message' => 'Partida criada com sucesso!',
            'match' => $match
        ]);
    }

    public function index(){
        $matches = MatchGame::orderBy('created_at', 'desc')->get();
        return response()->json($matches);
    }
}