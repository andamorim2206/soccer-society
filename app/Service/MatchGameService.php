<?php

namespace App\Service;

use App\Repositories\MatchGameRepositoryInterface;
use App\Models\MatchGame;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Service\PlayerService;
use App\Service\MatchPlayerService;
use Illuminate\Contracts\View\View;


class MatchGameService
{
    protected MatchGameRepositoryInterface $repository;
    private PlayerService $playerService;
    private MatchPlayerService $matchPlayerService;
      
    public function __construct(MatchGameRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function create(Request $request): int{
         $request->validate([
            'name' => 'required|string|max:255',
        ]);

        return $this->repository->create($request->all());
    }

    public function load(): Collection {
        return $this->repository->load();
    }

    public function findMatchById(int $matchId): MatchGame {
        return $this->repository->findMatchById( $matchId );
    }

    public function confirmPlayers(Request $request, $matchId): JsonResponse {

        $request->validate([
            'players' => 'required|array|min:1',
            'players.*' => 'exists:players,id',
        ]);
    
        $matchGame = $this->repository->findMatchById( $matchId );

        $players = $this->getPlayer()->findPlayerByIds($request->players);

    
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
            $this->getMatchPlayer()->updateOrCreateMatchGame($matchGame->id, $player->id);
        }

        $confirmedCount = $this->getMatchPlayer()->countPlayersByMatchId($matchGame->id);
        if ($confirmedCount >= 12) {
           $this->repository->updateToPrepared($matchGame);
        }

        return response()->json([
            'message' => 'Jogadores confirmados e adicionados à partida com sucesso!',
        ]);
    }

    public function finalized(int $matchId): void { 
        $this->repository->finalized($matchId);
    }

    public function start(int $matchId): void {
        $this->repository->start($matchId);
    }

    public function generateTeams(Request $request, $matchId): RedirectResponse | View  {
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

    public function setPlayer(PlayerService $playerService): self {
        $this->playerService = $playerService;

        return $this;
    }

    public function getPlayer(): PlayerService {
        return $this->playerService;
    }

    public function setMatchPlayer(MatchPlayerService $matchPlayerService): self {
        $this->matchPlayerService = $matchPlayerService;

        return $this;
    }

    public function getMatchPlayer(): MatchPlayerService {
        return $this->matchPlayerService;
    }
}
