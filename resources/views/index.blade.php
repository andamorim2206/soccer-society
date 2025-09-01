<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Soccer Society - Início</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="container mt-5">

    <!-- Card principal -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Soccer Society - Gerenciamento</h3>
        </div>
        <div class="card-body">

            <!-- Botões -->
            <div class="mb-4 d-flex justify-content-around">
                <a href="{{ route('matchgame.create') }}" class="btn btn-success btn-lg">⚽ Gerar Partida</a>
                <a href="/player/create" class="btn btn-info btn-lg">➕ Adicionar Jogadores</a>
                <a href="/player/list" class="btn btn-secondary btn-lg">👥 Listar Jogadores</a>
            </div>

            <!-- Listagem de partidas -->
            <h4 class="mt-4 mb-3">📋 Partidas Criadas</h4>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Status</th>
                            <th>Criado em</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="matchesTableBody">
                        <!-- Partidas serão carregadas via JS -->
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
async function loadMatches() {
    const tableBody = document.getElementById('matchesTableBody');
    tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Carregando...</td></tr>';

    try {
        const res = await fetch('/api/matchGame/list');
        const matches = await res.json();

        if (res.ok && matches.length > 0) {
            tableBody.innerHTML = matches.map(match => `
                <tr>
                    <td>${match.id}</td>
                    <td>${match.name}</td>
                    <td><span class="badge badge-${statusColor(match.status)}">${match.status}</span></td>
                    <td>${new Date(match.created_at).toLocaleString()}</td>
                    <td>
                         <button onclick="showMatchDetails(${match.id})" class="btn btn-sm btn-info">Detalhes</button>
                        <button onclick="endMatch(${match.id})" class="btn btn-sm btn-danger">Finalizar</button>
                    </td>
                </tr>
            `).join('');
        } else {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Nenhuma partida encontrada</td></tr>';
        }
    } catch (err) {
        tableBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Erro: ${err}</td></tr>`;
    }
}

function statusColor(status) {
    switch (status) {
        case 'pendente': return 'warning';
        case 'preparado': return 'info';
        case 'iniciado': return 'success';
        case 'finalizado': return 'secondary';
        default: return 'dark';
    }
}

function showMatchDetails(id) {
    window.location.href = `/matchgame/${id}/generate/teams`;
}

async function endMatch(id) {
    if (!confirm('Tem certeza que deseja cancelar esta partida?')) return;

    try {
        const res = await fetch(`/api/matchGame/${id}/finalized`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (res.ok) {
            alert('Partida finalizada com sucesso!');
            loadMatches();
        } else {
            alert('Erro ao cancelar a partida.');
        }
    } catch (err) {
        alert('Erro: ' + err);
    }
}

document.addEventListener('DOMContentLoaded', loadMatches);
</script>
</body>
</html>