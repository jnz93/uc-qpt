<?php

/**
 * Provide a form with options to create a voucher
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       unitycode.tech
 * @since      1.0.0
 *
 * @package    Uc_Qpt
 * @subpackage Uc_Qpt/admin/partials/templates/
 */
?>
<div id="register-voucher" class="uk-modal-container" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header uk-flex uk-flex-between">
            <div id="modal-title" class="">
                <h2 class="uk-modal-title">Registrar Voucher</h2>
                <p class=""></p>
            </div>
        </div>
        <div class="uk-modal-body">
            <form id="form-voucher" class="uk-grid-small" uk-grid>
                <div class="uk-width-1-2">
                    <label class="uk-form-label" for="ucqpt_company_voucher">Cod. Voucher</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="ucqpt_company_voucher" type="text" value="<?php echo strtoupper(wp_generate_password( 8, false, false )); ?>" placeholder="Clique no botão para gerar o código">
                    </div>
                    <div class="">
                        <button class="uk-button uk-button-secondary" type="button" onclick="generateCodVoucher('<?php echo $ajax_url; ?>')">Gerar Código</button>
                    </div>
                </div>
                <div class="uk-width-1-2">
                    <label class="uk-form-label" for="ucqpt_company_selected">Selecione a empresa</label>
                    <div class="uk-form-controls">
                        <?php
                            $args       = array(
                                'role__in' => 'contributor'
                            );
                            $companies  = get_users( $args );
                        ?>
                        <select name="ucqpt_company_selected" id="ucqpt_company_selected">
                            <option value="default" selected>Selecione uma empresa</option>
                            <?php foreach( $companies as $cia ) : ?>
                                <option value="<?php echo $cia->ID ?>"><?php echo $cia->display_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="">
                    <button class="uk-button uk-button-primary" type="button" onclick="createNewVoucher('<?php echo $ajax_url; ?>')">Cadastrar Voucher</button>
                </div>
            </form>
        </div>
        <div class="uk-modal-footer uk-text-right">
        </div>
    </div>
</div>