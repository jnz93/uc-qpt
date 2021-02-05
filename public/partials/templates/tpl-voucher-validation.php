<?php

/**
 * Provide a template for check voucher
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       unitycode.tech
 * @since      1.0.0
 *
 * @package    Uc_Qpt
 * @subpackage Uc_Qpt/public/partials/templates
 */
// echo $quiz_id;
?>
<script>
    var quizId = '<?php echo $quiz_id ?>';
</script>
<form class="uk-form-stacked">

    <div class="uk-margin">
        <label class="uk-form-label" for="ucqpt_voucher_code">Voucher</label>
        <div class="uk-form-controls">
            <input class="uk-input" id="ucqpt_voucher_code" type="text" placeholder="Insira o voucher aqui">
        </div>
    </div>

    <div class="uk-margin">
        <button type="button" class="uk-button uk-button-primary" onclick="autenticateVoucher('<?php echo admin_url('admin-ajax.php') ?>')">Validar voucher</button>
    </div>

</form>