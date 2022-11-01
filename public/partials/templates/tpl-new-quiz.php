<div class="uk-flex uk-width-1-1 uk-padding-small">
    <!-- Aside -->
    <div class="uk-flex uk-flex-wrap uk-flex-wrap-top uk-width-1-5 uk-box-shadow-small uk-text-center">
        <div class="asideContent uk-width-1-1">
            <div class="asideContent__logoWrapper">
                <img src="https://metodoprav.com.br/wp-content/uploads/2021/04/logotipo-PRAV-e1617632526222.jpg" alt="" class="asideContent__logo">
            </div>
            <h6 class="asideContent__title"><?php _e( 'Bem vindo(a) ' . $userName . '!', 'textdomain' ); ?></h6>
            <p class="asideContent__text"><?php _e( 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas pariatur expedita officiis veritatis', 'textdomain' ); ?></p>
        </div>
        <div class="asideContent uk-flex uk-flex-column uk-width-1-1">
            <span class="asideContent__text asideContent__text--caption"><?php _e( 'Você está utilizando o voucher:', 'textdomain' ); ?></span>
            <span class="asideContent__text"><strong><?php echo $voucherCode; ?></strong></span>
            <span class="asideContent__text uk-margin"><?php the_date( 'd-m-Y' ) ?></span>
            <a href="#" class="asideContent__link">
                <span uk-icon="question"></span>
                <?php _e( 'Como funciona?', 'textdomain'); ?>
            </a>
        </div>
    </div>

    <!-- Perguntas/Sliders -->
    <div id="contentBody" class="uk-padding-large uk-animation-slide-bottom uk-width-4-5">
        <div uk-slideshow="animation: scale; draggable: false; finite: true;">
            <div id="quizId" class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" data-quiz-id="<?php echo $quizId; ?>" data-voucher-id="<?php echo $voucherCode; ?>">
                <div class="uk-slideshow-items uk-box-shadow-medium">
                    <?php 
                    $weights = [1, 2, 4, 6];
                    $letters = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];
                    foreach( $questions as $id ){
                        $questionTitle  = get_the_title( $id );
                        $questionDesc   = get_the_content( $id );
                        $showQuestion   = get_post_meta( $id, '_ucqpt_show_question', true );
                        $answers        = [];
                        if ( $showQuestion != 'no' ){
                            # Answers
                            $args = array(
                                'post_type'		=> 'uc_answer',
                                'post_parent'	=> $id,
                                'posts_per_page'=> -1,
                            );
                            $answers = get_posts( $args );
                        }

                        include plugin_dir_path( __FILE__ ) . '/tpl-question.php';
                    }   
                    ?>
                </div> <!-- /end slideshow-items -->
                <ul class="uk-slideshow-nav uk-dotnav uk-flex-center uk-margin"></ul>

                <div class="wrapper-next uk-flex">
                    <div class="uk-width-3-4">
                        <div class="infoNext uk-flex uk-flex-middle">
                            <div class="infoNext__pill">
                                02
                            </div>
                            <div class="infoNext__aside">
                                <span class="infoNext__title"><?php _e( 'Próxima', 'textodmain'); ?></span>
                                <p class="infoNext__text">Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-1-4 uk-flex uk-flex-right">
                        <!-- Btn Next -->
                        <a href="#" uk-slideshow-item="next" class="btnNext">
                            <span class="btnNext__text"><?php _e( 'Próxima Pergunta', 'textdomain' ); ?></span>
                            <span class="btnNext__counter">05</span>
                        </a>

                        <!-- Btn finish -->
                        <button type="button" id="btnFinish" class="btnFinish"><?php _e( 'Finalizar Teste', 'textdomain' ); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery('.answerList__weightItem').click( function(){
    var el = jQuery(this);
    selectWeight(el);
});

jQuery('#btnFinish').click(function(){
    finishHim();
})

listenerNextEvent();
</script>