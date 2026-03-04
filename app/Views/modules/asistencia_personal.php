<div class="card border-0 shadow-sm"><div class="card-body">
    <h1 class="h4">Asistencia de personal</h1>
    <p>Este registro es independiente de la asistencia estudiantil.</p>
    <form method="post" action="<?= e(app_url('/asistencia/personal')) ?>">
        <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
        <button class="btn btn-primary">Marcar mi asistencia</button>
    </form>
</div></div>
<?php if (!empty($_SESSION['voz'])): ?>
<script>
const texto = <?= json_encode($_SESSION['voz']) ?>;
const utter = new SpeechSynthesisUtterance(texto); utter.lang='es-ES'; window.speechSynthesis.speak(utter);
</script>
<?php unset($_SESSION['voz']); endif; ?>
