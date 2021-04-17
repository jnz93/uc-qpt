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

            $user_id                 = $company->ID;
            $company_name               = $company->display_name;
            $company_vouchers           = get_user_meta( $user_id, 'ucqpt_company_vouchers', true );

            // Verificar se o voucher foi utilizado
            $vouchers_ids               = get_user_meta($user_id, 'ucqpt_company_vouchers_id', true);
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
            <tr uk-toggle="target: #company-data-<?php echo $user_id ?>" data-id="<?php echo $user_id; ?>">
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

    <!-- Modais -->
    <?php
    foreach ( $companies as $company ) :
        $user_id                    = $company->ID;
        $company_name               = $company->display_name;
        $company_email              = $company->user_email;
        $company_vouchers           = get_user_meta( $user_id, 'ucqpt_company_vouchers', true );
        $company_tel                = get_user_meta( $user_id, 'ucqpt_company_tel', true );
        $company_doc                = get_user_meta( $user_id, 'ucqpt_company_doc', true );

        ?>
        <div id="company-data-<?php echo $user_id; ?>" uk-modal>
            <div class="uk-modal-dialog uk-modal-body">
                <button class="uk-modal-close-default" type="button" uk-close></button>
                <h2 class="uk-modal-title"><?php echo $company_name ?></h2>

                <h4 class="">Dados da empresa</h4>
                <table class="uk-table uk-table-striped">
                    <tbody>
                        <tr>
                            <td>Nome/Empresa</td>
                            <td class="data" ondblclick="editCompanyData(jQuery(this))" data-id="<?php echo $user_id; ?>" data-type="name"><?php echo $company_name; ?></td>
                        </tr>
                        <tr>
                            <td>E-mail</td>
                            <td class="data" ondblclick="editCompanyData(jQuery(this))" data-id="<?php echo $user_id; ?>" data-type="email"><?php echo $company_email; ?></td>
                        </tr>
                        <tr>
                            <td>Telefone</td>
                            <td class="data" ondblclick="editCompanyData(jQuery(this))" data-id="<?php echo $user_id; ?>" data-type="phone"><?php echo $company_tel; ?></td>
                        </tr>
                        <tr>
                            <td>Documento/Cnpj</td>
                            <td class="data" ondblclick="editCompanyData(jQuery(this))" data-id="<?php echo $user_id; ?>" data-type="doc"><?php echo $company_doc; ?></td>
                        </tr>
                        <tr>
                            <td>Senha de acesso</td>
                            <td class="data" ondblclick="editCompanyData(jQuery(this))" data-id="<?php echo $user_id; ?>" data-type="pass">Clique para redefinir a senha</td>
                        </tr>
                    </tbody>
                </table>

                <h4 class="">Vouchers</h4>
                <?php require_once plugin_dir_path( __FILE__ ) . '/tpl-list-vouchers-by-user.php';  ?>
            </div>
        </div>
        <?php
    endforeach;
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