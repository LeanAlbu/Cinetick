document.addEventListener('DOMContentLoaded', () => {
    const movieDetailContainer = document.getElementById('movie-detail-container');
    const paymentModal = document.getElementById('payment-modal');
    const paymentForm = document.getElementById('payment-form');
    const paymentError = document.getElementById('payment-error');
    const closePaymentModal = paymentModal.querySelector('.modal-close');

    let currentFilmeId = null;
    const VALOR_INGRESSO = 25.00;

    const getFilmeIdFromUrl = () => {
        const params = new URLSearchParams(window.location.search);
        return params.get('id');
    };

    const fetchFilmeDetails = async (id) => {
        try {
            const response = await fetch(`../backEnd/public/filmes/${id}`);
            if (!response.ok) {
                throw new Error('Filme não encontrado.');
            }
            const filme = await response.json();
            currentFilmeId = filme.id;
            renderFilmeDetails(filme);
        } catch (error) {
            movieDetailContainer.innerHTML = `<p class="error-message">${error.message}</p>`;
        }
    };

    const renderFilmeDetails = (filme) => {
        movieDetailContainer.innerHTML = `
            <div class="movie-detail-card">
                <img src="${filme.imagem_url}" alt="Pôster de ${filme.title}" class="movie-detail-poster">
                <div class="movie-detail-info">
                    <h1>${filme.title}</h1>
                    <p><strong>Ano de Lançamento:</strong> ${filme.release_year}</p>
                    <p><strong>Diretor:</strong> ${filme.director}</p>
                    <p><strong>Descrição:</strong> ${filme.description}</p>
                    <button id="buy-ticket-btn" class="btn-primary">Comprar Ingresso</button>
                </div>
            </div>
        `;

        const buyTicketBtn = document.getElementById('buy-ticket-btn');
        buyTicketBtn.addEventListener('click', handleBuyTicketClick);
    };

    const handleBuyTicketClick = () => {
        if (isUserLoggedIn()) {
            window.location.href = 'assentos-cinema.html';
        } else {
            // Reutiliza a lógica do modal de login do script.js
            const loginModal = document.getElementById('login-modal');
            loginModal.classList.add('active');
        }
    };

    paymentForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        paymentError.style.display = 'none';

        const formData = new FormData(paymentForm);
        const data = {
            filme_id: currentFilmeId,
            valor: VALOR_INGRESSO,
            cpf: formData.get('cpf'),
            cartao: formData.get('cartao')
        };

        try {
            const response = await fetch('../backEnd/public/pagamentos', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.error || 'Ocorreu um erro no pagamento.');
            }

            paymentModal.classList.remove('active');
            Swal.fire({
                title: 'Sucesso!',
                text: 'Seu ingresso foi comprado com sucesso!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'index.html';
            });

        } catch (error) {
            paymentError.textContent = error.message;
            paymentError.style.display = 'block';
        }
    });

    closePaymentModal.addEventListener('click', () => {
                    paymentModal.classList.remove('active');    });

    // --- INICIALIZAÇÃO ---
    const filmeId = getFilmeIdFromUrl();
    if (filmeId) {
        fetchFilmeDetails(filmeId);
    } else {
        movieDetailContainer.innerHTML = '<p class="error-message">ID do filme não fornecido.</p>';
    }
});
