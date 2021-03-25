<?php

/**
 * Provide a table with all quizes created
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       unitycode.tech
 * @since      1.3.0
 *
 * @package    Uc_Qpt
 * @subpackage Uc_Qpt/admin/partials/templates/
 */
$ajax_url 	= admin_url( 'admin-ajax.php' );
$args = array(
    'post_type'         => 'uc_voucher',
    'author'            => $user_id,
    'order_by'          => 'post_date',
    'order'             => 'ASC',
    'posts_per_page'    => -1
);

$vouchers = new WP_Query($args);

?>
<?php 
if ( $vouchers->have_posts() ) :
    ?>
    <table class="uk-table uk-table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Utilizado</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while ( $vouchers->have_posts() ) :
            $vouchers->the_post();
            $post_id            = get_the_ID();
            $v_code             = get_the_title( );
            $v_is_used          = get_post_meta( $post_id, 'ucqpt_is_used', true );
            $v_result_test_data = get_post_meta( $post_id, 'ucqpt_result_test_data', true );
            ?>
            <tr data-id="<?php echo $post_id; ?>">
                <td><?php echo $v_code; ?></td>
                <td>
                    <?php if ( $v_is_used == 'yes') : ?>
                        Sim <span class="uk-margin-small-left" uk-icon="file-text" uk-tooltip="Abrir Resultado" uk-toggle="target: #result-voucher" onclick="setVoucherIdOnResultModal('<?php echo $post_id ?>', '<?php echo $v_code; ?>', '<?php echo $ajax_url; ?>')"></span>
                    <?php else : ?>
                        Não
                    <?php endif; ?>
                </td>
                <td><span class="uk-margin-small-right" uk-icon="pencil" uk-tooltip="Editar Voucher" uk-toggle="target: #edit-voucher" onclick="setVoucherIdOnModal('<?php echo $post_id; ?>', '<?php echo $v_code; ?>')"></span> <span style="display: none !important;" uk-icon="ban" uk-tooltip="Excluir voucher"></span></td>
            </tr>
            <?php
        endwhile;
        ?>
        </tbody>
    </table>
    <?php
endif;
?>

<!-- Modal para edição -->
<div id="edit-voucher" class="uk-modal-container" uk-modal data-vocher="">
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header uk-flex uk-flex-between">
            <div id="modal-title" class="">
                <h2 class="uk-modal-title"></h2>
                <p class=""></p>
            </div>
        </div>
        <div class="uk-modal-body">
            <form id="form-voucher" class="uk-grid-small" uk-grid>
                
                <p class="uk-width-1-1 uk-text-large">Dados do usuário</p>

                <div class="uk-width-1-2">
                    <label class="uk-form-label" for="ucqpt_customer_name">Nome Completo</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="ucqpt_customer_name" type="text" placeholder="Nome completo">
                    </div>
                </div> <!-- /end customer_name -->
                
                <div class="uk-width-1-2">
                    <label class="uk-form-label" for="ucqpt_customer_email">E-mail</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="ucqpt_customer_email" type="email" placeholder="email@domain.com">
                    </div>
                </div> <!-- /end customer_email -->

                <div class="uk-width-1-2">
                    <label class="uk-form-label" for="ucqpt_customer_cpf">CPF</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="ucqpt_customer_cpf" type="text" placeholder="000.000.000-00">
                    </div>
                </div> <!-- /end customer_cpf -->

                <div class="uk-width-1-2">
                    <label class="uk-form-label" for="ucqpt_customer_tel">Telefone</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="ucqpt_customer_tel" type="text" placeholder="(DDD) 0 0000-0000">
                    </div>
                </div> <!-- /end customer_tel -->

                <div class="">
                    <button class="uk-button uk-button-primary" type="button" onclick="updateVoucherUserData('<?php echo $ajax_url; ?>')">Atualizar dados</button>
                </div>
            </form>
        </div>
        <div class="uk-modal-footer uk-text-right">
        </div>
    </div>
</div>


<!-- This is the modal -->
<div id="result-voucher" uk-modal data-voucher="">
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title"></h2>
        <p class="result"></p>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Fechar</button>
        </p>
    </div>
</div>

<!-- <span class="uk-margin-small-left" uk-icon="file-text" uk-tooltip="Abrir Resultado" uk-toggle="target: #result-voucher" onclick="setVoucherIdOnResultModal($post_id, $v_code)"></span> -->