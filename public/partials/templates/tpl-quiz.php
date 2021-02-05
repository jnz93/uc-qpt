<?php

/**
 * Provide a template for a respond quiz answer
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       unitycode.tech
 * @since      1.0.0
 *
 * @package    Uc_Qpt
 * @subpackage Uc_Qpt/public/partials/templates
 */
$number = 1;
$question = '';
$ajax_url 	= admin_url( 'admin-ajax.php' );
?>
<!-- # Wrapper perguntas e respostas -->
<div class="wrapper-quiz" data-id="<?php echo $quiz_id ?>">
    <h2><?php echo $title_quiz ?></h2>
    <p><?php echo $desc_quiz ?></p>
    <div class="wrapper-result">
        <div id="caution-box">
            <h4>Teste de estilo</h4>
            <p class="">Analise cuidadosamente cada questão e suas alternativas e atribua:</p>
            <ul class="uk-list uk-list-hyphen">
                <li>Nota 6 para a alternativa que <b>MAIS</b> tem a ver com você.</li>
                <li>Nota 4 para a alternativa que se aproxima <b>UM POUCO MAIS</b> de você.</li>
                <li>Nota 2 para a alternativa que se aproxima <b>UM POUCO MENOS</b> de você.</li>
                <li>Nota 1 para alternativa que <b>MENOS</b> tem a ver com você.</li>
            </ul>
            <span class="">OBS: Use a pontuação 1, 2, 4 e 6 em todas as questões, na ordem que escolher, sem repetir valores.</span>
        </div>
        <?php 
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
    </div>
<div>
    <button class="btn-primary" type="button" onclick="submitAnswers(jQuery('.wrapper-question'), '<?php echo $quiz_id; ?>', '<?php echo $voucher_id ?>', '<?php echo $ajax_url; ?>')">Responder Quiz</button>
</div>
</div>
