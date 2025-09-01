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

    private ?int $matchId = null;

    
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

    public function loadMatchAndPlayerForMatch(int $matchId): array {
        $match = $this->repository->findMatchById($matchId);
        $players = $this->getPlayer()->listAllPlayersAvailableToMatch();  

        return [
            'match' => $match,
            'players' => $players
        ];
    }

    public function confirmPlayers(Request $request, $matchId): JsonResponse {
        $request->validate([
            'players' => 'required|array|min:1',
            'players.*' => 'exists:players,id',
        ]);
        $matchGame = $this->repository->findMatchById( $matchId );

        $players = $this->getPlayer()->findPlayerByIds($request->players);

    
        if ($players->count() % 2 != 0) {
            return response()->json([
                'message' => 'Você precisa selecionar pelo menos numero par de jogadores para continuar a partida.'
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
        if ($confirmedCount % 2 == 0 ) {
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

    public function generateTeams(Request $request, $matchId): JsonResponse  {
        $match = $this->repository->findMatchById( $matchId );
        $players = $this->getMatchPlayer()->findConfirmedPlayersByMatchId( $matchId );

        $balancer = new TeamBalancer();
        $balancer->generateTeams($players, $request["playersPerTeam"]);

        $team1 = $balancer->getTeam1();
        $team2 = $balancer->getTeam2();
        $bench = $balancer->getBench();

       $formatResponse = function($players) {
        return array_map(function($p) {
                return [
                    'Name'     => method_exists($p, 'getName') ? $p->getName() : '',
                    'Position' => method_exists($p, 'getPosition') ? $p->getPosition() : '',
                    'Xp'       => method_exists($p, 'getXp') ? $p->getXp() : 0,
                ];
            }, $players);
        };

        $response = [
            'teams' => [
                'team1' => $formatResponse($team1),
                'team2' => $formatResponse($team2),
                'bench' => $formatResponse($bench),
            ]
         ];

        return response()->json($response);
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

    public function setId(?int $id): self {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int {
        return $this->id;
    }
}
