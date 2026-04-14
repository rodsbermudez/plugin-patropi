<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="patropi-content">
    <h1 class="patropi-title"><?php _e( 'Dashboard', 'patropi-addon' ); ?></h1>
    <p class="mb-4"><?php _e( 'Bem-vindo ao Patropi Add-ons! Gerencie os módulos do plugin abaixo.', 'patropi-addon' ); ?></p>

    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><strong>Módulo FAQ</strong></span>
            <?php if ( $faq_enabled ) : ?>
                <span class="badge bg-success">Ativado</span>
            <?php else : ?>
                <span class="badge bg-secondary">Desativado</span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <p class="mb-1"><strong>FAQs cadastrados:</strong> <?php echo esc_html( $faq_count ); ?></p>
            <p class="mb-0"><strong>Shortcode:</strong> <code>[faq-patropi]</code></p>
        </div>
        <div class="card-footer">
            <a href="<?php echo admin_url( 'admin.php?page=patropi-faq' ); ?>" class="btn btn-primary btn-sm">Configurações</a>
            <a href="<?php echo admin_url( 'edit.php?post_type=faq-patropi' ); ?>" class="btn btn-secondary btn-sm">Ver FAQs</a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><strong>Módulo Mega Menu</strong></span>
            <?php if ( $mega_enabled ) : ?>
                <span class="badge bg-success">Ativado</span>
            <?php else : ?>
                <span class="badge bg-secondary">Desativado</span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <p class="mb-1"><strong>Itens do menu:</strong> <?php echo esc_html( $menu_item_count ); ?></p>
            <p class="mb-0"><strong>Shortcode:</strong> <code>[patropi-mega-menu]</code></p>
        </div>
        <div class="card-footer">
            <a href="<?php echo admin_url( 'admin.php?page=patropi-mega-menu' ); ?>" class="btn btn-primary btn-sm">Configurações</a>
        </div>
    </div>
</div>
