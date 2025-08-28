<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listar Jogadores</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3>Confirmar Jogadores</h3>
            <a href="/player/create" class="btn btn-success">Cadastrar Novo Player</a>
        </div>
        <div class="card-body">
            <div id="response"></div>

            <form id="confirmForm">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Confirmar</th>
                            <th>Nome</th>
                            <th>Posição</th>
                            <th>XP</th>
                        </tr>
                    </thead>
                    <tbody id="playerTable">
                        <!-- Lista será preenchida via JS -->
                    </tbody>
                </table>

                <button type="submit" class="btn btn-primary btn-block">Confirmar Selecionados</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS + jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const responseDiv = document.getElementById('response');
const playerTable = document.getElementById('playerTable');
const form = document.getElementById('confirmForm');

async function loadPlayers() {
    try {
        const res = await fetch('/api/players/list');
        const players = await res.json();

        playerTable.innerHTML = '';

        players.forEach(player => {
            const row = document.createElement('tr');

            row.innerHTML = `
                <td><input type="checkbox" name="players[]" value="${player.id}"></td>
                <td>${player.name}</td>
                <td>${player.position}</td>
                <td>${player.xp}</td>
            `;
            playerTable.appendChild(row);
        });
    } catch (err) {
        responseDiv.innerHTML = `<div class="alert alert-danger">Erro ao carregar jogadores: ${err}</div>`;
    }
}

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    const selected = formData.getAll('players[]');

    if (selected.length === 0) {
        alert('Selecione pelo menos um jogador.');
        return;
    }

    try {
        const urlSegments = window.location.pathname.split('/');
        const matchIndex = urlSegments.indexOf('matchgame');
        const matchId = urlSegments[matchIndex + 1];

        const res = await fetch(`/api/matchGame/${matchId}/confirm`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ players: selected })
        });

        const result = await res.json();

        if (res.ok) {
            responseDiv.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
            loadPlayers();
        } else {
            responseDiv.innerHTML = `<div class="alert alert-danger">${result.message}</div>`;
        }
    } catch (err) {
        responseDiv.innerHTML = `<div class="alert alert-danger">Erro: ${err}</div>`;
    }
});

loadPlayers();
</script>
</body>
</html>
