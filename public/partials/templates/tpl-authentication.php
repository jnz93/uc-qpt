
<div class="uk-width-1-1 uk-padding uk-text-center">
    <h3 class="uk-text-bold"><?php _e( 'Autenticação', 'textdomain') ?></h3>
    <p class="uk-text-bolder"><?php _e( 'Complete as informações antes de iniciar a avaliação.', 'textdomain' ); ?></p>

    <form class="uk-form uk-margin-medium-top">
        <div class="uk-margin">
            <label class="uk-form-label" for="user_full_name"><?php _e( 'Nome completo', 'textdomain' ); ?></label>
            <div class="uk-form-controls">
                <input class="uk-input uk-text-center uk-text-normal uk-text-emphasis formValidation__input" id="user_full_name" type="text" placeholder="Nome completo">
            </div>
        </div>

        <div class="uk-margin">
            <label class="uk-form-label" for="user_email"><?php _e( 'E-mail', 'textdomain') ?></label>
            <div class="uk-form-controls">
                <input class="uk-input uk-text-center uk-text-normal uk-text-emphasis formValidation__input" id="user_email" type="text" placeholder="E-mail">
            </div>
        </div>
        
        <div class="uk-margin">
            <label class="uk-form-label" for="user_phone"><?php _e( 'Telefone', 'textdomain' ); ?></label>
            <div class="uk-form-controls">
                <input class="uk-input uk-text-center uk-text-normal uk-text-emphasis formValidation__input" id="user_phone" type="text" placeholder="(ddd) xxxxx-xxxx">
            </div>
        </div>
        
        <div class="uk-margin">
            <button type="button" class="uk-button uk-box-shadow-small uk-box-shadow-hover-medium formValidation__submit" onclick="authenticateVoucher(jQuery(this))"><?php _e( 'Autenticar Voucher', 'textdomain'); ?></button>
        </div>
        <input type="hidden" name="voucher_id" id="voucher_id" value="<?php echo $vId; ?>">
    </form>
</div>
<script>
    jQuery(document).ready(function(){
        jQuery('#user_phone').mask('(00) 0 0000-0000');
    });
</script>