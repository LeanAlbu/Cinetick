document.addEventListener('DOMContentLoaded', () => {
    loadBomboniereItems();
});

async function loadBomboniereItems() {
    try {
        const response = await fetch(`${window.API_BASE_URL}/bomboniere`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const items = await response.json();
        const container = document.getElementById('bomboniere-items-container');
        if (!container) {
            console.error('Element with id "bomboniere-items-container" not found.');
            return;
        }

        if (items.length === 0) {
            container.innerHTML = '<p>Nenhum item na bomboniere no momento.</p>';
            return;
        }

        items.forEach(item => {
            const card = document.createElement('div');
            card.className = 'card';
            card.innerHTML = `
                <img src="${item.image_url}" alt="${item.name}">
                <div class="card-body">
                    <h5 class="card-title">${item.name}</h5>
                    <p class="card-text">${item.description}</p>
                    <p class="card-price">R$ ${parseFloat(item.price).toFixed(2)}</p>
                    <button class="btn btn-primary">Adicionar ao Carrinho</button>
                </div>
            `;
            container.appendChild(card);
        });
    } catch (error) {
        console.error('Erro ao carregar itens da bomboniere:', error);
        const container = document.getElementById('bomboniere-items-container');
        if(container) {
            container.innerHTML = '<p>Erro ao carregar os itens da bomboniere. Tente novamente mais tarde.</p>';
        }
    }
}
