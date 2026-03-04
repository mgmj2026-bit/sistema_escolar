<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Chat súper en vivo</h1>
    <a href="/dashboard" class="btn btn-outline-secondary btn-sm">Volver</a>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body" id="chat-box" style="height: 360px; overflow-y: auto;">
        <?php foreach ($messages as $message): ?>
            <div class="mb-2" data-id="<?= (int) $message['id'] ?>">
                <div class="small text-muted"><?= e($message['name']) ?> · <?= e($message['created_at']) ?></div>
                <div class="p-2 rounded bg-light"><?= e($message['body']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<form class="card border-0 shadow-sm" action="/chat" method="post">
    <div class="card-body">
        <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
        <div class="input-group">
            <input class="form-control" maxlength="500" name="body" placeholder="Escribe un mensaje..." required>
            <button class="btn btn-primary">Enviar</button>
        </div>
    </div>
</form>

<script>
(() => {
    const chatBox = document.getElementById('chat-box');
    let lastId = 0;
    for (const node of chatBox.querySelectorAll('[data-id]')) {
        lastId = Math.max(lastId, Number(node.dataset.id || 0));
    }

    const playBeep = () => {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        oscillator.type = 'sine';
        oscillator.frequency.setValueAtTime(880, audioContext.currentTime);
        gainNode.gain.setValueAtTime(0.03, audioContext.currentTime);
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        oscillator.start();
        oscillator.stop(audioContext.currentTime + 0.12);
    };

    const appendMessage = (msg) => {
        const wrapper = document.createElement('div');
        wrapper.className = 'mb-2';
        wrapper.dataset.id = String(msg.id);
        wrapper.innerHTML = `
            <div class="small text-muted">${msg.name} · ${msg.created_at}</div>
            <div class="p-2 rounded bg-light"></div>
        `;
        wrapper.querySelector('.bg-light').textContent = msg.body;
        chatBox.appendChild(wrapper);
        chatBox.scrollTop = chatBox.scrollHeight;
    };

    setInterval(async () => {
        const res = await fetch(`/chat/poll?last_id=${lastId}`, { headers: { 'Accept': 'application/json' } });
        if (!res.ok) return;
        const data = await res.json();
        if (!Array.isArray(data.messages) || data.messages.length === 0) return;

        for (const msg of data.messages) {
            appendMessage(msg);
            lastId = Math.max(lastId, Number(msg.id || 0));
            if (msg.name !== <?= json_encode($user['name']) ?>) {
                playBeep();
            }
        }
    }, 2500);
})();
</script>
