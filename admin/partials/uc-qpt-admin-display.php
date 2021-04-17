<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       unitycode.tech
 * @since      1.0.0
 *
 * @package    Uc_Qpt
 * @subpackage Uc_Qpt/admin/partials
 */
?>
<div class="uk-container">

    <nav class="uk-navbar-container" uk-navbar>

        <div class="uk-navbar-left">
            <h1 class="uk-heading-bullet">Bem Vindo(a) - </h1>
            <ul class="uk-navbar-nav uk-flex uk-flex-middle" style="display: none;">
                <li class="">
                    <a href="#"><button class="uk-button uk-button-primary uk-button-large" uk-toggle="target: #new-quiz">Adicionar Inventário</button></a>
                </li>
                <li class="">
                    <a href="#"><button class="uk-button uk-button-primary uk-button-large" uk-toggle="target: #register-company">Cadastrar Empresa</button></a>
                </li>
                <li class="">
                    <a href="#"><button class="uk-button uk-button-primary uk-button-large" uk-toggle="target: #register-voucher">Cadastrar Voucher</button></a>
                </li>
            </ul>
    
        </div>
    </nav>

    <ul class="uk-subnav uk-subnav-pill uk-flex-center uk-flex-middle" uk-switcher="animation: uk-animation-slide-left-medium, uk-animation-slide-right-medium">
        <li><a href="#">Inventário(s)</a></li>
        <li><a href="#">Empresa(s)</a></li>
    </ul>
    <ul class="uk-switcher uk-margin">
        <li><?php require_once plugin_dir_path( __FILE__ ) . 'templates/tpl-list-all-quizes.php'; ?></li>
        <li><?php require_once plugin_dir_path( __FILE__ ) . 'templates/tpl-list-companies.php'; ?></li>
    </ul>

    <?php    
    require_once plugin_dir_path( __FILE__ ) . 'uc-qpt-new-quiz.php';
    require_once plugin_dir_path( __FILE__ ) . 'templates/tpl-edit-inventory.php';
    require_once plugin_dir_path( __FILE__ ) . 'templates/tpl-register-company.php';
    require_once plugin_dir_path( __FILE__ ) . 'templates/tpl-create-voucher.php';
    ?>

</div>