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

    // Survives navigation in this tab; a reload or authentication change clears it.
    // Access tokens are never stored in the transcript.
    var historyKey = 'casatek.chat.v1';
    var messages = [];
    var maxMessages = 60;
    var maxAge = 2 * 60 * 60 * 1000;
    var welcome = box.innerHTML;
    var identity = currentIdentity();

    function currentIdentity() {
        try {
            var token = (localStorage.getItem('token') || '').replace(/^"|"$/g, '');
            var user = JSON.parse(localStorage.getItem('user') || 'null');
            return { token: token, scope: token ? (user && user.id != null ? 'user:' + user.id : null) : 'guest' };
        } catch (err) {
            return { token: '', scope: null };
        }
    }

    function syncIdentity() {
        var next = currentIdentity();
        if (next.scope !== identity.scope || next.token !== identity.token) {
            identity = next;
            messages = [];
            box.innerHTML = welcome;
            try { sessionStorage.removeItem(historyKey); } catch (err) {}
        }
        return next;
    }

    function saveHistory() {
        if (!identity.scope) return;
        try {
            sessionStorage.setItem(historyKey, JSON.stringify({
                scope: identity.scope, savedAt: Date.now(), messages: messages.slice(-maxMessages)
            }));
        } catch (err) { /* Storage may be disabled; the chat still works. */ }
    }

    function restoreHistory(checkReload) {
        try {
            var navigation = window.performance && window.performance.getEntriesByType
                ? window.performance.getEntriesByType('navigation')[0] : null;
            var isReload = navigation ? navigation.type === 'reload'
                : window.performance && window.performance.navigation && window.performance.navigation.type === 1;
            if (checkReload && isReload) {
                sessionStorage.removeItem(historyKey);
                return;
            }
            var saved = JSON.parse(sessionStorage.getItem(historyKey) || 'null');
            if (!saved) return;
            if (!identity.scope || saved.scope !== identity.scope || !Number.isFinite(saved.savedAt)
                || Date.now() - saved.savedAt > maxAge || !Array.isArray(saved.messages)) {
                sessionStorage.removeItem(historyKey);
                return;
            }
            messages = saved.messages.filter(function (m) {
                return m && typeof m.text === 'string' && m.text.length <= 6000 && typeof m.isUser === 'boolean';
            }).slice(-maxMessages);
            if (messages.length) {
                box.innerHTML = '';
                messages.forEach(function (m) { addMessage(m.text, m.isUser, false); });
            }
        } catch (err) { /* Ignore an unreadable or unavailable transcript. */ }
    }

    function addMessage(text, isUser, persist) {
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
        if (persist !== false) {
            messages.push({ text: text, isUser: isUser });
            messages = messages.slice(-maxMessages);
            saveHistory();
        }
        return row;
    }

    restoreHistory(true);
    window.addEventListener('storage', function (event) {
        if (event.key === 'token' || event.key === 'user' || event.key === null) syncIdentity();
    });
    window.addEventListener('pageshow', function (event) {
        syncIdentity();
        // A page restored by Back/Forward must respect a transcript cleared elsewhere.
        if (event.persisted) {
            messages = [];
            box.innerHTML = welcome;
            restoreHistory();
        }
    });
    // Login/logout changes localStorage in the same page, without a storage event.
    // Clear before navigation, including when the same account signs in again.
    window.addEventListener('pagehide', syncIdentity);
    window.addEventListener('focus', syncIdentity);

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        var requestIdentity = syncIdentity();
        var text = input.value.trim();
        if (!text || (sendBtn && sendBtn.disabled)) return;
        if (text.length > 500) {
            addMessage('Escribe un mensaje de hasta 500 caracteres.', false);
            return;
        }
        var context = messages.slice(-4).map(function (m) {
            return { role: m.isUser ? 'user' : 'assistant', content: m.text.slice(0, 1200) };
        });
        input.value = '';
        addMessage(text, true);
        var typing = addMessage('Escribiendo...', false, false);
        if (sendBtn) sendBtn.disabled = true;

        try {
            var headers = { 'Content-Type': 'application/json', 'Accept': 'application/json' };
            var token = requestIdentity.token;
            if (token) headers.Authorization = 'Bearer ' + token;
            var res = await fetch('/api/chat/send', {
                method: 'POST',
                headers: headers,
                body: JSON.stringify({ message: text, history: context })
            });
            var data = await res.json().catch(function () { return {}; });
            typing.remove();
            syncIdentity();
            if (identity.scope !== requestIdentity.scope || identity.token !== requestIdentity.token) return;
            addMessage(data.response || (res.status === 429 ? 'Alcanzaste el límite de mensajes. Espera un minuto.' : 'No pude responder en este momento.'), false);
        } catch (err) {
            typing.remove();
            syncIdentity();
            if (identity.scope !== requestIdentity.scope || identity.token !== requestIdentity.token) return;
            addMessage('No se pudo conectar con el asistente. Intenta de nuevo.', false);
        } finally {
            if (sendBtn) sendBtn.disabled = false;
            input.focus();
        }
    });
})();
