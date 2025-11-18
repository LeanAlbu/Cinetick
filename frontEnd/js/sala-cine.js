
document.addEventListener("DOMContentLoaded", () => {
    loadHeaderAndFooter();

    const paymentModal = document.getElementById('payment-modal');
    const paymentForm = document.getElementById('payment-form');
    const paymentError = document.getElementById('payment-error');
    const closePaymentModal = paymentModal.querySelector('.modal-close');
    const btnContinuar = document.getElementById('btn-continuar');

  const fileiras = ["H","G","F","E","D","C","B","A"];
  const cadeirasPorFileira = 10;
  const ocupados = ["E4","E5","F3","F7"];  /*exemplo*/
  const VALOR_INGRESSO = 25.00;
  let currentFilmeId = null;

  const container = document.getElementById("assentos-conteiner");
  if (!container) return;

  fileiras.forEach(fila => {
    const filaDiv = document.createElement("div");
    filaDiv.className = "fila";

    const label = document.createElement("span");
    label.className = "fila-label";
    label.textContent = fila;
    filaDiv.appendChild(label);

    const assentosDiv = document.createElement("div");
    assentosDiv.className = "assentos";

    for (let i = 1; i <= cadeirasPorFileira; i++) {
      const id = `${fila}${i}`;
      const seat = document.createElement("div");
      seat.className = "assento livre";
      seat.dataset.id = id;
      seat.textContent = i;

      if (ocupados.includes(id)) {
        seat.classList.remove("livre");
        seat.classList.add("ocupado");
      }

      seat.addEventListener("click", () => {
        if (seat.classList.contains("ocupado")) return;
        seat.classList.toggle("selecionado");
      });

      assentosDiv.appendChild(seat);
    }

    filaDiv.appendChild(assentosDiv);
    container.appendChild(filaDiv);
  });
  
  btnContinuar.addEventListener('click', () => {
    const selecionados = document.querySelectorAll('.assento.selecionado').length;
    const valorTotal = selecionados * VALOR_INGRESSO;
    document.getElementById('valor-ingresso').textContent = `R$ ${valorTotal.toFixed(2)}`;

    const seats = [...document.querySelectorAll('.assento.selecionado')].map(a => a.textContent.trim());

  const compra = {
      id: 'p_' + Date.now(),
      userId: 'demo-1', // Placeholder
      movieId: 1, // Placeholder
      movieTitle: 'Título', // Placeholder
      date: new Date().toISOString().slice(0,10),
      total: valorTotal
  };

    paymentModal.style.display = 'flex';
    paymentError.style.display = 'none';
  });

  closePaymentModal.addEventListener('click', () => {
            paymentModal.classList.remove('flex');
  });

  window.addEventListener('click', (event) => {
            if (event.target === paymentModal) {
              paymentModal.classList.remove('active');
            }
  });

  paymentForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    paymentError.style.display = 'none';

    const formData = new FormData(paymentForm);
        const data = {
            filme_id: currentFilmeId,
            valor: valorTotal,
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

    // This part seems to be incomplete and causing errors.
    // const filmeId = getFilmeIdFromUrl();
    // if (filmeId) {
    //     fetchFilmeDetails(filmeId);
    // } else {
    //     movieDetailContainer.innerHTML = '<p class="error-message">ID do filme não fornecido.</p>';
    // }

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
        modalContainer.innerHTML = modalHtml;

        const { setupUserMenu } = await import('./common/ui.js');
        setupUserMenu();
    } catch (error) {
        console.error('Erro ao carregar o cabeçalho ou rodapé:', error);
    }
}

