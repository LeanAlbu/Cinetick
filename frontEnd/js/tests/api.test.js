import { createMovieCard, fetchMovies, displayMovies } from '../common/api.js';

// Mock da função global fetch
global.fetch = jest.fn();

// Define as variáveis globais que agora são usadas pelos módulos
beforeAll(() => {
    Object.defineProperty(window, 'BASE_URL', {
        value: 'http://localhost/Cinetick/backEnd/public',
        writable: true
    });
    Object.defineProperty(window, 'API_BASE_URL', {
        value: 'http://localhost/Cinetick/backEnd/public/api',
        writable: true
    });
});


describe('createMovieCard', () => {
    test('deve criar um card de filme com imagem e título', () => {
        const filme = {
            id: 1,
            title: 'Filme Teste',
            imagem_url: '/uploads/posters/poster.jpg'
        };
        const cardHtml = createMovieCard(filme);
        expect(cardHtml).toContain('filme.html?id=1');
        expect(cardHtml).toContain(`${window.BASE_URL}/uploads/posters/poster.jpg`);
        expect(cardHtml).toContain('Pôster de Filme Teste');
        expect(cardHtml).toContain('<h3>Filme Teste</h3>');
    });

    test('deve usar placeholder se imagem_url não estiver presente', () => {
        const filme = {
            id: 2,
            title: 'Filme Sem Imagem'
        };
        const cardHtml = createMovieCard(filme);
        expect(cardHtml).toContain('img/filme-placeholder.png');
        expect(cardHtml).toContain('Pôster de Filme Sem Imagem');
    });
});

describe('fetchMovies', () => {
    beforeEach(() => {
        fetch.mockClear();
    });

    test('deve retornar filmes quando a API responder com sucesso', async () => {
        const mockMovies = [{ id: 1, title: 'Filme 1' }];
        fetch.mockImplementationOnce(() =>
            Promise.resolve({
                ok: true,
                json: () => Promise.resolve(mockMovies),
            })
        );

        const movies = await fetchMovies('/some-endpoint');
        expect(movies).toEqual(mockMovies);
        expect(fetch).toHaveBeenCalledWith(`${window.API_BASE_URL}/some-endpoint`);
    });

    test('deve lançar um erro quando a API responder com falha', async () => {
        fetch.mockImplementationOnce(() =>
            Promise.resolve({
                ok: false,
                statusText: 'Not Found',
                json: () => Promise.resolve({ error: 'Filmes não encontrados' }),
            })
        );

        await expect(fetchMovies('/some-endpoint')).rejects.toThrow('Filmes não encontrados');
    });
});

describe('displayMovies', () => {
    let container;

    beforeEach(() => {
        document.body.innerHTML = '<div id="movies-container"></div>';
        container = document.getElementById('movies-container');
    });

    test('deve exibir cards de filmes no container', () => {
        const mockMovies = [
            { id: 1, title: 'Filme A', imagem_url: 'urlA.jpg' },
            { id: 2, title: 'Filme B', imagem_url: 'urlB.jpg' },
        ];
        displayMovies(mockMovies, 'movies-container');
        expect(container.innerHTML).toContain('Filme A');
        expect(container.innerHTML).toContain('Filme B');
        expect(container.querySelectorAll('.movie-card').length).toBe(2);
    });

    test('deve exibir mensagem se não houver filmes', () => {
        displayMovies([], 'movies-container');
        expect(container.innerHTML).toContain('Nenhum filme encontrado no momento.');
    });

    test('não deve fazer nada se o container não existir', () => {
        document.body.innerHTML = ''; // Remove o container
        displayMovies([{ id: 1, title: 'Filme A' }], 'non-existent-container');
        expect(document.body.innerHTML).toBe(''); // O body deve permanecer vazio
    });
});
