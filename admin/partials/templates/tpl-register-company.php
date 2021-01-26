<?php

/**
 * Provide a template for register new company
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       unitycode.tech
 * @since      1.0.0
 *
 * @package    Uc_Qpt
 * @subpackage Uc_Qpt/admin/partials/templates
 */
?>
<div id="register-company" class="uk-modal-container" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header uk-flex uk-flex-between">
            <div id="modal-title" class="">
                <h2 class="uk-modal-title">Registrar Empresa</h2>
                <p class=""></p>
            </div>
        </div>
        <div class="uk-modal-body">
            <form id="form-company" class="uk-grid-small" uk-grid>
                <div class="uk-width-1-2">
                    <label class="uk-form-label" for="ucqpt_company_name">Nome da Empresa</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="ucqpt_company_name" type="text" placeholder="Adicione o nome da empresa">
                    </div>
                </div>
                <div class="uk-width-1-2">
                    <label class="uk-form-label" for="ucqpt_company_email">E-mail</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="ucqpt_company_email" type="email" placeholder="Ex: email@empresa.com.br">
                    </div>
                </div>
                <div class="uk-width-1-3">
                    <label class="uk-form-label" for="ucqpt_company_phone">Telefone/Whatsapp</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="ucqpt_company_phone" type="tel" placeholder="Ex: (ddd) 00000-0000">
                    </div>
                </div>
                <div class="uk-width-1-3">
                    <label class="uk-form-label" for="ucqpt_company_cnpj">CNPJ</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="ucqpt_company_cnpj" type="text" placeholder="Ex: 00.000.000/0000-00">
                    </div>
                </div>
                <div class="uk-width-1-3">
                    <label class="uk-form-label" for="ucqpt_company_vouchers">Vouchers</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="ucqpt_company_vouchers" type="number" placeholder="Ex: 100">
                    </div>
                </div>
                <div class="">
                    <button class="uk-button uk-button-primary" type="button" onclick="submitCompanyData('<?php echo $ajax_url; ?>')">Registrar Empresa</button>
                </div>
            </form>
        </div>
        <div class="uk-modal-footer uk-text-right">
        </div>
    </div>
</div>