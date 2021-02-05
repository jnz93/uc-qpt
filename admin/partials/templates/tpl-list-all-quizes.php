<?php

/**
 * Provide a table with all quizes created
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
    'post_type'         => 'uc_quiz',
    'post_status'       => 'publish',
    'posts_per_page'    => 10,
);
$query = new WP_Query($args);

# Testando resultados registrados no voucher
$v_is_used = get_post_meta( 920, 'ucqpt_is_used', true );
$v_result_test_data = get_post_meta( 920, 'ucqpt_result_test_data', true );

?>

<?php 
if ( $query->have_posts() ) :
    ?>
    <table class="uk-table uk-table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Perguntas</th>
                <th>Resultados</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while ( $query->have_posts() ) :
            $query->the_post();
            $post_id            = get_the_ID();
            $questions_str      = get_post_meta( $post_id, 'quiz_questions_ids', true );
            $questions_arr      = explode(',', $questions_str);
            $questions_total    = count($questions_arr);

            ?>
            <tr>
                <td><?php the_title(); ?></td>
                <td><?php echo $questions_total; ?></td>
                <td>0</td>
            </tr>
            <?php
        endwhile;
        ?>
        </tbody>
    </table>
    <?php
endif;
?>