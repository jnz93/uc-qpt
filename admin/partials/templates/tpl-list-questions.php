<?php

/**
 * Provide a table with all questions and answers
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
    'post_type'         => 'uc_question',
    'post_status'       => 'publish',
    'posts_per_page'    => -1,
);
$query = new WP_Query($args);

?>

<?php 
if ( $query->have_posts() ) :
    ?>
    <table class="uk-table uk-table-striped">
        <thead>
            <tr>
                <th>Questões</th>
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
            </tr>
            <?php
        endwhile;
        ?>
        </tbody>
    </table>
<?php
else :
    ?>
    <div class="uk-flex uk-flex-column uk-flex-center uk-flex-middle">
        <div class="uk-width-2-3">
            <h3 class="uk-heading-line uk-text-center"><span>Ainda não temos nada por aqui</span></h3>
            <button class="uk-button uk-button-primary uk-button-large" uk-toggle="target: #new-quiz" style="display: block; margin: auto;">Novo Inventário</button>
        </div>
    </div>
    <?php
endif;
?>