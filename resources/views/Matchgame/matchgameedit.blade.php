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
            <a href="/player/create" class="btn btn-light">➕ Cadastrar Jogador</a>
            <a href="/" class="btn btn-light mr-2">🏠 Home</a>
        </div>
        <div class="card-body">
            <div id="response"></div>

            <form id="confirmForm">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Confirmar</th>
                            <th>Desistiu</th>
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

function getMatchIdFromUrl() {
    const urlSegments = window.location.pathname.split('/').filter(Boolean);
    const matchIndex = urlSegments.indexOf('matchgame');
    if (matchIndex === -1) return null;
    return urlSegments[matchIndex + 1];
}

async function loadPlayers() {
    try {
        const res = await fetch('/api/players/listAllPlayersAvailableToMatch');
        const players = await res.json();
        console.log(players);
        playerTable.innerHTML = '';

        players.forEach(player => {
            const row = document.createElement('tr');

            // CORREÇÃO: confirmar habilitado quando isPlaying === true
            //           desistiu habilitado quando isPlaying === false
            row.innerHTML = `
                <td class="text-center align-middle">
                    <input type="checkbox" class="confirm" data-id="${player.id}" ${player.isPlaying ? 'disabled' : ''}>
                </td>
                <td class="text-center align-middle">
                    <input type="checkbox" class="gaveUp" data-id="${player.id}" ${player.isPlaying ? '' : 'disabled'}>
                </td>
                <td class="align-middle">${player.name}</td>
                <td class="align-middle">${player.position}</td>
                <td class="align-middle">${player.xp}</td>
            `;

            playerTable.appendChild(row);
        });
    } catch (err) {
        responseDiv.innerHTML = `<div class="alert alert-danger">Erro ao carregar jogadores: ${err}</div>`;
    }
}

form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const matchId = getMatchIdFromUrl();
    if (!matchId) {
        alert('Não foi possível determinar matchId a partir da URL.');
        return;
    }

    const payloadPlayers = [];
    const confirmBoxes = document.querySelectorAll('input.confirm');
    const gaveUpBoxes = document.querySelectorAll('input.gaveUp');

    // Cria um map por playerId para combinar os dois checkboxes
    const mapById = new Map();
    confirmBoxes.forEach(box => {
        const id = parseInt(box.dataset.id, 10);
        mapById.set(id, { id, confirmed: box.checked, gaveUp: false });
    });
    gaveUpBoxes.forEach(box => {
        const id = parseInt(box.dataset.id, 10);
        const entry = mapById.get(id) || { id, confirmed: false, gaveUp: false };
        entry.gaveUp = box.checked;
        mapById.set(id, entry);
    });

    // Adiciona apenas jogadores que têm pelo menos um checkbox marcado
    mapById.forEach(value => {
        if (value.confirmed || value.gaveUp) {
            payloadPlayers.push(value);
        }
    });

    if (payloadPlayers.length === 0) {
        alert('Selecione pelo menos um jogador.');
        return;
    }

    const finalPayload = {
        matchId: parseInt(matchId, 10), // transforma matchId em número
        players: payloadPlayers
    };

    console.log('Payload enviado:\n', JSON.stringify(finalPayload, null, 4));

    try {
        const res = await fetch('/api/matchgame/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(finalPayload)
        });

        const result = await res.json();

        if (res.ok) {
            responseDiv.innerHTML = `<div class="alert alert-success">${result.message ?? 'Atualização enviada com sucesso.'}</div>`;

            window.location.href = `/matchgame/${matchId}/generate/teams`;
            loadPlayers();
        } else {
            responseDiv.innerHTML = `<div class="alert alert-danger">${result.message ?? JSON.stringify(result)}</div>`;
        }
    } catch (err) {
        responseDiv.innerHTML = `<div class="alert alert-danger">Erro ao enviar: ${err}</div>`;
    }
});

// Carrega os jogadores ao abrir a página
loadPlayers();
</script>
</body>
</html>
