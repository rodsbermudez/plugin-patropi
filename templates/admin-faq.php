<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="patropi-content">
    <h1 class="patropi-title"><?php _e( 'Configurações do FAQ', 'patropi-addon' ); ?></h1>

    <form method="post">
        <?php wp_nonce_field( 'patropi_faq_settings' ); ?>

        <div class="patropi-card">
            <h3 class="patropi-card-title"><?php _e( 'Ativar Módulo FAQ', 'patropi-addon' ); ?></h3>
            <label class="patropi-toggle">
                <input type="checkbox" name="faq_enabled" value="1" <?php checked( $current_settings['faq_enabled'], true ); ?>>
                <span class="patropi-toggle-switch"></span>
                <span class="patropi-toggle-label"><?php _e( 'Ativar módulo FAQ', 'patropi-addon' ); ?></span>
            </label>
        </div>

        <div class="patropi-card">
            <h3 class="patropi-card-title"><?php _e( 'Como Usar', 'patropi-addon' ); ?></h3>
            <div class="patropi-instructions">
                <h4><?php _e( '1. Adicionar o Shortcode', 'patropi-addon' ); ?></h4>
                <p><?php _e( 'Coloque o shortcode', 'patropi-addon' ); ?> <code>[faq-patropi]</code> <?php _e( 'nas páginas onde você deseja exibir as perguntas frequentes.', 'patropi-addon' ); ?></p>

                <h4><?php _e( '2. Criar FAQs', 'patropi-addon' ); ?></h4>
                <p><?php _e( 'Vá no menu', 'patropi-addon' ); ?> <strong>FAQs</strong> <?php _e( 'no admin do WordPress e crie suas perguntas e respostas.', 'patropi-addon' ); ?></p>

                <h4><?php _e( '3. Definir Disponibilidade', 'patropi-addon' ); ?></h4>
                <p><?php _e( 'Em cada FAQ, utilize o campo "Disponibilidade" no sidebar para selecionar em quais páginas aquele FAQ deve aparecer.', 'patropi-addon' ); ?></p>
            </div>
        </div>

        <div class="patropi-card">
            <h3 class="patropi-card-title"><?php _e( 'Opções do Accordion', 'patropi-addon' ); ?></h3>
            <label class="patropi-toggle">
                <input type="checkbox" name="faq_open_first" value="1" <?php checked( $current_settings['faq_open_first'], true ); ?>>
                <span class="patropi-toggle-switch"></span>
                <span class="patropi-toggle-label"><?php _e( 'Primeiro item aberto ao carregar', 'patropi-addon' ); ?></span>
            </label>
            <label class="patropi-toggle" style="margin-top: 15px;">
                <input type="checkbox" name="faq_close_others" value="1" <?php checked( $current_settings['faq_close_others'], true ); ?>>
                <span class="patropi-toggle-switch"></span>
                <span class="patropi-toggle-label"><?php _e( 'Fechar outros ao abrir um', 'patropi-addon' ); ?></span>
            </label>
            
            <hr style="margin: 20px 0;">
            
            <label class="patropi-toggle">
                <input type="checkbox" name="faq_icon_rotation" value="1" <?php checked( $current_settings['faq_icon_rotation'], true ); ?>>
                <span class="patropi-toggle-switch"></span>
                <span class="patropi-toggle-label"><?php _e( 'Ativar rotação de 180º', 'patropi-addon' ); ?></span>
            </label>
            <p class="patropi-card-text" style="margin-top: 5px; font-size: 12px;"><?php _e( 'Se ativado, usa um ícone que gira ao abrir/fechar. Se desativado, permite escolher dois ícones diferentes.', 'patropi-addon' ); ?></p>
            
            <?php 
            $rotation_enabled = ! empty( $current_settings['faq_icon_rotation'] );
            ?>
            
            <div class="icon-section-single" style="margin-top: 15px; <?php echo $rotation_enabled ? '' : 'display: none;'; ?>">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;"><?php _e( 'Selecionar ícone:', 'patropi-addon' ); ?></label>
                <div class="icon-select-container" style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <?php foreach ( $icon_options as $key => $option ) : ?>
                        <label class="icon-option" style="cursor: pointer; padding: 8px; border: 2px solid #ddd; border-radius: 4px; display: flex; align-items: center; gap: 5px; <?php echo ( $current_settings['faq_icon_closed'] === $key ) ? 'border-color: #2c3e50; background: #f8f9fa;' : ''; ?>">
                            <input type="radio" name="faq_icon_closed" value="<?php echo esc_attr( $key ); ?>" <?php checked( $current_settings['faq_icon_closed'], $key ); ?> style="display: none;">
                            <span class="dashicons <?php echo esc_attr( $option['closed'] ); ?>" style="color: #555;"></span>
                            <span style="font-size: 12px;"><?php echo esc_html( $option['label'] ); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="icon-section-dual" style="margin-top: 15px; <?php echo $rotation_enabled ? 'display: none;' : ''; ?>">
                <div style="margin-bottom: 10px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;"><?php _e( 'Ícone fechado:', 'patropi-addon' ); ?></label>
                    <div class="icon-select-container" style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <?php foreach ( $icon_options as $key => $option ) : ?>
                            <label class="icon-option" style="cursor: pointer; padding: 8px; border: 2px solid #ddd; border-radius: 4px; display: flex; align-items: center; gap: 5px; <?php echo ( $current_settings['faq_icon_closed'] === $option['closed'] ) ? 'border-color: #2c3e50; background: #f8f9fa;' : ''; ?>">
                                <input type="radio" name="faq_icon_closed" value="<?php echo esc_attr( $option['closed'] ); ?>" <?php checked( $current_settings['faq_icon_closed'], $option['closed'] ); ?> style="display: none;">
                                <span class="dashicons <?php echo esc_attr( $option['closed'] ); ?>" style="color: #555;"></span>
                                <span style="font-size: 12px;"><?php echo esc_html( $option['label'] ); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;"><?php _e( 'Ícone aberto:', 'patropi-addon' ); ?></label>
                    <div class="icon-select-container" style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <?php foreach ( $icon_options as $key => $option ) : ?>
                            <label class="icon-option" style="cursor: pointer; padding: 8px; border: 2px solid #ddd; border-radius: 4px; display: flex; align-items: center; gap: 5px; <?php echo ( $current_settings['faq_icon_open'] === $option['open'] ) ? 'border-color: #2c3e50; background: #f8f9fa;' : ''; ?>">
                                <input type="radio" name="faq_icon_open" value="<?php echo esc_attr( $option['open'] ); ?>" <?php checked( $current_settings['faq_icon_open'], $option['open'] ); ?> style="display: none;">
                                <span class="dashicons <?php echo esc_attr( $option['open'] ); ?>" style="color: #555;"></span>
                                <span style="font-size: 12px;"><?php echo esc_html( $option['label'] ); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <p class="patropi-submit">
            <input type="submit" name="patropi_faq_save" class="button button-primary" value="<?php _e( 'Salvar Configurações', 'patropi-addon' ); ?>">
        </p>
    </form>

    <div class="patropi-card">
        <h3 class="patropi-card-title"><?php _e( 'Personalização do CSS', 'patropi-addon' ); ?></h3>
        <p class="patropi-card-text"><?php _e( 'Edite o CSS do accordion abaixo. As alterações serão aplicadas automaticamente no frontend.', 'patropi-addon' ); ?></p>
        <form method="post">
            <?php wp_nonce_field( 'patropi_faq_css_save', 'patropi_faq_css_nonce' ); ?>
            <textarea name="faq_custom_css" rows="20" class="large-text code" style="font-family: monospace; font-size: 12px; width: 100%; box-sizing: border-box; margin-bottom: 10px;"><?php echo esc_textarea( $faq_custom_css ); ?></textarea>
            <div class="patropi-css-buttons">
                <input type="submit" name="patropi_faq_css_save" class="button button-primary" value="<?php _e( 'Salvar CSS', 'patropi-addon' ); ?>">
                <input type="submit" name="patropi_faq_css_reset" class="button button-secondary" value="<?php _e( 'Resetar para Padrão', 'patropi-addon' ); ?>" onclick="return confirm('<?php _e( 'Tem certeza que deseja resetar o CSS para o padrão?', 'patropi-addon' ); ?>');">
            </div>
        </form>
    </div>
</div>
