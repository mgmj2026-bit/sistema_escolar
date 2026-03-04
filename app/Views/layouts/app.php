<?php /** @var string $viewPath */ ?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e((string) env('APP_NAME', 'Sistema Escolar')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>body{background:#f4f7fb}.glass{background:#ffffffdd;backdrop-filter:blur(6px);border-radius:16px}</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4"><div class="container-fluid">
    <a class="navbar-brand fw-bold" href="/dashboard">🎓 Sistema Escolar Inteligente</a>
    <?php if (!empty($_SESSION['user'])): ?>
        <div class="d-flex gap-2 align-items-center">
            <a class="btn btn-sm btn-light" href="/asistencia/personal">Asistencia personal</a>
            <a class="btn btn-sm btn-light" href="/asistencia/mi">Mi asistencia</a>
            <span class="text-white small"><?= e($_SESSION['user']['name']) ?></span>
            <form action="/logout" method="post"><button class="btn btn-sm btn-warning">Salir</button></form>
        </div>
    <?php endif; ?>
</div></nav>
<main class="container pb-5"><?php require $viewPath; ?></main>
</body></html>
