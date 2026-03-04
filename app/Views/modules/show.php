<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <a href="/dashboard" class="btn btn-link p-0 mb-3">← Volver al panel</a>
        <h1 class="h3"><i class="bi <?= e($module['icon']) ?>"></i> <?= e($module['title']) ?></h1>
        <p class="text-muted"><?= e($module['desc']) ?></p>

        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <div class="p-3 border rounded bg-light">
                    <h2 class="h6">Seguridad aplicada</h2>
                    <ul class="mb-0">
                        <li>Control de acceso por sesión.</li>
                        <li>Protección CSRF.</li>
                        <li>Headers de hardening y CSP.</li>
                        <li>Consultas SQL preparadas.</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 border rounded bg-light">
                    <h2 class="h6">Escalabilidad</h2>
                    <ul class="mb-0">
                        <li>Arquitectura MVC modular.</li>
                        <li>Separación por dominios.</li>
                        <li>Preparado para microservicios futuros.</li>
                        <li>Trazabilidad y auditoría.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
