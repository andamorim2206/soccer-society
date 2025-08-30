<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geração de Equipes - Partida {{ $match->name }}</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Geração de Equipes</h2>
    <h4>Partida: {{ $match->name }} | Status: {{ ucfirst($match->status) }}</h4>

    <div id="response"></div>

    <div class="row mt-3">
        <!-- Time 1 -->
        <div class="col-md-6">
            <div class="card shadow p-3">
                <h4 class="text-center">Time 1</h4>
                <ul class="list-group" id="team1List">
                    @foreach($team1 as $player)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>{{ $player->name }} - <em>{{ $player->position }}</em></div>
                            <span class="badge bg-primary">{{ $player->xp }} XP</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Time 2 -->
        <div class="col-md-6">
            <div class="card shadow p-3">
                <h4 class="text-center">Time 2</h4>
                <ul class="list-group" id="team2List">
                    @foreach($team2 as $player)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>{{ $player->name }} - <em>{{ $player->position }}</em></div>
                        <span class="badge bg-primary">{{ $player->xp }} XP</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <form action="{{ route('matchgame.start', $match->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success btn-block">Iniciar Partida</button>
        </form>
    </div>

    <p class="text-muted mt-2" style="font-size: 0.9rem;">
        O sistema organiza os jogadores em uma árvore binária baseada na experiência, permitindo que os mais habilidosos sejam rapidamente identificados e acessados. A partir dessa estrutura, os jogadores são distribuídos entre os dois times de forma alternada, sempre equilibrando a soma de experiência de cada time. Durante a alocação, são respeitadas as regras de posições mínimas, garantindo que cada time tenha goleiros para cada time. O resultado final é uma divisão justa e balanceada, tanto em habilidade quanto em função, pronta para a partida.
    </p>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
