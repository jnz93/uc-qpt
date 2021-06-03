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
<script>
var ajaxUrl = '<?php echo $ajax_url; ?>';
</script>
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
            <tr uk-toggle="target: #company-data" data-id="<?php echo $company_id; ?>" onclick="getCompanyData('<?php echo $company_id; ?>')">
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
    <button class="uk-button uk-button-primary uk-button-large" uk-toggle="target: #register-company" style="display: block; margin: auto;">Cadastrar Empresa</button>

    <!-- Off Canvas for company data -->
    <div id="company-data" uk-offcanvas="overlay: true; flip: true">
        <div class="uk-offcanvas-bar" style="min-width: 460px;">
           <!-- Data with ajax -->
        </div>
    </div>
    <?php
else :
    ?>
    <div class="uk-flex uk-flex-column uk-flex-center uk-flex-middle">
        <div class="uk-width-2-3">
            <h3 class="uk-heading-line uk-text-center"><span>Desculpe. Ainda não temos empresas cadastradas.</span></h3>
            <button class="uk-button uk-button-primary uk-button-large" uk-toggle="target: #register-company" style="display: block; margin: auto;">Cadastrar Empresa</button>
        </div>
    </div>
    <?php
endif;
?>