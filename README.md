# CineTick

<div align="center">
  <img src="https://github.com/LeanAlbu/Cinetick/raw/main/frontEnd/img/logo.png" alt="Logo CineTick" width="300px">
</div>

<br>

> **⚠️ Aviso Importante:**
> O link do **GitHub Pages** (Deploy) exibe apenas uma **Landing Page demonstrativa** do front-end, pois o GitHub não suporta nativamente a execução de PHP e Banco de Dados MySQL. Para testar as funcionalidades completas do sistema (Login, Compra, Painel Admin), siga as instruções da seção [🚀 Como Executar o Projeto](#-como-executar-o-projeto) abaixo.

---

## 📖 Sobre o Projeto

O **CineTick** é uma plataforma voltada para a implementação do ambiente virtual de franquias de cinema. A plataforma oferece um catálogo abrangente de filmes em cartaz, permitindo que os clientes escolham e comprem seus ingressos de forma prática e segura, com um sistema de pagamento online integrado.

O sistema foca amplamente na interação com o usuário, permitindo que este veja os filmes em cartaz, suas avaliações, comentários de outros usuários e interaja diretamente com uma comunidade focada na sétima arte.

### 🔗 Links
- **Deploy (Landing Page):** [Acessar CineTick no GitHub Pages](https://leanalbu.github.io/Cinetick/)
- **Repositório da Disciplina:** [Engenharia de Software 2025/2](https://github.com/disciplinas-prof-Edeilson-UFT/eng-soft-2025-2)

---

## 📚 Informações Acadêmicas

| Campo | Detalhe |
| :--- | :--- |
| **Universidade** | Universidade Federal do Tocantins - Campus Palmas |
| **Curso** | Ciência da Computação |
| **Disciplina** | Engenharia de Software |
| **Semestre** | 2025/2 |
| **Professor** | Edeilson Milhomem |

---

## 📋 Visão do Produto

### Para os usuários
O CineTick representa uma mescla ideal entre a eficiência de comprar ingressos pela internet e a diversão de uma rede social onde todos têm um interesse em comum. Juntando entretenimento e praticidade, o público pode usufruir dos melhores lançamentos, trocar ideias sobre filmes e ainda garantir seu ingresso sem sair de casa.

### Para os administradores
O CineTick apresenta a simplificação do serviço de fazer cinema. Sem a necessidade de filas, check-ups locais ou dinheiro físico, a plataforma permite ao administrador do cinema focar nas operações centrais, oferecendo relatórios, estatísticas, controle rápido de sessões e fácil inserção de novos filmes em cartaz.

---

## ✨ Funcionalidades e Requisitos

### Requisitos Funcionais
* **RF01 - Cadastro do Usuário:** Permitir que novos usuários criem uma conta no sistema.
* **RF02 - Login do Usuário:** Permitir que usuários cadastrados acessem a plataforma.
* **RF03 - Catálogo de filmes:** Exibir o catálogo completo de filmes disponíveis.
* **RF04 - Futuros lançamentos:** Mostrar informações sobre os próximos lançamentos.
* **RF05 - Compra de ingressos:** Permitir que os usuários selecionem e comprem ingressos.
* **RF06 - Pagamento Online:** Integrar um sistema de pagamento para a compra de ingressos.

### User Stories
* **US01 - Cadastro do Usuário:** Como um cliente, quero poder criar uma conta no sistema registrando minhas informações pessoais, como nome e e-mail. Para isso, na tela de login, devo clicar em "Cadastrar".
* **US02 - Login do Usuário:** Como um cliente cadastrado, quero poder acessar a plataforma utilizando meu e-mail e senha. Também desejo poder recuperar minha senha caso a esqueça, clicando em "Esqueci minha senha".
* **US03 - Catálogo de filmes:** Como um cliente logado, quero poder visualizar o catálogo completo de filmes disponíveis, com descrições e imagens, para que eu possa escolher o que desejo assistir.
* **US04 - Futuros lançamentos:** Como cliente logado, quero ter acesso a uma prévia dos futuros lançamentos, sabendo a data e o gênero do filme.
* **US05 - Compra de ingressos:** Como um cliente logado, quero poder comprar ingressos para os filmes escolhidos. Para isso, devo clicar em "Comprar ingressos" abaixo do pôster do filme selecionado.

---

## 🛠️ Tecnologias Utilizadas

* **Linguagem Back-end:** PHP 8+
* **Banco de Dados:** MySQL (Relacional)
* **Servidor Local:** Apache (via XAMPP)
* **Front-end:** HTML5, CSS3, JavaScript
* **Gerenciamento de Dependências:** Composer

---

## 📅 Sprints de Desenvolvimento

### Sprint 1: Esqueleto sólido e funcional
Neste primeiro momento de desenvolvimento, demos larga ênfase na produção de um esqueleto sólido que comporte a adição de funcionalidades futuras. Nesta etapa, asseguramos ao cliente um projeto funcional, mesmo sem todas as opções finais.
* **Entregas:** Página inicial, Filmes em cartaz, Compra de Ingressos (v1), Cadastro simples, Adição de Filmes.

### Sprint 2: Implementações avançadas
Visamos trazer uma versão próxima da final das funcionalidades já adicionadas, assegurando que o projeto esteja em um estado funcional e polido.
* **Entregas:** Sistema de login avançado, Padronização estética (Final), Compra de ingressos (Final), Banco de dados integrado.

### Sprint 3: Complementos e Gestão
Adicionadas funções que complementam as da iteração 2, focando na experiência do usuário e controle administrativo.
* **Entregas:** Painel de ADM, Controle de sessões, Alocação de Poltronas, Recuperação de senha.

### Sprint 4: Qualidade de Código
Foco total em testes para garantir a estabilidade do sistema.
* **Entregas:** Testes unitários PHP (PHPUnit) e Testes unitários JS.

### Sprint 5: Bomboniere e Finalização
Adição de serviços extras e ajustes finais para a release.
* **Entregas:** Sistema de bomboniere, Repaginação de banners, Melhoria na alocação de rotas.

---

## 🚀 Como Executar o Projeto

Siga os passos abaixo para rodar o ambiente de desenvolvimento localmente com todas as funcionalidades (PHP + MySQL).

### Pré-requisitos
* [Git](https://git-scm.com)
* [XAMPP](https://www.apachefriends.org/pt_br/index.html) (ou ambiente LAMP/WAMP equivalente)

### Passo a Passo

1. **Clone o repositório:**
   ```bash
   git clone [https://github.com/LeanAlbu/Cinetick.git](https://github.com/LeanAlbu/Cinetick.git)
   ```

2. **Mova os arquivos para o servidor:**
   * Copie a pasta `Cinetick` clonada.
   * Cole a pasta dentro do diretório raiz do XAMPP (geralmente em `C:\xampp\htdocs`).

3. **Inicie os serviços:**
   * Abra o **XAMPP Control Panel**.
   * Inicie os módulos **Apache** e **MySQL** clicando no botão "Start".

4. **Configure o Banco de Dados:**
   * Acesse `http://localhost/phpmyadmin` no seu navegador.
   * Crie um novo banco de dados com o nome: `cinetick`.
   * Clique na aba **Importar**.
   * Selecione o arquivo `.sql` localizado na pasta `database` ou `sql` dentro do projeto clonado.
   * Execute a importação.

5. **Acesse o Projeto:**
   * Abra o navegador e digite:
     ```
     http://localhost/Cinetick
     ```

> **Nota:** Verifique se o arquivo de conexão com o banco de dados (ex: `conexao.php`) está configurado com as credenciais padrão do seu XAMPP (geralmente `user: root` e `password: vazio`).

---

## 👥 Equipe de Desenvolvimento

Este projeto foi desenvolvido pela equipe da disciplina de Engenharia de Software:

| Nome | GitHub | Função |
| :--- | :--- | :--- |
| **Leandro Albuquerque** | [@LeanAlbu](https://github.com/LeanAlbu) | Full Stack Dev |
| **[Nome do Aluno 2]** | [@usuario](https://github.com) | [Função] |
| **[Nome do Aluno 3]** | [@usuario](https://github.com) | [Função] |
| **[Nome do Aluno 4]** | [@usuario](https://github.com) | [Função] |

---

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

<br>

<div align="center">
  <sub>Desenvolvido com 💙 para a disciplina de Engenharia de Software - UFT 2025</sub>
</div>
