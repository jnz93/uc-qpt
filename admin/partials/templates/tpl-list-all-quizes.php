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

?>

<?php 
if ( $query->have_posts() ) :
    ?>
    <table class="uk-table uk-table-striped">
        <thead>
            <tr>
                <th class="">Inventário(s)</th>
                <th class="">Shortcode <span class="uk-margin-small-left" uk-icon="icon: question" uk-tooltip="title: Utilize o shortcode em posts, páginas ou widgets; pos: top"></span></th>
                <th class="">Pergunta(s) <span class="uk-margin-small-left" uk-icon="icon: question" uk-tooltip="title: Total de perguntas nesse inventário; pos: top"></span></th>
                <th class="">Resultado(s) <span class="uk-margin-small-left" uk-icon="icon: question" uk-tooltip="title: Quantas vezes o inventário foi submetido; pos: top"></span></th>
                <th class="">Ações <span class="uk-margin-small-left" uk-icon="icon: question" uk-tooltip="title: Quantas vezes o inventário foi submetido; pos: top"></span></th>
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
                <td>[shortcode]</td>
                <td><?php echo $questions_total; ?></td>
                <td>0</td>
                <td><td><span class="uk-margin-small-right" uk-icon="pencil" uk-tooltip="Editar Inventário" uk-toggle="target: #edit-inventory" onclick="loadInventoryData('<?php echo $post_id; ?>', '<?php echo $ajax_url; ?>')"></span> <span uk-icon="ban" uk-tooltip="Excluir Inventário"></span></td>
            </tr>
            <?php
        endwhile;
        ?>
        </tbody>
    </table>
    <?php
endif;
?>