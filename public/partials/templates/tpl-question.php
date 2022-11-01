<div class="questionItem uk-card uk-card-default uk-card-large uk-padding" data-question-id="<?php echo $id ?>">
    <h2 class="questionItem__title uk-card-title uk-text-bolder"><?php _e( $questionTitle, 'textdomain' ); ?></h2>

    <div class="containerAnswers">
        <ul class="answerList">
            <?php if( !empty($answers) ) :
                $pos = 0;
                foreach( $answers as $answer ): ?>
                    <li class="answerList__item uk-flex uk-flex-middle uk-padding-small uk-box-shadow-hover-small uk-margin-small-top uk-margin-small-bottom" data-answer-id="<?php echo $answer->ID; ?>">
                        <div class="uk-width-4-5 uk-flex uk-flex-middle uk-margin-right">
                            <span class="answerList__text answerList__text--dropcap uk-text-bolder"><?php echo $letters[$pos] . ')'; ?></span>
                            <span class="answerList__text"><?php _e( $answer->post_title, 'textdomain' ); ?></span>
                        </div>
                        <div class="uk-width-1-5 uk-flex-wrap">
                            <span class="answerList__text answerList__text--small"><?php _e( 'Selecione o peso:', 'textdomain' ); ?></span>
                            <div class="uk-flex">
                                <?php
                                    foreach( $weights as $value ){
                                        echo '<span class="answerList__weightItem" data-value="'. $value .'">'. $value .'</span>';
                                    }
                                ?>
                            </div>
                        </div>
                    </li>
                <?php 
                $pos++;
                endforeach; 
            endif;
            ?>
        </ul>
    </div>
</div>