const { test } = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const source = fs.readFileSync(require('node:path').join(__dirname, '../../public/js/chat.js'), 'utf8');

function storage(initial = {}) {
    const data = new Map(Object.entries(initial));
    return { getItem: k => data.get(k) ?? null, setItem: (k, v) => data.set(k, v), removeItem: k => data.delete(k) };
}

function page(local = storage(), session = storage(), fetchImpl, navigationType = 'navigate') {
    function element() {
        return { children: [], style: {}, handlers: {}, value: '', disabled: false,
            appendChild(child) { this.children.push(child); child.parent = this; },
            remove() { if (this.parent) this.parent.children = this.parent.children.filter(x => x !== this); },
            addEventListener(name, fn) { this.handlers[name] = fn; }, focus() {},
            set innerHTML(value) { this.html = value; this.children = []; }, get innerHTML() { return this.html || ''; } };
    }
    const nodes = Object.fromEntries(['chat-form', 'chat-input', 'chat-messages', 'chat-send'].map(id => [id, element()]));
    nodes['chat-messages'].innerHTML = 'Welcome';
    const listeners = {};
    vm.runInNewContext(source, {
        document: { getElementById: id => nodes[id], createElement: element, head: element() },
        window: { addEventListener: (name, fn) => { listeners[name] = fn; },
            performance: { getEntriesByType: () => [{ type: navigationType }] } },
        localStorage: local, sessionStorage: session,
        fetch: fetchImpl || (async () => ({ status: 200, json: async () => ({ response: 'Respuesta del bot' }) }))
    });
    return { nodes, listeners, session,
        async send(text) { nodes['chat-input'].value = text; await nodes['chat-form'].handlers.submit({ preventDefault() {} }); },
        texts() { return nodes['chat-messages'].children.map(row => row.children[0].textContent); } };
}

test('restores conversation on another view without duplicate greeting or typing indicator', async () => {
    const local = storage(), session = storage();
    const first = page(local, session);
    await first.send('¿Qué productos tienen?');
    const second = page(local, session);
    assert.deepEqual(second.texts(), ['¿Qué productos tienen?', 'Respuesta del bot']);
    assert(!session.getItem('casatek.chat.v1').includes('Escribiendo'));
});

test('clears private transcript on logout and account switch', async () => {
    const local = storage({ token: 'secret-token', user: JSON.stringify({ id: 1 }) }), session = storage();
    await page(local, session).send('Mis pedidos');
    assert(!session.getItem('casatek.chat.v1').includes('secret-token'));
    local.setItem('token', 'second-token'); local.setItem('user', JSON.stringify({ id: 2 }));
    const second = page(local, session);
    assert.deepEqual(second.texts(), []);
    await second.send('Mi carrito');
    local.removeItem('token'); local.removeItem('user');
    second.listeners.focus();
    assert.deepEqual(second.texts(), []);
    assert.equal(session.getItem('casatek.chat.v1'), null);
});

test('does not render a private response if account changes while request is pending', async () => {
    const local = storage({ token: 'one', user: JSON.stringify({ id: 1 }) });
    let resolve;
    const chat = page(local, storage(), () => new Promise(r => { resolve = r; }));
    const pending = chat.send('Mis pedidos');
    local.setItem('token', 'two'); local.setItem('user', JSON.stringify({ id: 2 }));
    resolve({ status: 200, json: async () => ({ response: 'Private order 123' }) });
    await pending;
    assert.deepEqual(chat.texts(), []);
});

test('chat remains usable when browser storage is unavailable', async () => {
    const denied = { getItem() { throw Error('disabled'); }, setItem() { throw Error('disabled'); }, removeItem() {} };
    const chat = page(denied, denied);
    await chat.send('Hola');
    assert.deepEqual(chat.texts(), ['Hola', 'Respuesta del bot']);
});

test('expired transcript is discarded', () => {
    const session = storage({ 'casatek.chat.v1': JSON.stringify({ scope: 'guest', savedAt: Date.now() - 3 * 60 * 60 * 1000,
        messages: [{ text: 'Expired', isUser: true }] }) });
    assert.deepEqual(page(storage(), session).texts(), []);
    assert.equal(session.getItem('casatek.chat.v1'), null);
});

test('sends bounded recent context without duplicating current question and resets it on reload', async () => {
    const local = storage(), session = storage(), bodies = [];
    const fetcher = async (url, options) => {
        bodies.push(JSON.parse(options.body));
        return { status: 200, json: async () => ({ response: 'Modelo A y modelo B' }) };
    };
    const chat = page(local, session, fetcher);
    await chat.send('Compara cámaras');
    await chat.send('¿Cuánto varían sus precios?');
    assert.deepEqual(bodies[1].history, [
        { role: 'user', content: 'Compara cámaras' },
        { role: 'assistant', content: 'Modelo A y modelo B' }
    ]);
    for (let i = 0; i < 4; i++) await chat.send('Otra pregunta');
    assert.equal(bodies.at(-1).history.length, 4);
    await page(local, session, fetcher, 'reload').send('Hola');
    assert.deepEqual(bodies.at(-1).history, []);
});

test('reload clears transcript but normal navigation preserves it', async () => {
    const local = storage(), session = storage();
    await page(local, session).send('Hola');
    assert.equal(page(local, session).texts().length, 2);
    assert.deepEqual(page(local, session, undefined, 'reload').texts(), []);
    assert.equal(session.getItem('casatek.chat.v1'), null);
    assert.deepEqual(page(local, session).texts(), []);
});

test('a new login to the same account starts empty', async () => {
    const local = storage({ token: 'old-session', user: JSON.stringify({ id: 1 }) }), session = storage();
    const chat = page(local, session);
    await chat.send('Mis compras');
    local.setItem('token', 'new-session');
    chat.listeners.pagehide();
    assert.deepEqual(page(local, session).texts(), []);
    assert.equal(session.getItem('casatek.chat.v1'), null);
});

test('back/forward cache cannot resurrect a transcript cleared on reload', async () => {
    const local = storage(), session = storage(), previous = page(local, session);
    await previous.send('Hola');
    page(local, session, undefined, 'reload');
    previous.listeners.pageshow({ persisted: true });
    assert.deepEqual(previous.texts(), []);
});
