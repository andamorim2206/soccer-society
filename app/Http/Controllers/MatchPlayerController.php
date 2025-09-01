<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\MatchPlayerRepository;
use App\Service\MatchPlayerService;


class MatchPlayerController extends Controller
{
    public function actionUpdate(Request $request){
        $matchId = $request->input('matchId'); 
        $players = $request->input('players', []); // array de jogadores

        $matchPlayer = (new MatchPlayerService(new MatchPlayerRepository()));

        $matchPlayer->updatePlayersMatch($players, $matchId);
        
        return response()->json([
            'message' => 'Recebido com sucesso!',
            'data' => $players
        ]);
    }

    public function actionListAllPlayersByMatchId(int $matchId) {
        $matchPlayer = (new MatchPlayerService(new MatchPlayerRepository()))
            ->findConfirmedPlayersByMatchId( $matchId );
        ;

        return response()->json($matchPlayer);
    }
}
