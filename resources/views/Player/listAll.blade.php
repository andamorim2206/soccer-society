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
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">👥 Jogadores Cadastrados</h3>
            <a href="/player/create" class="btn btn-light">➕ Cadastrar Jogador</a>
        </div>
        <div class="card-body">

            <div id="response"></div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nome</th>
                            <th>Posição</th>
                            <th>XP</th>
                        </tr>
                    </thead>
                    <tbody id="playersTableBody">
                        <!-- Jogadores carregados via JS -->
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap JS + jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
async function loadPlayers() {
    const tableBody = document.getElementById('playersTableBody');
    tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Carregando...</td></tr>';

    try {
        const res = await fetch('/api/players/list');
        const players = await res.json();

        if (res.ok && players.length > 0) {
            tableBody.innerHTML = players.map(player => `
                <tr>
                    <td>${player.name}</td>
                    <td>${player.position}</td>
                    <td>${player.xp}</td>
                </tr>
            `).join('');
        } else {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Nenhum jogador encontrado</td></tr>';
        }
    } catch (err) {
        tableBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Erro: ${err}</td></tr>`;
    }
}

async function deletePlayer(id) {
    if (!confirm('Tem certeza que deseja remover este jogador?')) return;

    try {
        const res = await fetch(`/api/players/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (res.ok) {
            alert('Jogador removido com sucesso!');
            loadPlayers();
        } else {
            alert('Erro ao remover o jogador.');
        }
    } catch (err) {
        alert('Erro: ' + err);
    }
}

document.addEventListener('DOMContentLoaded', loadPlayers);
</script>
</body>
</html>
