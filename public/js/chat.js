(function () {
    var form = document.getElementById('chat-form');
    var input = document.getElementById('chat-input');
    var box = document.getElementById('chat-messages');
    var sendBtn = document.getElementById('chat-send');
    if (!form || !input || !box) return;

    // ---------- Estilos responsivos del chat (aplican a todas las páginas) ----------
    var css = ''
        // Escritorio: panel flotante, con alto calculado para no pisar el menú
        + '#chat-panel{width:420px;max-width:calc(100vw - 2.5rem);'
        +   'height:min(560px,calc(100vh - 15rem));height:min(560px,calc(100dvh - 15rem));min-height:280px;}'
        + '#chat-messages{flex:1 1 0;height:auto;min-height:0;max-height:none;}'
        + '#chat-messages p{font-size:.875rem;}'
        + '#chat-input{font-size:.875rem;}'
        // Celular o pantalla baja: chat a pantalla completa
        + '@media (max-width:640px),(max-height:500px){'
        +   '#chat-widget{z-index:100000;}'
        +   '#chat-panel{position:fixed;top:0;right:0;bottom:0;left:0;width:100%;max-width:none;'
        +     'height:100vh;height:100dvh;min-height:0;border-radius:0;}'
        +   '#chat-panel.flex ~ #chat-toggle{display:none;}'
        +   '#chat-form{padding-bottom:calc(.75rem + env(safe-area-inset-bottom,0px));}'
        +   '#chat-input{font-size:16px;}' // 16px evita el zoom automático en iPhone
        + '}';
    var style = document.createElement('style');
    style.textContent = css;
    document.head.appendChild(style);

    // ---------- Mensajes ----------
    var bubbleBot = 'max-w-[85%] bg-white dark:bg-gray-700 border border-gray-100 dark:border-gray-600 text-gray-700 dark:text-gray-100 leading-relaxed px-3 py-2 rounded-2xl rounded-tl-sm shadow-sm';
    var bubbleUser = 'max-w-[85%] bg-[#22C55E] text-white leading-relaxed px-3 py-2 rounded-2xl rounded-tr-sm shadow-sm';

    function addMessage(text, isUser) {
        var row = document.createElement('div');
        row.className = 'flex' + (isUser ? ' justify-end' : '');
        var p = document.createElement('p');
        p.className = isUser ? bubbleUser : bubbleBot;
        p.style.whiteSpace = 'pre-wrap';
        p.style.wordBreak = 'break-word';
        p.textContent = text;
        row.appendChild(p);
        box.appendChild(row);
        box.scrollTop = box.scrollHeight;
        return row;
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        var text = input.value.trim();
        if (!text) return;
        input.value = '';
        addMessage(text, true);
        var typing = addMessage('Escribiendo...', false);
        if (sendBtn) sendBtn.disabled = true;

        try {
            var res = await fetch('/api/chat/send', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ message: text })
            });
            var data = await res.json().catch(function () { return {}; });
            typing.remove();
            addMessage(data.response || 'No pude responder en este momento.', false);
        } catch (err) {
            typing.remove();
            addMessage('No se pudo conectar con el asistente. Intenta de nuevo.', false);
        } finally {
            if (sendBtn) sendBtn.disabled = false;
            input.focus();
        }
    });
})();