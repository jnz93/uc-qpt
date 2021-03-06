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
			'quiz_id' 	=> 747
		), $atts, 'quiz' );

		# Admin url ajax
		$ajax_url = admin_url( 'admin-ajax.php' );

		# Data quiz
		$quiz_id = $atts['quiz_id'];
		$title_quiz = get_the_title( $quiz_id );
		$desc_quiz 	= get_the_excerpt( $quiz_id );
		
		# Ids das perguntas
		$meta_ids	= get_post_meta($quiz_id, 'quiz_questions_ids', true);
		$idsarr 	= explode(',', $meta_ids);

		# Wrapper perguntas e respostas
		if ( !empty($idsarr) ) :
			$quizContent = '<div class="wrapper-quiz" data-id="'. $quiz_id .'"><h2>'. $title_quiz .'</h2><p>'. $desc_quiz .'</p><div class="wrapper-result">';
			$number = 1;
			$question = '';
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

				// echo '<pre>';
				// print_r($answers);
				// echo '</pre>';

				# Front-end
				$question .= '<div class="wrapper-question" data-id="'. $id .'"><h4 id=" class="">'. $number .' - '. $title_question .'</h4>';
				
				if ( !empty($answers) ) :
					$letters = array('a)', 'b)', 'c)', 'd)', 'e)', 'f)');
					$pos = 0;
					$options = '<div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">';
					foreach ( $answers as $answer ) :
						$options .= '<label><input class="uk-radio" type="radio" name="group-'. $id .'" data-id="'. $answer->ID .'"> '. ucfirst($letters[$pos]).' '. $answer->post_title .'</label><br>';
						$pos++;
					endforeach;
					$options .= '</div>';
				endif;

				$question .= $options;
				$question .= '</div>';
				$number++;
			endforeach;
		echo $quizContent;
		echo $question;
		endif;
		?>
		</div><div><button class="btn-primary" type="button" onclick="submitAnswers('<?php echo $ajax_url ?>', jQuery('.wrapper-question'))">Responder Quiz</button></div></div>
		<?php
	}


	/**
	 * Ajax requisition result
	 * 
	 * @since 1.0.0
	 */
	public function ucqpt_submit_quiz()
	{
		$answers 		= $_POST['answers'];
		$question_ids 	= $_POST['questionIds'];

		// print_r($answers);
		// print_r($question_ids);
		# Definição do peso das  perguntas
		$total_questions 	= count($question_ids);
		$value_question 	= 10 / $total_questions;

		// echo $value_question . '----';
		// echo $number_questions;
		$result_quiz = 0.0;
		if( !empty($answers) ) :
			$meta_key = 'answer_is_correct';
			foreach ($answers as $id) :
				$is_correct = get_post_meta($id, $meta_key, true);
				if ( $is_correct == 'true' ) :
					// echo 'Resposta certa! - ' . $id . '<br>';
					$result_quiz = $result_quiz + $value_question;
				else :
					// echo 'Resposta errada! - ' . $id . '<br>';
				endif;
			endforeach;
		endif;

		// echo 'Resultado: ' . $result_quiz;

		// RESULTADO
		$result_text 	= '<h3>Resultado: </h3><p>Sua nota: '. $result_quiz .'</p>';
		$pragmatico 	= '<p>PRAGMÁTICO: Percebe e valoriza no mundo a ação, decisão e o resultado.</p>';
		$visionario 	= '<p>VISIONÁRIO: Percebe e valoriza no mundo as ideias, o novo e a oportunidade.</p>';
		$afetivo 		= '<p>AFETIVO: Percebe e valoriza no mundo o sentimento e as pessoas.</p>';
		$racional 		= '<p>RACIONAL: Percebe e valoriza as regras, a previsibilidade e a organização.</p>';

		if ( $result_quiz < 2.5) :
			echo $result_text . $pragmatico;
		elseif ( $result_quiz > 2.5 && $result_quiz < 5.0 ) :
			echo $result_text . $visionario;
		elseif ( $result_quiz > 5.0 && $result_quiz < 7.5 ) :
			echo $result_text . $afetivo;
		else :
			echo $result_text . $racional;
		endif;
	}
}
