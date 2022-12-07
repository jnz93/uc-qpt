<div class="uk-flex uk-flex-middle uk-width-1-1">
    <!-- Aside -->
    <div class="uk-flex uk-flex-wrap uk-flex-wrap-top uk-width-1-5 uk-box-shadow-small uk-text-center uk-padding-small uk-padding-remove-top uk-padding-remove-bottom" style="height: 100vh">
        <div class="asideContent uk-width-1-1">
            <div class="asideContent__logoWrapper">
                <img src="https://metodoprav.com.br/wp-content/uploads/2021/04/logotipo-PRAV-e1617632526222.jpg" alt="" class="asideContent__logo">
            </div>
            <h6 class="asideContent__title"><?php _e( 'Bem vindo(a) ' . $userName . '!', 'textdomain' ); ?></h6>
            <p class="asideContent__text"><?php _e( '"Se você conhece o inimigo e conhece a si mesmo, não precisa temer o resultado de cem batalhas. Se… conheces a ti mesmo, mas não conhece o inimigo, para cada vitória ganha sofrerá também uma derrota. Caso não conheça nem o inimigo nem a si mesmo, perderá todas as batalhas" - Sun Tzu, A arte da Guerra', 'textdomain' ); ?></p>
        </div>
        <div class="asideContent uk-flex uk-flex-column uk-width-1-1">
            <span class="asideContent__text asideContent__text--caption"><?php _e( 'Você está utilizando o voucher:', 'textdomain' ); ?></span>
            <span class="asideContent__text"><strong><?php echo $voucherCode; ?></strong></span>
            <span class="asideContent__text uk-margin"><?php the_date( 'd-m-Y' ) ?></span>
            <button class="asideContent__button uk-link" onclick="openHowtoWork()">
                <span uk-icon="question"></span>
                <?php _e( 'Como funciona?', 'textdomain'); ?>
            </button>
        </div>

        <!-- Como funciona -->
        <div id="how-to-work" class="howtowork">
            <div class="howtowork__body">
                <h2 class="uk-text-bolder uk-text-emphasis"><?php _e('Inventário de Personalidade', 'textdomain'); ?></h2>
                <p><?php _e('Analise cuidadosamente cada questão e suas alternativas e atribua:', 'textdomain'); ?></p>
                <ul class="uk-list uk-list-striped uk-margin-medium-top uk-margin-medium-bottom">
                    <li><?php _e('<b>Nota 6</b> para a alternativa que MAIS tem a ver com você.', 'textdomain'); ?></li>
                    <li><?php _e('<b>Nota 4</b> para a alternativa que se aproxima UM POUCO MAIS de você.', 'textdomain'); ?></li>
                    <li><?php _e('<b>Nota 2</b> para a alternativa que se aproxima UM POUCO MENOS de você.', 'textdomain'); ?></li>
                    <li><?php _e('<b>Nota 1</b> para alternativa que MENOS tem a ver com você.', 'textdomain'); ?></li>
                </ul>
                <p class="uk-text-meta"><?php _e('OBS: Use a pontuação 1, 2, 4 e 6 em todas as questões, na ordem que escolher, sem repetir valores.', 'textdomain'); ?></p>

                <button class="uk-button uk-button-primary uk-width-1-1 uk-margin-medium-top" onclick="closeHowtoWork()"><?php _e( 'Fechar', 'textdomain' ); ?></button>
            </div>
        </div>
    </div>

    <!-- Perguntas/Sliders -->
    <div id="contentBody" class="uk-padding-large uk-animation-slide-bottom uk-width-4-5">
        
        <!-- Contador -->
        <div class="uk-position-absolute questionCount">
            <div class="questionCount__container">
                <div class="uk-width-1-2">
                    <span class="questionCount__text countValue"></span>
                </div>
                <div class="uk-width-1-2">
                    <span class="questionCount__text questionCount__text--white countTotal"></span>
                </div>
            </div>
        </div>

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
                <ul class="uk-slideshow-nav uk-dotnav uk-flex-center"></ul>

                <div class="wrapper-next uk-flex">
                    <div class="uk-width-1-1 uk-position-absolute">
                        <div class="actionBar uk-flex-center">
                            <!-- Btn Voltar -->
                            <button type="button" uk-slideshow-item="previous" class="btnPrevious uk-text-center uk-width-2-5 uk-margin-small-right">
                                <span uk-icon="arrow-left"></span>
                                <span class="uk-margin-small-left"><?php _e( 'Voltar', 'textdomain' ); ?></span>
                            </button>
    
                            <!-- Btn reset -->
                            <button type="button" class="btnReset uk-text-center uk-width-1-5 uk-margin-small-right">
                                <span class="btnNext__text uk-margin-small-right"><?php _e( 'Resetar pesos', 'textdomain' ); ?></span>
                                <span uk-icon="refresh"></span>
                            </button>
    
                            <!-- Btn Next -->
                            <button type="button" uk-slideshow-item="next" class="btnNext uk-text-center uk-width-2-5">
                                <span class="btnNext__text uk-margin-small-right"><?php _e( 'Próxima Pergunta', 'textdomain' ); ?></span>
                                <span uk-icon="arrow-right"></span>
                            </button>
                        </div>

                        <!-- Btn finish -->
                        <button type="button" id="btnFinish" class="btnFinish uk-text-center uk-width-1-1">
                            <?php _e( 'Finalizar Teste', 'textdomain' ); ?>
                            <span uk-icon="check uk-margin-small-left"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
listenerAfterSlideEvent();

jQuery('.answerList__weightItem').click( function(){
    var el = jQuery(this);
    selectWeight(el);
});

jQuery('#btnFinish').click(function(){
    finishHim();
})

jQuery('.btnReset').click( function(){
    clearAnswers();
    jQuery('.actionBar').removeClass('actionBar--enabled');
})

jQuery('.btnNext').click(function(){
    jQuery('.actionBar').removeClass('actionBar--enabled');
})
</script>