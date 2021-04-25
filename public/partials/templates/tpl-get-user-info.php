<?php

/**
 * Provide a template user submit info data
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       unitycode.tech
 * @since      1.0.0
 *
 * @package    Uc_Qpt
 * @subpackage Uc_Qpt/public/partials/templates
 */
?>
<script>
    var voucherId = '<?php echo $data_voucher['id']; ?>';
</script>
<form class="uk-form-stacked">

    <div class="uk-margin">
        <label class="uk-form-label" for="user_full_name">Nome completo</label>
        <div class="uk-form-controls">
            <input class="uk-input" id="user_full_name" type="text" placeholder="Nome completo">
        </div>
    </div>

    <div class="uk-margin">
        <label class="uk-form-label" for="user_email">E-mail</label>
        <div class="uk-form-controls">
            <input class="uk-input" id="user_email" type="text" placeholder="E-mail">
        </div>
    </div>
    
    <div class="uk-margin">
        <label class="uk-form-label" for="user_phone">Telefone</label>
        <div class="uk-form-controls">
            <input class="uk-input" id="user_phone" type="text" placeholder="(ddd) xxxxx-xxxx">
        </div>
    </div>
    
    <div class="uk-margin">
        <button type="button" class="uk-button uk-button-primary" onclick="submitUserData('<?php echo admin_url('admin-ajax.php') ?>')">Enviar dados</button>
    </div>
</form>

<script>
jQuery(document).ready(function (){
    jQuery('#user_phone').mask('(00) 0 0000-0000');
});