<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastrar Player</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Ícones (Bootstrap Icons) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h3>Cadastrar Novo Player</h3>
            <a href="/" class="btn btn-light btn-sm">
                <i class="bi bi-house-fill"></i> Home
            </a>
        </div>
        <div class="card-body">
            <div id="response"></div>

            <form id="playerForm">
                <!-- Nome -->
                <div class="form-group">
                    <label for="name">Nome:</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nome do jogador" required>
                </div>

                <!-- Posição -->
                <div class="form-group">
                    <label for="position">Posição:</label>
                    <select name="position" id="position" class="form-control" required>
                        <option value="">Selecione a posição</option>
                        <option value="Goleiro">Goleiro</option>
                        <option value="Zagueiro">Zagueiro</option>
                        <option value="Meio-campo">Meio-campo</option>
                        <option value="Atacante">Atacante</option>
                    </select>
                </div>

                <!-- XP -->
                <div class="form-group">
                    <label for="xp">XP:</label>
                    <input type="number" name="xp" id="xp" class="form-control" min="0" max="255" value="0" required>
                </div>

                <button type="submit" class="btn btn-success btn-block">Criar Player</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS + jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const form = document.getElementById('playerForm');
const responseDiv = document.getElementById('response');

form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    try {
        const res = await fetch('/api/players', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });

        const result = await res.json();
        console.log(result);
        if (res.ok) {
            responseDiv.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
            form.reset();
        } else {
            responseDiv.innerHTML = `<div class="alert alert-danger">${JSON.stringify(result)}</div>`;
        }
    } catch (err) {
        responseDiv.innerHTML = `<div class="alert alert-danger">Erro: ${err}</div>`;
    }
});
</script>
</body>
</html>
