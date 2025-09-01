<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geração de Equipes - Partida</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Geração de Equipes</h2>
        <div>
            <button class="btn btn-primary btn-sm mr-2" onclick="goHome()">Home</button>
            <button class="btn btn-secondary btn-sm" onclick="editPlayers()">Editar Jogadores Confirmados</button>
        </div>
    </div>

    <div id="response"></div>

    <div class="row mt-3">
        <!-- Time 1 -->
        <div class="col-md-4">
            <div class="card shadow p-3 mb-3">
                <h4 class="text-center">Time 1</h4>
                <ul class="list-group" id="team1List"></ul>
            </div>
        </div>

        <!-- Time 2 -->
        <div class="col-md-4">
            <div class="card shadow p-3 mb-3">
                <h4 class="text-center">Time 2</h4>
                <ul class="list-group" id="team2List"></ul>
            </div>
        </div>

        <!-- Time Reserva -->
        <div class="col-md-4">
            <div class="card shadow p-3 mb-3">
                <h4 class="text-center">Time Reserva</h4>
                <ul class="list-group" id="reserveList"></ul>
            </div>
        </div>
    </div>

    <div class="row mt-3 mb-3">
        <div class="col-md-4">
            <input type="number" id="playersPerTeam" class="form-control" placeholder="Jogadores por time" min="1" required>
        </div>
        <div class="col-md-8 d-flex justify-content-between">
            <button class="btn btn-success" onclick="balanceTeams()">Balancear / Rebalancear</button>
            <button id="startBtn" class="btn btn-warning" onclick="startMatch()" disabled>Iniciar Partida</button>
        </div>
    </div>

    <p class="text-muted mt-4" style="font-size: 0.9rem;">
        O sistema organiza os jogadores entre os times de forma equilibrada, respeitando posições e experiência. 
        Time Reserva serve para adicionar jogadores extras que podem entrar durante a partida. 
        Use o botão de Balancear para redistribuir os jogadores caso algum seja adicionado ou removido. 
        Depois clique em Iniciar Partida para confirmar os times.
    </p>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
let team1 = [];
let team2 = [];
let reserve = [];
let balanced = false;

// Renderiza os times
function renderTeam(team) {
    let listElement, arr;
    if (team === 1) { listElement = document.getElementById('team1List'); arr = team1; }
    else if (team === 2) { listElement = document.getElementById('team2List'); arr = team2; }
    else { listElement = document.getElementById('reserveList'); arr = reserve; }

    listElement.innerHTML = '';
    arr.forEach(player => {
        const li = document.createElement('li');
        li.className = 'list-group-item';
        li.innerHTML = `<strong>${player.Name}</strong> - ${player.Position} - XP: ${player.Xp}`;
        listElement.appendChild(li);
    });
}

// MatchId da URL
function getMatchIdFromUrl() {
    const segments = window.location.pathname.split('/').filter(Boolean);
    const index = segments.indexOf('matchgame');
    if (index === -1 || !segments[index + 1]) return null;
    return segments[index + 1];
}

// Salvar times no localStorage
function saveTeams(matchId) {
    const data = { team1, team2, reserve };
    localStorage.setItem(`balancedTeams_${matchId}`, JSON.stringify(data));
}

// Carregar times salvos
function loadTeams(matchId) {
    const saved = localStorage.getItem(`balancedTeams_${matchId}`);
    if (saved) {
        const data = JSON.parse(saved);
        team1 = data.team1 || [];
        team2 = data.team2 || [];
        reserve = data.reserve || [];
        renderTeam(1);
        renderTeam(2);
        renderTeam('reserve');
        balanced = true;
        document.getElementById('startBtn').disabled = false;
    }
}

// Navegação
function goHome() { window.location.href = '/'; }
function editPlayers() {
    const id = getMatchIdFromUrl();
    if (!id) { alert('Não foi possível determinar o matchId da URL.'); return; }
    window.location.href = `/matchgame/${id}/list/edit`;
}

// Balancear times
async function balanceTeams() {
    const playersPerTeam = parseInt(document.getElementById('playersPerTeam').value, 10);
    if (!playersPerTeam || playersPerTeam < 1) {
        alert('Informe o número de jogadores por time.');
        return;
    }

    const matchId = getMatchIdFromUrl();
    if (!matchId) { alert('Não foi possível determinar o matchId da URL.'); return; }

    try {
        const res = await fetch(`/matchgame/${matchId}/teams/generate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ playersPerTeam })
        });

        if (!res.ok) throw new Error('Erro ao gerar times');
        const data = await res.json();

        team1 = Object.values(data.teams.team1);
        team2 = Object.values(data.teams.team2);
        reserve = Object.values(data.teams.bench);

        renderTeam(1);
        renderTeam(2);
        renderTeam('reserve');

        document.getElementById('response').innerHTML = '<div class="alert alert-success">Times balanceados com sucesso!</div>';
        balanced = true;
        document.getElementById('startBtn').disabled = false;

        // salvar no storage
        saveTeams(matchId);
    } catch (err) {
        document.getElementById('response').innerHTML = `<div class="alert alert-danger">${err.message}</div>`;
        balanced = false;
        document.getElementById('startBtn').disabled = true;
    }
}

// Início da partida
function startMatch() {
    const matchId = getMatchIdFromUrl();
    if (!matchId) { alert('Não foi possível determinar o matchId da URL.'); return; }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/api/matchgame/${matchId}/start`;

    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    form.appendChild(csrfInput);

    document.body.appendChild(form);
    form.submit();
}

// Inicialização
const currentMatchId = getMatchIdFromUrl();
if (currentMatchId) {
    loadTeams(currentMatchId); // carrega se existir
}
</script>
</body>
</html>
