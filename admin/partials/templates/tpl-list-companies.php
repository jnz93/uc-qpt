<?php

/**
 * Provide a table with all vouchers
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       unitycode.tech
 * @since      1.0.0
 *
 * @package    Uc_Qpt
 * @subpackage Uc_Qpt/admin/partials/templates/
 */

$ajax_url = admin_url('admin-ajax.php');
$args = array(
    'role__in'          => array( 'contributor' ),
    'post_status'       => 'publish',
    'posts_per_page'    => -1,
);
$companies = get_users( $args );

?>

<?php 
if ( ! empty( $companies ) ) :
    ?>
    <table id="table-companies" class="uk-table uk-table-striped">
        <thead>
            <tr>
                <th>Empresa(s)</th>
                <th>Vouchers Total</th>
                <th>Vouchers Utilizados</th>
                <th>Vouchers Restantes</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ( $companies as $company ) :

            $company_id                 = $company->ID;
            $company_name               = $company->display_name;
            $company_vouchers           = get_user_meta( $company_id, 'ucqpt_company_vouchers', true );

            // Verificar se o voucher foi utilizado
            $vouchers_ids               = get_user_meta($company_id, 'ucqpt_company_vouchers_id', true);
            $vouchers_ids               = explode(',', $vouchers_ids);
            $vouchers_ids               = array_filter($vouchers_ids);
            $total_used                 = 0;
            
            // Contagem dos vouchers utilizados
            foreach ($vouchers_ids as $id) {
                $was_used = get_post_meta($id, 'ucqpt_is_used', true);

                if ( $was_used == 'yes' ) :
                    $total_used++;
                endif;
            }

            $remaining_vouchers         = intval($company_vouchers) - intval($total_used);
            ?>
            <tr>
                <td><?php echo $company_name; ?></td>
                <td><?php echo $company_vouchers; ?></td>
                <td><?php echo $total_used; ?></td>
                <td><?php echo $remaining_vouchers; ?></td>
            </tr>
            <?php
        endforeach;
        ?>
        </tbody>
    </table>
    <?php
endif;
?>