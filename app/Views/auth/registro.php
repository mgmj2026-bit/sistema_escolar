<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Registro de usuario</h1>
                <form method="post" action="/registro">
                    <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
                    <div class="mb-3"><label class="form-label">Nombre completo</label><input class="form-control" name="name" required></div>
                    <div class="mb-3"><label class="form-label">Correo</label><input type="email" class="form-control" name="email" required></div>
                    <div class="mb-3"><label class="form-label">Rol</label>
                        <select class="form-select" name="role" required>
                            <option value="estudiante">Estudiante</option><option value="padre">Padre de familia</option><option value="docente">Docente</option>
                            <option value="auxiliar">Auxiliar</option><option value="secretaria">Secretaria</option><option value="subdirector">Subdirector(a)</option><option value="director">Director(a)</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Contraseña</label><input type="password" class="form-control" name="password" minlength="8" required></div>
                    <button class="btn btn-primary">Registrar y enviar verificación</button>
                </form>
            </div>
        </div>
    </div>
</div>
