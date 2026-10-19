// Protección de páginas de admin: sin token o sin rol "admin" -> inicio de sesión
(function () {
    var token = localStorage.getItem('token');
    var roles = [];
    try { roles = JSON.parse(localStorage.getItem('roles') || '[]'); } catch (e) {}

    var esAdmin = roles.some(function (r) { return r.name === 'admin'; });

    if (!token || !esAdmin) {
        window.location.href = '/iniciarsesion.html';
    }
})();