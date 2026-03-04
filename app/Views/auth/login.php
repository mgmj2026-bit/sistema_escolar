<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm border-0 glass">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Ingreso seguro</h1>
                <p class="text-muted">Autenticación robusta + sesiones protegidas</p>

                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= e($_SESSION['error']) ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <form method="post" action="/login" autocomplete="off">
                    <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
                    <div class="mb-3">
                        <label class="form-label">Correo</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <button class="btn btn-primary w-100">Entrar</button>
                </form>
                <small class="d-block mt-3 text-muted">Usuario demo: admin@school.local / Admin123*</small>
            </div>
        </div>
    </div>
</div>
