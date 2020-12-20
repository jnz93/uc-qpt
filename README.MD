# README

## UC QUIZ PERSONALITY TEST
O plugin facilitara a vida de pequenas agências digitais independente do segmento. Com ele será possível: cadastrar projetos, cadastrar clientes, enviar notificações de cobrança via e-mail e SMS além de facilitar o gerenciamento financeiro com o poderoso woocommerce integrado com a nossa solução custom.

Plugin feito para coachs. O administrador poderá criar quiz, adicionar perguntas, respostas, colocar manualmente o valor/peso de cada pergunta e também adicionar vários resultados com base na pontuação do questionário. 

## Documentações
* [Wordpress Plugin API](https://codex.wordpress.org/Plugin_API)
* [Framework Front-End UiKit](https://getuikit.com/docs/introduction)

### Regras do sistema
 * Para cada pergunta será possível adicionar várias respostas, marcar a resposta certa e definir o peso(valor) da pergunta para somar no resultado final. 
 * O administrador deverá marcar os usuários que podem acessar o questionário(via id na criação do quiz). Usuários não vinculados não terão acesso.
 * Testes concluídos não poderão ser refeitos ou editados pelo usuário(bloquear após a conclusão)
 * O administrador poderá inserir vários resultados possíveis com base na pontuação do teste.

 * __O usuário/cliente__
  * Poderá se registrar via site ou então ter seu registro feito pelo administrador.
  * Deve ter acesso ao painel de usuário onde os testes vinculados ao seu ID estarão disponíveis, assim como os resultados de testes já feitos.
  * Cada usuário poderá fazer o teste disponível uma única vez.

 * __Front-end da página do quiz__
  * Utilizar um modelo de página como template(elementor) para o questionário. Além disso o resultado deverá ser exibido na hora.

### Post Types
 * __CPT: uc_quiz__: O título recebe o nome do quiz, também podemos adicionar configurações de template e categorias.
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
