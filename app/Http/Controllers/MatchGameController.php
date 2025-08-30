<?php

namespace App\Http\Controllers;

use App\Repositories\MatchGameRepository;
use App\Repositories\MatchGameRepositoryInterface;
use App\Repositories\MatchPlayerRepository;
use App\Repositories\PlayerRepository;
use App\Service\MatchGameService;
use App\Service\PlayerService;
use App\Service\MatchPlayerService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MatchGameController extends Controller
{
    protected MatchGameRepositoryInterface $repository;

    public function __construct(MatchGameRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function actionCreate(Request $request){
        
        $matchGame = new MatchGameService(new MatchGameRepository());

        $matchId = $matchGame->create($request);

        return response()->json([
            'message' => 'Partida criada com sucesso!',
            'matchId' => $matchId
        ]);
    }

    public function actionConfirmPlayersForm($matchId)
    {
        $match = (new MatchGameService(new MatchGameRepository()))
            ->setPlayer(new PlayerService(new PlayerRepository()))
            ->findMatchById( $matchId )
        ;

        $players = $match->getPlayer()->listAll();
    
        return view('MatchGame.matchgameconfirmed', compact('match', 'players'));
    }

    public function actionConfirmPlayers(Request $request, $matchId)
    {
        return (new MatchGameService(new MatchGameRepository()))
            ->setPlayer(new PlayerService(new PlayerRepository()))
            ->setMatchPlayer(new MatchPlayerService(new MatchPlayerRepository()))
            ->confirmPlayers( $request, $matchId )
        ;
    }

    public function actionListAllGames(): JsonResponse
    {
        $games = (new MatchGameService(new MatchGameRepository()))->load();

        return response()->json($games);
    }

    public function actionFinalized(int $matchId): JsonResponse
    {
       (new MatchGameService(new MatchGameRepository()))->finalized( $matchId );

        return response()->json(['message' => 'Partida cancelada com sucesso',]);
    }

    public function generateTeams(Request $request, $matchId): RedirectResponse | View
    {
        return (new MatchGameService(new MatchGameRepository()))->generateTeams($request, $matchId );
    }

    public function startMatch(int $matchId)
    {
        $this->repository->start( $matchId );

        return redirect('/')->with('success', 'Partida iniciada com sucesso!');
    }
}
