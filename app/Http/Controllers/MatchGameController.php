<?php

namespace App\Http\Controllers;

use App\Models\MatchPlayer;
use App\Models\Player;
use App\Service\TeamBalancer;
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
            'matchId' => $match->id
        ]);
    }

    public function index(){
        $matches = MatchGame::orderBy('created_at', 'desc')->get();
        return response()->json($matches);
    }

    public function confirmPlayersForm($match_id)
    {
        $match = MatchGame::findOrFail($match_id);
        $players = Player::all();
    
        return view('MatchGame.matchgameconfirmed', compact('match', 'players'));
    }

    public function confirmPlayers(Request $request, $match_id)
    {
        $request->validate([
            'players' => 'required|array|min:1',
            'players.*' => 'exists:players,id',
        ]);

        $match = MatchGame::findOrFail($match_id);

        $players = Player::whereIn('id', $request->players)->get();

        if ($players->count() < 6) {
            return response()->json([
                'message' => 'Você precisa selecionar pelo menos 6 jogadores para confirmar a partida.'
            ], 422);
        }

        $goalkeepers = $players->where('position', 'Goleiro');
        if ($goalkeepers->count() < 2) {
            return response()->json([
                'message' => 'A partida precisa ter pelo menos 2 goleiros entre os jogadores selecionados.'
            ], 422);
        }

        foreach ($players as $player) {
            MatchPlayer::updateOrCreate(
                ['match_id' => $match->id, 'player_id' => $player->id],
                ['confirmed' => true]
            );
        }

        $confirmedCount = MatchPlayer::where('match_id', $match->id)->count(); 
        if ($confirmedCount >= 12) {
            $match->status = 'preparado';
            $match->save();
        }

        return response()->json([
            'message' => 'Jogadores confirmados e adicionados à partida com sucesso!',
        ]);
    }

    public function listAllGames()
    {
        $games = MatchGame::orderBy('created_at', 'desc')->get();
        return response()->json($games);
    }

    public function finalized($id)
    {
        MatchGame::where('id', $id)->update([
            'status' => 'finalizado',
        ]);

        return response()->json(['message' => 'Partida cancelada com sucesso',]);
    }

    public function generateTeams(Request $request, $matchId)
    {
        $match = MatchGame::findOrFail($matchId);
        $players = $match->confirmedPlayers()->get()->all();

        if (count($players) < 6) {
            return redirect()->back()->with('error', 'Não há jogadores suficientes para gerar os times.');
        }

        $playersPerTeam = $request->input('players_per_team', ceil(count($players) / 2));
        $playersPerTeam = min($playersPerTeam, ceil(count($players) / 2)); // Garante que não exceda a metade

        $balancer = new TeamBalancer();
        $balancer->generate($players, $playersPerTeam);

        $team1 = $balancer->getTeam1();
        $team2 = $balancer->getTeam2();

        return view('Matchgame.matchgamegenerationteams', compact('team1', 'team2', 'match'));
    }

    public function startMatch($matchId)
    {
        $match = MatchGame::findOrFail($matchId);
        $match->status = 'iniciado';
        $match->save();

        return redirect('/')->with('success', 'Partida iniciada com sucesso!');
    }
}
