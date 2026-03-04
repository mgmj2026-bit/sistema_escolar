<?php /** @var string $viewPath */ ?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e((string) env('APP_NAME', 'Sistema Escolar')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7fb; }
        .glass { background: #ffffffdd; backdrop-filter: blur(6px); border-radius: 16px; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="/dashboard">🎓 Sistema Escolar Inteligente</a>
        <?php if (!empty($_SESSION['user'])): ?>
            <div class="d-flex align-items-center gap-3 text-white">
                <span><i class="bi bi-person-circle"></i> <?= e($_SESSION['user']['name']) ?></span>
                <form action="/logout" method="post">
                    <button class="btn btn-sm btn-light">Salir</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</nav>

<main class="container pb-5">
    <?php require $viewPath; ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
