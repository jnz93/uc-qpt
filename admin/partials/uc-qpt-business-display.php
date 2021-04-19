<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       unitycode.tech
 * @since      1.3.0
 *
 * @package    Uc_Qpt
 * @subpackage Uc_Qpt/admin/partials
 */
$user_id    = get_current_user_id();
$user       = get_userdata( $user_id );
// print_r($user);
?>
<div class="uk-container">

    <nav class="uk-navbar-container" uk-navbar style="display: none;">

        <div class="uk-navbar-left">
            <h1 class="uk-heading-bullet">Bem Vindo(a) <?php echo $user->user_login; ?>!</h1>
        </div>
    </nav>

    <!-- <ul class="uk-subnav uk-subnav-pill" uk-switcher>
        <li><a href="#">Vouchers Disponíveis</a></li>
    </ul>
    <ul class="uk-switcher uk-margin">
        <li><?php #require_once plugin_dir_path( __FILE__ ) . 'templates/tpl-list-vouchers-by-user.php'; ?></li>
    </ul> -->

    <?php require_once plugin_dir_path( __FILE__ ) . 'templates/tpl-list-vouchers-by-user.php'; ?>
    <?php    
    // require_once plugin_dir_path( __FILE__ ) . 'uc-qpt-new-quiz.php';
    // require_once plugin_dir_path( __FILE__ ) . 'templates/tpl-register-company.php';
    // require_once plugin_dir_path( __FILE__ ) . 'templates/tpl-create-voucher.php';
    ?>

</div>