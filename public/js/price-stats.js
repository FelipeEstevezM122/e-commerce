/* HU-10 - Estadisticas de precios + dolar en vivo (compartido por la vista de cliente y la de admin)
   Uso: PriceStats.init({ admin: true|false, theme: {...colores} })
   Endpoints que consume:
     GET /api/exchange-rate                    -> dolar en vivo        (API externa, via backend)
     GET /api/price-stats | /api/admin/price-stats   -> resumen        (BD: product_price_history)
     GET /api/price-stats/products/{id}        -> linea de tiempo      (BD: product_price_history)
   Todas las respuestas traen "simulated": true cuando aun no hay historial real en la BD. */
const PriceStats = (function () {
    const $ = (id) => document.getElementById(id);
    const fmt = (n) => 'Bs ' + Number(n).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    const pctTxt = (p) => (p > 0 ? '+' : '') + Number(p).toFixed(2) + '%';
    const pctCls = (p) => (p > 0 ? 'up' : p < 0 ? 'down' : 'flat');
    const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    let cfg, charts = {}, lastRate = null, rateAt = null, days = 30, usdRate = 0;

    function headers() {
        const h = { Accept: 'application/json' };
        if (cfg.admin) h.Authorization = 'Bearer ' + (localStorage.getItem('token') || '').replace(/^"|"$/g, '');
        return h;
    }
    async function api(url) {
        const r = await fetch(url, { headers: headers() });
        if (r.status === 401 || r.status === 403) { if (cfg.admin) window.location.href = '/iniciarsesion.html'; }
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
    }

    /* ---------- DOLAR EN VIVO ---------- */
    async function loadRate() {
        try {
            const d = await api('/api/exchange-rate');
            const prev = lastRate;
            lastRate = d; rateAt = Date.now();
            const main = d.parallel || d.official;
            usdRate = main.sell;
            $('usdOfficial').textContent = d.official ? d.official.sell.toFixed(2) : '—';
            $('usdOfficialBuy').textContent = d.official ? d.official.buy.toFixed(2) : '—';
            $('usdParallel').textContent = d.parallel ? d.parallel.sell.toFixed(2) : '—';
            $('usdParallelBuy').textContent = d.parallel ? d.parallel.buy.toFixed(2) : '—';
            $('usdSource').textContent = d.source + (d.fallback ? ' · valor de respaldo' : '');
            const trend = $('usdTrend');
            if (prev && prev.parallel && d.parallel && prev.parallel.sell !== d.parallel.sell) {
                const up = d.parallel.sell > prev.parallel.sell;
                trend.className = 'trend ' + (up ? 'up' : 'down');
                trend.innerHTML = '<i class="fa-solid fa-arrow-' + (up ? 'up' : 'down') + '"></i> ' + (up ? 'Subió' : 'Bajó') + ' desde la última lectura';
            } else trend.textContent = 'Sin cambios desde la última lectura';
            $('liveDot').classList.toggle('stale', !!d.fallback);
            convert();
        } catch (e) {
            $('liveDot').classList.add('stale');
            $('usdSource').textContent = 'No se pudo actualizar. Reintentando…';
        }
    }
    function tickAge() {
        if (!rateAt) return;
        const s = Math.round((Date.now() - rateAt) / 1000);
        $('usdAge').textContent = s < 5 ? 'Actualizado ahora' : 'Actualizado hace ' + s + ' s';
    }
    function convert() {
        const v = parseFloat($('convAmount').value) || 0;
        const dir = $('convDir').value;
        if (!usdRate) return;
        $('convResult').textContent = dir === 'usd2bob' ? fmt(v * usdRate) : '$ ' + (v / usdRate).toFixed(2);
    }

    /* ---------- GRAFICAS ---------- */
    function baseOpts(extra) {
        const t = cfg.theme;
        return Object.assign({
            responsive: true, maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { display: false }, tooltip: { backgroundColor: t.tipBg, titleColor: t.tipText, bodyColor: t.tipText, borderColor: t.line, borderWidth: 1, padding: 10 } },
            scales: {
                x: { ticks: { color: t.muted, maxRotation: 0, autoSkip: true }, grid: { display: false } },
                y: { ticks: { color: t.muted }, grid: { color: t.grid } }
            }
        }, extra || {});
    }
    function draw(id, config) { if (charts[id]) charts[id].destroy(); charts[id] = new Chart($(id), config); }
    const dLabel = (iso) => new Date(iso).toLocaleDateString('es-BO', { day: '2-digit', month: 'short' });

    // Grafica de PICOS por producto: linea quebrada (sin suavizar) + maximo/minimo resaltados
    function drawTimeline(res) {
        const t = cfg.theme, prices = res.points.map((p) => p.price);
        const max = Math.max(...prices), min = Math.min(...prices);
        const ptColor = prices.map((v) => (v === max ? t.up : v === min ? t.down : t.line));
        const ptSize = prices.map((v) => (v === max || v === min ? 8 : 4));
        draw('chartTimeline', {
            type: 'line',
            data: { labels: res.points.map((p) => dLabel(p.at)), datasets: [{ label: 'Precio (Bs)', data: prices, borderColor: t.line, backgroundColor: t.fill, fill: true, tension: 0, borderWidth: 2.5, pointBackgroundColor: ptColor, pointBorderColor: ptColor, pointRadius: ptSize, pointHoverRadius: 9 }] },
            options: baseOpts({ plugins: { legend: { display: false }, tooltip: { backgroundColor: t.tipBg, titleColor: t.tipText, bodyColor: t.tipText, callbacks: { label: (c) => ' ' + fmt(c.parsed.y) + (usdRate ? '  (≈ $ ' + (c.parsed.y / usdRate).toFixed(2) + ')' : '') } } } })
        });
        $('tlMax').textContent = fmt(max); $('tlMin').textContent = fmt(min);
        const first = prices[0], last = prices[prices.length - 1], p = first ? (last - first) / first * 100 : 0;
        const el = $('tlVar'); el.textContent = pctTxt(p); el.className = 'val ' + pctCls(p);
        $('tlNote').style.display = res.simulated ? 'flex' : 'none';
    }

    /* ---------- RESUMEN ---------- */
    function renderOverview(d) {
        const t = cfg.theme;
        $('simBanner').style.display = d.simulated ? 'flex' : 'none';
        $('kChanges').textContent = d.kpis.changes;
        $('kProducts').textContent = d.kpis.products_changed;
        const k = $('kAvg'); k.textContent = pctTxt(d.kpis.avg_pct); k.className = 'kpi-val ' + pctCls(d.kpis.avg_pct);
        $('kRises').textContent = d.kpis.rises; $('kDrops').textContent = d.kpis.drops;

        // Picos del mercado: variacion % promedio por dia (barras verdes/rojas)
        draw('chartDaily', {
            type: 'bar',
            data: { labels: d.daily.map((x) => dLabel(x.date)), datasets: [{ data: d.daily.map((x) => x.avg_pct), backgroundColor: d.daily.map((x) => (x.avg_pct >= 0 ? t.up : t.down)), borderRadius: 4 }] },
            options: baseOpts({ plugins: { legend: { display: false }, tooltip: { backgroundColor: t.tipBg, titleColor: t.tipText, bodyColor: t.tipText, callbacks: { label: (c) => ' Variación promedio: ' + pctTxt(c.parsed.y) } } } })
        });

        const rank = (list) => list.length ? list.map((p, i) => '<li><span class="pos">' + (i + 1) + '</span><span class="nm">' + esc(p.name) + '<small>' + fmt(p.first) + ' → ' + fmt(p.last) + '</small></span><span class="chip ' + pctCls(p.pct) + '">' + pctTxt(p.pct) + '</span></li>').join('') : '<li class="empty">Sin datos</li>';
        $('listRises').innerHTML = rank(d.top_rises.filter((p) => p.pct > 0));
        $('listDrops').innerHTML = rank(d.top_drops.filter((p) => p.pct < 0));

        $('recentBody').innerHTML = d.recent.map((r) => '<tr><td>' + new Date(r.at).toLocaleDateString('es-BO', { day: '2-digit', month: 'short', year: 'numeric' }) + '</td><td>' + esc(r.name) + '</td><td>' + fmt(r.old) + '</td><td>' + fmt(r.new) + '</td><td><span class="chip ' + pctCls(r.pct) + '">' + pctTxt(r.pct) + '</span></td>' + (cfg.admin ? '<td>' + esc(r.by) + '</td>' : '') + '</tr>').join('') || '<tr><td colspan="6" class="empty">Aún no hay cambios de precio en este período</td></tr>';

        const sel = $('productSel');
        if (!sel.options.length) {
            sel.innerHTML = d.products.map((p) => '<option value="' + p.id + '">' + esc(p.name) + '</option>').join('');
            if (d.products.length) loadTimeline(d.products[0].id);
        }
    }

    async function loadTimeline(id) {
        try { drawTimeline(await api('/api/price-stats/products/' + id + '?days=' + Math.max(days, 30))); }
        catch (e) { $('tlNote').style.display = 'flex'; }
    }
    async function loadOverview() {
        $('loading').style.display = 'flex';
        try { renderOverview(await api((cfg.admin ? '/api/admin/price-stats' : '/api/price-stats') + '?days=' + days)); }
        catch (e) { $('errBox').style.display = 'flex'; }
        $('loading').style.display = 'none';
    }

    function init(c) {
        cfg = c;
        document.querySelectorAll('[data-days]').forEach((b) => b.addEventListener('click', () => {
            document.querySelectorAll('[data-days]').forEach((x) => x.classList.remove('active'));
            b.classList.add('active'); days = +b.dataset.days; $('errBox').style.display = 'none';
            loadOverview(); if ($('productSel').value) loadTimeline($('productSel').value);
        }));
        $('productSel').addEventListener('change', (e) => loadTimeline(e.target.value));
        ['convAmount', 'convDir'].forEach((i) => $(i).addEventListener('input', convert));
        loadRate(); loadOverview();
        setInterval(loadRate, 60000);   // dolar: refresco cada 60 s (la API se cachea 2 min en el backend)
        setInterval(tickAge, 1000);
    }
    return { init };
})();
