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
    let cfg, charts = {}, lastRate = null, rateAt = null, days = 30, usdRate = 0, recentPage = 1, recentRequestId = 0, forecastRequestId = 0, timelineRequestId = 0, exchangeHistory = null, exchangeHistoryPromise = null, currentTimeline = null;

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
        const t = cfg.theme, prices = (res.points || []).map((p) => Number(p.price)).filter(Number.isFinite);
        if (!prices.length) {
            $('tlMax').textContent = '—'; $('tlMin').textContent = '—'; $('tlVar').textContent = '—';
            $('tlNote').style.display = 'flex';
            return;
        }
        const max = prices.length ? Math.max(...prices) : 0, min = prices.length ? Math.min(...prices) : 0;
        const ptColor = prices.map((v) => (v === max ? t.up : v === min ? t.down : t.line));
        const ptSize = prices.map((v) => (v === max || v === min ? 8 : 4));
        draw('chartTimeline', {
            type: 'line',
            data: { labels: res.points.map((p) => dLabel(p.at)), datasets: [{ label: 'Precio (Bs)', data: prices, borderColor: t.line, backgroundColor: t.fill, fill: true, tension: 0, borderWidth: 2.5, pointBackgroundColor: ptColor, pointBorderColor: ptColor, pointRadius: ptSize, pointHoverRadius: 9 }] },
            options: baseOpts({ plugins: { legend: { display: false }, tooltip: { backgroundColor: t.tipBg, titleColor: t.tipText, bodyColor: t.tipText, callbacks: { label: (c) => ' ' + fmt(c.parsed.y) + (usdRate ? '  (≈ $ ' + (c.parsed.y / usdRate).toFixed(2) + ')' : '') } } } })
        });
        $('tlMax').textContent = prices.length ? fmt(max) : '—'; $('tlMin').textContent = prices.length ? fmt(min) : '—';
        const first = prices[0], last = prices[prices.length - 1], p = first ? (last - first) / first * 100 : 0;
        const el = $('tlVar'); el.textContent = pctTxt(p); el.className = 'val ' + pctCls(p);
        $('tlNote').style.display = res.simulated ? 'flex' : 'none';


    }


    /* ---------- PRONÓSTICO: PRODUCTO + TCO PÚBLICO DEL BCB ----------
       El rango se basa en movimientos históricos ACUMULADOS de 7 y 30 días.
       Evita subestimar la volatilidad por el MAD diario (ceros de fines de semana)
       y NO convierte cambios manuales extremos en tendencias proyectadas.
       No representa una probabilidad calibrada ni un máximo absoluto garantizado.
    */
    const clamp = (x, lo, hi) => Math.max(lo, Math.min(hi, x));
    const med = (a) => { const v = [...a].sort((x,y)=>x-y); return v.length ? (v.length % 2 ? v[(v.length-1)/2] : (v[v.length/2-1]+v[v.length/2])/2) : 0; };
    const mad = (a) => med(a.map((x) => Math.abs(x-med(a)))) * 1.4826;
    const quantile = (values, p) => {
        const v = values.filter(Number.isFinite).sort((a,b) => a-b);
        if (!v.length) return null;
        const pos = (v.length - 1) * p, i = Math.floor(pos), r = pos - i;
        return v[i] + (v[Math.min(i + 1, v.length - 1)] - v[i]) * r;
    };
    const rateDays = (points) => {
        const byDate = new Map();
        for (const p of points) {
            const sell = Number(p.sell), date = String(p.date ?? '').slice(0, 10);
            if (sell > 0 && /^\d{4}-\d{2}-\d{2}$/.test(date)) {
                const at = Date.parse(date + 'T12:00:00Z');
                if (Number.isFinite(at)) byDate.set(date, {at, sell});
            }
        }
        return [...byDate.values()].sort((a,b) => a.at-b.at);
    };
    const findRate = (rates, at) => { let chosen=null; for (const r of rates) { if (r.at <= at) chosen=r.sell; else break; } return chosen; };

    // Variaciones históricas para el mismo horizonte, no volatilidad diaria extrapolada.
    // Se tolera hasta 1 día de diferencia si el proveedor tiene alguna fecha ausente.
    const rollingRateReturns = (rates, horizon) => {
        const result = [], dayMs = 86400000;
        for (let i = 1; i < rates.length; i++) {
            const target = rates[i].at - horizon * dayMs;
            let closest = -1, distance = Infinity;
            for (let j = i - 1; j >= 0; j--) {
                const diff = Math.abs(rates[j].at - target);
                if (diff < distance) { distance = diff; closest = j; }
                if (rates[j].at < target - dayMs) break;
            }
            if (closest < 0 || distance > dayMs) continue;
            const actualDays = (rates[i].at-rates[closest].at) / dayMs;
            if (actualDays <= 0) continue;
            const change = Math.log(rates[i].sell / rates[closest].sell) * horizon / actualDays;
            if (Number.isFinite(change)) result.push(change);
        }
        return result;
    };

    function forecast(res, exchange) {
        const history = (res.points || []).map(x => ({at: new Date(x.at).getTime(), price: Number(x.price)}))
            .filter(x => Number.isFinite(x.at) && Number.isFinite(x.price) && x.price > 0)
            .sort((a,b) => a.at-b.at);
        const rates = rateDays(exchange.points || []);
        if (!history.length || rates.length < 20)
            return { unavailable: 'Faltan cotizaciones históricas del dólar o precios reales del producto.' };

        // Los saltos bruscos registrados manualmente (p. ej. Bs 1.000 -> Bs 10)
        // no implican que el dólar tenga esa misma influencia.
        const steps = [];
        let extreme = 0;
        for (let i = 1; i < history.length; i++) {
            const delta = (history[i].at-history[i-1].at) / 86400000;
            if (delta <= 0) continue;
            const raw = Math.log(history[i].price/history[i-1].price);
            if (Math.abs(raw) > Math.log(1.5)) extreme++;
            if (delta < 1 || Math.abs(raw) > Math.log(1.5)) continue;
            const startRate = findRate(rates, history[i-1].at);
            const endRate = findRate(rates, history[i].at);
            steps.push({daily:raw/delta, usd:startRate && endRate ? Math.log(endRate/startRate)/delta : null});
        }
        const enoughProduct = steps.length >= 8 &&
            (history[history.length-1].at-history[0].at)/86400000 >= 30;
        const drift = enoughProduct ? clamp(med(steps.map(x=>x.daily)), -.005, .005) : 0;
        const priceVol = enoughProduct ? clamp(mad(steps.map(x=>x.daily)), 0, .02) : 0;

        // Elasticidad real: solo si hay suficientes variaciones de dólar y producto
        // separadas en el tiempo. Si no, se prueban supuestos explícitos (25%-100%).
        let elasticity = null;
        const pairs = steps.filter(x=>x.usd !== null && Math.abs(x.usd) > .0001);
        if (enoughProduct && pairs.length >= 8) {
            const xs=pairs.map(x=>x.usd), ys=pairs.map(x=>x.daily), mx=med(xs), my=med(ys);
            const denom=xs.reduce((acc,x)=>acc+(x-mx)**2,0);
            if (denom > 1e-7)
                elasticity = clamp(xs.reduce((acc,x,i)=>acc+(x-mx)*(ys[i]-my),0)/denom,0,1.5);
        }
        const last = history[history.length-1].price;
        const forecastFor = (h) => {
            const returns = rollingRateReturns(rates, h);
            // Sin al menos 20 períodos comparables, no se publican límites arbitrarios.
            if (returns.length < 20) return null;
            const usdLow = quantile(returns, .05);
            const usdMid = quantile(returns, .50);
            const usdHigh = quantile(returns, .95);
            const weights = elasticity === null ? [.25, 1] : [elasticity];
            const midWeight = elasticity === null ? .625 : elasticity;
            const base = Math.log(last) + clamp(drift*h, -.15, .15);
            const residual = priceVol * Math.sqrt(h) * 1.28;
            const lowerCandidates = weights.map(w => Math.exp(base+usdLow*w-residual));
            const upperCandidates = weights.map(w => Math.exp(base+usdHigh*w+residual));
            const mid = Math.exp(base + usdMid * midWeight);
            // El precio sin cambios también es posible cuando no se conoce el traspaso.
            const low = Math.max(.01, Math.min(last, mid, ...lowerCandidates));
            const high = Math.max(last, mid, ...upperCandidates);
            return {days:h, mid:+mid.toFixed(2), low:+low.toFixed(2), high:+high.toFixed(2),
                samplePeriods:returns.length, usdLow, usdHigh};
        };
        const scenarios = [forecastFor(7), forecastFor(30)].filter(Boolean);
        if (!scenarios.length) return {unavailable:'No hay suficientes períodos comparables para calcular los escenarios.'};
        return {last,points:history.length,ratePoints:rates.length,extreme,elasticity,scenarios,
            lowConfidence:!enoughProduct || extreme>0 || elasticity===null,
            lastRate:rates[rates.length-1]};
    }
    function renderForecast(res, exchange) {
        const body=$('forecastBody'), note=$('forecastNote'), status=$('forecastStatus');
        if(!body || !note || !status) return;

        const message=(reason, state='No disponible')=>{
            body.innerHTML='<p class="forecast-message">'+esc(reason)+'</p>';
            status.textContent=state;
            status.className='forecast-status';
            note.innerHTML='<i class="fa-solid fa-circle-info" aria-hidden="true"></i><span>Se necesitan datos reales para estimar precios.</span>';
        };
        if(res.simulated || !exchange || !Array.isArray(exchange.points)) {
            message('Aún no hay suficientes datos reales para estimar este precio.', 'Sin datos');
            return;
        }
        const f=forecast(res, exchange);
        if(f.unavailable) {
            message('Todavía faltan datos para calcular un rango de precios.', 'Datos insuficientes');
            return;
        }

        status.textContent=f.lowConfidence ? 'Pocos datos' : 'Estimación disponible';
        status.className='forecast-status '+(f.lowConfidence ? 'low' : 'ready');

        const cards=f.scenarios.map((v)=>{
            const highPct=(v.high/f.last-1)*100;
            const lowPct=(v.low/f.last-1)*100;
            const span=v.high-v.low;
            const midPosition=span>0 ? clamp((v.mid-v.low)/span*100, 0, 100) : 50;
            return '<article class="forecast-tile">'+
                '<h3 class="forecast-tile-title"><i class="fa-regular fa-calendar-days" aria-hidden="true"></i> Próximos '+v.days+' días</h3>'+
                '<div class="forecast-limits">'+
                  '<div class="forecast-limit"><span class="limit-label"><i class="fa-solid fa-arrow-trend-down down" aria-hidden="true"></i> Mínimo estimado</span><strong class="down">'+fmt(v.low)+'</strong><span class="limit-change '+pctCls(lowPct)+'">'+pctTxt(lowPct)+' vs. último precio</span></div>'+
                  '<div class="forecast-limit"><span class="limit-label"><i class="fa-solid fa-arrow-trend-up up" aria-hidden="true"></i> Máximo estimado</span><strong class="up">'+fmt(v.high)+'</strong><span class="limit-change '+pctCls(highPct)+'">'+pctTxt(highPct)+' vs. último precio</span></div>'+
                '</div>'+
                '<div class="forecast-range" aria-hidden="true"><span class="forecast-range-dot" style="--forecast-mid:'+midPosition.toFixed(1)+'%"></span></div>'+
                '<p class="forecast-average">Precio central estimado: <b>'+fmt(v.mid)+'</b></p>'+
                '</article>';
        }).join('');

        body.innerHTML='<div class="forecast-summary"><span class="forecast-current-label">Último precio registrado</span><strong class="forecast-current-price">'+fmt(f.last)+'</strong></div>'+
            cards+'<p class="forecast-meta">Se analizaron '+f.ratePoints+' cotizaciones TCO BCB y '+Math.max(0,f.points-1)+' cambios del producto. '+
            (f.elasticity===null ? 'Sin relación comprobada producto/dólar: se comparan impactos del 25% al 100%. ' : 'Relación producto/dólar calculada a partir de su historial. ')+
            'Rangos obtenidos de movimientos históricos de 7 y 30 días.</p>';
        note.innerHTML='<i class="fa-solid fa-circle-info" aria-hidden="true"></i><span>'+
            (f.lowConfidence ? 'Rango orientativo: faltan datos para medir cómo influye el dólar en este producto.' : 'Rango orientativo; no es un precio garantizado.')+
            '</span>';
    }
    async function loadForecast(id, timeline) {
        const requestId=++forecastRequestId;
        const body=$('forecastBody'); if(!body)return;
        $('forecastStatus').textContent='Analizando…';
        body.innerHTML='<p class="forecast-message">Calculando mínimo y máximo…</p>';
        try {
            if (!exchangeHistory) {
                if (!exchangeHistoryPromise) {
                    exchangeHistoryPromise=api('/api/price-stats/exchange-history?days=365')
                        .then(d => {exchangeHistory=d;return d;})
                        .finally(() => {exchangeHistoryPromise=null;});
                }
                await exchangeHistoryPromise;
            }
            if(requestId!==forecastRequestId)return;
            renderForecast(timeline,exchangeHistory);
        } catch(e) {
            if(requestId!==forecastRequestId)return;
            $('forecastStatus').textContent='No disponible';
                body.innerHTML='<p class="forecast-message">No pudimos consultar el historial del dólar. Intenta actualizar la página.</p>';
            $('forecastNote').innerHTML='<i class="fa-solid fa-circle-info" aria-hidden="true"></i><span>No hay datos del dólar para estimar precios.</span>';
        }
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

        renderRecent(d);

        const sel = $('productSel');
        // Poblar el selector también cuando contiene el placeholder inicial.
        const products = Array.isArray(d.products) ? d.products : [];
        const currentSelection = sel.value;
        const firstLoad = sel.options.length === 0 || (sel.options.length === 1 && sel.options[0].value === '');
        if (firstLoad || !products.some((p) => String(p.id) === currentSelection)) {
            sel.innerHTML = products.map((p) => '<option value="' + esc(p.id) + '">' + esc(p.name) + '</option>').join('');
            if (products.length) loadTimeline(products[0].id);
            else {
                $('forecastStatus').textContent = 'Sin productos';
                $('forecastBody').innerHTML = '<p class="muted-s">No hay productos disponibles para calcular una predicción.</p>';
            }
        }
    }

    /* ---------- PAGINACIÓN DE ÚLTIMOS CAMBIOS ---------- */
    function renderRecent(d) {
        const rows = Array.isArray(d.recent) ? d.recent : [];
        const p = d.recent_pagination || { page: 1, per_page: 10, total: rows.length, last_page: 1 };
        recentPage = p.page;
        $('recentBody').innerHTML = rows.map((r) => '<tr><td>' + new Date(r.at).toLocaleDateString('es-BO', { day: '2-digit', month: 'short', year: 'numeric' }) + '</td><td>' + esc(r.name) + '</td><td>' + fmt(r.old) + '</td><td>' + fmt(r.new) + '</td><td><span class="chip ' + pctCls(r.pct) + '">' + pctTxt(r.pct) + '</span></td>' + (cfg.admin ? '<td>' + esc(r.by) + '</td>' : '') + '</tr>').join('') || '<tr><td colspan="' + (cfg.admin ? 6 : 5) + '" class="empty">Aún no hay cambios de precio en este período</td></tr>';

        const nav = $('recentPagination'), actions = $('recentPageActions');
        nav.hidden = p.total <= p.per_page;
        const start = p.total ? (p.page - 1) * p.per_page + 1 : 0;
        const end = Math.min(p.page * p.per_page, p.total);
        $('recentPageInfo').textContent = 'Mostrando ' + start + '–' + end + ' de ' + p.total + ' cambios';
        actions.replaceChildren();
        if (nav.hidden) return;

        function button(label, page, disabled, current = false) {
            const b = document.createElement('button');
            b.type = 'button'; b.className = 'recent-page-btn';
            b.textContent = label; b.disabled = disabled;
            b.setAttribute('aria-label', current ? 'Página ' + page + ', actual' : label === 'Anterior' || label === 'Siguiente' ? label + ', página ' + page : 'Ir a la página ' + page);
            if (current) b.setAttribute('aria-current', 'page');
            else if (!disabled) b.addEventListener('click', () => loadRecentPage(page));
            actions.appendChild(b);
        }
        button('Anterior', p.page - 1, p.page <= 1);
        // Mostrar una ventana de páginas y puntos suspensivos si hay muchas.
        const visible = new Set([1, p.last_page, p.page - 1, p.page, p.page + 1]);
        let prev = 0;
        for (const n of [...visible].filter((n) => n >= 1 && n <= p.last_page).sort((a, b) => a - b)) {
            if (prev && n > prev + 1) {
                const dots = document.createElement('span'); dots.className = 'recent-page-ellipsis'; dots.textContent = '…';
                dots.setAttribute('aria-hidden', 'true'); actions.appendChild(dots);
            }
            button(String(n), n, false, n === p.page);
            prev = n;
        }
        button('Siguiente', p.page + 1, p.page >= p.last_page);
    }

    async function loadRecentPage(page) {
        const requestId = ++recentRequestId;
        const actions = $('recentPageActions');
        actions.querySelectorAll('button').forEach((b) => { b.disabled = true; });
        try {
            const d = await api((cfg.admin ? '/api/admin/price-stats' : '/api/price-stats') + '?days=' + days + '&recent_page=' + page);
            if (requestId !== recentRequestId) return;
            renderRecent(d); // No recrear las gráficas ni modificar el selector de productos.
        } catch (e) {
            if (requestId !== recentRequestId) return;
            $('recentPageInfo').textContent = 'No se pudo cargar la página. Intenta nuevamente.';
            actions.querySelectorAll('button').forEach((b) => { b.disabled = b.getAttribute('aria-current') === 'page'; });
        }
    }

    async function loadTimeline(id) {
        const requestId=++timelineRequestId;
        ++forecastRequestId; // invalida pronósticos de un producto anterior
        if ($('forecastStatus')) $('forecastStatus').textContent='Cargando producto…';
        if ($('forecastBody')) $('forecastBody').innerHTML='<p class="forecast-message">Analizando el producto…</p>';
        try {
            const res = await api('/api/price-stats/products/' + encodeURIComponent(id) + '?days=' + Math.max(days, 30));
            if (requestId!==timelineRequestId || String($('productSel').value)!==String(id)) return;
            currentTimeline=res;
            drawTimeline(res);
            loadForecast(id, res);
        } catch (e) {
            if (requestId!==timelineRequestId) return;
            $('tlNote').style.display='flex';
            if ($('forecastStatus')) $('forecastStatus').textContent='Error de historial';
            if ($('forecastBody')) $('forecastBody').textContent='No se pudo cargar el historial del producto: '+e.message;
        }
    }
    async function loadOverview() {
        const requestId = ++recentRequestId; // Descarta respuestas anteriores al cambiar de período.
        recentPage = 1;
        $('loading').style.display = 'flex';
        try {
            const d = await api((cfg.admin ? '/api/admin/price-stats' : '/api/price-stats') + '?days=' + days + '&recent_page=1');
            if (requestId === recentRequestId) renderOverview(d);
        }
        catch (e) {
            if (requestId === recentRequestId) {
                $('errBox').style.display = 'flex';
                $('forecastStatus').textContent = 'Error de estadísticas';
                $('forecastBody').innerHTML = '<p class="muted-s">No fue posible cargar la lista de productos: ' + esc(e.message) + '.</p>';
            }
        }
        if (requestId === recentRequestId) $('loading').style.display = 'none';
    }

    function init(c) {
        cfg = c;
        document.querySelectorAll('[data-days]').forEach((b) => b.addEventListener('click', () => {
            document.querySelectorAll('[data-days]').forEach((x) => x.classList.remove('active'));
            b.classList.add('active'); days = +b.dataset.days; $('errBox').style.display = 'none';
            exchangeHistory = null; exchangeHistoryPromise = null; loadOverview(); if ($('productSel').value) loadTimeline($('productSel').value);
        }));
        $('productSel').addEventListener('change', (e) => loadTimeline(e.target.value));
        ['convAmount', 'convDir'].forEach((i) => $(i).addEventListener('input', convert));
        loadRate(); loadOverview();
        setInterval(loadRate, 60000);   // dolar: refresco cada 60 s (la API se cachea 2 min en el backend)
        setInterval(tickAge, 1000);
    }
    return { init };
})();
