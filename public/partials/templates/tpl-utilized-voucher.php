<div class="uk-card uk-card-default uk-card-hover uk-card-body uk-text-center">
    <span uk-icon="icon: warning; ratio: 2"></span>
    <p class="uk-text-bold"><?php _e( 'Alguém já utilizou este voucher.', 'textdomain' ); ?></p>
    <ul class="uk-list">
        <p class="uk-text-meta"><?php _e( 'Você pode baixar o resultado clicando no botão abaixo!') ?></p>
        <a href="<?php echo $attachLink ?>" class="uk-button uk-button-secondary" target="_blank"><?php _e( 'Baixar Resultado', 'textdomain' ); ?><span uk-icon="icon: download"></span></a>
    </ul>
</div>