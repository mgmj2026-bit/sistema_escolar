<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Panel central blindado</h1>
        <p class="text-muted mb-0">Hola, <?= e($user['name']) ?>. Arquitectura modular, monitoreable y preparada para crecimiento.</p>
    </div>
    <a href="/chat" class="btn btn-success"><i class="bi bi-chat-left-dots-fill"></i> Chat en vivo</a>
</div>

<div class="row g-3">
    <?php foreach ($modules as $module): ?>
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h5"><i class="bi <?= e($module['icon']) ?> text-primary"></i> <?= e($module['title']) ?></h2>
                    <p class="text-muted"><?= e($module['desc']) ?></p>
                    <a href="/modulo?slug=<?= e($module['slug']) ?>" class="btn btn-outline-primary btn-sm">Abrir módulo</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
