<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Repositories\MatchGameRepositoryInterface;
use App\Models\MatchPlayer;
use App\Models\Player;
use App\Service\TeamBalancer;
use Illuminate\Http\Request;
use App\Models\MatchGame;

class MatchGameController extends Controller
{
    protected MatchGameRepositoryInterface $repository;

    public function __construct(MatchGameRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function create(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $matchId = $this->repository->create($request->all());

        return response()->json([
            'message' => 'Partida criada com sucesso!',
            'matchId' => $matchId
        ]);
    }

    public function index(){
        $matches = $this->repository->load();
        return response()->json($matches);
    }

    public function confirmPlayersForm($match_id)
    {
        $match = $this->repository->findMatchById( $match_id );
        $players = Player::all();
    
        return view('MatchGame.matchgameconfirmed', compact('match', 'players'));
    }

    public function confirmPlayers(Request $request, $match_id)
    {
        $request->validate([
            'players' => 'required|array|min:1',
            'players.*' => 'exists:players,id',
        ]);

        $match = $this->repository->findMatchById( $match_id );

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
        $games = $this->repository->load(); // load
        return response()->json($games);
    }

    public function finalized(int $matchId)
    {
       $this->repository->finalized($matchId);

        return response()->json(['message' => 'Partida cancelada com sucesso',]);
    }

    public function generateTeams(Request $request, $matchId)
    {
        $match = $this->repository->findMatchById( $matchId );
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

    public function startMatch(int $matchId)
    {
        $this->repository->start( $matchId );

        return redirect('/')->with('success', 'Partida iniciada com sucesso!');
    }
}
