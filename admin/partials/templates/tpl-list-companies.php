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
    <table class="uk-table uk-table-striped">
        <thead>
            <tr>
                <th>Empresa</th>
                <th>Vouchers</th>
                <th>Vouchers Utilizados</th>
                <th>Vouchers Restantes</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ( $companies as $company ) :

            $company_id             = $company->ID;
            $company_name           = $company->display_name;
            $company_vouchers       = get_user_meta( $company_id, 'ucqpt_company_vouchers', true );
            $company_used_vouchers  = get_user_meta( $company_id, 'ucqpt_company_registered_vouchers', true );
            $company_used_vouchers  = explode( ',', $company_used_vouchers );
            $company_used_vouchers  = count( $company_used_vouchers );
            $remaining_vouchers     = intval($company_vouchers) - $company_used_vouchers;
            ?>
            <tr>
                <td><?php echo $company_name; ?></td>
                <td><?php echo $company_vouchers; ?></td>
                <td><?php echo $company_used_vouchers; ?></td>
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