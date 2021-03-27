<?php

/**
 * Provide a form for register new quiz
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       unitycode.tech
 * @since      1.0.0
 *
 * @package    Uc_Qpt
 * @subpackage Uc_Qpt/admin/partials/
 */

$ajax_url = admin_url('admin-ajax.php');
?>
<div id="new-quiz" class="uk-modal-container" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header uk-flex uk-flex-between">
            <div id="modal-title" class="">
                <h2 class="uk-modal-title">Novo teste</h2>
                <p class=""></p>
            </div>
        </div>
        <div class="uk-modal-body">
            <form class="uk-grid-small" uk-grid>
                <div class="uk-width-1-2">
                    <label class="uk-form-label" for="ucqpt_test_name">Nome do Teste</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="ucqpt_test_name" type="text" placeholder="Adicione o nome do teste">
                    </div>
                </div>
                <div class="uk-width-1-2">
                    <label class="uk-form-label" for="ucqpt_test_description">Descrição do teste</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="ucqpt_test_description" type="text" placeholder="Adicione uma descrição">
                    </div>
                </div>
                <div class="">
                    <button class="uk-button uk-button-primary" type="button" onclick="createNewQuiz(jQuery(this), '<?php echo $ajax_url; ?>')">Publicar teste</button>
                </div>
            </form>

            <div class="wrapper-new-question" style="display: none">
                <!-- <div class="uk-width-1-1">
                    <hr class="uk-divider-icon">
                </div> -->
                
                <div id="container-questions" class="uk-width-1-1">
                    <div class="uk-width-1-1 uk-flex uk-flex-center">
                        <button class="uk-button uk-button-default uk-button-medium" type="button" onclick="addTplQuestion('<?php echo $ajax_url; ?>')"><span uk-icon="icon:  plus"></span> Adiconar nova pergunta</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="uk-modal-footer uk-text-right">
        </div>
    </div>
</div>
