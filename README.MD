# README

## MINDFLOW PERSONALITY
Solução para aplicação do inventário de personalidade Método PRAV da [Mindflow Academy](https://metodoprav.com.br). Um sistema multi-camadas e desenhado especificamente para o gerenciamento e aplicação dos testes de personalidade.
O sistema possibilita a criação de inventários, cadastro de novas perguntas e respostas, reutilização de perguntas já cadastradas no banco, cadastro de empresas, sistema de vouchers para aplicação dos testes, disparos de notificações via e-mail, edição e customização de arquivos PDF's e muito mais.

### Regras do sistema
 * Para cada pergunta é possível adicionar até 4 respostas.
 * Administradores ou empresas podem cadastrar usuários nos vouchers disponíveis. Para ter acesso ao teste o Usuário deve fazer a autenticação com as informações pré-cadastradas(Nome, e-mail e telefone)
 * Testes concluídos não poderão ser refeitos ou editados pelo usuário
 * Temos várias combinações de resultados de perfis possíveis em PDF. Sempre que um teste é concluído, com base na pontuação dos perfis, o sistema da "match" no arquivo de resultado. Esse arquivo passa pela edição onde o nome do usuário e o gráfico da pontuação são inseridos, após isso o arquivo fica disponível para download na tela de conclusão do inventário.

 * __Usuário__
    * Deve ser vinculado a um voucher pela empresa ou administrador.
    * Cada usuário poderá fazer o teste disponível uma única vez.

* __Empresa__
    * São cadastradas por administradores via painel de administração do sistema.
    * Tem acesso ao painel exclusivo onde podem gerenciar os vouchers disponibilizados.

* __Administrador__
    * Gerenciamento de inventários, perguntas e respostas;
    * Gerenciamento de empresas e usuários;
    * Painel de administração do sistema.

### Módulos
* Quiz
   * Criação do quiz
   * Adição das perguntas e suas configurações
   * Adição das respostas e suas configurações
   * Correção e Resultado
* Front-end
  * Página do quiz
* Painel usuário
  * Mostrar os testes disponíveis no painel do usuário
  * Mostrar os resultados dos testes no painel do usuário
  * Criar página template para o quiz(front-end)

### Custom Post Types
 * __uc_quiz__: O título recebe o nome do quiz, também podemos adicionar configurações de template e categorias.
   * No editor: Assim como em outros plugins de quiz, nós vamos inserir botões e opções para adicionar perguntas e respostas relativos ao quiz.
   * No Front-end: Dentro do post inserimos um shortcode com as questões relacionadas a ele.
   * Cada quiz recebe um meta-campo "ids/user ids" para salvar id(s) do(s) usuários habilitados a fazer o quiz.
    > Em um campo select mostrar todos os usuários cadastrados. Também mostrar uma opção de busca por nome. O administrador poderá selecionar vários usuários.

 * __Post type: uc_questions__: Cada nova questão será um post com título, descrição e até categoria.
   * Cada pergunta recebe um meta-campo "valor/peso" que deve ser preenchido pelo administrador com um valor numérico.
   * Cada pergunta recebe um meta-campo "show/hide" para definir se a pergunta deverá aparecer ou não no teste do usuário no front-end.

 * __Post type: uc_anwers__: Cada resposta será um novo post com um titulo genérico e a descrição inserida pelo administrador.
   * Cada resposta será vinculada a pergunta que pertence através de ID ou hierarquia de posts.
   * Além disso cada resposta recebe um meta-campo "correto" para apontar a resposta certa.

## Documentações
* [Wordpress Plugin API](https://codex.wordpress.org/Plugin_API)
* [Framework Front-End UiKit](https://getuikit.com/docs/introduction)

## Instalação
1) Faça o upload no diretório ou instale o plugin de forma comum pelo painel do wordpress

-----------

* Contributors: @jnz93
* Tags: #agencydigital #management #projects #business
* Requires at least: 3.0.1
* Tested up to: 3.4
* Stable tag: 4.3
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
