<div class="uk-card uk-card-default uk-card-hover uk-card-body uk-text-center">
    <div class="form-authentication">
        <p class="uk-text-bolder"><?php _e( 'Complete suas informações antes de iniciar a avaliação.', 'textdomain' ); ?></p>
    
        <form class="uk-form">
            <div class="uk-margin">
                <label class="uk-form-label" for="user_full_name"><?php _e( 'Nome completo', 'textdomain' ); ?></label>
                <div class="uk-form-controls">
                    <input class="uk-input uk-text-center" id="user_full_name" type="text" placeholder="Nome completo">
                </div>
            </div>
    
            <div class="uk-margin">
                <label class="uk-form-label" for="user_email"><?php _e( 'E-mail', 'textdomain') ?></label>
                <div class="uk-form-controls">
                    <input class="uk-input uk-text-center" id="user_email" type="text" placeholder="E-mail">
                </div>
            </div>
            
            <div class="uk-margin">
                <label class="uk-form-label" for="user_phone"><?php _e( 'Telefone', 'textdomain' ); ?></label>
                <div class="uk-form-controls">
                    <input class="uk-input uk-text-center" id="user_phone" type="text" placeholder="(ddd) xxxxx-xxxx">
                </div>
            </div>
            
            <div class="uk-margin">
                <button type="button" class="uk-button uk-button-primary" onclick="submitUserData('<?php echo admin_url('admin-ajax.php') ?>')">Enviar dados</button>
            </div>
        </form>
    </div>
</div>
