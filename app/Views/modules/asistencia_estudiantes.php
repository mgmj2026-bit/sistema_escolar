<div class="card border-0 shadow-sm"><div class="card-body">
    <h1 class="h4">Asistencia de estudiantes</h1>
    <p>Solo docentes pueden registrar asistencia de sus secciones asignadas.</p>
    <form method="post" action="/asistencia/estudiantes" class="row g-2">
        <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
        <div class="col-md-4"><input class="form-control" name="student_id" placeholder="ID estudiante" required></div>
        <div class="col-md-4"><select class="form-select" name="estado"><option value="presente">Presente</option><option value="tarde">Tarde</option><option value="ausente">Ausente</option></select></div>
        <div class="col-md-4"><button class="btn btn-primary">Guardar asistencia</button></div>
    </form>
</div></div>
