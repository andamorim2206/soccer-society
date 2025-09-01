<?php

namespace App\Service;

use App\Repositories\PlayerRepositoryInterface;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class PlayerService 
{
    protected PlayerRepositoryInterface $repository;

    private int $id;
    private string $name;
    private string $position;
    private int $xp = 0;

    private MatchGameService $matchGameService;

    public function __construct(PlayerRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function create(Request $request): void {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|in:Goleiro,Zagueiro,Meio-campo,Atacante',
            'xp' => 'required|integer|min:0|max:255',
        ]);

        $player = $request->only(['name', 'position', 'xp']);

        $this->repository->create($player);
    }
    
    public function listAll() : JsonResponse {
        return $this->repository->listAll();
    }

    public function findPlayerByIds(array $playerIds): Collection {
        return $this->repository->findPlayerByIds($playerIds);
    }
    
    public function listAllPlayersAvailableToMatch(): array {
        $playersArray = $this->repository->listAllPlayersAvailableToMatch();

        $output = [];
        
        foreach ($playersArray as $player) {
            $playerInfo = [
                "id" => $player->getId(),
                "name" => $player->getName(),
                "position" => $player->getPosition(),
                "xp" => $player->getXp(),
                "matchId" => $player->getMatchGameService()->getId(),
                "isPlaying" => $player->getMatchGameService()->getMatchPlayer()->isPlaying(),
            ];

            $output[] = $playerInfo ;
        }

        return $output;
    }

    public function setId(int $id): self {
        $this->id = $id;    

        return $this;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function setPosition(string $position): self {
        $this->position = $position;

        return $this;
    }

    public function setXP(int $xp): self {
        $this->xp = $xp;

        return $this;
    }

    public function getId(): int { 
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPosition(): string {
        return $this->position;
    }

    public function getXp(): int {
        return $this->xp;
    }

    public function setMatchGameService(MatchGameService $matchGameService): self {
        $this->matchGameService = $matchGameService;

        return $this;
    }

    public function getMatchGameService(): MatchGameService {
        return $this->matchGameService;
    }

}