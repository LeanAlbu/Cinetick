document.addEventListener('DOMContentLoaded', function() {

    const API_BASE_URL = 'http://localhost/Cinetick/backEnd/public';

    const registerForm = document.getElementById('register-form');
    const registerError = document.getElementById('register-error');

    if (registerForm) {
        registerForm.addEventListener('submit', async function(event) {
            event.preventDefault();
            registerError.style.display = 'none';
            registerError.innerHTML = '';

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch(`${API_BASE_URL}/users`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ name, email, password })
                });

                const result = await response.json();

                if (!response.ok) {
                    // Transforma a string de erro em uma lista HTML
                    const errors = result.error.split(', ').map(e => `<li>${e}</li>`).join('');
                    registerError.innerHTML = `<ul>${errors}</ul>`;
                    registerError.style.display = 'block';
                } else {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Cadastro realizado!',
                        text: 'Você agora pode fazer o login.',
                        showConfirmButton: true
                    });
                    // Redireciona para a página inicial após o SweetAlert
                    window.location.href = 'index.html';
                }
            } catch (error) {
                console.error('Falha ao registrar:', error);
                registerError.textContent = 'Não foi possível conectar ao servidor.';
                registerError.style.display = 'block';
            }
        });
    }

    // Adiciona funcionalidade ao link "Faça login" dentro da página de cadastro
    const loginLinkFromRegister = document.getElementById('login-link-from-register');
    if (loginLinkFromRegister) {
        const loginModal = document.getElementById('login-modal');
        loginLinkFromRegister.addEventListener('click', (e) => {
            e.preventDefault();
            if(loginModal) loginModal.classList.add('active');
        });
    }
});
