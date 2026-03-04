<div class="card border-0 shadow-sm"><div class="card-body">
    <h1 class="h4">Mi asistencia (solo lectura)</h1>
    <table class="table table-sm"><thead><tr><th>Fecha</th><th>Estado</th><th>Registrado</th></tr></thead><tbody>
    <?php foreach ($registros as $r): ?><tr><td><?= e($r['fecha']) ?></td><td><?= e($r['estado']) ?></td><td><?= e($r['created_at']) ?></td></tr><?php endforeach; ?>
    </tbody></table>
</div></div>
