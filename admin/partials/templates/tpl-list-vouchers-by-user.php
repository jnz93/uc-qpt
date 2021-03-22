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
            $v_is_used          = get_post_meta( $post_id, 'ucqpt_is_used', true );
            $v_result_test_data = get_post_meta( $post_id, 'ucqpt_result_test_data', true );
            ?>
            <tr>
                <td><?php the_title(); ?></td>
                <td><?php echo $v_is_used == 'yes' ? 'Sim <span class="uk-margin-small-left" uk-icon="file-text" uk-tooltip="Abrir Resultado"></span>' : 'Não'; ?></td>
                <td><span class="uk-margin-small-right" uk-icon="pencil" uk-tooltip="Editar Voucher"></span> <span uk-icon="ban"uk-tooltip="Excluir voucher"></span></td>
            </tr>
            <?php
        endwhile;
        ?>
        </tbody>
    </table>
    <?php
endif;
?>