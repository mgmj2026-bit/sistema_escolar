<div class="row justify-content-center"><div class="col-md-5">
<div class="card border-0 shadow-sm"><div class="card-body p-4">
<h1 class="h4">Segundo factor (2FA)</h1>
<?php if (!empty($_SESSION['error'])): ?><div class="alert alert-danger"><?= e($_SESSION['error']); unset($_SESSION['error']); ?></div><?php endif; ?>
<?php if (!empty($_SESSION['2fa_ok'])): ?><div class="alert alert-info"><?= e($_SESSION['2fa_ok']); ?></div><?php endif; ?>
<form method="post" action="<?= e(app_url('/2fa')) ?>"><input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
<label class="form-label">Código de 6 dígitos</label><input class="form-control mb-3" name="code" pattern="\d{6}" required>
<button class="btn btn-primary w-100">Validar código</button></form>
</div></div></div></div>
