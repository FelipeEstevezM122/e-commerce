/* =====================================================================
 * admin-logout.js  — script compartido por TODAS las vistas del panel admin
 *   - confirmarSalir(): muestra un mensaje de confirmación y, si se acepta,
 *     cierra la sesión (token) y manda a /iniciarsesion.html
 *   - adminToken() / adminAuthHeaders(): helpers del token Bearer
 * Uso en cada vista:  <script src="/js/admin-logout.js"></script>
 *                     <button class="ah-btn-logout" onclick="confirmarSalir()">
 * ===================================================================== */
(function () {
    var LOGIN_URL = '/iniciarsesion.html';
    var TOKEN_KEYS = ['token', 'auth_token', 'access_token', 'authToken', 'admin_token'];

    window.adminToken = function () {
        for (var i = 0; i < TOKEN_KEYS.length; i++) {
            try {
                var v = localStorage.getItem(TOKEN_KEYS[i]);
                if (v) return v.replace(/^"|"$/g, '');
            } catch (e) {}
        }
        return '';
    };
    window.adminAuthHeaders = function () {
        return { 'Accept': 'application/json', 'Authorization': 'Bearer ' + window.adminToken() };
    };

    function limpiarSesion() {
        TOKEN_KEYS.concat(['user', 'role', 'usuario']).forEach(function (k) {
            try { localStorage.removeItem(k); } catch (e) {}
        });
    }

    function inyectarEstilos() {
        if (document.getElementById('logoutModalStyles')) return;
        var st = document.createElement('style');
        st.id = 'logoutModalStyles';
        st.textContent =
            '#logoutOverlay{position:fixed;inset:0;background:rgba(0,0,0,.72);backdrop-filter:blur(4px);display:none;align-items:center;justify-content:center;z-index:9999;padding:16px}' +
            '#logoutOverlay.open{display:flex}' +
            '#logoutBox{background:#111f16;border:1px solid rgba(239,68,68,.35);border-radius:16px;padding:28px 26px;max-width:380px;width:100%;text-align:center;color:#f3f4f6;font-family:"DM Sans",system-ui,sans-serif;box-shadow:0 20px 60px rgba(0,0,0,.6)}' +
            '#logoutBox .lo-icon{width:54px;height:54px;border-radius:50%;background:rgba(239,68,68,.12);color:#f87171;display:flex;align-items:center;justify-content:center;font-size:22px;margin:0 auto 14px}' +
            '#logoutBox h3{font-size:18px;font-weight:700;margin:0 0 6px}' +
            '#logoutBox p{font-size:13px;color:#9ca3af;margin:0 0 20px}' +
            '#logoutBox .lo-actions{display:flex;gap:10px}' +
            '#logoutBox button{flex:1;padding:11px 14px;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;font-family:inherit;border:1px solid transparent}' +
            '#logoutBox .lo-cancel{background:transparent;border-color:rgba(255,255,255,.12);color:#9ca3af}' +
            '#logoutBox .lo-cancel:hover{color:#fff;border-color:rgba(255,255,255,.3)}' +
            '#logoutBox .lo-ok{background:#ef4444;color:#fff}' +
            '#logoutBox .lo-ok:hover{background:#dc2626}' +
            '#logoutBox .lo-ok:disabled{opacity:.6;cursor:wait}';
        document.head.appendChild(st);
    }

    function crearModal() {
        var ov = document.getElementById('logoutOverlay');
        if (ov) return ov;
        ov = document.createElement('div');
        ov.id = 'logoutOverlay';
        ov.innerHTML =
            '<div id="logoutBox" role="dialog" aria-modal="true" aria-labelledby="loTitle">' +
              '<div class="lo-icon"><i class="fa-solid fa-right-from-bracket"></i></div>' +
              '<h3 id="loTitle">¿Cerrar sesión?</h3>' +
              '<p>Se cerrará tu sesión de administrador y volverás al inicio de sesión.</p>' +
              '<div class="lo-actions">' +
                '<button type="button" class="lo-cancel" id="loCancel">Cancelar</button>' +
                '<button type="button" class="lo-ok" id="loOk">Sí, salir</button>' +
              '</div>' +
            '</div>';
        document.body.appendChild(ov);

        ov.addEventListener('click', function (e) { if (e.target === ov) cerrar(); });
        document.getElementById('loCancel').addEventListener('click', cerrar);
        document.getElementById('loOk').addEventListener('click', salir);
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') cerrar(); });
        return ov;
    }

    function cerrar() {
        var ov = document.getElementById('logoutOverlay');
        if (ov) ov.classList.remove('open');
    }

    function salir() {
        var btn = document.getElementById('loOk');
        btn.disabled = true;
        btn.textContent = 'Saliendo...';
        var fin = function () { limpiarSesion(); window.location.href = LOGIN_URL; };
        fetch('/api/logout', { method: 'POST', headers: window.adminAuthHeaders() })
            .catch(function () {})
            .then(fin);
    }

    /* Chip "Admin" del header: muestra el nombre real y abre /perfil_admin.html */
    function iniciarChipUsuario() {
        var u = null;
        try { u = JSON.parse(localStorage.getItem('user') || 'null'); } catch (e) {}
        document.querySelectorAll('.ah-user').forEach(function (chip) {
            if (u && u.name) {
                var n = chip.querySelector('.ah-user-name');
                if (n) n.textContent = String(u.name).split(' ')[0];
            }
            chip.style.cursor = 'pointer';
            chip.setAttribute('title', 'Mi perfil');
            chip.addEventListener('click', function () { window.location.href = '/perfil_admin.html'; });
        });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', iniciarChipUsuario);
    else iniciarChipUsuario();

    window.confirmarSalir = function () {
        inyectarEstilos();
        crearModal().classList.add('open');
    };
})();