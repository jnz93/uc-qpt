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
		add_shortcode( 'quiz', array($this, 'template_quiz') );

		// Ajax Actions
		add_action('wp_ajax_ucqpt_submit_quiz', array($this, 'ucqpt_submit_quiz'));
		add_action('wp_ajax_nopriv_ucqpt_submit_quiz', array($this, 'ucqpt_submit_quiz'));

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
			'quiz_id' 	=> 0
		), $atts, 'quiz' );

		if ( !is_user_logged_in() ) :
			die('Somente usuários logados tem acesso!');
		endif;

		$quiz_id 	= $atts['quiz_id'];
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

		$has_completed_test = Uc_Qpt_Public::ucqpt_check_user_has_completed_test($user_id, $quiz_id);
		
		// echo $has_completed;
		if ( $has_completed_test == false && !empty($idsarr)) :			
			
			# Wrapper perguntas e respostas
			$quizContent = '<div class="wrapper-quiz" data-id="'. $quiz_id .'"><h2>'. $title_quiz .'</h2><p>'. $desc_quiz .'</p><div class="wrapper-result">';
			$number = 1;
			$question = '';
			$caution = '<div>
							<h4>Teste de estilo</h4>
							<p class="">Analise cuidadosamente cada questão e suas alternativas e atribua:</p>
							<ul class="uk-list uk-list-hyphen">
								<li>Nota 6 para a alternativa que <b>MAIS</b> tem a ver com você.</li>
								<li>Nota 4 para a alternativa que se aproxima <b>UM POUCO MAIS</b> de você.</li>
								<li>Nota 2 para a alternativa que se aproxima <b>UM POUCO MENOS</b> de você.</li>
								<li>Nota 1 para alternativa que <b>MENOS</b> tem a ver com você.</li>
							</ul>
							<span class="">OBS: Use a pontuação 1, 2, 4 e 6 em todas as questões, na ordem que escolher, sem repetir valores.</span>
						</div>';
			foreach ( $idsarr as $id ) :

				# Data question
				$title_question = get_the_title( $id );
				$desc_question 	= get_the_content( $id );

				# Answers
				$args = array(
					'post_type'		=> 'uc_answer',
					'post_parent'	=> $id,
					'posts_per_page'=> -1,
				);
				$answers = get_posts( $args );

				# Front-end
				$question .= '<div class="wrapper-question" data-id="'. $id .'"><h4 id=" class="">'. $number .' - '. $title_question .'</h4>';
				
				if ( !empty($answers) ) :
					$letters = array('a)', 'b)', 'c)', 'd)', 'e)', 'f)');
					$pos = 0;
					$options = '<div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">';
					foreach ( $answers as $answer ) :
						$options .= '<div class="uk-width-1-1" uk-grid>
										<label class="uk-width-1-1@s">
											<input class="uk-radio" type="checkbox" name="group-'. $id .'" data-id="'. $answer->ID .'"> '. ucfirst($letters[$pos]).' '. $answer->post_title .'
											<input class="uk-input uk-form-width-small" type="number" id="" placeholder="Peso">
										</label>
									</div>';
						$pos++;
					endforeach;
					$options .= '</div>';
				endif;

				$question .= $options;
				$question .= '</div>';
				$number++;
			endforeach;
			echo $quizContent;
			echo $caution;
			echo $question;
			?>
			</div><div><button class="btn-primary" type="button" onclick="submitAnswers(jQuery('.wrapper-question'), '<?php echo $quiz_id; ?>', '<?php echo $user_id; ?>', '<?php echo $ajax_url; ?>')">Responder Quiz</button></div></div>
		<?php
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

			// Análise das pontuações e definição de pontos fortes e fracos
			$strength_points 	= array();
			$weak_points 		= array();
			$line_of_cut 		= 30;
			
			# Produção do resultado
			if ( $total_afetivo >= $line_of_cut ) :
				$strength_points[] = 'Afetivo';
			else :
				$weak_points[] = 'Afetivo';
			endif;

			if ( $total_pragmatico >= $line_of_cut ) :
				$strength_points[] = 'Pragmático';
			else :
				$weak_points[] = 'Pragmático';
			endif;

			if ( $total_racional >= $line_of_cut ) :
				$strength_points[] = 'Racional';
			else :
				$weak_points[] = 'Racional';
			endif;

			if ( $total_visionario >= $line_of_cut ) :
				$strength_points[] = 'Visionário';
			else :
				$weak_points[] = 'Visionário';
			endif;

			$strength_points_str 	= implode(', ', $strength_points);
			$weak_points_str 		= implode(', ', $weak_points);

			# Tratamento dados do resultado do quiz para salvar
			$user_key_result 			= 'qpt_result_of_' . $quiz_id;
			$user_meta_value_result 	= 'quiz_id:'. $quiz_id .'|res_str_pts:'. $strength_points_str .'|res_weak_pts:'. $weak_points_str .'|pts_a:'. $total_afetivo .'|pts_p:'. $total_pragmatico .'|pts_r:'. $total_racional .'|pts_v:'. $total_visionario .'|total_pts:'. $total_points;
			
			# Tratamento para salvar id do quiz submetido
			$user_key_qids				= 'qpt_ids_done';
			$user_meta_qids_value 		= get_user_meta( $user_id, $user_meta_qids, true ) . ', ' . $quiz_id;
			
			# Salvar dados no usuário
			update_user_meta( $user_id, $user_key_result, $user_meta_value_result );
			update_user_meta( $user_id, $user_key_qids, $user_meta_qids_value );

			# Salvar id do usuário no quiz submetido
			$meta_key_quiz 		= 'qpt_users_ids';
			$meta_value_quiz 	= get_post_meta( $quiz_id, $meta_key_quiz, true ) . ',' . $user_id;
			update_post_meta( $quiz_id, $meta_key_quiz, $meta_value_quiz );

			# Imprimindo o Resultado
			$result = '<div class="uk-card uk-card-default uk-card-body uk-width-1-2">
						<h3 class="uk-card-title">Resultado</h3>
						<ul class="uk-list">
							<li>Pontos Fortes: '. $strength_points_str.'</li>
							<li>Pontos Fracos: '. $weak_points_str .'</li>
							<li>Total: '. $total_points .'</li>
						</ul>
					</div>';

			echo $result;

		else :
			die('Dados inválidos');
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
}
