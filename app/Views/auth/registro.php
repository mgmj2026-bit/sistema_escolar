<div class="row justify-content-center"><div class="col-md-7 col-lg-6"><div class="card shadow-sm border-0"><div class="card-body p-4">
<h1 class="h4 mb-3">Registro por DNI institucional</h1>
<?php if (!empty($_SESSION['error'])): ?><div class="alert alert-danger"><?= e($_SESSION['error']); unset($_SESSION['error']); ?></div><?php endif; ?>
<form method="post" action="/registro">
<input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
<div class="mb-3"><label class="form-label">DNI (8 dígitos)</label><input class="form-control" name="dni" pattern="\d{8}" required></div>
<div class="mb-3"><label class="form-label">Correo</label><input type="email" class="form-control" name="email" required></div>
<div class="mb-3"><label class="form-label">Contraseña</label><input type="password" class="form-control" name="password" minlength="8" required></div>
<div class="mb-2"><label class="form-label">CAPTCHA</label>
<div class="d-flex align-items-center gap-2"><code id="captcha-text" class="p-2 bg-light border rounded"><?= e($captcha) ?></code><button type="button" id="leer-captcha" class="btn btn-sm btn-outline-secondary">🔊</button></div>
<input class="form-control mt-2" name="captcha" placeholder="Escribe el código" required>
</div>
<button class="btn btn-primary">Validar DNI y registrar cuenta</button>
</form>
</div></div></div></div>
<script>document.getElementById('leer-captcha')?.addEventListener('click',()=>{const t=document.getElementById('captcha-text')?.textContent||'';const u=new SpeechSynthesisUtterance(t.split('').join(' '));u.lang='es-ES';speechSynthesis.speak(u);});</script>
