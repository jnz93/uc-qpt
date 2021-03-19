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

        <!-- # Perguntas -->
        <div id="regForm" class="">
        <?php 
        foreach ( $idsarr as $id ) :

            # Data question
            $title_question = get_the_title( $id );
            $desc_question 	= get_the_content( $id );
            $show_question  = get_post_meta( $id, '_ucqpt_show_question', true );

            if ( $show_question != 'no' ) :
                # Answers
                $args = array(
                    'post_type'		=> 'uc_answer',
                    'post_parent'	=> $id,
                    'posts_per_page'=> -1,
                );
                $answers = get_posts( $args );
                ?>
                <div class="wrapper-question tab" data-id="<?php echo $id; ?>">
                    <h4 id="" class=""><?php echo $number .' - '. $title_question; ?></h4>
                    <?php
                    if ( !empty($answers) ) :
                        $letters = array('a)', 'b)', 'c)', 'd)', 'e)', 'f)');
                        $pos = 0; ?>

                        <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">                        
                        <?php
                        foreach ( $answers as $answer ) : ?>
                            <form class="uk-width-1-1" uk-grid>
                                    <label class="uk-width-1-1@s">
                                        <input class="uk-radio" type="checkbox" name="<?php echo 'group-'. $id;?>" data-id="<?php echo $answer->ID ?>"> <?php echo ucfirst($letters[$pos]).' '. $answer->post_title ?>
                                        <input class="uk-input uk-form-width-small" type="number" id="" placeholder="Peso" style="display: none;">
                                        <select name="" id="" class="uk-select uk-form-width-small" onChange="filterWeights(jQuery(this))" oninput="this.className = ''">
                                            <option value="0" class="" selected>Selecione o peso</option>
                                            <option value="1" class="">1</option>
                                            <option value="2" class="">2</option>
                                            <option value="4" class="">4</option>
                                            <option value="6" class="">6</option>
                                        </select>
                                    </label> 
                                    <?php
                                $pos++;
                        endforeach; ?>
                                <button type="reset" class="uk-button uk-button-default" onclick="resetAnswer(jQuery(this))">Resetar pergunta</button>
                            </form>
                        </div>
                        <?php
                    endif;
                    $number++;
                    ?>
                </div>
                <?php
            endif;
        endforeach;
        ?>

        <!-- Control Buttons -->
        <div style="overflow:auto;">
            <div style="float:right;">
                <button type="button" id="prevBtn" onclick="nextPrev(-1)">Anterior</button>
                <button type="button" id="nextBtn" onclick="nextPrev(1)">Próxima</button>
            </div>
        </div>

        <!-- Circles which indicates the steps of the form: -->
        <div style="text-align:center;margin-top:40px;">
            <span class="step"></span>
            <span class="step"></span>
            <span class="step"></span>
            <span class="step"></span>
        </div>
    </div>
    <div>
        <button class="btn-primary" type="button" onclick="submitAnswers(jQuery('.wrapper-question'), '<?php echo $quiz_id; ?>', '<?php echo $voucher_id ?>', '<?php echo $ajax_url; ?>')">Responder Quiz</button>
    </div>
</div>