document.addEventListener('DOMContentLoaded', function() {
    const API_BASE_URL = 'http://localhost/Cinetick/backEnd/public';
    const modal = document.getElementById('banner-modal');
    const modalTitle = document.getElementById('modal-title');
    const bannerForm = document.getElementById('banner-form');
    const bannerIdInput = document.getElementById('banner-id');
    const addBannerBtn = document.getElementById('add-banner-btn');
    const closeBtn = document.querySelector('.close-btn');
    const bannersTbody = document.getElementById('banners-tbody');
    const imagemInput = document.getElementById('imagem');
    const imagemPreview = document.getElementById('imagem-preview');

    async function fetchBanners() {
        try {
            const response = await fetch(`${API_BASE_URL}/api/banners`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const banners = await response.json();
            renderBanners(banners);
        } catch (error) {
            console.error('Erro ao buscar banners:', error);
        }
    }

    function renderBanners(banners) {
        bannersTbody.innerHTML = '';
        banners.forEach(banner => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${banner.title}</td>
                <td><img src="${banner.imagem_url}" alt="${banner.title}" width="100"></td>
                <td><a href="${banner.link_url}" target="_blank">${banner.link_url}</a></td>
                <td>${banner.ativo ? 'Sim' : 'Não'}</td>
                <td>
                    <button class="btn btn-edit" data-id="${banner.id}">Editar</button>
                    <button class="btn btn-delete" data-id="${banner.id}">Deletar</button>
                </td>
            `;
            bannersTbody.appendChild(tr);
        });
    }

    function openModalForEdit(banner) {
        modalTitle.textContent = 'Editar Banner';
        bannerForm.reset();
        bannerIdInput.value = banner.id;
        document.getElementById('title').value = banner.title;
        document.getElementById('link_url').value = banner.link_url;
        document.getElementById('ativo').checked = banner.ativo;
        imagemPreview.src = banner.imagem_url;
        imagemPreview.style.display = 'block';
        modal.style.display = 'block';
    }

    addBannerBtn.addEventListener('click', () => {
        modalTitle.textContent = 'Adicionar Banner';
        bannerForm.reset();
        bannerIdInput.value = '';
        imagemPreview.style.display = 'none';
        modal.style.display = 'block';
    });

    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    imagemInput.addEventListener('change', () => {
        const file = imagemInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                imagemPreview.src = e.target.result;
                imagemPreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    bannerForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        const id = bannerIdInput.value;
        const formData = new FormData(bannerForm);
        
        // The checkbox value is only sent if it's checked.
        // To ensure we always send a boolean, we can do this.
        formData.set('ativo', document.getElementById('ativo').checked);

        const url = id ? `${API_BASE_URL}/api/banners/update/${id}` : `${API_BASE_URL}/api/banners`;
        const method = 'POST'; // Always POST for multipart/form-data

        try {
            const response = await fetch(url, {
                method: method,
                body: formData,
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            }

            modal.style.display = 'none';
            fetchBanners();
        } catch (error) {
            console.error('Erro ao salvar banner:', error);
            alert(`Erro ao salvar banner: ${error.message}`);
        }
    });

    bannersTbody.addEventListener('click', async (event) => {
        const target = event.target;
        const id = target.dataset.id;

        if (target.classList.contains('btn-edit')) {
            try {
                const response = await fetch(`${API_BASE_URL}/api/banners/${id}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const banner = await response.json();
                openModalForEdit(banner);
            } catch (error) {
                console.error('Erro ao buscar banner para edição:', error);
            }
        }

        if (target.classList.contains('btn-delete')) {
            if (confirm('Tem certeza que deseja deletar este banner?')) {
                try {
                    const response = await fetch(`${API_BASE_URL}/api/banners/${id}`, {
                        method: 'DELETE',
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    fetchBanners();
                } catch (error) {
                    console.error('Erro ao deletar banner:', error);
                }
            }
        }
    });

    fetchBanners();
});
