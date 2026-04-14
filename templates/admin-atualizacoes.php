<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="patropi-content">
    <h1 class="patropi-title"><?php _e( 'Atualizações', 'patropi-addon' ); ?></h1>
    <p class="patropi-card-text"><?php _e( 'Histórico de versões e melhorias do plugin.', 'patropi-addon' ); ?></p>

    <?php foreach ( $changelog as $version => $changes ) : ?>
        <div class="patropi-card">
            <h3 class="patropi-card-title"><?php echo esc_html( 'Versão ' . $version ); ?></h3>
            <ul class="patropi-changelog">
                <?php foreach ( $changes as $change ) : ?>
                    <li><?php echo esc_html( $change ); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</div>
