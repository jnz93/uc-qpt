<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       unitycode.tech
 * @since      1.0.0
 *
 * @package    Uc_Qpt
 * @subpackage Uc_Qpt/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Uc_Qpt
 * @subpackage Uc_Qpt/admin
 * @author     jnz93 <contato@unitycode.tech>
 */
class Uc_Qpt_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Menu admin item
		add_action('admin_menu', array($this, 'create_plugin_menu'));

		// Create custom post type
		add_action('init', array($this, 'create_custom_post_types'));

		// Ajax actions
		add_action('wp_ajax_ucqpt_create_new_quiz', array($this, 'ucqpt_create_new_quiz_by_ajax')); // executed when logged in
		add_action('wp_ajax_ucqpt_create_draft_quiz', array($this, 'ucqpt_create_draft_quiz_by_ajax')); // executed when logged in
		add_action('wp_ajax_ucqpt_create_question_and_answers', array($this, 'ucqpt_create_question_and_answers_by_ajax')); // executed when logged in
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Uc_Qpt_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Uc_Qpt_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/uc-qpt-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'uikit', plugin_dir_url( __FILE__ ) . 'css/uikit.min.css', array(), '3.6.5');

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Uc_Qpt_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Uc_Qpt_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/uc-qpt-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'uikit', plugin_dir_url( __FILE__ ) . 'js/uikit.min.js', array(), '3.6.5', false );
		wp_enqueue_script( 'uikit-icons', plugin_dir_url( __FILE__ ) . 'js/uikit-icons.min.js', array(), '3.6.5', false );
	}

	/**
	 * Create page admin of plugin
	 * 
	 * @since 1.0.0
	 */
	public function create_plugin_menu()
	{
		$page_title = 'Quiz Personality Test';
		$menu_title = 'QPT - Quiz';
		$menu_slug 	= 'qpt-admin';
		$capability = 10;
		$icon_url 	= 'dashicons-editor-spellcheck';
		$position 	= 20;

		add_menu_page($page_title, $menu_title, $capability, $menu_slug, array($this, 'construct_plugin_page'), $icon_url, $position);
	}

	/**
	 * Construct page admin function
	 * 
	 * @since 1.0.0
	 */
	public function construct_plugin_page()
	{
		$buttons = '<p uk-margin>
						<button class="uk-button uk-button-default uk-button-large" uk-toggle="target: #new-quiz">Novo Teste de Personalidade</button>
					</p>';

		$card_buttons = '<div class="uk-child-width-1-2@s uk-grid-match" uk-grid>
							<div>
								<div class="uk-card uk-card-default uk-card-body">
									<h3 class="uk-card-title">Menu Principal</h3>
									'. $buttons .'
								</div>
							</div>
						</div>';
		
		// Outputs
		echo $card_buttons;

		// include templates
		require_once plugin_dir_path( __FILE__ ) . 'partials/templates/tpl-list-all-quizes.php';
		require_once plugin_dir_path( __FILE__ ) . 'partials/uc-qpt-new-quiz.php';
		// require_once plugin_dir_path( __FILE__ ) . 'partials/templates/uchb-register-customer.php';
		// require_once plugin_dir_path( __FILE__ ) . 'partials/templates/uchb-register-budget.php';
	}

	/**
	 * Register custom post types
	 * 
	 * @since 1.0.0
	 */
	public function create_custom_post_types()
	{
		$labels = array(
			'name'                  => _x( 'Quiz Personality Test', 'Post type general name', 'textdomain' ),
			'singular_name'         => _x( 'QPT', 'Post type singular name', 'textdomain' ),
			'menu_name'             => _x( 'QPT', 'Admin Menu text', 'textdomain' ),
			'name_admin_bar'        => _x( 'QPT', 'Add New on Toolbar', 'textdomain' ),
			'add_new'               => __( 'Adicionar novo', 'textdomain' ),
			'add_new_item'          => __( 'Adicionar novo teste', 'textdomain' ),
			'new_item'              => __( 'Novo teste', 'textdomain' ),
			'edit_item'             => __( 'Editar teste', 'textdomain' ),
			'view_item'             => __( 'Ver teste', 'textdomain' ),
			'all_items'             => __( 'Todos os testes', 'textdomain' ),
			'search_items'          => __( 'Procurar teste', 'textdomain' ),
			'parent_item_colon'     => __( 'Parent test:', 'textdomain' ),
			'not_found'             => __( 'Nenhum teste encontrado.', 'textdomain' ),
			'not_found_in_trash'    => __( 'Nenhum teste encontrado na lixeira.', 'textdomain' ),
			'featured_image'        => _x( 'Capa do teste', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
			'set_featured_image'    => _x( 'Definir imagem de capa', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
			'remove_featured_image' => _x( 'Remover imagem de capa', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
			'use_featured_image'    => _x( 'Usar imagem como capa', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
			'archives'              => _x( 'Arquivo de testes', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
			'insert_into_item'      => _x( 'Inserir no teste', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
			'uploaded_to_this_item' => _x( 'Carregar para este teste', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
			'filter_items_list'     => _x( 'Filtro de testes', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
			'items_list_navigation' => _x( 'Navegação da lista de teste', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
			'items_list'            => _x( 'Lista de teste', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => 'Teste de personalidade',
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'quiz' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => true,
			'menu_position'      => 20,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail' ),
			'taxonomies'         => array( 'category', 'post_tag' ),
			'show_in_rest'       => true
		);
		register_post_type( 'uc_quiz', $args );

		// Questões
		unset($labels);
		unset($args);
		$labels = array(
			'name'                  => _x( 'QPT - Questões', 'Post type general name', 'textdomain' ),
			'singular_name'         => _x( 'Questão', 'Post type singular name', 'textdomain' ),
			'menu_name'             => _x( 'QPT - Questões', 'Admin Menu text', 'textdomain' ),
			'name_admin_bar'        => _x( 'QPT - Questões', 'Add New on Toolbar', 'textdomain' ),
			'add_new'               => __( 'Adicionar nova', 'textdomain' ),
			'add_new_item'          => __( 'Adicionar nova questão', 'textdomain' ),
			'new_item'              => __( 'Nova questão', 'textdomain' ),
			'edit_item'             => __( 'Editar Questão', 'textdomain' ),
			'view_item'             => __( 'Ver Questão', 'textdomain' ),
			'all_items'             => __( 'Todas as Questões', 'textdomain' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => 'Perguntas utilizadas nos testes',
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'question' ),
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 20,	
			'supports'           => array( 'title', 'editor', 'author' ),
			'taxonomies'         => array( 'category', 'post_tag' ),
			'show_in_rest'       => true
		);
		register_post_type( 'uc_question', $args );

		// Respostas
		unset($labels);
		unset($args);
		$labels = array(
			'name'                  => _x( 'QPT - Respostas', 'Post type general name', 'textdomain' ),
			'singular_name'         => _x( 'Resposta', 'Post type singular name', 'textdomain' ),
			'menu_name'             => _x( 'QPT - Respostas', 'Admin Menu text', 'textdomain' ),
			'name_admin_bar'        => _x( 'QPT - Respostas', 'Add New on Toolbar', 'textdomain' ),
			'add_new'               => __( 'Adicionar nova', 'textdomain' ),
			'add_new_item'          => __( 'Adicionar nova resposta', 'textdomain' ),
			'new_item'              => __( 'Nova resposta', 'textdomain' ),
			'edit_item'             => __( 'Editar resposta', 'textdomain' ),
			'view_item'             => __( 'Ver resposta', 'textdomain' ),
			'all_items'             => __( 'Todas as respostas', 'textdomain' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => 'Perguntas utilizadas nos testes',
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'answers' ),
			'has_archive'        => true,
			'hierarchical'       => true,
			'menu_position'      => 20,	
			'supports'           => array( 'title', 'editor', 'author' ),
			'show_in_rest'       => true
		);
		register_post_type( 'uc_answer', $args );
	}

	/**
	 * Recebe os dados para criação do novo teste via ajax
	 * 
	 * @since 1.0.0
	 */
	public function ucqpt_create_new_quiz_by_ajax()
	{
		$data 				= $_POST['data'];
		$extract_data 		= explode('||', $data);
		$post_title 		= $extract_data[0];
		$post_description 	= $extract_data[1];

		$postarr = array(
			'post_title'    => $post_title,
			'post_content'  => $post_description,
			'post_status'   => 'publish',
			'post_type'		=> 'uc_quiz'
		);
		$post_id = wp_insert_post( $postarr );

		if ( !is_wp_error( $post_id )) :
			echo '<span id="quiz-id" style="display: none;">'. $post_id .'</span>';
		else :
			echo 'err';
		endif;
	}

	/**
	 * Cria novo rascunho de teste via ajax
	 * 
	 * @since 1.0.0
	 */
	public function ucqpt_create_draft_quiz_by_ajax()
	{
		$postarr = array(
			'post_title'	=> 'Rascunho de avaliação',
			'post_status'	=> 'draft',
			'post_type'		=> 'uc_quiz'
		);

		$draft_id = wp_insert_post( $postarr );

		if ( !is_wp_error( $draft_id ) ) :
			echo $draft_id;
		else :
			echo 'Erro ao criar rascunho';
		endif;
	}

	/**
	 * Criação de perguntas e respostas via ajax
	 * 
	 * @since 1.0.0
	 */
	public function ucqpt_create_question_and_answers_by_ajax()
	{
		$question 	= $_POST['question'];
		$answers 	= $_POST['answers'];
		$quiz_id 	= $_POST['quizId'];

		$answers = explode('||', $answers);
		// insert new question
		$postarr = array(
			'post_title'	=> $question,
			'post_content'	=> '',
			'post_type'		=> 'uc_question',
			'post_status'	=> 'publish'
		);
		$question_id = wp_insert_post( $postarr );
		if ( !is_wp_error( $question_id ) ) :

			foreach ($answers as $answer) :

				# Sanitize Answer title
				$arr 		= explode('>>', $answer);
				$title 		= $arr[0];
				$perfil		= $arr[1];

				$postarr = array(
					'post_title' 	=> $title,
					'post_parent'	=> $question_id,
					'post_type'		=> 'uc_answer',
					'post_status'	=> 'publish'
				);
				$answer_id = wp_insert_post( $postarr );

				// update_post_meta para assinalar certa ou errada.
				update_post_meta( $answer_id, 'answer_perfil', $perfil);
			endforeach;

			// Salvar id da pergunta no quiz
			$curr_questions_ids = get_post_meta($quiz_id, 'quiz_questions_ids', true);
			if ( !empty($curr_questions_ids) ) :
				$new_questions_ids = $curr_questions_ids . ',' . $question_id;
			else :
				$new_questions_ids = $question_id;
			endif;

			// Update post meta
			update_post_meta( $quiz_id, 'quiz_questions_ids', $new_questions_ids );
		endif;
	}
}
