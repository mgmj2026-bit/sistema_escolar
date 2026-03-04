<div class="row g-3">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h2 class="h5">Contactos por rol</h2>
                <?php foreach ($contactos as $rol => $lista): if (empty($lista)) continue; ?>
                    <div class="mt-3">
                        <strong class="text-uppercase small text-muted"><?= e($rol) ?></strong>
                        <?php foreach ($lista as $c): ?>
                            <div><a href="/chat?contacto=<?= (int) $c['id'] ?>" class="text-decoration-none <?= $contactoId === (int)$c['id'] ? 'fw-bold' : '' ?>"><?= e($c['name']) ?></a></div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>

                <?php if ($user['role'] === 'docente'): ?>
                    <hr>
                    <h3 class="h6">Filtrar estudiantes por grado/sección que enseñas</h3>
                    <select id="seccion-docente" class="form-select form-select-sm">
                        <option value="">Selecciona sección</option>
                        <?php foreach ($secciones as $s): ?><option value="<?= (int)$s['id'] ?>"><?= e($s['nombre']) ?></option><?php endforeach; ?>
                    </select>
                    <div id="lista-estudiantes" class="mt-2"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3"><div class="card-body" id="chat-box" style="height: 390px; overflow-y:auto;">
            <?php foreach ($mensajes as $m): ?>
                <div class="mb-2" data-id="<?= (int)$m['id'] ?>">
                    <div class="small text-muted"><?= e($m['remitente']) ?> · <?= e($m['created_at']) ?></div>
                    <div class="p-2 rounded <?= (int)$m['sender_id'] === (int)$user['id'] ? 'bg-primary text-white' : 'bg-light' ?>"><?= e($m['body']) ?></div>
                </div>
            <?php endforeach; ?>
        </div></div>

        <?php if ($contactoId > 0): ?>
        <form class="card border-0 shadow-sm" action="/chat" method="post">
            <div class="card-body">
                <input type="hidden" name="_csrf" value="<?= e($csrf) ?>"><input type="hidden" name="receiver_id" value="<?= (int)$contactoId ?>">
                <div class="input-group"><input class="form-control" maxlength="500" name="body" required placeholder="Escribe un mensaje..."><button class="btn btn-primary">Enviar</button></div>
            </div>
        </form>
        <?php else: ?><div class="alert alert-info">Selecciona un contacto permitido para iniciar chat privado.</div><?php endif; ?>
    </div>
</div>

<script>
(() => {
 const chatBox=document.getElementById('chat-box'); if(!chatBox) return;
 let lastId=0; for(const n of chatBox.querySelectorAll('[data-id]')) lastId=Math.max(lastId, Number(n.dataset.id||0));
 const contacto=<?= (int)$contactoId ?>;
 const yo=<?= json_encode($user['name']) ?>;
 const beep=()=>{const a=new(window.AudioContext||window.webkitAudioContext)();const o=a.createOscillator();const g=a.createGain();o.frequency.value=880;g.gain.value=0.03;o.connect(g);g.connect(a.destination);o.start();o.stop(a.currentTime+.1);};
 const append=(m)=>{const d=document.createElement('div');d.className='mb-2';d.dataset.id=m.id;d.innerHTML=`<div class="small text-muted">${m.remitente} · ${m.created_at}</div><div class="p-2 rounded ${Number(m.sender_id)===<?= (int)$user['id'] ?>?'bg-primary text-white':'bg-light'}"></div>`;d.querySelector('.p-2').textContent=m.body;chatBox.appendChild(d);chatBox.scrollTop=chatBox.scrollHeight;};
 if(contacto>0){setInterval(async()=>{const r=await fetch(`/chat/poll?contacto=${contacto}&last_id=${lastId}`); if(!r.ok)return; const d=await r.json(); for(const m of (d.messages||[])){append(m);lastId=Math.max(lastId,Number(m.id||0)); if(m.remitente!==yo) beep();}},2000);} 
 const sel=document.getElementById('seccion-docente'); if(sel){sel.addEventListener('change', async()=>{if(!sel.value) return; const r=await fetch(`/chat/estudiantes?seccion_id=${sel.value}`); const d=await r.json(); const box=document.getElementById('lista-estudiantes'); box.innerHTML=''; (d.estudiantes||[]).forEach(e=>{const a=document.createElement('a');a.href=`/chat?contacto=${e.id}`;a.className='d-block';a.textContent=e.name;box.appendChild(a);});});}
})();
</script>
