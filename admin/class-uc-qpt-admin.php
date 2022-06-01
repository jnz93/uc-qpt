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
		add_action('admin_enqueue_scripts', array($this, 'ucqpt_hide_menu_wordpress'));

		// Create custom post type
		add_action('init', array($this, 'create_custom_post_types'));

		// Redirect to admin plugin page after login on wp admin
		add_filter( 'login_redirect', array( $this, 'ucqpt_admin_default_page' ), 10, 3 );

		// Remove admin noticies for not super admin users
		add_action('in_admin_header', array( $this, 'ucqpt_hide_notices_to_all_but_super_admin'), 99);

		// Woocommerce Menu		
		add_filter ( 'woocommerce_account_menu_items', array($this, 'ucqpt_remove_my_account_links') );

		// Ajax actions
		add_action('wp_ajax_ucqpt_create_new_quiz', array($this, 'ucqpt_create_new_quiz_by_ajax')); // executed when logged in
		add_action('wp_ajax_ucqpt_create_draft_quiz', array($this, 'ucqpt_create_draft_quiz_by_ajax')); // executed when logged in
		add_action('wp_ajax_ucqpt_create_question_and_answers', array($this, 'ucqpt_create_question_and_answers_by_ajax')); // executed when logged in
		add_action('wp_ajax_ucqpt_register_company', array($this, 'ucqpt_register_company_by_ajax')); // executed when logged in
		add_action('wp_ajax_ucqpt_generate_voucher_code', array($this, 'ucqpt_generate_voucher_code_by_ajax')); // executed when logged in
		add_action('wp_ajax_ucqpt_create_voucher', array($this, 'ucqpt_create_voucher_by_ajax')); // executed when logged in
		add_action('wp_ajax_ucqpt_switch_show_question', array($this, 'ucqpt_switch_show_question_by_ajax')); // executed when logged in
		add_action('wp_ajax_ucqpt_update_voucher_data', array($this, 'ucqpt_update_voucher_data_by_ajax')); // executed when logged in
		add_action('wp_ajax_ucqpt_get_used_voucher_data', array($this, 'ucqpt_get_used_voucher_data_by_ajax')); // executed when logged in
		add_action('wp_ajax_ucqpt_load_inventory_data', array($this, 'ucqpt_load_inventory_data_by_ajax')); // executed when logged in
		add_action('wp_ajax_ucqpt_update_data', array($this, 'ucqpt_update_data_by_ajax')); // executed when logged in
		add_action('wp_ajax_ucqpt_update_company_data', array($this, 'ucqpt_update_company_data_by_ajax')); // executed when logged in
		add_action('wp_ajax_ucqpt_refresh_vouchers_table', array($this, 'ucqpt_refresh_vouchers_table_by_ajax')); // executed when logged in
		add_action('wp_ajax_ucqpt_get_voucher_data', array($this, 'ucqpt_get_voucher_data_by_ajax')); // executed when logged in
		add_action('wp_ajax_ucqpt_get_company_data', array($this, 'ucqpt_get_company_data_by_ajax')); // executed when logged in

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
		wp_enqueue_script( 'jquery-mask', plugin_dir_url( __FILE__ ) . 'js/jquery.mask.min.js', array(), '', false );
	}

	/**
	 * Create page admin of plugin
	 * 
	 * @since 1.0.0
	 */
	public function create_plugin_menu()
	{
		// Admin page
		$page_title = 'MindFlow Inventário';
		$menu_title = 'MindFlow Admin';
		$menu_slug 	= 'mindflow-admin';
		$capability = '5';
		$icon_url 	= 'dashicons-editor-spellcheck';
		$position 	= 20;

		add_menu_page($page_title, $menu_title, $capability, $menu_slug, array($this, 'construct_plugin_page_admin'), $icon_url, $position);

		// Contributor page
		$page_title = 'MindFlow Vouchers';
		$menu_title = 'MindFlow Empresas';
		$menu_slug 	= 'mindflow-business';
		$capability = '0';
		$icon_url 	= 'dashicons-list-view';
		$position 	= 21;

		add_menu_page($page_title, $menu_title, $capability, $menu_slug, array($this, 'construct_plugin_page_business'), $icon_url, $position);
	}

	/**
	 * Construct page admin function
	 * 
	 * @since 1.0.0
	 */
	public function construct_plugin_page_admin()
	{
		$buttons = '<p uk-margin>
						<button class="uk-button uk-button-default uk-button-large" uk-toggle="target: #new-quiz">Novo Teste de Personalidade</button>
					</p>';
		$buttons .= '<p uk-margin>
						<button class="uk-button uk-button-default uk-button-large" uk-toggle="target: #register-company">Cadastrar Empresa</button>
					</p>';
		$buttons .= '<p uk-margin>
						<button class="uk-button uk-button-default uk-button-large" uk-toggle="target: #register-voucher">Cadastrar Voucher</button>
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
		// echo $card_buttons;
		echo '<div class="uk-container">';
			require_once plugin_dir_path( __FILE__ ) . 'partials/uc-qpt-admin-display.php';
			// include templates
			require_once plugin_dir_path( __FILE__ ) . 'partials/templates/tpl-list-all-quizes.php';
			require_once plugin_dir_path( __FILE__ ) . 'partials/uc-qpt-new-quiz.php';
			require_once plugin_dir_path( __FILE__ ) . 'partials/templates/tpl-register-company.php';
			require_once plugin_dir_path( __FILE__ ) . 'partials/templates/tpl-create-voucher.php';
		echo '</div>';
	}

	/**
	 * Construct page admin for business user
	 * 
	 * @since 1.3.0
	 */
	public function construct_plugin_page_business()
	{
		echo '<div class="uk-container">';
			require_once plugin_dir_path( __FILE__ ) . 'partials/uc-qpt-business-display.php';
		echo '</div>';
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
			'show_in_menu'       => false,
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
			'show_in_menu'       => false,
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
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'answers' ),
			'has_archive'        => true,
			'hierarchical'       => true,
			'menu_position'      => 20,	
			'supports'           => array( 'title', 'editor', 'author' ),
			'show_in_rest'       => true
		);
		register_post_type( 'uc_answer', $args );

		// Voucher
		unset($labels);
		unset($args);
		$labels = array(
			'name'                  => _x( 'Vouchers', 'Post type general name', 'textdomain' ),
			'singular_name'         => _x( 'Voucher', 'Post type singular name', 'textdomain' ),
			'menu_name'             => _x( 'Vouchers', 'Admin Menu text', 'textdomain' ),
			'name_admin_bar'        => _x( 'Vouchers', 'Add New on Toolbar', 'textdomain' ),
			'add_new'               => __( 'Adicionar novo', 'textdomain' ),
			'add_new_item'          => __( 'Adicionar novo voucher', 'textdomain' ),
			'new_item'              => __( 'Nova Voucher', 'textdomain' ),
			'edit_item'             => __( 'Editar Voucher', 'textdomain' ),
			'view_item'             => __( 'Ver Voucher', 'textdomain' ),
			'all_items'             => __( 'Todas os Vouchers', 'textdomain' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => 'Vouchers para usuários fazer os testes',
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'voucher' ),
			'has_archive'        => true,
			'hierarchical'       => true,
			'menu_position'      => 20,	
			'supports'           => array( 'title', 'author' ),
			'show_in_rest'       => true
		);
		register_post_type( 'uc_voucher', $args );
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
			echo $post_id;
			die();
			
		else :
			die('Err');
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

			# show question default = yes
			update_post_meta( $question_id, '_ucqpt_show_question', 'yes' ); # yes = mostra / no = não mostra

			// Update post meta
			update_post_meta( $quiz_id, 'quiz_questions_ids', $new_questions_ids );

			echo $question_id;
			die();
		endif;
	}

	/**
	 * Registro de empresa via form
	 * 
	 * @since 1.1.0
	 */
	public function ucqpt_register_company_by_ajax()
	{
		$company_name 		= $_POST['name'];
		$company_email 		= $_POST['email'];
		$company_pass 		= $_POST['pass'];
		$company_tel 		= $_POST['tel'];
		$company_cnpj 		= $_POST['cnpj'];
		$company_vouchers 	= $_POST['vouchers'];

		if ( empty($company_name) || empty($company_email) || empty($company_vouchers) ) :
			die('Erro ao registrar usuário');
		endif;

		$user_data = array(
			'user_login'            => $company_name,   //(string) The user's login username.
			'user_nicename'         => $company_name,   //(string) The URL-friendly user name.
			'user_email'            => $company_email,  //(string) The user email address.
			'user_pass'				=> $company_pass,	//(string) The password
			'role'                  => 'contributor',   //(string) User's role.
		);
		$user_id = wp_insert_user( $user_data );
		
		if ( !is_wp_error( $user_id ) ) :
			$this->ucqpt_create_vouchers_by_qty( $user_id, $company_vouchers );
			$this->ucqpt_send_created_account_notification( $company_name, $company_pass, $company_email );

			update_user_meta( $user_id, 'ucqpt_company_tel', $company_tel );
			update_user_meta( $user_id, 'ucqpt_company_doc', $company_cnpj );			
			update_user_meta( $user_id, 'ucqpt_company_vouchers', $company_vouchers);

			die('success');
		else :
			die('error');
		endif;
	}

	/**
	 * Generate voucher code
	 * 
	 * @since 1.1.0
	 */
	public function ucqpt_generate_voucher_code_by_ajax()
	{
		$voucher = strtoupper(wp_generate_password( 8, false, false ));

		die($voucher);
	}

	/**
	 * Create voucher by ajax 
	 * 
	 * @since 1.1.0
	 */
	public function ucqpt_create_voucher_by_ajax()
	{
		$voucher_code 	= $_POST['voucherCode'];
		$company_id		= $_POST['companyId'];
		$user_name 		= $_POST['userName'];
		$user_email 	= $_POST['userEmail'];
		$user_tel 		= $_POST['userTel'];
		$user_cpf 		= $_POST['userCpf'];
		
		$key_limit 		= 'ucqpt_company_vouchers';
		$key_voucher	= 'ucqpt_company_registered_vouchers';

		# Verificar a disponibilidade de vouchers com base no limite disponível para empresa
		// update_user_meta( $company_id, 'ucqpt_company_vouchers', $company_vouchers);
		$voucher_limit			= get_user_meta( $company_id, $key_limit, true );
		$voucher_registered_str	= get_user_meta( $company_id, $key_voucher, true );
		$voucher_registered 	= explode( ',', $voucher_registered_str );
		$voucher_total_registered = count( $voucher_registered );

		if ( $voucher_limit > $voucher_total_registered ) :

			$postarr = array(
				'post_title'    => $voucher_code,
				'post_status'   => 'publish',
				'post_type'		=> 'uc_voucher'
			);
			$voucher_id = wp_insert_post( $postarr );

			if ( !is_wp_error( $voucher_id ) ) :
				$new_voucher_registered_value = $voucher_code . ',' . $voucher_registered_str;

				update_post_meta( $voucher_id, 'ucqpt_voucher_code', $voucher_code ); # Salvando o voucher code
				update_post_meta( $voucher_id, 'ucqpt_company_id', $company_id ); # Salvando o ID do usuário(empresa) no voucher
				update_user_meta( $company_id, $key_voucher, $new_voucher_registered_value ); # Salvando a string de vouchers no usuário(empresa)

				// Salvar dados do consumidor
				update_post_meta( $voucher_id, 'ucqpt_costumer_name', $user_name );
				update_post_meta( $voucher_id, 'ucqpt_costumer_email', $user_email );
				update_post_meta( $voucher_id, 'ucqpt_costumer_cpf', $user_cpf );
				update_post_meta( $voucher_id, 'ucqpt_costumer_tel', $user_tel );
				
				$new_title 		= $voucher_code . '-' . $voucher_id;
				$data_update 	= array(
					'ID' 			=> $voucher_id,
					'post_title' 	=> $new_title,
				);
				wp_update_post( $data_update );
				
				die($new_title);
			else :

				$error_string = $voucher_id->get_error_message();
				die('error');

			endif;
		endif;

		die();
	}

	/**
	 * Redirect to plugin admin page after login on admin wp
	 * 
	 * @since 1.2.0
	 */
	public function ucqpt_admin_default_page($redirect_to, $request, $user) 
	{

		if ( isset( $user->roles ) && is_array( $user->roles ) ) :

			if ( in_array( 'administrator', $user->roles ) ) :

				$redirect_to = get_admin_url() . 'admin.php?page=mindflow-admin';

			elseif ( in_array( 'contributor', $user->roles ) ) :

				$redirect_to = get_admin_url() . 'admin.php?page=mindflow-business';

			endif;
		endif;

		return $redirect_to;
	}

	
	/**
	 * Switcher: show or hide question on quiz
	 * 
	 * @since 1.2.0
	 */
	public function ucqpt_switch_show_question_by_ajax()
	{
		$post_id	= $_POST['qId'];
		$show 		= $_POST['show'];

		if ( empty( $show ) || empty($post_id) ) :
			die('Valor inválido!');
		endif;

		update_post_meta( $post_id, '_ucqpt_show_question', $show ); # yes = mostra / no = não mostra

		echo 'success';
		die();
	}

	/**
	 * Método para criar vouchers passando um número de itens
	 * 
	 * @param $cia_id = id da empresa
	 * @param $v_qty = quantidade de vouchers
	 * @since 1.3.0
	 */
	public function ucqpt_create_vouchers_by_qty( $cia_id, $qty )
	{
		if ( empty( $cia_id ) || empty( $qty ) )
			return;

		$voucher_qty 		= (int) $qty;
		$str_vouchers_id 	= '';
		
		for ( $i = 0; $i < $voucher_qty; $i++ ) :
			$voucher_code = strtoupper(wp_generate_password( 8, false, false ));

			// Criando o post
			$postarr = array(
				'post_title'    => $voucher_code,
				'post_status'   => 'publish',
				'post_type'		=> 'uc_voucher',
				'post_author'	=> $cia_id
			);
			$voucher_id = wp_insert_post( $postarr );

			if ( !is_wp_error( $voucher_id ) ) :
				update_post_meta( $voucher_id, 'ucqpt_voucher_code', $voucher_code ); # Salvando o voucher code
				update_post_meta( $voucher_id, 'ucqpt_company_id', $cia_id ); # Salvando o ID do usuário(empresa) no voucher
				

				// Update no titulo
				$new_title 		= $voucher_code . '-' . $voucher_id;
				$data_update 	= array(
					'ID' 			=> $voucher_id,
					'post_title' 	=> $new_title,
				);
				wp_update_post( $data_update );

				$str_vouchers 		.= ',' . $new_title;
				$str_vouchers_id 	.= ',' . $voucher_id;
		
			endif;
		endfor;

		/**
		 * Coleta e update de meta-informações sobre os vouchers
		*/
		$key_vouchers_total = 'ucqpt_company_vouchers';
		$curr_vouchers_total = get_user_meta( $cia_id, $key_vouchers_total, true );

		$new_vouchers_total  = $curr_vouchers_total + $qty;
		update_user_meta( $cia_id, $key_vouchers_total, $new_vouchers_total ); # Salvando ids de vouchers criados no usuário(empresa)

		$key_vouchers_id 	= 'ucqpt_company_vouchers_id';
		$curr_vouchers_id 	= get_user_meta( $cia_id, $key_vouchers_id, true );

		$str_vouchers_id = $curr_vouchers_id . $str_vouchers_id;
		update_user_meta( $cia_id, $key_vouchers_id, $str_vouchers_id ); # Salvando ids de vouchers criados no usuário(empresa)
	}

	/**
	 * Método para fazer update nos dados do usuário no voucher
	 * 
	 */
	public function ucqpt_update_voucher_data_by_ajax()
	{
		$post_id	= $_POST['voucherId'];
		$user_name 	= $_POST['userName'];
		$user_email = $_POST['userEmail'];
		$user_tel 	= $_POST['userTel'];
		$user_doc 	= $_POST['userDoc'];

		if ( empty( $user_name ) || empty($post_id) ) :
			die('Dados inválido!');
		endif;

		// Salvar dados do consumidor
		update_post_meta( $post_id, 'ucqpt_costumer_name', $user_name );
		update_post_meta( $post_id, 'ucqpt_costumer_email', $user_email );
		update_post_meta( $post_id, 'ucqpt_costumer_cpf', $user_doc );
		update_post_meta( $post_id, 'ucqpt_costumer_tel', $user_tel );

		die();
	}

	/**
	 * Get dados do voucher utilizado
	 * 
	 * @since 1.3.0
	 */
	public function ucqpt_get_used_voucher_data_by_ajax()
	{
		$voucher_id = $_POST['voucherId'];

		if ( empty( $voucher_id ) )
			return;
		
		$user_name 		= get_post_meta( $voucher_id, 'ucqpt_costumer_name', true );
		$user_email 	= get_post_meta( $voucher_id, 'ucqpt_costumer_email', true );
		$user_cpf 		= get_post_meta( $voucher_id, 'ucqpt_costumer_cpf', true );
		$user_tel 		= get_post_meta( $voucher_id, 'ucqpt_costumer_tel', true );
		$data_result 	= get_post_meta( $voucher_id, 'ucqpt_test_result_data', true );

		$result_arr = explode( '|', $data_result );

		// Dados do usuário
		$user_data = '<ul class="uk-list uk-list-striped">
						<li>Nome: '. $user_name .'</li>
						<li>E-mail: '. $user_email .'</li>
						<li>Telefone: '. $user_tel .'</li>
						<li>CPF: '. $user_cpf .'</li>
					</ul>';

		$sanitazed_data = array();
		foreach ( $result_arr as $r ) :
			$r_arr = explode( ':', $r );

			$sanitazed_data[] = $r_arr[1];
		endforeach;
		$result = '<table class="uk-table uk-table-striped">
						<thead>
							<tr>
								<th>Dados</th>
								<th>Pontuação</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Forças</td>
								<td>'. $sanitazed_data[1] .'</td>
							</tr>
							<tr>
								<td>Fraquezas</td>
								<td>'. $sanitazed_data[2] .'</td>
							</tr>
							<tr>
								<td>Total Afetivo</td>
								<td>'. $sanitazed_data[3] .'</td>
							</tr>
							<tr>
								<td>Total Pragmático</td>
								<td>'. $sanitazed_data[4] .'</td>
							</tr>
							<tr>
								<td>Total Racional</td>
								<td>'. $sanitazed_data[5] .'</td>
							</tr>
							<tr>
								<td>Total Visionário</td>
								<td>'. $sanitazed_data[6] .'</td>
							</tr>
						</tbody>
					</table>';

		echo '<ul class="uk-subnav uk-subnav-pill" uk-switcher>
					<li><a href="#">Dados do usuário</a></li>
					<li><a href="#">Resultado</a></li>
				</ul>
				<ul class="uk-switcher uk-margin">
					<li>'. $user_data .'</li>
					<li>'. $result .'</li>
				</ul>';
				Uc_Qpt_Public::render_graphic_result($sanitazed_data);
		die();
	}

	/**
	 * Enviar e-mail com dados de acesso ao usuário/empresa cadastrada
	 * 
	 * @param $login = usuario de acesso
	 * @param $pass = senha de acesso
	 * @param $email = email do cliente
	 * @since 1.3.2
	 */
	public function ucqpt_send_created_account_notification( $login, $pass, $email )
	{
		$url_panel 		= get_permalink( wc_get_page_id( 'myaccount' ) );
		$to 			= $email;
		$subject 		= 'Mindflow - Cadastro na plataforma';
		$headers 		= array('Content-Type: text/html; charset=UTF-8');
		$message 		= '<h2>Dados de acesso</h2>
							<table>
								<thead>
									<tr>
										<th>Desc.</th>
										<th>Dado</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Login</td>
										<td>'. $login .'</td>
									</tr>
									<tr>
										<td>Senha</td>
										<td>'. $pass .'</td>
									</tr>
									<tr>
										<td>Endereço do painel</td>
										<td>'. $url_panel .'</td>
									</tr>
								</tbody>
							</table>';
		

		wp_mail( $to, $subject, $message, $headers );
	}

	/**
	 * Remover items do menu wordpress conforme o nível do usuário
	 * 
	 * @since 1.3.5
	 */
	public function ucqpt_hide_menu_wordpress()
	{
		if ( is_admin() && current_user_can( 'contributor' ) ) :
			echo '<style>#adminmenumain{display: none !important}</style>';
		endif;
	}
	
	/**
	 * Carregar dados de um inventário via ajax
	 * @since 1.4.0
	 */
	public function ucqpt_load_inventory_data_by_ajax()
	{
		if ( empty($_POST) ) :
			die();
		endif;

		$inventory_id = $_POST['data'];
		
		// Collect data
		$title 			= get_the_title( $inventory_id );
		$desc 			= get_the_excerpt( $inventory_id );
		$ajax_url 		= admin_url('admin-ajax.php');

		$list_ids 		= get_post_meta( $inventory_id, 'quiz_questions_ids', true ); 
		$arr_ids		= explode(',', $list_ids);
		?>
		<input type="hidden" name="ajaxurl" id="ajaxurl" value="<?php echo $ajax_url; ?>">
		<button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header uk-flex uk-flex-between">
            <div class="">
                <h2 class="uk-modal-title" ondblclick="editElement(jQuery(this), '<?php echo $inventory_id; ?>')" uk-tooltip="title: Clique duas vezes para editar; pos: right"><?php echo $title ?></h2>
                <p class="uk-text"><?php echo $desc ?></p>
            </div>
        </div>
        <div class="uk-modal-body">
            <div class="wrapper-new-question">    
                <div id="wrapper-data" class="uk-width-1-1">
                    <!-- <div class="uk-width-1-1 uk-flex uk-flex-center">
                        <button class="uk-button uk-button-default uk-button-medium" type="button" onclick="addTplQuestion('<?php echo $ajax_url; ?>')"><span uk-icon="icon:  plus"></span> Adiconar nova pergunta</button>
                    </div> -->
					<?php
					if ( !empty($arr_ids) ) :
						$tpl = '';
						foreach ( $arr_ids as $id ) :

							# Pergunta
							$question_title = get_the_title( $id );?>

							<div class="uk-card uk-card-default uk-width-1-1 uk-margin-small-bottom" data-question-id="<?php echo $id ?>">
								<div class="uk-card-header">
									<div class="uk-grid">
										<h4 class="uk-width-5-6" ondblclick="editElement(jQuery(this), '<?php echo $id; ?>')" uk-tooltip="title: Clique duas vezes para editar; pos: top-right"><?php echo $question_title ?></h4>
										<div class="uk-flex ucqpt-actions uk-width-1-6">
											<div class="uk-grid-small uk-child-width-auto uk-grid">
												<label class="uk-margin-small-bottom" uk-tooltip="Desative a pergunta e ela não será exibida ao usuário.">
													<input class="uk-checkbox" type="checkbox" data-value="yes" onclick="setShowHide('<?php echo $ajax_url; ?>', jQuery(this))"> Desativar
												</label>
											</div>
										</div>
										<div class="uk-margin uk-margin-small-right uk-width-1-1">
											<button class="uk-margin-small-right" uk-tooltip="Mostrar respostas" <?php echo 'uk-toggle="target: #answers-'. $id .'; animation: uk-animation-fade"' ?>><i class="" uk-icon="list"></i>Respostas</button>
											<!-- <button class="" uk-tooltip="Excluir da base de perguntas"><i class="" uk-icon="trash"></i>Excluir</button> -->
										</div>
									</div>
								</div>
								<?php

								# Respostas
								$args = array(
									'post_parent'    => $id,
									'post_status'    => 'publish',
								);
								$answers = get_children( $args );

								if ( ! is_wp_error( $answers ) ) : ?>
									<div id="<?php echo 'answers-'. $id .''; ?>" class="uk-card-body" hidden>
										<div class="uk-width-1-1">
											<h4 class="">Respostas</h4>
											<?php
											foreach ( $answers as $answer ) :
											
												$answer_title 	= $answer->post_title;
												$answer_id		= $answer->ID; 
												$answer_perfil 	= get_post_meta( $answer_id, 'answer_perfil', true);
												switch ( $answer_perfil ) :
													case $answer_perfil == 'A':
														$answer_perfil = 'Afetivo';
														break;

													case $answer_perfil == 'P':
														$answer_perfil = 'Pragmático';
														break;

													case $answer_perfil == 'R':
														$answer_perfil = 'Racional';
														break;
													
													case $answer_perfil == 'V':
														$answer_perfil = 'Visionário';
														break;

													default:
														$answer_perfil = 'Não identificado';
														break;
												endswitch;

												?>
												<div class="uk-margin uk-flex uk-flex-row " answer-id="<?php echo $answer_id; ?>">
													<p class="uk-text-emphasis uk-width-5-6" ondblclick="editElement(jQuery(this), '<?php echo $answer_id; ?>')" uk-tooltip="title: Clique duas vezes para editar; pos: top-right"><?php echo $answer_title; ?></p>
													<div class="uk-width-1-6 uk-position-relative">
														<span class="uk-badge uk-label uk-position-absolute uk-position-center-right"><?php echo $answer_perfil; ?></span>
													</div>
												</div> 
												<?php
											endforeach;
											?>
										</div>
									</div>
									<?php
								endif;?>
								</div>
							<?php
						endforeach;
						?>
						</div>
						<?php
					endif;
					?>
				</div>
            </div>
		</div>
		</div>
        <div class="uk-modal-footer uk-text-right">
        </div>
		<?php
		die();
	}

	/**
	 * Salva título de inventário, pergunta e resposta via ajax
	 * 
	 * @since v1.4.0 
	 */
	public function ucqpt_update_data_by_ajax()
	{
		$id 	  	= $_POST['id'];
		$new_data 	= $_POST['title'];

		$postarr = array(
			'ID'           => $id,
			'post_title'   => $new_data,
			// 'post_content' => 'This is the updated content.',
		);
		$update = wp_update_post( $postarr );

		if ( ! is_wp_error( $update ) ) :
			echo 'success';
		endif;

		die();
	}

	/**
	 * Update nos dados de empresas cadastradas via ajax
	 * 
	 * @since v1.4.0
	 */
	public function ucqpt_update_company_data_by_ajax()
	{
		$id 		= $_POST['id'];
		$type 		= $_POST['type'];
		$value 		= $_POST['title'];
		$user_data	= array(
			'ID' 	=> $id,
		);

		if ( $type ) :
			switch ($type) :

				case $type == 'name':
					$user_data['display_name'] 	= $value;
					break;

				case $type == 'email':
					$user_data['user_email'] 	= $value;
					break;
					
				case $type == 'pass':
					$user_data['user_pass']		= $value;
					break;

				default:
					unset($user_data);
					break;

			endswitch;

			if ( ! empty( $user_data ) ) :
				
				$update = wp_update_user( $user_data );

				if ( !is_wp_error( $update ) ) :
					die ('success');
				else :
					die('error');
				endif;

			else :

				if ( $type == 'phone' ) :
					update_user_meta( $id, 'ucqpt_company_tel', $value );
					die('success');
				endif;

				if ( $type == 'doc' ) :
					update_user_meta( $id, 'ucqpt_company_doc', $value );
					die('success');
				endif;

				if ( $type == 'vouchers' ) :
					$this->ucqpt_create_vouchers_by_qty( $id, $value );
					die('success');
				endif;

			endif;
		else :

			die('error');

		endif;
	}

	/**
	 * Hide admin notices for the users are not super admin
	 * 
	 * @since v1.4.2
	 */
	public function ucqpt_hide_notices_to_all_but_super_admin(){
		if ( !is_super_admin() ) {
			remove_all_actions( 'user_admin_notices' );
			remove_all_actions( 'admin_notices' );
		}
	}

	/**
	 * Disable my account menu items
	 * 
	 * @since v1.5.0
	 */
	public function ucqpt_remove_my_account_links( $menu_links ){
	
		unset( $menu_links['edit-address'] ); // Addresses
		unset( $menu_links['orders'] ); // Remove Orders
		unset( $menu_links['downloads'] ); // Disable Downloads
	
		//unset( $menu_links['dashboard'] ); // Remove Dashboard
		//unset( $menu_links['payment-methods'] ); // Remove Payment Methods
		//unset( $menu_links['edit-account'] ); // Remove Account details tab
		//unset( $menu_links['customer-logout'] ); // Remove Logout link
	
		return $menu_links;
	
	}

	/**
	 * Refresh vouchers table data
	 * 
	 * @since v1.5.2
	 */
	public function ucqpt_refresh_vouchers_table_by_ajax() 
	{
		$user_id = $_POST['id'];

		if ( empty( $user_id ) ) :
			die('Usuário inválido');
		endif;

		$args = array(
			'post_type'         => 'uc_voucher',
			'author'            => $user_id,
			'order_by'          => 'post_date',
			'order'             => 'ASC',
			'posts_per_page'    => -1
		);
		
		$vouchers       = new WP_Query($args);
		$vouchers_count = $vouchers->post_count;

		if ( $vouchers->have_posts() ) :
			?>
			<table class="uk-table uk-table-divider uk-table-hover uk-overflow-auto">
				<thead>
					<tr>
						<th>Código</th>
						<th>Utilizado</th>
						<th>Ações</th>
					</tr>
				</thead>
				<tbody>
				<?php
				while ( $vouchers->have_posts() ) :
					$vouchers->the_post();
					$post_id            = get_the_ID();
					$v_code             = get_the_title( );
					$v_is_used          = get_post_meta( $post_id, 'ucqpt_is_used', true );
					$v_result_test_data = get_post_meta( $post_id, 'ucqpt_result_test_data', true );
					?>
					<tr data-id="<?php echo $post_id; ?>">
						<td><?php echo $v_code; ?></td>
						<td>
							<?php if ( $v_is_used == 'yes') : ?>
								Sim <span class="uk-margin-small-left" uk-icon="file-text" uk-tooltip="Abrir Resultado" uk-toggle="target: #result-voucher" onclick="setVoucherIdOnResultModal('<?php echo $post_id ?>', '<?php echo $v_code; ?>', '<?php echo $ajax_url; ?>')"></span>
							<?php else : ?>
								Não
							<?php endif; ?>
						</td>
						<td>
							<span class="uk-margin-small-right" uk-icon="pencil" uk-tooltip="Editar Voucher" uk-toggle="target: #edit-voucher" onclick="setVoucherIdOnModal('<?php echo $post_id; ?>', '<?php echo $v_code; ?>', '<?php echo $ajax_url ?>')"></span> 
							<span style="display: none !important;" uk-icon="ban" uk-tooltip="Excluir voucher"></span>
						</td>
					</tr>
					<?php
				endwhile;
				?>
				</tbody>
			</table>
			<?php
		endif;

		die();
	}

	/**
	 * Returns the user voucher data
	 * 
	 * @since v1.5.3
	 */
	public function ucqpt_get_voucher_data_by_ajax()
	{
		if( empty( $_POST ) ) return;

		$voucher_id 	= $_POST['id'];

		$user_name 		= get_post_meta( $voucher_id, 'ucqpt_costumer_name', true );
		$user_email 	= get_post_meta( $voucher_id, 'ucqpt_costumer_email', true );
		$user_cpf 		= get_post_meta( $voucher_id, 'ucqpt_costumer_cpf', true );
		$user_tel 		= get_post_meta( $voucher_id, 'ucqpt_costumer_tel', true );

		$data_to_client = '{"name": "'. $user_name .'", "email": "'. $user_email .'", "doc": "'. $user_cpf .'", "tel": "'. $user_tel .'"}';

		echo $data_to_client;
		die();
	}


	/**
	 * Recebe o id e retorna os dados da empresa
	 * 
	 * @since v1.6.0
	 */
	public function ucqpt_get_company_data_by_ajax()
	{
		if( empty( $_POST ) ) return;

		$company_id = $_POST['companyId'];

		include( plugin_dir_path( __FILE__ ) . 'partials/templates/company/company-data.php' );
		die();
	}
}
