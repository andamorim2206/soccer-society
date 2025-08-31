<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $players = [
            ['id' => 1, 'name' => 'Player 1', 'xp' => 50, 'position' => 'Goleiro'],
            ['id' => 2, 'name' => 'Player 2', 'xp' => 100, 'position' => 'Zagueiro'],
            ['id' => 3, 'name' => 'Player 3', 'xp' => 90, 'position' => 'Meio-campo'],
            ['id' => 4, 'name' => 'Player 4', 'xp' => 50, 'position' => 'Atacante'],
            ['id' => 5, 'name' => 'Player 5', 'xp' => 36, 'position' => 'Goleiro'],
            ['id' => 6, 'name' => 'Player 6', 'xp' => 10, 'position' => 'Zagueiro'],
            ['id' => 7, 'name' => 'Player 7', 'xp' => 15, 'position' => 'Meio-campo'],
            ['id' => 8, 'name' => 'Player 8', 'xp' => 80, 'position' => 'Goleiro'],
            ['id' => 9, 'name' => 'Player 9', 'xp' => 90, 'position' => 'Meio-campo'],
            ['id' => 10, 'name' => 'Player 10', 'xp' => 100, 'position' => 'Atacante'],
            ['id' => 11,'name' => 'Player 11', 'xp' => 5, 'position' => 'Zagueiro'],
            ['id' => 12,'name' => 'Player 12', 'xp' => 25, 'position' => 'Meio-campo'],
        ];

        DB::table('players')->insert($players);
    }
}