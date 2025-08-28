<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gerar Partida</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h3>⚽ Criar Nova Partida</h3>
        </div>
        <div class="card-body">
            <div id="response"></div>

            <form id="matchForm">
                <!-- Nome da partida -->
                <div class="form-group">
                    <label for="name">Nome da Partida:</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Digite o nome da partida" required>
                </div>

                <button type="submit" class="btn btn-success btn-block">Gerar Partida</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS + jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const form = document.getElementById('matchForm');
const responseDiv = document.getElementById('response');

form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    try {
        const res = await fetch('/api/matchgame/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });

        const result = await res.json();

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