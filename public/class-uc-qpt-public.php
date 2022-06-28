<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       unitycode.tech
 * @since      1.0.0
 *
 * @package    Uc_Qpt
 * @subpackage Uc_Qpt/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Uc_Qpt
 * @subpackage Uc_Qpt/public
 * @author     jnz93 <contato@unitycode.tech>
 */
class Uc_Qpt_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Add shortcodes
		add_shortcode( 'mindflow', array($this, 'template_quiz') );

		// Ajax Actions
		add_action('wp_ajax_ucqpt_submit_quiz', array($this, 'ucqpt_submit_quiz'));
		add_action('wp_ajax_nopriv_ucqpt_submit_quiz', array($this, 'ucqpt_submit_quiz'));
		
		add_action('wp_ajax_ucqpt_checking_voucher', array($this, 'ucqpt_checking_voucher_by_ajax'));
		add_action('wp_ajax_nopriv_ucqpt_checking_voucher', array($this, 'ucqpt_checking_voucher_by_ajax'));

		add_action('wp_ajax_uqpt_record_user_data', array($this, 'uqpt_record_user_data_by_ajax'));
		add_action('wp_ajax_nopriv_uqpt_record_user_data', array($this, 'uqpt_record_user_data_by_ajax'));

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/uc-qpt-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'uikit', plugin_dir_url( __FILE__ ) . 'css/uikit.min.css', array(), '3.6.9', 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/uc-qpt-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'uikit', plugin_dir_url( __FILE__ ) . 'js/uikit.min.js', array(), '3.6.9', false );
		wp_enqueue_script( 'uikit-icons', plugin_dir_url( __FILE__ ) . 'js/uikit-icons.min.js', array(), '3.6.9', false );
		wp_enqueue_script( 'jquery-mask', plugin_dir_url( __FILE__ ) . 'js/jquery.mask.min.js', array(), '3.6.9', false );

	}

	/**
	 * Shortcode template quiz
	 * @param $quiz_id
	 * 
	 * @since 1.0.0
	 */
	public function template_quiz($atts)
	{
		$atts = shortcode_atts( array(
			'id' 	=> 0
		), $atts, 'mindflow' );

		$quiz_id 	= $atts['id'];
		$curr_user 	= wp_get_current_user();
		$user_id 	= $curr_user->ID;

		# Admin url ajax
		$ajax_url 	= admin_url( 'admin-ajax.php' );

		# Data quiz
		$title_quiz = get_the_title( $quiz_id );
		$desc_quiz 	= get_the_excerpt( $quiz_id );
		
		# Ids das perguntas
		$meta_ids	= get_post_meta($quiz_id, 'quiz_questions_ids', true);
		$idsarr 	= explode(',', $meta_ids);

		#$has_completed_test = Uc_Qpt_Public::ucqpt_check_user_has_completed_test($user_id, $quiz_id);
		
		// echo $has_completed;
		// if ( $has_completed_test == false && !empty($idsarr)) :			
		if ( true ) :	

			ob_start();
			include( plugin_dir_path( __FILE__ ) . '/partials/templates/tpl-voucher-validation.php' );
			$append = ob_get_clean();
    		return $content . $append;
			
			// require_once plugin_dir_path( __FILE__ ) . '/partials/templates/tpl-quiz.php';
		else :
			Uc_Qpt_Public::ucqpt_print_result_test($user_id, $quiz_id);
		endif;
	}


	/**
	 * Ajax requisition result
	 * 
	 * @since 1.0.0
	 */
	public function ucqpt_submit_quiz()
	{
		# Data - model array = [pos] => 'q_id:a_id:weight'
		$data 		= $_POST['data'];
		$user_id 	= $_POST['userId'];
		$quiz_id 	= $_POST['quizId'];
		$voucher_id = $_POST['voucherId'];

		$voucher_email = get_post_meta( $voucher_id, 'ucqpt_costumer_email', true );

		if ( !empty($data) ) :

			# Iniciação das variaveis de contagem
			$total_afetivo 		= 0;
			$total_pragmatico 	= 0;
			$total_racional 	= 0;
			$total_visionario 	= 0;
			$total_points 		= 0;

			# Avaliação e notas
			foreach ( $data as $item ) : 

				# collect ids and weight
				$itemarr	= explode(':', $item);
				$q_id 		= intval($itemarr[0]);
				$a_id 		= intval($itemarr[1]);
				$weight 	= intval($itemarr[2]);

				if ( strlen($q_id) != 0 && strlen($a_id) != 0 && strlen($weight) != 0 ) :
					
					# Definição do perfil e contagem dos pontos
					$a_perfil_abv 	= get_post_meta( $a_id, 'answer_perfil', true);
					$a_perfil_str 	= '';
					switch ( $a_perfil_abv ) :
						case 'A':
							$a_perfil_str = 'Afetivo';
							$total_afetivo += $weight;
							break;
						case 'P':
							$a_perfil_str = 'Pragmático';
							$total_pragmatico += $weight;
							break;
						case 'R':
							$a_perfil_str = 'Racional';
							$total_racional += $weight;
							break;
						case 'V':
							$a_perfil_str = 'Visionário';
							$total_visionario += $weight;
							break;
						default:
							$a_perfil_str = 'Perfil não cadastrado.';
							break;
					endswitch;
					$total_points += $weight;
				else : 
					die('Verifique os dados enviados e tente novamente.');
				endif;
			
			endforeach;
			
			# Produção do resultado
			$result_arr 	= array(
				'Afetivo'		=> $total_afetivo,
				'Pragmático'	=> $total_pragmatico,
				'Racional'		=> $total_racional,
				'Visionário'	=> $total_visionario
			);
			array_multisort( $result_arr, SORT_DESC );
			// Análise das pontuações e definição de pontos fortes e fracos
			$strength_points 	= array();
			$weak_points 		= array();
			$line_of_cut 		= 80;
			
			foreach( $result_arr as $key => $value ) :
				if( $value >= $line_of_cut ) :
					$strength_points[] = $key;
				else :
					$weak_points[] = $key;
				endif;
			endforeach;

			# Verificar se temos pelo menos 2 perfis fortes
			# Se não tiver então inserimos manualmente os dois maiores valores como fortes e os menores como fraco
			if( count( $strength_points ) <= 1 ) :
				$strength_points[0] = $result_arr[0];
				$strength_points[1] = $result_arr[1];
				$weak_points[0]		= $result_arr[2];
				$weak_points[1]		= $result_arr[3];
			endif;

			$strength_points_str 	= implode('/', $strength_points);
			$weak_points_str 		= implode('/', $weak_points);

			# Tratamento dados do resultado do quiz para salvar no voucher
			$key_voucher_result 		= 'ucqpt_test_result_data';
			$key_voucher_is_used 		= 'ucqpt_is_used';
			$voucher_value_result_data 	= $quiz_id
											.'|res_str_pts:'. $strength_points_str 
											.'|res_weak_pts:'. $weak_points_str 
											.'|pts_a:'. $total_afetivo 
											.'|pts_p:'. $total_pragmatico 
											.'|pts_r:'. $total_racional 
											.'|pts_v:'. $total_visionario 
											.'|total_pts:'. $total_points;

			$key_voucher_id		= 'ucqpt_vouchers_data';
			$value_voucher_ids 	= get_post_meta( $quiz_id, $meta_key_voucher_id, true ) . ',' . $voucher_id;	

			# Salvar resultado no voucher
			update_post_meta( $voucher_id, $key_voucher_result, $voucher_value_result_data );
			update_post_meta( $voucher_id, $key_voucher_is_used, 'yes' );
			
			# Salvar id do voucher no quiz(id)
			update_post_meta( $quiz_id, $key_voucher_id, $value_voucher_ids );

			# Descrição e textos
			$result_description = 'Os pontos fortes são representados pelas funções cognitivas principal e auxiliar (es) (uma ou duas funções auxiliares). O estilo é o resultado da combinação dos pontos fortes ou aptidões em que podemos atuar naturalmente e desenvolver nossos talentos. Os pontos fracos serão representados por áreas que não possuímos aptidões para atuar. O estilo não muda ao longo da vida, o desafio é conhecê-lo e gerir melhor suas forças, riscos e fraquezas.';
			$persona_afetiva	= array( 'Afetiva', 'Padrão cognitivo com aptidão para relacionamento com as pessoas. Sua energia psíquica busca a empatia e a participação das pessoas. Perfil Afetivo visa harmonizar e integrar as pessoas.' );
			$persona_racional 	= array( 'Racional', 'Padrão cognitivo com aptidão para dados e fatos e senso de realidade. Sua energia psíquica busca sistematizar, organizar e padronizar ambientes e relações. Perfil lógico e analítico traz precisão e controle ao meio.' );
			$persona_pragmatico = array( 'Pragmático', 'Padrão cognitivo com aptidão para ação, foco e resultados. Sua energia psíquica busca fazer o que precisa ser feito. Perfil prático, decidido e realizador torna o ambiente produtivo.' );
			$persona_visionario = array( 'Visionário', 'Padrão cognitivo com aptidão para ideias, conceitos e oportunidades. Sua energia psíquica busca mudar e inovar seu campo de percepção. Perfil reflexivo busca trazer estratégia e renovação para o meio.' );
	
			# Linkando o doc para donwload
			$name_archive = strtolower($strength_points_str);
			$name_archive = str_replace('/', '-', $name_archive);
			$name_archive = $name_archive . '.pdf';
			$path_archive = plugin_dir_url( __FILE__ ) . 'reports/'.$name_archive;

			// Salvar caminho do arquivo no voucher
			update_post_meta($voucher_id, 'ucqpt_path_archive', $path_archive);

			# Imprimindo o Resultado
			$output = '<div class="uk-card uk-card-default uk-card-body uk-width-1-1">
						<h3 class="uk-card-title">Resultado</h3>
						<ul class="uk-list">
							<li>Pontos Fortes: '. $strength_points_str.'</li>
							<li>Pontos Fracos: '. $weak_points_str .'</li>
							<li>Um e-mail foi enviado para '. $voucher_email .' com o resultado completo!</li>
						</ul>';

			// Testes
			$result = Uc_Qpt_PDFResult::run( $voucher_id );		
			if ( $result ) :
				
				$output .= '<p>Resultado processado com sucesso!</p>';

				$data 			= Uc_Qpt_EmailResult::send( $voucher_id ); # Disparo de notificação usuário
				$notifyAdmin 	= Uc_Qpt_EmailResult::sendAdminNotification( $result ); # Disparo da notificação admin
				if ( $data ) :
					$output .= '<p>E-mail enviado com sucesso!</p>';
				else :
					$output .= '<p>Problema ao enviar e-mail.</p>';
				endif;
				
			endif;
			
			$output .= '</div>';
			echo $output;
			die();

		else :
			die('Dados inválidos. Atualize a página e faça o teste novamente!');
		endif;
	}

	
	/**
	 * Checa se o usuário já fez o teste
	 * 
	 * @param $user_id
	 * @param $quiz_id 
	 * 
	 * @since 1.1.0
	 */
	public function ucqpt_check_user_has_completed_test($user_id, $quiz_id)
	{
		$user_has_completed_str		= get_user_meta( $user_id, 'qpt_ids_done', true ); # String de ids de testes concluídos pelo usuário
		$test_has_completed_str 	= get_post_meta( $quiz_id, 'qpt_users_ids', true); # String de ids de usuários que concluiram o teste

		$user_has_completed_arr 	= explode(',', $user_has_completed_str);
		$test_has_completed_arr 	= explode(',', $test_has_completed_str);

		$has_testid_in_user 		= in_array($quiz_id, $user_has_completed_arr);
		$has_userid_in_test 		= in_array($user_id, $test_has_completed_arr);

		if ( $has_testid_in_user && $has_userid_in_test ) :
			return true;
		else :
			return false;
		endif;
	}


	/**
	 * Imprime resultado do teste completado
	 * 
	 * @param $user_id
	 * @param $quiz_id
	 * 
	 * @since 1.1.0
	 */
	public function ucqpt_print_result_test($user_id, $quiz_id)
	{
		$data_result 		= get_user_meta( $user_id, 'qpt_result_of_'. $quiz_id, true ); # Resultado completo do teste
		$data_result_arr 	= explode('|', $data_result);

		$sanitized_result 	= array();
		for ( $i = 0; $i < count($data_result_arr) ; $i++ ) :

			$data = explode( ':', $data_result_arr[$i] );
			$sanitized_result[$data[0]] = $data[1];
	
		endfor;

		$quiz_id 			= $sanitized_result['quiz_id'];
		$pts_strong 		= $sanitized_result['res_str_pts'];
		$pts_weakness		= $sanitized_result['res_weak_pts'];
		$pts_afetivo 		= $sanitized_result['pts_a'];
		$pts_pragmatico 	= $sanitized_result['pts_p'];
		$pts_racional 		= $sanitized_result['pts_r'];
		$pts_visionario 	= $sanitized_result['pts_v'];
		$pts_total 			= $sanitized_result['total_pts'];

		$result 		= '<div class="uk-card uk-card-default uk-card-body uk-width-1-2@m">
								<div class="uk-card-badge uk-label">Total: '. $pts_total .'</div>
								<h3 class="uk-card-title">Resultado final:</h3>
								<ul class="uk-list uk-list-striped">
									<li>Ponto(s) forte(s): '. $pts_strong .'</li>
									<li>Ponto(s) fraco(s): '. $pts_weakness .'</li>
									<li>Afetivo: '. $pts_afetivo .'</li>
									<li>Pragmático: '. $pts_pragmatico .'</li>
									<li>Racional: '. $pts_racional .'</li>
									<li>Visionário: '. $pts_visionario .'</li>
								</ul>
							</div>';

		echo $result;
	}

	/**
	 * Verificação do voucher para iniciar o teste
	 * 
	 * @since 1.1.0
	 */
	public function ucqpt_checking_voucher_by_ajax()
	{
		$voucher_code = $_POST['voucherCode'];
		
		# Get and sanitize response
		$get_voucher 	= wp_remote_get( get_site_url() . '/wp-json/wp/v2/search?search='. $voucher_code .'&post_type=uc_voucher' );
		$voucher_data 	= wp_remote_retrieve_body($get_voucher);
		$voucher_data 	= json_decode($voucher_data);

		if ( ! empty( $voucher_data ) ) :
			
			# Voucher Data
			$v_id 			= $voucher_data[0]->id;
			$v_title 		= $voucher_data[0]->title;
			$v_url 			= $voucher_data[0]->url;
			$v_status 		= get_post_status( $v_id );
			$v_date 		= get_post_datetime( $v_id );
			$data_voucher 	= array();
			$voucher_used 	= false;
			$curr_date 		= new DateTime();
			$today 			= $curr_date->format('d-M-Y H:i:s');

			$was_used 		= get_post_meta( $v_id, 'ucqpt_is_used', true );
			$by_user 		= get_post_meta( $v_id, 'ucqpt_for_user_data', true );
			$company_id 	= get_post_meta( $v_id, 'ucqpt_company_id', true);
			$result_data 	= get_post_meta( $v_id, 'ucqpt_test_result_data', true );
			$attachment 	= get_attached_media( '', $v_id );
			$pdf_link 		= '';
			if( !empty( $attachment ) ) :
				foreach( $attachment as $item ) {
					$pdf_link .= $item->guid;
				}
			endif;

			$data_voucher['id'] 			= $v_id;
			$data_voucher['company_id']		= $company_id;
			$data_voucher['created_date']	= $v_date;
			$data_voucher['utilized_date']	= $today;
			$data_voucher['status'] 		= $v_status;
			$data_voucher['link'] 			= $v_url;
			$data_voucher['title'] 			= $v_title;
			$data_voucher['utilized'] 		= $was_used;
			$data_voucher['user_data']		= $by_user;
			$data_voucher['result_data'] 	= $result_data;

			if ( $was_used == 'yes' ) :
				$voucher_used = true;
			endif;
			
			if ( $voucher_used ) :

				$result = '<div class="uk-card uk-card-default uk-card-body uk-width-1-1 retorno">
					<h3 class="uk-card-title">Voucher já utilizado.</h3>
					<ul class="uk-list">
						<p class="">Faça o download do resultado clicando no botão abaixo</p>
						<a href="'. $pdf_link .'" class="uk-button uk-button-secondary" target="_blank">Baixar Resultado</a>
					</ul>
				</div>';

			echo $result;

			else :

				require_once plugin_dir_path( __FILE__ ) . '/partials/templates/tpl-get-user-info.php';

			endif;

		else :
			
			echo '<div class="uk-card uk-card-default uk-card-body uk-width-1-1 retorno">
					<h3 class="uk-card-title">Ops! Algo deu errado.</h3>
					<ul class="uk-list">
						<p class="">Voucher <b>' . $voucher_code . '</b> Inválido!</p>
					</ul>
				</div>';

		endif;

		die();
	}

	/**
	 * Salva os dados do usuário no teste e voucher e libera o teste
	 * 
	 * @since 1.1.0
	 */
	public function uqpt_record_user_data_by_ajax()
	{
		if( empty( $_POST ) ) return;

		$user_name 	= $_POST['name'];
		$user_email = $_POST['email'];
		$user_phone = $_POST['phone'];
		$voucher_id = $_POST['voucher'];
		$quiz_id 	= $_POST['quiz'];

		$saved_user_name 		= get_post_meta( $voucher_id, 'ucqpt_costumer_name', true);
		$saved_user_email 		= get_post_meta( $voucher_id, 'ucqpt_costumer_email', true);
		$saved_user_cpf 		= get_post_meta( $voucher_id, 'ucqpt_costumer_cpf', true);
		$saved_user_tel 		= get_post_meta( $voucher_id, 'ucqpt_costumer_tel', true);

		# Data quiz
		$title_quiz = get_the_title( $quiz_id );
		$desc_quiz 	= get_the_excerpt( $quiz_id );
		
		# Ids das perguntas
		$meta_ids	= get_post_meta($quiz_id, 'quiz_questions_ids', true);
		$idsarr 	= explode(',', $meta_ids);

		if ( strtolower($saved_user_name) == strtolower($user_name) || $saved_user_email == $user_email || $saved_user_tel == $user_phone ) :

			update_post_meta( $voucher_id, 'ucqpt_costumer_name', $user_name );
			update_post_meta( $voucher_id, 'ucqpt_costumer_email', $user_email );
			update_post_meta( $voucher_id, 'ucqpt_costumer_tel', $user_phone );

			require_once plugin_dir_path( __FILE__ ) . '/partials/templates/tpl-quiz.php';

		else: 

			echo 'Dados inválidos. Tente novamente.';

		endif;

		die();
	}

	/**
	 * Retorna o template do gráfico html
	 * 
	 * @param arr $data array do resultado
	 */
	public function render_graphic_result($data)
	{
		ob_start();
		include( plugin_dir_path( __FILE__ ) . '/partials/templates/tpl-graphic-result.php' );
		$append = ob_get_clean();
		echo $content . $append;
	}
}