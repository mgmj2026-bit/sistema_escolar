<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Panel central seguro</h1>
        <p class="text-muted mb-0">Hola, <?= e($user['name']) ?> (<?= e($user['role']) ?>).</p>
    </div>
    <div class="d-flex gap-2">
        <a href="/chat" class="btn btn-success">Chat privado</a>
        <a href="/reportes" class="btn btn-outline-primary">Reportes</a>
    </div>
</div>

<div class="row g-3">
    <?php foreach ($modules as $module): ?>
    <div class="col-md-6 col-xl-4"><div class="card border-0 shadow-sm h-100"><div class="card-body">
        <h2 class="h5"><i class="bi <?= e($module['icon']) ?> text-primary"></i> <?= e($module['title']) ?></h2>
        <p class="text-muted"><?= e($module['desc']) ?></p>
        <a href="/modulo?slug=<?= e($module['slug']) ?>" class="btn btn-outline-primary btn-sm">Abrir módulo</a>
    </div></div></div>
    <?php endforeach; ?>
</div>

<div class="card mt-4 border-0 shadow-sm"><div class="card-body">
    <h2 class="h5">Cambiar contraseña</h2>
    <form method="post" action="/cambiar-clave" class="row g-2">
        <input type="hidden" name="_csrf" value="<?= e(App\Core\Security::csrfToken()) ?>">
        <div class="col-md-6"><input type="password" class="form-control" name="password" minlength="8" required placeholder="Nueva contraseña"></div>
        <div class="col-md-3"><button class="btn btn-warning">Actualizar</button></div>
    </form>
</div></div>
