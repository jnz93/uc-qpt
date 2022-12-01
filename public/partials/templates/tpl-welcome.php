<div id="modal-full" class="uk-modal-full" uk-modal>
    <div class="uk-modal-dialog" style="background-color: #fdfcfc !important;">
        <button class="uk-modal-close-full uk-close-large" type="button" uk-close></button>
        <div id="modal-content" class="uk-grid-collapse uk-child-width-1-2@s uk-flex-middle" uk-grid>
            <!-- COVER -->
            <div class="uk-flex uk-flex-center uk-flex-middle uk-background-cover uk-light uk-animation-slide-top" data-src="https://metodoprav.com.br/wp-content/uploads/2022/09/pexels-andrew-neel-3178818.jpg" uk-img uk-height-viewport>
                <div class="modal-header uk-text-center">
                    <img class="welcome__logotipo" src="https://metodoprav.com.br/wp-content/uploads/2022/11/logotipo-PRAV-e1617632526222-removebg-preview.png" alt="">
                    <h3 class="welcome__title uk-text-bold"><?php _e( 'Inventário de Personalidade', 'textdomain' ); ?></h3>
                </div>
                <div class="welcome__coverBackground"></div>
            </div>

            <!-- FORM -->
            <div class="uk-padding-large uk-animation-slide-bottom">

                <div id="form-wrapper" class="uk-flex uk-flex-center uk-flex-middle uk-box-shadow-medium formValidation">
                    <div class="uk-width-1-1 uk-padding uk-text-center">
                        <h3 class="uk-text-bold"><?php _e( 'Bem vindo(a)!', 'textodmain'); ?></h3>
                        <p class=""><?php _e( 'Faça a validação do voucher para prosseguir.', 'textdomain' ); ?></p>
                        <form class="form-validate uk-margin-medium-top">
                            <label class="uk-form-label formValidation__label" for="ucqpt_voucher_code"><?php _e( 'Insira o voucher', 'textdomain' ); ?></label>
                            <div class="uk-form-controls">
                                <input class="uk-input uk-text-center uk-text-uppercase uk-text-bold uk-text-emphasis formValidation__input" id="ucqpt_voucher_code" type="text" placeholder="Ex: XXXXXXXX-XXX">
                            </div>
                            <button type="button" id="validateVoucher" class="uk-button uk-box-shadow-small uk-box-shadow-hover-medium formValidation__submit"><?php _e( 'Solicitar Validação', 'textdomain' ); ?></button>
                        </form>
                    </div>
                </div>
                <input type="hidden" name="quiz_id" id="quiz_id" value="<?php echo $quizId; ?>">
            </div>
        </div>
    </div>
</div>