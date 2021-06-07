<?php

/**
 * Tabela de vouchers disponíveis para empresa
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       unitycode.tech
 * @since      1.3.0
 *
 * @package    Uc_Qpt
 * @subpackage Uc_Qpt/admin/partials/templates/company
 */

$ajax_url 	= admin_url( 'admin-ajax.php' );
$args = array(
    'post_type'         => 'uc_voucher',
    'author'            => $company_id,
    'order_by'          => 'post_date',
    'order'             => 'ASC',
    'posts_per_page'    => -1
);

$vouchers       = new WP_Query($args);
$vouchers_count = $vouchers->post_count;
?>
<?php 
if ( $vouchers->have_posts() ) :
    ?>
    <div class="uk-flex uk-flex-between uk-flex-middle">
        <span id="<?php echo 'total-' . $user_id; ?>" class="uk-label uk-label-success" data-total="<?php echo $vouchers_count; ?>"><?php echo 'Total: ' . $vouchers_count; ?></span>
        
        <?php if( is_admin() || current_user_can( 'administrator' ) ) : ?>
        <div class="uk-margin">
            <label class="uk-form-label uk-margin-bottom" for="<?php echo 'edit-' . $user_id ?>">Adicionar vouchers</label>
            <div class="uk-form-controls">
                <input name="" id="<?php echo 'edit-' . $user_id ?>" class="uk-input uk-form-width-xsmall uk-form-small" type="text" value="0" data-type="vouchers" data-id="<?php echo $user_id ?>" />
                <button class="uk-button uk-button-default uk-button-small" onclick="updateCompanyData(jQuery(this))">Adicionar</button>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div id="<?php echo 'wrapper-vouchers-' . $user_id; ?>" class="uk-overflow-auto">
        <table class="uk-table uk-table-divider uk-table-hover">
            <thead>
                <tr>
                    <th>Código</th>
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
                    <td>
                        <span class="uk-margin-small-right" uk-icon="pencil" uk-tooltip="Editar Voucher" uk-toggle="target: #edit-voucher" onclick="setDataVoucherOnModal('<?php echo $post_id; ?>', '<?php echo $v_code; ?>')"></span> 
                        <span style="display: none !important;" uk-icon="ban" uk-tooltip="Excluir voucher"></span>
                    </td>
                </tr>
                <?php
            endwhile;
            ?>
            </tbody>
        </table>
    </div>
    <?php
endif;
?>

<!-- <span class="uk-margin-small-left" uk-icon="file-text" uk-tooltip="Abrir Resultado" uk-toggle="target: #result-voucher" onclick="setVoucherIdOnResultModal($post_id, $v_code)"></span> -->