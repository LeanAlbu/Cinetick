document.addEventListener('DOMContentLoaded', () => {
    const commentForm = document.getElementById('comment-form');

    commentForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(commentForm);
        const data = {
            comment: formData.get('comment'),
            rating: formData.get('rating'),
        };

        const filmeId = formData.get('filme_id');

        try {
            const response = await fetch(`${window.API_BASE_URL}/filmes/${filmeId}/comments`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.error || 'Ocorreu um erro ao enviar o comentário.');
            }

            Swal.fire({
                title: 'Sucesso!',
                text: 'Seu comentário foi enviado com sucesso!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                location.reload();
            });

        } catch (error) {
            Swal.fire({
                title: 'Erro!',
                text: error.message,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
});