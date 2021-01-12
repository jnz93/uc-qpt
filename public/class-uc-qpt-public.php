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
		# Data sanitize: model string = q_id:a_id:weight
		$data = $_POST['data'];
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
}
