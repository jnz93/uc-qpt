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
<script>
 showTab(currentTab);
</script>
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
                <div class="wrapper-question uk-card uk-card-default tab" data-id="<?php echo $id; ?>">
                    <div class="uk-card-header">
                        <h4 id="" class="uk-text-lead"><?php echo $number .' - '. $title_question; ?></h4>
                    </div>
                    <div class="uk-card-body">
                        <?php
                        if ( !empty($answers) ) :
                            $letters = array('a)', 'b)', 'c)', 'd)', 'e)', 'f)');
                            $pos = 0; ?>

                            <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">                        
                            <?php
                            foreach ( $answers as $answer ) : ?>
                                <form class="uk-width-1-1">
                                    <div class="" uk-grid>
                                        <label class="uk-width-1-1@s" onchange="validateAnswers(jQuery(this))">
                                            <span class="uk-text-emphasis" name="<?php echo 'group-'. $id;?>" data-id="<?php echo $answer->ID ?>"><?php echo ucfirst($letters[$pos]).' '. $answer->post_title ?></span>
                                            
                                            <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                                                <span class="uk-margin-small-right">Peso:</span>
                                                <select name="uk-text-emphasis" id="" class="uk-select uk-form-width-small" onChange="filterWeights(jQuery(this))" oninput="this.className = ''">
                                                    <option value="0" class="" selected>Selecione</option>
                                                    <option value="1" class="">1</option>
                                                    <option value="2" class="">2</option>
                                                    <option value="4" class="">4</option>
                                                    <option value="6" class="">6</option>
                                                </select>
                                                <span class="uk-alert-success uk-flex uk-flex-center uk-justify-center uk-margin-small-left" uk-icon="check" style="width:40px; height: 40px; padding: 0; border-radius: 100%; opacity: 0;"></span>
                                                <span class="uk-alert-danger uk-flex uk-flex-center uk-justify-center uk-margin-small-left" uk-icon="close" style="width:40px; height: 40px; padding: 0; border-radius: 100%; opacity: 0;"></span>
                                            </div>
                                        </label> 
                                        <div class="uk-divider-small uk-margin-small-top"></div>
                                    </div>
                                    <?php
                                    $pos++;
                            endforeach; ?>
                                    <button type="reset" class="uk-button uk-button-default uk-margin-small-top" onclick="resetAnswers(jQuery(this))">Resetar respostas</button>
                                </form>
                            </div>
                            <?php
                        endif;
                        $number++;
                        ?>
                    </div>
                </div>
                <?php
            endif;
        endforeach;
        ?>
        <ul class="uk-dotnav uk-flex uk-flex-center uk-margin-small-top">
            <?php
            foreach ($idsarr as $id) : ?>
                <li class="step"></li>
            <?php
            endforeach; ?>
        </ul>
        <!-- Control Buttons -->
        <div class="uk-flex uk-flex-between uk-margin-small-top">
            <button type="button" id="prevBtn" class="uk-button uk-button-secondary uk-button-small" onclick="nextPrev(-1)" style="display:none;"><span uk-icon="arrow-left"></span> Anterior</button>
            <button type="button" id="nextBtn" class="uk-button uk-button-secondary uk-button-small" onclick="nextPrev(1)" style="display:none;">Próxima <span uk-icon="arrow-right"></span></button>
        </div>
    </div>
    <div id="wrapper-submit" class="uk-flex uk-flex-center" style="display: none;">
        <button class="uk-button uk-button-primary" type="button" onclick="submitAnswers(jQuery('.wrapper-question'), '<?php echo $quiz_id; ?>', '<?php echo $voucher_id ?>', '<?php echo $ajax_url; ?>')">Responder Quiz</button>
    </div>
</div>

<script>
    showTab(currentTab);
</script>