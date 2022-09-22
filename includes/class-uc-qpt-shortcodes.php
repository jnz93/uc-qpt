<?php
/**
 * The file that defines the shortcode classes
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @package    Uc_Qpt
 * @subpackage Uc_Qpt/includes
 * @since       1.0.0
 * @author      jnz93 <box@unitycode.tech>
 * @link        https://codex.wordpress.org/Shortcode_API
 */
class Uc_Qpt_Shortcodes{
    
    /**
	 * Construtor da classe e inicializador de hooks e shortcodes.
	 *
	 * @since    1.0.0
	 */
    public function __construct()
    {

        # Quiz Shortcode
        add_shortcode( 'mindflowApp', [ $this, 'createQuizApp' ] );
    }

    /**
     * Shortcode do teste
     */
    public function createQuizApp( $atts )
    {
        $a = shortcode_atts(
            [
                'title' => __('Inventário de Personalidade', 'textdomain'),
                'id'    => 0,
			],
            $atts
        );

        $quizId     = $atts['id'];
        if( $quizId == 0 ) return;

        # Data quiz
		$quizTitle  = get_the_title( $quizId );
		$quizDesc   = get_the_excerpt( $quizId );
        $questions  = explode( ',', get_post_meta( $quizId, 'quiz_questions_ids', true ) );

        ob_start();
        require plugin_dir_path( __DIR__ ) . 'public/partials/templates/tpl-welcome.php';

        return ob_get_clean();
    }
}