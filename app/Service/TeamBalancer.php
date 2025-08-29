<?php

namespace App\Service;

use App\Models\Player;

class TeamBalancer
{
    protected array $positionsMin = [
        'Goleiro' => 1,
        'Zagueiro' => 2,
        'Meio-campo' => 2,
        'Atacante' => 2,
    ];

    protected int $playersPerTeam;
    protected array $team1 = [];
    protected array $team2 = [];

    public function __construct(int $playersPerTeam = 6)
    {
        $this->playersPerTeam = $playersPerTeam;
    }

    public function generate(array $players, int $playersPerTeam): void
    {
        $this->team1 = [];
        $this->team2 = [];
    
        $root = null;
        foreach ($players as $player) {
            $root = $this->insertBST($root, $player);
        }

        $sortedPlayers = [];
        $this->reverseInOrder($root, $sortedPlayers);

        foreach ($sortedPlayers as $player) {
            $xpTeam1 = array_sum(array_map(fn($p) => $p->xp, $this->team1));
            $xpTeam2 = array_sum(array_map(fn($p) => $p->xp, $this->team2));

            if ((count($this->team1) < $playersPerTeam && $xpTeam1 <= $xpTeam2) || count($this->team2) >= $playersPerTeam) {
                $this->team1[] = $player;
            } else {
                $this->team2[] = $player;
            }
        }
    }

    private function insertBST($node, Player $player)
    {
        if ($node === null) {
            return ['player' => $player, 'left' => null, 'right' => null];
        }

        if ($player->xp < $node['player']->xp) {
            $node['left'] = $this->insertBST($node['left'], $player);
        } else {
            $node['right'] = $this->insertBST($node['right'], $player);
        }

        return $node;
    }

    // Percorre árvore em ordem decrescente
    private function reverseInOrder($node, array &$result)
    {
        if ($node === null) return;

        $this->reverseInOrder($node['right'], $result);
        $result[] = $node['player'];
        $this->reverseInOrder($node['left'], $result);
    }

    public function getTeam1(): array
    {
        return $this->team1;
    }

    public function getTeam2(): array
    {
        return $this->team2;
    }
}