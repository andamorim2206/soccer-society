<?php

namespace App\Service;

use App\Models\Player;

class TeamBalancer
{
    private array $team1 = [];
    private array $team2 = [];
    private array $bench = [];

    public function generateTeams(array $players, int $playersPerTeam): array
    {
        $this->team1 = [];
        $this->team2 = [];
        $this->bench = [];

        $playersByPosition = $this->groupPlayersByPosition($players);

        $this->setupInitialTeams($playersByPosition, $playersPerTeam);

        $this->placeRemainingPlayers($playersByPosition, $playersPerTeam);

        $this->bench = $this->determineBench($players);

        return [
            'team1' => $this->team1,
            'team2' => $this->team2,
            'bench' => $this->bench,
        ];
    }

    public function getTeam1(): array
    {
        return $this->team1;
    }

    public function getTeam2(): array
    {
        return $this->team2;
    }

    public function getBench(): array
    {
        return $this->bench;
    }

    private function groupPlayersByPosition(array $players): array
    {
        $grouped = [];
        foreach ($players as $player) {
            $grouped[$player->getPosition()][] = $player;
        }

        foreach ($grouped as &$group) {
            usort($group, fn($a, $b) => $b->getXp() <=> $a->getXp());
        }

        return $grouped;
    }

private function setupInitialTeams(array &$playersByPosition, int $playersPerTeam): void
{
    foreach (Player::getPositions() as $position) {
        if (!array_key_exists($position, $playersByPosition) || !is_array($playersByPosition[$position])) {
            $playersByPosition[$position] = [];
        }

        $players = &$playersByPosition[$position];

        if ($this->isRangedPosition($position)) {
            $this->placeRangedPlayers($players, $playersPerTeam);
        } else {
            $this->placeFixedPlayers($players, $playersPerTeam);
        }
    }
}

private function placeRangedPlayers(array &$players, int $playersPerTeam): void
{
    foreach (['team1', 'team2'] as $team) {
        if (!empty($players) && count($this->{$team}) < $playersPerTeam) {
            $this->{$team}[] = array_shift($players);
        }
    }
}

private function placeFixedPlayers(array &$players, int $playersPerTeam): void
{
    foreach (['team1', 'team2'] as $team) {
        if (!empty($players) && count($this->{$team}) < $playersPerTeam) {
            $this->{$team}[] = array_shift($players);
        }
    }
}

    private function placeRemainingPlayers(array &$playersByPosition, int $playersPerTeam): void
    {
        $remainingPlayers = [];
        foreach ($playersByPosition as $group) {
            $remainingPlayers = array_merge($remainingPlayers, $group);
        }

        foreach ($remainingPlayers as $player) {
            if ($this->canAddToTeam($this->team1, $playersPerTeam)) {
                $this->team1[] = $player;
                continue;
            }

            if ($this->canAddToTeam($this->team2, $playersPerTeam)) {
                $this->team2[] = $player;
                continue;
            }
        }
    }

    private function determineBench(array $allPlayers): array
    {
        $assignedPlayers = array_merge($this->team1, $this->team2);

        return array_filter($allPlayers, function($player) use ($assignedPlayers) {
            foreach ($assignedPlayers as $assigned) {
                if ($player->getId() === $assigned->getId()) {
                    return false;
                }
            }
            return true;
        });
    }

    private function canAddToTeam(array $team, int $playersPerTeam): bool
    {
        return count($team) < $playersPerTeam;
    }

    private function isRangedPosition(string $position): bool
    {
        return in_array($position, ['Atacante', 'Meio-campo']);
    }
}
