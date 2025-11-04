const API_BASE_URL = 'http://localhost/Cinetick/backEnd/public'; // ajuste quando integrar

// Tenta obter usuário do localStorage
function getLocalUser() {
  try { return JSON.parse(localStorage.getItem('cinetick_user')); }
  catch(e) { return null; }
}

function setLocalUser(user) {
  localStorage.setItem('cinetick_user', JSON.stringify(user));
}

function logoutLocal() {
  localStorage.removeItem('cinetick_user');
}

// Compras locais armazenadas para modo offline/dev
function getLocalPurchases() {
  try { return JSON.parse(localStorage.getItem('cinetick_purchases')) || []; }
  catch(e) { return []; }
}
function setLocalPurchases(list) {
  localStorage.setItem('cinetick_purchases', JSON.stringify(list));
}

// UI helpers
function renderUser(user) {
  const nameEl = document.getElementById('user-name');
  const emailEl = document.getElementById('user-email');
  const logoutBtn = document.getElementById('btn-logout');
  const demoBtn = document.getElementById('btn-demo-login');

  if (user) {
    nameEl.textContent = user.name || user.email || 'Usuário';
    emailEl.textContent = user.email ? `Email: ${user.email}` : '';
    logoutBtn.style.display = 'inline-block';
    demoBtn.style.display = 'none';
  } else {
    nameEl.textContent = 'Visitante';
    emailEl.textContent = '';
    logoutBtn.style.display = 'none';
    demoBtn.style.display = 'inline-block';
  }
}

function renderPurchases(purchases) {
  const list = document.getElementById('purchases-list');
  const noPurchases = document.getElementById('no-purchases');
  list.innerHTML = '';
  if (!purchases || purchases.length === 0) {
    noPurchases.style.display = 'block';
    return;
  }
  noPurchases.style.display = 'none';
  purchases.forEach(p => {
    const div = document.createElement('div');
    div.className = 'purchase';
    div.innerHTML = `
      <div>
        <strong>${p.movieTitle || 'Filme'}</strong><br>
        <small>${p.date || ''} — Assentos: ${p.seats.join(', ')}</small>
      </div>
      <div>
        <strong>R$ ${Number(p.total).toFixed(2)}</strong>
      </div>
    `;
    list.appendChild(div);
  });
}

// Tenta buscar compras na API (se existir), senão usa storage local
async function loadPurchases(user) {
  // Se não tiver usuário, carrega compras locais (modo demo)
  if (!user) {
    renderPurchases(getLocalPurchases());
    return;
  }

  // Tenta API real: GET /user/{id}/purchases  (ajuste quando integrar)
  try {
    const resp = await fetch(`${API_BASE_URL}/users/${user.id}/purchases`, { credentials: 'include' });
    if (resp.ok) {
      const json = await resp.json();
      // espera array de compras
      renderPurchases(json.purchases || []);
      return;
    }
  } catch (e) {
    // falha na fetch, cai pro local
  }

  // fallback local
  renderPurchases(getLocalPurchases().filter(p => p.userId === user.id));
}

// Demo: cria usuário local e compras para testar
function enableDemoMode() {
  const demoUser = { id: 'demo-1', name: 'Usuário Demo', email: 'demo@local' };
  setLocalUser(demoUser);

  // cria compras mock se não existirem
  const existing = getLocalPurchases();
  if (existing.length === 0) {
    const demoPurchases = [
      { id: 'p1', userId: 'demo-1', movieTitle: 'Matrix', date: '2025-08-05', seats: ['A1','A2'], total: 50.00 },
      { id: 'p2', userId: 'demo-1', movieTitle: 'Duna', date: '2025-09-10', seats: ['B3'], total: 25.00 }
    ];
    setLocalPurchases(demoPurchases);
  }
  init(); // recarrega UI
}

// Inicialização
function init() {
  const user = getLocalUser();
  renderUser(user);
  loadPurchases(user);

  document.getElementById('btn-demo-login').onclick = () => enableDemoMode();
  document.getElementById('btn-logout').onclick = () => { logoutLocal(); init(); };
}

init();
