<?php

/**
 * Mostra os dados da empresa no padrão switcher(uikit)
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       unitycode.tech
 * @since      1.3.0
 *
 * @package    Uc_Qpt
 * @subpackage Uc_Qpt/admin/partials/templates/company
 */
$company                = get_user_to_edit( $company_id );
$company_name           = $company->display_name;
$company_email          = $company->user_email;
$company_tel            = get_user_meta( $company_id, 'ucqpt_company_tel', true );
$company_doc            = get_user_meta( $company_id, 'ucqpt_company_doc', true );
$company_vouchers       = get_user_meta( $company_id, 'ucqpt_company_vouchers', true );
?>
<button class="uk-offcanvas-close" type="button" uk-close></button>
<h2 class="uk-modal-title uk-heading-bullet"><?php echo $company_name ?></h2>
<ul class="uk-subnav uk-subnav-pill" uk-switcher="animation: uk-animation-fade">
    <li><a href="#">Dados da empresa</a></li>
    <li><a href="#">Vouchers <span class="uk-badge"><?php echo $company_vouchers; ?></span></a></li>
</ul>
<ul class="uk-switcher uk-margin">
    <li>
		<?php include( plugin_dir_path( __FILE__ ) . 'table-data.php' ); ?>
    </li>
    <li>
		<?php include( plugin_dir_path( __FILE__ ) . 'list-vouchers.php' ); ?>
    </li>
</ul>
