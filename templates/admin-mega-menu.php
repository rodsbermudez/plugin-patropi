<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="patropi-content">
    <h1 class="patropi-title"><?php _e( 'Configurações do Mega Menu', 'patropi-addon' ); ?></h1>

    <form method="post">
        <?php wp_nonce_field( 'patropi_mega_menu_save' ); ?>

        <div class="patropi-card">
            <h3 class="patropi-card-title"><?php _e( 'Ativar Módulo Mega Menu', 'patropi-addon' ); ?></h3>
            <label class="patropi-toggle">
                <input type="checkbox" name="mega_enabled" value="1" <?php checked( $settings['enabled'], true ); ?>>
                <span class="patropi-toggle-switch"></span>
                <span class="patropi-toggle-label"><?php _e( 'Ativar módulo Mega Menu', 'patropi-addon' ); ?></span>
            </label>
            <p class="patropi-card-text" style="margin-top: 10px;">
                <?php _e( 'Use o shortcode', 'patropi-addon' ); ?> <code>[patropi-mega-menu]</code> <?php _e( 'nas páginas onde deseja exibir o menu.', 'patropi-addon' ); ?>
            </p>
        </div>

        <div class="patropi-card">
            <h3 class="patropi-card-title"><?php _e( 'Estilos do Menu Principal', 'patropi-addon' ); ?></h3>
            
            <div class="patropi-style-section mb-4">
                <h5 class="text-muted mb-3" style="font-size: 12px; text-transform: uppercase;"><?php _e( 'Texto', 'patropi-addon' ); ?></h5>
                <div class="row">
                    <div class="col-6 col-md-4">
                        <label><strong><?php _e( 'Cor (normal)', 'patropi-addon' ); ?></strong></label>
                        <input type="color" name="main_menu[text_color]" value="<?php echo esc_attr( $settings['main_menu']['text_color'] ); ?>" class="form-control mb-2" style="width: 60px; height: 38px; padding: 2px;">
                        <label class="patropi-toggle" style="margin: 0;">
                            <input type="checkbox" name="main_menu[text_color_transparent]" value="1" <?php checked( ! empty( $settings['main_menu']['text_color_transparent'] ) ); ?>>
                            <span class="patropi-toggle-switch"></span>
                            <span class="patropi-toggle-label" style="font-size: 12px;"><?php _e( 'Transparente', 'patropi-addon' ); ?></span>
                        </label>
                    </div>
                    <div class="col-6 col-md-4">
                        <label><strong><?php _e( 'Cor (hover)', 'patropi-addon' ); ?></strong></label>
                        <input type="color" name="main_menu[text_hover]" value="<?php echo esc_attr( $settings['main_menu']['text_hover'] ); ?>" class="form-control mb-2" style="width: 60px; height: 38px; padding: 2px;">
                        <label class="patropi-toggle" style="margin: 0;">
                            <input type="checkbox" name="main_menu[text_hover_transparent]" value="1" <?php checked( ! empty( $settings['main_menu']['text_hover_transparent'] ) ); ?>>
                            <span class="patropi-toggle-switch"></span>
                            <span class="patropi-toggle-label" style="font-size: 12px;"><?php _e( 'Transparente', 'patropi-addon' ); ?></span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="patropi-style-section mb-4">
                <h5 class="text-muted mb-3" style="font-size: 12px; text-transform: uppercase;"><?php _e( 'Fundo', 'patropi-addon' ); ?></h5>
                <div class="row">
                    <div class="col-6 col-md-4">
                        <label><strong><?php _e( 'Cor (normal)', 'patropi-addon' ); ?></strong></label>
                        <input type="color" name="main_menu[bg_color]" value="<?php echo esc_attr( $settings['main_menu']['bg_color'] ); ?>" class="form-control mb-2" style="width: 60px; height: 38px; padding: 2px;">
                        <label class="patropi-toggle" style="margin: 0;">
                            <input type="checkbox" name="main_menu[bg_color_transparent]" value="1" <?php checked( ! empty( $settings['main_menu']['bg_color_transparent'] ) ); ?>>
                            <span class="patropi-toggle-switch"></span>
                            <span class="patropi-toggle-label" style="font-size: 12px;"><?php _e( 'Transparente', 'patropi-addon' ); ?></span>
                        </label>
                    </div>
                    <div class="col-6 col-md-4">
                        <label><strong><?php _e( 'Cor (hover)', 'patropi-addon' ); ?></strong></label>
                        <input type="color" name="main_menu[bg_hover]" value="<?php echo esc_attr( $settings['main_menu']['bg_hover'] ); ?>" class="form-control mb-2" style="width: 60px; height: 38px; padding: 2px;">
                        <label class="patropi-toggle" style="margin: 0;">
                            <input type="checkbox" name="main_menu[bg_hover_transparent]" value="1" <?php checked( ! empty( $settings['main_menu']['bg_hover_transparent'] ) ); ?>>
                            <span class="patropi-toggle-switch"></span>
                            <span class="patropi-toggle-label" style="font-size: 12px;"><?php _e( 'Transparente', 'patropi-addon' ); ?></span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="patropi-style-section">
                <h5 class="text-muted mb-3" style="font-size: 12px; text-transform: uppercase;"><?php _e( 'Borda Inferior', 'patropi-addon' ); ?></h5>
                <div class="row">
                    <div class="col-6 col-md-3">
                        <label><strong><?php _e( 'Espessura', 'patropi-addon' ); ?></strong></label>
                        <input type="text" name="main_menu[border_width]" value="<?php echo esc_attr( $settings['main_menu']['border_width'] ); ?>" class="form-control" placeholder="ex: 2px">
                    </div>
                    <div class="col-6 col-md-3">
                        <label><strong><?php _e( 'Cor (normal)', 'patropi-addon' ); ?></strong></label>
                        <input type="color" name="main_menu[border_color]" value="<?php echo esc_attr( $settings['main_menu']['border_color'] ); ?>" class="form-control mb-2" style="width: 60px; height: 38px; padding: 2px;">
                        <label class="patropi-toggle" style="margin: 0;">
                            <input type="checkbox" name="main_menu[border_color_transparent]" value="1" <?php checked( ! empty( $settings['main_menu']['border_color_transparent'] ) ); ?>>
                            <span class="patropi-toggle-switch"></span>
                            <span class="patropi-toggle-label" style="font-size: 12px;"><?php _e( 'Transparente', 'patropi-addon' ); ?></span>
                        </label>
                    </div>
                    <div class="col-6 col-md-3">
                        <label><strong><?php _e( 'Cor (hover)', 'patropi-addon' ); ?></strong></label>
                        <input type="color" name="main_menu[border_hover_color]" value="<?php echo esc_attr( $settings['main_menu']['border_hover_color'] ); ?>" class="form-control mb-2" style="width: 60px; height: 38px; padding: 2px;">
                        <label class="patropi-toggle" style="margin: 0;">
                            <input type="checkbox" name="main_menu[border_hover_transparent]" value="1" <?php checked( ! empty( $settings['main_menu']['border_hover_transparent'] ) ); ?>>
                            <span class="patropi-toggle-switch"></span>
                            <span class="patropi-toggle-label" style="font-size: 12px;"><?php _e( 'Transparente', 'patropi-addon' ); ?></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="patropi-card">
            <h3 class="patropi-card-title"><?php _e( 'Layout do Menu Principal', 'patropi-addon' ); ?></h3>
            <div class="row">
                <div class="col-6 col-md-3">
                    <label><strong><?php _e( 'Alinhamento', 'patropi-addon' ); ?></strong></label>
                    <select name="main_menu[align]" class="form-control">
                        <option value="flex-start" <?php selected( $settings['main_menu']['align'] ?? 'flex-start', 'flex-start' ); ?>><?php _e( 'Esquerda', 'patropi-addon' ); ?></option>
                        <option value="center" <?php selected( $settings['main_menu']['align'] ?? 'flex-start', 'center' ); ?>><?php _e( 'Centro', 'patropi-addon' ); ?></option>
                        <option value="flex-end" <?php selected( $settings['main_menu']['align'] ?? 'flex-start', 'flex-end' ); ?>><?php _e( 'Direita', 'patropi-addon' ); ?></option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label><strong><?php _e( 'Gap entre itens', 'patropi-addon' ); ?></strong></label>
                    <input type="text" name="main_menu[item_gap]" value="<?php echo esc_attr( $settings['main_menu']['item_gap'] ?? '0px' ); ?>" class="form-control" placeholder="ex: 10px">
                </div>
                <div class="col-6 col-md-3">
                    <label><strong><?php _e( 'Padding (top/bottom)', 'patropi-addon' ); ?></strong></label>
                    <input type="text" name="main_menu[item_padding_y]" value="<?php echo esc_attr( $settings['main_menu']['item_padding_y'] ?? '15px' ); ?>" class="form-control" placeholder="ex: 15px">
                </div>
                <div class="col-6 col-md-3">
                    <label><strong><?php _e( 'Padding (left/right)', 'patropi-addon' ); ?></strong></label>
                    <input type="text" name="main_menu[item_padding_x]" value="<?php echo esc_attr( $settings['main_menu']['item_padding_x'] ?? '20px' ); ?>" class="form-control" placeholder="ex: 20px">
                </div>
            </div>
        </div>

        <div class="patropi-card">
            <h3 class="patropi-card-title"><?php _e( 'Configurações do Mega Menu', 'patropi-addon' ); ?></h3>
            <div class="row">
                <div class="col-6 col-md-3">
                    <label><strong><?php _e( 'Largura', 'patropi-addon' ); ?></strong></label>
                    <input type="text" name="mega_menu[width]" value="<?php echo esc_attr( $settings['mega_menu']['width'] ); ?>" class="form-control" placeholder="ex: 100% ou 1200px">
                </div>
                <div class="col-6 col-md-3">
                    <label><strong><?php _e( 'Largura máxima', 'patropi-addon' ); ?></strong></label>
                    <input type="text" name="mega_menu[max_width]" value="<?php echo esc_attr( $settings['mega_menu']['max_width'] ); ?>" class="form-control" placeholder="ex: 1200px">
                </div>
                <div class="col-6 col-md-3">
                    <label><strong><?php _e( 'Disparo do menu', 'patropi-addon' ); ?></strong></label>
                    <select name="mega_menu[trigger]" class="form-control">
                        <option value="hover" <?php selected( $settings['mega_menu']['trigger'], 'hover' ); ?>><?php _e( 'Ao passar o mouse (hover)', 'patropi-addon' ); ?></option>
                        <option value="click" <?php selected( $settings['mega_menu']['trigger'], 'click' ); ?>><?php _e( 'Ao clicar (click)', 'patropi-addon' ); ?></option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label><strong><?php _e( 'Animação', 'patropi-addon' ); ?></strong></label>
                    <select name="mega_menu[animation]" class="form-control">
                        <option value="fade" <?php selected( $settings['mega_menu']['animation'] ?? 'fade', 'fade' ); ?>><?php _e( 'FadeIn / FadeOut', 'patropi-addon' ); ?></option>
                        <option value="slide" <?php selected( $settings['mega_menu']['animation'] ?? 'fade', 'slide' ); ?>><?php _e( 'SlideIn / SlideOut', 'patropi-addon' ); ?></option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-6 col-md-3">
                    <label><strong><?php _e( 'Padding (top/bottom)', 'patropi-addon' ); ?></strong></label>
                    <input type="text" name="mega_menu[padding_y]" value="<?php echo esc_attr( $settings['mega_menu']['padding_y'] ?? '20px' ); ?>" class="form-control" placeholder="ex: 20px">
                </div>
                <div class="col-6 col-md-3">
                    <label><strong><?php _e( 'Padding (left/right)', 'patropi-addon' ); ?></strong></label>
                    <input type="text" name="mega_menu[padding_x]" value="<?php echo esc_attr( $settings['mega_menu']['padding_x'] ?? '20px' ); ?>" class="form-control" placeholder="ex: 20px">
                </div>
                <div class="col-6 col-md-3">
                    <label><strong><?php _e( 'Sombra inferior', 'patropi-addon' ); ?></strong></label>
                    <label class="patropi-toggle" style="margin-top: 5px;">
                        <input type="checkbox" name="mega_menu[shadow]" value="1" <?php checked( $settings['mega_menu']['shadow'] ?? false, true ); ?>>
                        <span class="patropi-toggle-switch"></span>
                        <span class="patropi-toggle-label"><?php _e( 'Ativar', 'patropi-addon' ); ?></span>
                    </label>
                </div>
                <div class="col-6 col-md-3">
                    <label><strong><?php _e( 'Cor de fundo', 'patropi-addon' ); ?></strong></label>
                    <div class="d-flex align-items-center gap-2" style="height: 38px;">
                        <input type="color" name="mega_menu[dropdown_bg]" value="<?php echo esc_attr( $settings['mega_menu']['dropdown_bg'] ?? '#ffffff' ); ?>" class="form-control form-control-color" style="width: 50px; height: 38px; padding: 2px; margin: 0;">
                        <label class="patropi-toggle mb-0" style="margin-top: 0;">
                            <input type="checkbox" name="mega_menu[dropdown_bg_transparent]" value="1" <?php checked( ! empty( $settings['mega_menu']['dropdown_bg_transparent'] ) ); ?>>
                            <span class="patropi-toggle-switch"></span>
                            <span class="patropi-toggle-label">Transparente</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="patropi-card">
            <h3 class="patropi-card-title"><?php _e( 'Configurações Mobile/Tablet', 'patropi-addon' ); ?></h3>
            <div class="row">
                <div class="col-6 col-md-3">
                    <label><strong><?php _e( 'Cor do ícone', 'patropi-addon' ); ?></strong></label>
                    <input type="color" name="mega_menu[mobile_icon_color]" value="<?php echo esc_attr( $settings['mega_menu']['mobile_icon_color'] ?? '#333333' ); ?>" class="form-control form-control-color" style="width: 50px; height: 40px; padding: 2px;">
                </div>
                <div class="col-6 col-md-3">
                    <label><strong><?php _e( 'Cor de fundo', 'patropi-addon' ); ?></strong></label>
                    <input type="color" name="mega_menu[mobile_bg_color]" value="<?php echo esc_attr( $settings['mega_menu']['mobile_bg_color'] ?? '#ffffff' ); ?>" class="form-control form-control-color" style="width: 50px; height: 40px; padding: 2px;">
                </div>
                <div class="col-6 col-md-3">
                    <label><strong><?php _e( 'Largura (%)', 'patropi-addon' ); ?></strong></label>
                    <input type="number" name="mega_menu[mobile_width]" value="<?php echo esc_attr( $settings['mega_menu']['mobile_width'] ?? '85' ); ?>" class="form-control" min="50" max="100" placeholder="50-100">
                    <small class="text-muted" style="font-size: 11px;"><?php _e( 'Valor entre 50% e 100%', 'patropi-addon' ); ?></small>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mb-4">
            <input type="submit" name="patropi_mega_menu_save" class="button button-primary" value="<?php _e( 'Salvar Configurações', 'patropi-addon' ); ?>">
        </div>

        <div class="patropi-card">
            <h3 class="patropi-card-title"><?php _e( 'Itens do Menu', 'patropi-addon' ); ?></h3>
            <p class="patropi-card-text"><?php _e( 'Adicione os itens do menu principal. Para cada item, você pode definir se terá mega menu ou apenas um link simples.', 'patropi-addon' ); ?></p>
            
            <div id="patropi-menu-items-container">
                <?php 
                $menu_items = $settings['menu_items'] ?? array();
                $item_index = 0;
                
                foreach ( $menu_items as $item ) : 
                ?>
                    <div class="patropi-menu-item card mb-3" data-index="<?php echo $item_index; ?>">
                        <div class="card-header patropi-accordion-header d-flex justify-content-between align-items-center" style="cursor: pointer; background-color: #f8f9fa;">
                            <strong>
                                <span class="dashicons dashicons-menu" style="color: #ccc; margin-right: 8px;"></span> 
                                <span class="patropi-item-title-preview"><?php echo esc_html( ! empty($item['text']) ? 'Item: ' . $item['text'] : 'Item ' . ($item_index + 1) ); ?></span>
                            </strong>
                            <div>
                                <span class="dashicons dashicons-arrow-down-alt2 patropi-accordion-icon"></span>
                            </div>
                        </div>
                        <div class="card-body patropi-accordion-body" style="display: none;">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <label><strong><?php _e( 'Texto do item', 'patropi-addon' ); ?></strong></label>
                                    <input type="text" name="menu_items[<?php echo $item_index; ?>][text]" value="<?php echo esc_attr( $item['text'] ?? '' ); ?>" class="form-control" placeholder="Nome do item">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label><strong><?php _e( 'Tem mega menu?', 'patropi-addon' ); ?></strong></label>
                                    <div class="d-flex align-items-center" style="height: 38px;">
                                        <label class="patropi-toggle mb-0" style="margin-top: 0;">
                                            <input type="checkbox" name="menu_items[<?php echo $item_index; ?>][has_mega]" value="1" <?php checked( $item['has_mega'] ?? false, true ); ?> class="patropi-has-mega-toggle">
                                            <span class="patropi-toggle-switch"></span>
                                            <span class="patropi-toggle-label"><?php _e( 'Ativar mega menu', 'patropi-addon' ); ?></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="patropi-mega-config" style="<?php echo empty( $item['has_mega'] ) ? 'display: none;' : ''; ?> mt-3">
                                <hr>
                                <h5><?php _e( 'Configurações do Mega Menu', 'patropi-addon' ); ?></h5>
                                
                                <div class="mb-3">
                                    <button type="button" class="btn btn-primary btn-sm patropi-add-column" data-item-index="<?php echo $item_index; ?>">+ Adicionar coluna</button>
                                </div>

                                <div class="patropi-columns-container">
                                    <?php 
                                    $columns = $item['columns'] ?? array();
                                    $col_index = 0;
                                    
                                    if ( empty( $columns ) ) {
                                        $columns = array( array() );
                                    }
                                    
                                    foreach ( $columns as $col ) : 
                                    ?>
                                        <div class="patropi-column card mb-2" data-col-index="<?php echo $col_index; ?>">
                                            <div class="card-header patropi-accordion-header d-flex justify-content-between align-items-center" style="cursor: pointer;">
                                                <span><strong><?php _e( 'Coluna', 'patropi-addon' ); ?> <?php echo $col_index + 1; ?></strong> <span class="dashicons dashicons-arrow-down-alt2 patropi-accordion-icon" style="margin-left: 8px;"></span></span>
                                                <button type="button" class="btn btn-danger btn-sm patropi-remove-column" style="position: relative; z-index: 2;"><?php _e( 'Remover', 'patropi-addon' ); ?></button>
                                            </div>
                                            <div class="card-body patropi-accordion-body" style="display: none;">
                                                <div class="row">
                                                    <div class="col-4 col-md-4">
                                                        <label><strong><?php _e( 'Largura (%)', 'patropi-addon' ); ?></strong></label>
                                                        <input type="number" name="menu_items[<?php echo $item_index; ?>][columns][<?php echo $col_index; ?>][width]" value="<?php echo esc_attr( $col['width'] ?? '25' ); ?>" class="form-control" min="1" max="100">
                                                    </div>
                                                    <div class="col-4 col-md-4">
                                                        <label><strong><?php _e( 'Tem título?', 'patropi-addon' ); ?></strong></label>
                                                        <div class="d-flex align-items-center" style="height: 38px;">
                                                            <label class="patropi-toggle mb-0" style="margin-top: 0;">
                                                                <input type="checkbox" name="menu_items[<?php echo $item_index; ?>][columns][<?php echo $col_index; ?>][has_title]" value="1" <?php checked( $col['has_title'] ?? false, true ); ?> class="patropi-has-title-toggle">
                                                                <span class="patropi-toggle-switch"></span>
                                                                <span class="patropi-toggle-label"><?php _e( 'Mostrar', 'patropi-addon' ); ?></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 col-md-4">
                                                        <label><strong><?php _e( 'Layout', 'patropi-addon' ); ?></strong></label>
                                                        <select name="menu_items[<?php echo $item_index; ?>][columns][<?php echo $col_index; ?>][layout]" class="form-control patropi-col-layout">
                                                            <option value="links" <?php selected( $col['layout'] ?? 'links', 'links' ); ?>><?php _e( 'Links', 'patropi-addon' ); ?></option>
                                                            <option value="image" <?php selected( $col['layout'] ?? 'links', 'image' ); ?>><?php _e( 'Imagem', 'patropi-addon' ); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="patropi-title-config mt-2" style="<?php echo empty( $col['has_title'] ) ? 'display: none;' : ''; ?>">
                                                    <label><strong><?php _e( 'Título da coluna', 'patropi-addon' ); ?></strong></label>
                                                    <input type="text" name="menu_items[<?php echo $item_index; ?>][columns][<?php echo $col_index; ?>][title]" value="<?php echo esc_attr( $col['title'] ?? '' ); ?>" class="form-control">
                                                </div>

                                                <div class="patropi-links-config mt-3" style="<?php echo ( $col['layout'] ?? 'links' ) !== 'links' ? 'display: none;' : ''; ?>">
                                                    <label><strong><?php _e( 'Adicionar links', 'patropi-addon' ); ?></strong></label>
                                                    <p class="text-muted" style="font-size: 12px;"><?php _e( 'Máximo 10 links por coluna.', 'patropi-addon' ); ?></p>
                                                    <div class="patropi-links-list">
                                                        <?php 
                                                        $links = $col['links'] ?? array();
                                                        $link_index = 0;
                                                        
                                                        if ( empty( $links ) ) {
                                                            $links = array( array() );
                                                        }
                                                        
                                                        foreach ( $links as $link ) : 
                                                        ?>
                                                            <div class="patropi-link-item row mb-2">
                                                                <div class="col-md-3">
                                                                    <select name="menu_items[<?php echo $item_index; ?>][columns][<?php echo $col_index; ?>][links][<?php echo $link_index; ?>][type]" class="form-control patropi-link-type">
                                                                        <option value="page" <?php selected( $link['type'] ?? 'page', 'page' ); ?>><?php _e( 'Página', 'patropi-addon' ); ?></option>
                                                                        <option value="custom" <?php selected( $link['type'] ?? 'page', 'custom' ); ?>><?php _e( 'Link customizado', 'patropi-addon' ); ?></option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-3 patropi-page-select">
                                                                    <select name="menu_items[<?php echo $item_index; ?>][columns][<?php echo $col_index; ?>][links][<?php echo $link_index; ?>][page_id]" class="form-control">
                                                                        <option value=""><?php _e( 'Selecione uma página', 'patropi-addon' ); ?></option>
                                                                        <?php foreach ( $pages as $page ) : ?>
                                                                            <option value="<?php echo $page->ID; ?>" <?php selected( $link['page_id'] ?? '', $page->ID ); ?>><?php echo esc_html( $page->post_title ); ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-3 patropi-custom-url" style="<?php echo ( $link['type'] ?? 'page' ) !== 'custom' ? 'display: none;' : ''; ?>">
                                                                    <input type="text" name="menu_items[<?php echo $item_index; ?>][columns][<?php echo $col_index; ?>][links][<?php echo $link_index; ?>][url]" value="<?php echo esc_attr( $link['url'] ?? '' ); ?>" class="form-control" placeholder="URL">
                                                                </div>
                                                                <div class="col-md-2 patropi-custom-text" style="<?php echo ( $link['type'] ?? 'page' ) !== 'custom' ? 'display: none;' : ''; ?>">
                                                                    <input type="text" name="menu_items[<?php echo $item_index; ?>][columns][<?php echo $col_index; ?>][links][<?php echo $link_index; ?>][text]" value="<?php echo esc_attr( $link['text'] ?? '' ); ?>" class="form-control" placeholder="Texto">
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <button type="button" class="btn btn-danger btn-sm patropi-remove-link">×</button>
                                                                </div>
                                                            </div>
                                                            <?php $link_index++; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <button type="button" class="btn btn-secondary btn-sm mt-2 patropi-add-link" data-max-col="<?php echo $item_index; ?>" data-max-col-index="<?php echo $col_index; ?>">+ Adicionar link</button>
                                                </div>

                                                <div class="patropi-image-config mt-3" style="<?php echo ( $col['layout'] ?? 'links' ) !== 'image' ? 'display: none;' : ''; ?>">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label><strong><?php _e( 'Ícone (dashicon)', 'patropi-addon' ); ?></strong></label>
                                                            <input type="text" name="menu_items[<?php echo $item_index; ?>][columns][<?php echo $col_index; ?>][image_data][icon]" value="<?php echo esc_attr( $col['image_data']['icon'] ?? '' ); ?>" class="form-control" placeholder="dashicons-cart">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label><strong><?php _e( 'URL da imagem', 'patropi-addon' ); ?></strong></label>
                                                            <input type="text" name="menu_items[<?php echo $item_index; ?>][columns][<?php echo $col_index; ?>][image_data][image_url]" value="<?php echo esc_attr( $col['image_data']['image_url'] ?? '' ); ?>" class="form-control" placeholder="https://...">
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-md-6">
                                                            <label><strong><?php _e( 'Título', 'patropi-addon' ); ?></strong></label>
                                                            <input type="text" name="menu_items[<?php echo $item_index; ?>][columns][<?php echo $col_index; ?>][image_data][title]" value="<?php echo esc_attr( $col['image_data']['title'] ?? '' ); ?>" class="form-control">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label><strong><?php _e( 'Descrição', 'patropi-addon' ); ?></strong></label>
                                                            <input type="text" name="menu_items[<?php echo $item_index; ?>][columns][<?php echo $col_index; ?>][image_data][description]" value="<?php echo esc_attr( $col['image_data']['description'] ?? '' ); ?>" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <label><strong><?php _e( 'Link (URL)', 'patropi-addon' ); ?></strong></label>
                                                        <input type="text" name="menu_items[<?php echo $item_index; ?>][columns][<?php echo $col_index; ?>][image_data][link_url]" value="<?php echo esc_attr( $col['image_data']['link_url'] ?? '' ); ?>" class="form-control" placeholder="https://...">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $col_index++; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="patropi-simple-link-config mt-3" style="<?php echo ! empty( $item['has_mega'] ) ? 'display: none;' : ''; ?>">
                                <hr>
                                <h5><?php _e( 'Link do item (sem mega menu)', 'patropi-addon' ); ?></h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label><strong><?php _e( 'Tipo de link', 'patropi-addon' ); ?></strong></label>
                                        <select name="menu_items[<?php echo $item_index; ?>][link_type]" class="form-control patropi-link-type-simple">
                                            <option value="page" <?php selected( $item['link_type'] ?? 'page', 'page' ); ?>><?php _e( 'Página', 'patropi-addon' ); ?></option>
                                            <option value="custom" <?php selected( $item['link_type'] ?? 'page', 'custom' ); ?>><?php _e( 'URL customizada', 'patropi-addon' ); ?></option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 patropi-simple-page" style="<?php echo ( $item['link_type'] ?? 'page' ) !== 'page' ? 'display: none;' : ''; ?>">
                                        <label><strong><?php _e( 'Selecionar página', 'patropi-addon' ); ?></strong></label>
                                        <select name="menu_items[<?php echo $item_index; ?>][link_page_id]" class="form-control">
                                            <option value=""><?php _e( 'Selecione uma página', 'patropi-addon' ); ?></option>
                                            <?php foreach ( $pages as $page ) : ?>
                                                <option value="<?php echo $page->ID; ?>" <?php selected( $item['link_page_id'] ?? '', $page->ID ); ?>><?php echo esc_html( $page->post_title ); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 patropi-simple-custom" style="<?php echo ( $item['link_type'] ?? 'page' ) !== 'custom' ? 'display: none;' : ''; ?>">
                                        <label><strong><?php _e( 'URL', 'patropi-addon' ); ?></strong></label>
                                        <input type="text" name="menu_items[<?php echo $item_index; ?>][link_url]" value="<?php echo esc_attr( $item['link_url'] ?? '' ); ?>" class="form-control" placeholder="https://...">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="button" class="btn btn-danger btn-sm patropi-remove-item"><?php _e( 'Remover item', 'patropi-addon' ); ?></button>
                            </div>
                        </div>
                    </div>
                    <?php 
                    $item_index++;
                endforeach; 
                
                if ( empty( $menu_items ) ) :
                ?>
                    <div class="patropi-menu-item card mb-3" data-index="0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label><strong><?php _e( 'Texto do item', 'patropi-addon' ); ?></strong></label>
                                    <input type="text" name="menu_items[0][text]" value="" class="form-control" placeholder="Nome do item">
                                </div>
                                <div class="col-md-6">
                                    <label><strong><?php _e( 'Tem mega menu?', 'patropi-addon' ); ?></strong></label>
                                    <label class="patropi-toggle" style="margin-top: 5px;">
                                        <input type="checkbox" name="menu_items[0][has_mega]" value="1" class="patropi-has-mega-toggle">
                                        <span class="patropi-toggle-switch"></span>
                                        <span class="patropi-toggle-label"><?php _e( 'Ativar mega menu', 'patropi-addon' ); ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <button type="button" class="btn btn-primary mt-3" id="patropi-add-menu-item">+ Adicionar item</button>
        </div>

        <p class="patropi-submit">
            <input type="submit" name="patropi_mega_menu_save" class="button button-primary" value="<?php _e( 'Salvar Configurações', 'patropi-addon' ); ?>">
        </p>
    </form>
</div>
