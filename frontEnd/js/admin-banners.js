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

    // --- FUNÇÃO AUXILIAR PARA REQUISIÇÕES SEGURAS ---
    // Esta função evita o erro "JSON.parse" lendo o texto antes
    async function safeFetch(url, options = {}) {
        const response = await fetch(url, options);
        const responseText = await response.text();

        // Debug: Mostra no console exatamente o que o servidor mandou
        // Se houver erro de PHP, vai aparecer aqui entre as linhas vermelhas
        if (!response.ok || !responseText.startsWith('{') && !responseText.startsWith('[')) {
            console.log("🔴 --- RESPOSTA DO SERVIDOR (DEBUG) --- 🔴");
            console.log(responseText);
            console.log("🔴 ------------------------------------ 🔴");
        }

        try {
            const data = JSON.parse(responseText);
            
            if (!response.ok) {
                throw new Error(data.message || data.error || `Erro HTTP: ${response.status}`);
            }
            
            return data;
        } catch (e) {
            // Se falhar ao converter para JSON, lança um erro com o texto original
            if (!response.ok) {
                throw new Error(`Erro HTTP: ${response.status}`);
            }
            throw new Error(`Resposta inválida do servidor: ${responseText.substring(0, 100)}... (Veja o console)`);
        }
    }

    async function fetchBanners() {
        try {
            const banners = await safeFetch(`${API_BASE_URL}/api/banners`, { 
                credentials: 'include' 
            });
            renderBanners(banners);
        } catch (error) {
            console.error('Erro ao buscar banners:', error);
            // Não vamos alertar na busca para não travar a tela, apenas logar
        }
    }

    function renderBanners(banners) {
        bannersTbody.innerHTML = '';
        if (!Array.isArray(banners)) {
            console.error('Formato de banners inválido:', banners);
            return;
        }
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
        document.getElementById('ativo').checked = banner.ativo == 1 || banner.ativo == '1' || banner.ativo === true;
        
        if (banner.imagem_url) {
            imagemPreview.src = banner.imagem_url;
            imagemPreview.style.display = 'block';
        } else {
            imagemPreview.style.display = 'none';
        }
        
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
        
        // Checkbox handling
        formData.set('ativo', document.getElementById('ativo').checked ? 'true' : 'false');

        const url = id ? `${API_BASE_URL}/api/banners/update/${id}` : `${API_BASE_URL}/api/banners`;
        // Nota: Em algumas configurações de servidor, UPDATE com multipart/form-data pode exigir POST com _method
        const method = 'POST'; 

        try {
            await safeFetch(url, {
                method: method,
                credentials: 'include',
                body: formData,
            });

            modal.style.display = 'none';
            fetchBanners();
            alert('Banner salvo com sucesso!');
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
                const banner = await safeFetch(`${API_BASE_URL}/api/banners/${id}`, { 
                    credentials: 'include' 
                });
                openModalForEdit(banner);
            } catch (error) {
                console.error('Erro ao buscar banner para edição:', error);
                alert('Erro ao carregar dados do banner. Veja o console.');
            }
        }

        if (target.classList.contains('btn-delete')) {
            if (confirm('Tem certeza que deseja deletar este banner?')) {
                try {
                    await safeFetch(`${API_BASE_URL}/api/banners/${id}`, {
                        method: 'DELETE',
                        credentials: 'include',
                    });
                    fetchBanners();
                } catch (error) {
                    console.error('Erro ao deletar banner:', error);
                    alert(`Erro ao deletar: ${error.message}`);
                }
            }
        }
    });

    // Carregar banners ao iniciar
    fetchBanners();
});
