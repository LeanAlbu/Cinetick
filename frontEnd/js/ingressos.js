document.addEventListener('DOMContentLoaded', () => {
    loadHeaderAndFooter();
    loadIngressos();
});

async function loadHeaderAndFooter() {
    const headerContainer = document.querySelector('.main-header');
    const modalContainer = document.getElementById('login-modal-container');

    try {
        const headerResponse = await fetch('templates/header.html');
        const headerHtml = await headerResponse.text();
        headerContainer.innerHTML = headerHtml;

        const modalResponse = await fetch('templates/login-modal.html');
        const modalHtml = await modalResponse.text();
        if (modalContainer) modalContainer.innerHTML = modalHtml;

        const { setupUserMenu } = await import('./common/ui.js');
        setupUserMenu();
    } catch (error) {
        console.error('Erro ao carregar o cabeçalho ou rodapé:', error);
    }
}

function loadIngressos() {
    const user = JSON.parse(localStorage.getItem('cinetick_user'));
    const container = document.getElementById('lista-ingressos');

    if (!container) return;

    if (!user) {
        container.innerHTML = `<p>Você precisa estar logado para ver seus ingressos.</p>`;
        return;
    }

    const allPurchases = JSON.parse(localStorage.getItem('cinetick_purchases') || '[]');
    const userPurchases = allPurchases.filter(p => p.userId === user.id);

    if (userPurchases.length === 0) {
        container.innerHTML = `<p>Você ainda não possui ingressos.</p>`;
    } else {
        container.innerHTML = userPurchases.map(p => `
            <div class="ticket-card">
                <strong>${p.movieTitle}</strong><br>
                Data: ${new Date(p.date).toLocaleDateString()}<br>
                Valor: R$ ${p.total.toFixed(2)}
            </div>
        `).join('');
    }
}
