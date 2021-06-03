<?php

/**
 * Tabela com dados de cadastro da empresa
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       unitycode.tech
 * @since      1.3.0
 *
 * @package    Uc_Qpt
 * @subpackage Uc_Qpt/admin/partials/templates/company
 */
?>
<table class="uk-table uk-table-striped">
    <tbody>
        <tr>
            <td>Nome/Empresa</td>
            <td class="data" ondblclick="editCompanyData(jQuery(this))" data-id="<?php echo $company_id; ?>" data-type="name"><?php echo $company_name; ?></td>
        </tr>
        <tr>
            <td>E-mail</td>
            <td class="data" ondblclick="editCompanyData(jQuery(this))" data-id="<?php echo $company_id; ?>" data-type="email"><?php echo $company_email; ?></td>
        </tr>
        <tr>
            <td>Telefone</td>
            <td class="data" ondblclick="editCompanyData(jQuery(this))" data-id="<?php echo $company_id; ?>" data-type="phone"><?php echo $company_tel; ?></td>
        </tr>
        <tr>
            <td>Documento/Cnpj</td>
            <td class="data" ondblclick="editCompanyData(jQuery(this))" data-id="<?php echo $company_id; ?>" data-type="doc"><?php echo $company_doc; ?></td>
        </tr>
        <tr>
            <td>Senha de acesso</td>
            <td class="data" ondblclick="editCompanyData(jQuery(this))" data-id="<?php echo $company_id; ?>" data-type="pass">Clique para redefinir a senha</td>
        </tr>
    </tbody>
</table>