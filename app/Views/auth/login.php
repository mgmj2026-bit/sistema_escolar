<div class="row justify-content-center"><div class="col-md-6 col-lg-5"><div class="card shadow-sm border-0 glass"><div class="card-body p-4">
<h1 class="h4 mb-3">Ingreso seguro al sistema escolar</h1>
<?php if (!empty($_SESSION['error'])): ?><div class="alert alert-danger"><?= e($_SESSION['error']); unset($_SESSION['error']); ?></div><?php endif; ?>
<?php if (!empty($_SESSION['ok'])): ?><div class="alert alert-success"><?= e($_SESSION['ok']); unset($_SESSION['ok']); ?></div><?php endif; ?>
<form method="post" action="<?= e(app_url('/login')) ?>" autocomplete="off">
<input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
<div class="mb-3"><label class="form-label">Correo</label><input type="email" class="form-control" name="email" required></div>
<div class="mb-3"><label class="form-label">Contraseña</label><input type="password" class="form-control" name="password" required></div>
<div class="mb-2"><label class="form-label">CAPTCHA</label>
<div class="d-flex align-items-center gap-2"><code id="captcha-text" class="p-2 bg-light border rounded"><?= e($captcha) ?></code><button type="button" id="leer-captcha" class="btn btn-sm btn-outline-secondary">🔊</button></div>
<input class="form-control mt-2" name="captcha" placeholder="Escribe el código" required>
</div>
<button class="btn btn-primary w-100">Entrar</button>
</form>
<a href="<?= e(app_url('/registro')) ?>" class="btn btn-link px-0 mt-2">Crear cuenta nueva</a>
</div></div></div></div>
<script>document.getElementById('leer-captcha')?.addEventListener('click',()=>{const t=document.getElementById('captcha-text')?.textContent||'';const u=new SpeechSynthesisUtterance(t.split('').join(' '));u.lang='es-ES';speechSynthesis.speak(u);});</script>
