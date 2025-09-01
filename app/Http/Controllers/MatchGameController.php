<?php

namespace App\Http\Controllers;

use App\Repositories\MatchGameRepository;
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
        $data = (new MatchGameService(new MatchGameRepository()))
            ->setPlayer(new PlayerService(new PlayerRepository()))
            ->loadMatchAndPlayerForMatch( $matchId )
        ;
    
        return view('matchgame.confirmed', [
            'match'   => $data['match'],
            'players' => $data['players'],
        ]);
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
    

    public function actionGenerateTeams(Request $request, $matchId): JsonResponse
    {
        return (new MatchGameService(new MatchGameRepository()))
            ->setMatchPlayer(new MatchPlayerService(new MatchPlayerRepository()))
            ->generateTeams($request, $matchId )
        ;
    }

    public function actionStartMatch(int $matchId)
    {
        (new MatchGameService(new MatchGameRepository()))->start( $matchId );

        return redirect('/')->with('success', 'Partida iniciada com sucesso!');
    }
}
