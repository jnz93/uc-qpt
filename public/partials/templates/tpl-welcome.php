<div id="modal-full" class="uk-modal-full" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-full uk-close-large" type="button" uk-close></button>
        <div id="modal-content" class="uk-grid-collapse uk-child-width-1-2@s uk-flex-middle" uk-grid>
            <div class="uk-flex uk-flex-center uk-flex-middle uk-background-cover uk-light uk-animation-slide-top" data-src="https://metodoprav.com.br/wp-content/uploads/2022/09/pexels-andrew-neel-3178818.jpg" uk-img uk-height-viewport></div>
            <div class="uk-padding-large uk-animation-slide-bottom">
                <div class="modal-header uk-text-center">
                    <img class="logotipo" src="https://metodoprav.com.br/wp-content/uploads/2021/04/logotipo-PRAV-e1617632526222.jpg" alt="">
                    <h3 class="uk-text-bold"><?php _e( 'Inventário de Personalidade', 'textdomain' ); ?></h3>
                </div>
                <div id="form-wrapper" class="">
                    <div class="uk-card uk-card-default uk-card-hover uk-card-body uk-text-center">
                        <div class="form-validate">
                            <p class="uk-text-bolder"><?php _e( 'Seja bem vindo(a)! Faça a validação do voucher para continuar.', 'textdomain' ); ?></p>
                            <div class="uk-margin">
                                <label class="uk-form-label" for="ucqpt_voucher_code"><?php _e( 'Voucher', 'textdomain' ); ?></label>
                                <div class="uk-form-controls">
                                    <input class="uk-input uk-input uk-form-width-medium uk-form-large uk-text-center" id="ucqpt_voucher_code" type="text" placeholder="Ex: XXXXXXXX-XXX">
                                </div>
                            </div>
        
                            <div class="uk-margin">
                                <button type="button" id="validateVoucher" class="uk-button uk-button-primary"><?php _e( 'Validar voucher', 'textdomain' ); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="quiz_id" id="quiz_id" value="<?php echo $quizId; ?>">
</div>