<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Patropi_Addon_Admin {
    private $version;
    private $plugin_name;

    public function __construct() {
        $this->plugin_name = 'patropi-addon';
        $this->version = '1.0.0';
    }

    public function enqueue_styles( $hook ) {
        if ( strpos( $hook, 'patropi' ) === false ) {
            return;
        }

        wp_enqueue_style( 'dashicons' );

        wp_enqueue_style(
            'patropi-bootstrap',
            'https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/flatly/bootstrap.min.css',
            array(),
            '5.3.2'
        );

        wp_enqueue_style(
            'patropi-admin',
            PATROPI_ADDON_PLUGIN_URL . 'assets/css/admin.css',
            array( 'patropi-bootstrap', 'dashicons' ),
            $this->version
        );
    }

    public function enqueue_scripts( $hook ) {
        if ( strpos( $hook, 'patropi' ) === false ) {
            return;
        }

        wp_enqueue_script(
            'bootstrap',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js',
            array( 'jquery' ),
            '5.3.2',
            true
        );

        wp_enqueue_script(
            'patropi-admin',
            PATROPI_ADDON_PLUGIN_URL . 'assets/js/admin.js',
            array( 'jquery', 'bootstrap' ),
            $this->version,
            true
        );
    }

    public function add_menu_page() {
        add_menu_page(
            __( 'Patropi Add-ons', 'patropi-addon' ),
            __( 'Patropi Add-ons', 'patropi-addon' ),
            'manage_options',
            'patropi-dashboard',
            array( $this, 'render_dashboard' ),
            PATROPI_ADDON_PLUGIN_URL . 'assets/images/patropi-favicon.png',
            3
        );

        add_submenu_page(
            'patropi-dashboard',
            __( 'Dashboard', 'patropi-addon' ),
            __( 'Dashboard', 'patropi-addon' ),
            'manage_options',
            'patropi-dashboard',
            array( $this, 'render_dashboard' )
        );

        add_submenu_page(
            'patropi-dashboard',
            __( 'FAQ', 'patropi-addon' ),
            __( 'FAQ', 'patropi-addon' ),
            'manage_options',
            'patropi-faq',
            array( $this, 'render_faq' )
        );

        add_submenu_page(
            'patropi-dashboard',
            __( 'Mega Menu', 'patropi-addon' ),
            __( 'Mega Menu', 'patropi-addon' ),
            'manage_options',
            'patropi-mega-menu',
            array( $this, 'render_mega_menu' )
        );

        add_submenu_page(
            'patropi-dashboard',
            __( 'Atualizações', 'patropi-addon' ),
            __( 'Atualizações', 'patropi-addon' ),
            'manage_options',
            'patropi-atualizacoes',
            array( $this, 'render_atualizacoes' )
        );
    }

    public function render_layout_start() {
        echo '<div class="wrap patropi-wrap">';
    }

    private function render_header( $active_tab = '' ) {
        $logo_url = PATROPI_ADDON_PLUGIN_URL . 'assets/images/patropi-logo.png';
        ?>
        <div class="patropi-header">
            <img src="<?php echo esc_url( $logo_url ); ?>" alt="Patropi" class="patropi-logo">
            <?php if ( $active_tab ) : ?>
            <ul class="nav nav-tabs patropi-tabs">
                <li class="nav-item">
                    <a class="nav-link <?php echo $active_tab === 'dashboard' ? 'active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=patropi-dashboard' ); ?>">
                        <?php _e( 'Dashboard', 'patropi-addon' ); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $active_tab === 'faq' ? 'active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=patropi-faq' ); ?>">
                        <?php _e( 'FAQ', 'patropi-addon' ); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $active_tab === 'mega-menu' ? 'active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=patropi-mega-menu' ); ?>">
                        <?php _e( 'Mega Menu', 'patropi-addon' ); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $active_tab === 'atualizacoes' ? 'active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=patropi-atualizacoes' ); ?>">
                        <?php _e( 'Atualizações', 'patropi-addon' ); ?>
                    </a>
                </li>
            </ul>
            <?php endif; ?>
        </div>
        <?php
    }

    public function render_dashboard() {
        $settings = new Patropi_Addon_Settings();
        $faq_enabled = $settings->is_faq_enabled();
        
        $faq_count = 0;
        if ( $faq_enabled ) {
            $faq_count = wp_count_posts( 'faq-patropi' );
            $faq_count = $faq_count ? $faq_count->publish : 0;
        }

        require_once PATROPI_ADDON_PLUGIN_DIR . 'includes/class-patropi-mega-menu.php';
        $mega_menu = new Patropi_Mega_Menu();
        $mega_settings = $mega_menu->get_settings();
        $mega_enabled = ! empty( $mega_settings['enabled'] );
        $menu_items = $mega_settings['menu_items'] ?? array();
        $menu_item_count = count( $menu_items );

        $this->render_layout_start();
        $this->render_header( 'dashboard' );
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
                    <p class="mb-1"><strong>FAQs cadastrados:</strong> <?php echo $faq_count; ?></p>
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
                    <p class="mb-1"><strong>Itens do menu:</strong> <?php echo $menu_item_count; ?></p>
                    <p class="mb-0"><strong>Shortcode:</strong> <code>[patropi-mega-menu]</code></p>
                </div>
                <div class="card-footer">
                    <a href="<?php echo admin_url( 'admin.php?page=patropi-mega-menu' ); ?>" class="btn btn-primary btn-sm">Configurações</a>
                </div>
            </div>

        </div>
        <?php
        echo '</div>';
    }

    public function render_faq() {
        $settings = new Patropi_Addon_Settings();

        if ( isset( $_POST['patropi_faq_save'] ) && check_admin_referer( 'patropi_faq_settings' ) ) {
            $new_settings = array(
                'faq_enabled' => ! empty( $_POST['faq_enabled'] ) ? 1 : 0,
                'faq_open_first' => ! empty( $_POST['faq_open_first'] ) ? 1 : 0,
                'faq_close_others' => ! empty( $_POST['faq_close_others'] ) ? 1 : 0,
                'faq_icon_rotation' => ! empty( $_POST['faq_icon_rotation'] ) ? 1 : 0,
                'faq_icon_closed' => isset( $_POST['faq_icon_closed'] ) ? $_POST['faq_icon_closed'] : 'dashicons-arrow-down',
                'faq_icon_open' => isset( $_POST['faq_icon_open'] ) ? $_POST['faq_icon_open'] : 'dashicons-arrow-up'
            );
            $settings->update_settings( $new_settings );
            
            update_option( 'patropi_addon_flush_needed', true );
            
            $current_settings = $new_settings;
        } else {
            $current_settings = $settings->get_settings();
        }

        $this->render_layout_start();
        $this->render_header( 'faq' );
        
        if ( isset( $_POST['patropi_faq_css_save'] ) && check_admin_referer( 'patropi_faq_css_save', 'patropi_faq_css_nonce' ) ) {
            $css_value = isset( $_POST['faq_custom_css'] ) ? $_POST['faq_custom_css'] : '';
            $settings->update_settings( array( 'faq_custom_css' => $css_value ) );
            echo '<div class="notice notice-success is-dismissible"><p>CSS salvo com sucesso!</p></div>';
        } elseif ( isset( $_POST['patropi_faq_css_reset'] ) && check_admin_referer( 'patropi_faq_css_save', 'patropi_faq_css_nonce' ) ) {
            $settings->reset_faq_css();
            echo '<div class="notice notice-success is-dismissible"><p>CSS resetado para o padrão!</p></div>';
        }
        
        $current_settings = $settings->get_settings();
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
                    $icon_options = $settings->get_icon_options();
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
                    <textarea name="faq_custom_css" rows="20" class="large-text code" style="font-family: monospace; font-size: 12px; width: 100%; box-sizing: border-box; margin-bottom: 10px;"><?php echo esc_textarea( $settings->get_faq_custom_css() ); ?></textarea>
                    <div class="patropi-css-buttons">
                        <input type="submit" name="patropi_faq_css_save" class="button button-primary" value="<?php _e( 'Salvar CSS', 'patropi-addon' ); ?>">
                        <input type="submit" name="patropi_faq_css_reset" class="button button-secondary" value="<?php _e( 'Resetar para Padrão', 'patropi-addon' ); ?>" onclick="return confirm('<?php _e( 'Tem certeza que deseja resetar o CSS para o padrão?', 'patropi-addon' ); ?>');">
                    </div>
                </form>
            </div>
        </div>
        <?php
        echo '</div>';
    }

    public function render_atualizacoes() {
        $changelog = array(
            '0.2.0' => array(
                'Novo módulo: Mega Menu',
                'Shortcode [patropi-mega-menu] para exibir o menu',
                'Interface gráfica no admin para criar o menu (builder)',
                'Menu principal com estilos customizáveis (cores, fundo, borda)',
                'Mega menu com configurações de largura e largura máxima',
                'Trigger: hover (ao passar mouse) ou click (ao clicar)',
                'Itens do menu: podem ter mega menu ou link simples',
                'Cada mega menu pode ter 1-6 colunas configuráveis',
                'Cada coluna: largura %, título opcional, layout (links ou imagem)',
                'Layout de links: adicionar páginas ou links customizados (máx 10)',
                'Layout de imagem: ícone, imagem, título, descrição e link'
            ),
            '0.1.2' => array(
                'Sistema de ícones com rotação de 180º',
                'Toggle para ativar/desativar rotação',
                'Se rotação ativada: usa um ícone que gira',
                'Se rotação desativada: permite selecionar dois ícones diferentes (aberto/fechado)',
                'Ajuste visual no admin: seções de ícones aparecem/escondem em tempo real ao toggle',
                'Correção de bug: ícones não alternavam corretamente quando rotação estava desligada',
                'Lista de ícones disponíveis: Insert, Remove, Arrow Up, Arrow Down, Arrow Up (Alt2), Arrow Down (Alt2), Plus (Alt2), Minus'
            ),
            '0.1.1' => array(
                'Criação do plugin Patropi Add-ons',
                'Criação do módulo FAQ com:',
                '- Custom Post Type FAQ',
                '- Campo de seleção múltipla de páginas',
                '- Shortcode [faq-patropi]',
                '- Accordion com opções personalizáveis',
                '- Sistema de CSS customizável com editor e reset',
                '- Página de configurações com toggle para ativar/desativar'
            ),
            '0.1.0' => array(
                'Versão inicial do plugin'
            )
        );

        $this->render_layout_start();
        $this->render_header( 'atualizacoes' );
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
        <?php
        echo '</div>';
    }

    public function render_mega_menu() {
        require_once PATROPI_ADDON_PLUGIN_DIR . 'includes/class-patropi-mega-menu.php';
        $mega_menu = new Patropi_Mega_Menu();

        if ( isset( $_POST['patropi_mega_menu_save'] ) && check_admin_referer( 'patropi_mega_menu_save' ) ) {
            $new_settings = array(
                'enabled' => ! empty( $_POST['mega_enabled'] ) ? 1 : 0
            );

            if ( isset( $_POST['main_menu'] ) && is_array( $_POST['main_menu'] ) ) {
                $new_settings['main_menu'] = $_POST['main_menu'];
                
                $transparent_fields = array(
                    'text_color_transparent',
                    'text_hover_transparent',
                    'bg_color_transparent',
                    'bg_hover_transparent',
                    'border_color_transparent',
                    'border_hover_transparent'
                );
                
                foreach ( $transparent_fields as $field ) {
                    if ( ! isset( $_POST['main_menu'][ $field ] ) ) {
                        $new_settings['main_menu'][ $field ] = 0;
                    }
                }
            }

            if ( isset( $_POST['mega_menu'] ) && is_array( $_POST['mega_menu'] ) ) {
                $new_settings['mega_menu'] = $_POST['mega_menu'];
                
                if ( ! isset( $_POST['mega_menu']['dropdown_bg_transparent'] ) ) {
                    $new_settings['mega_menu']['dropdown_bg_transparent'] = 0;
                }
                if ( ! isset( $_POST['mega_menu']['shadow'] ) ) {
                    $new_settings['mega_menu']['shadow'] = 0;
                }
            }

            if ( isset( $_POST['menu_items'] ) && is_array( $_POST['menu_items'] ) ) {
                $new_settings['menu_items'] = $_POST['menu_items'];
            }

            $mega_menu->update_settings( $new_settings );
            echo '<div class="notice notice-success is-dismissible"><p>Configurações salvas com sucesso!</p></div>';
        }

        $settings = $mega_menu->get_settings();
        $pages = get_pages( array( 'post_status' => 'publish' ) );

        $this->render_layout_start();
        $this->render_header( 'mega-menu' );
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
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="mega_menu[dropdown_bg]" value="<?php echo esc_attr( $settings['mega_menu']['dropdown_bg'] ?? '#ffffff' ); ?>" class="form-control form-control-color" style="width: 50px; height: 40px; padding: 2px;">
                                <label class="patropi-toggle" style="margin-top: 0;">
                                    <input type="checkbox" name="mega_menu[dropdown_bg_transparent]" value="1" <?php checked( ! empty( $settings['mega_menu']['dropdown_bg_transparent'] ) ); ?>>
                                    <span class="patropi-toggle-switch"></span>
                                    <span class="patropi-toggle-label">Transparente</span>
                                </label>
                            </div>
                        </div>
                    </div>
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
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <label><strong><?php _e( 'Texto do item', 'patropi-addon' ); ?></strong></label>
                                            <input type="text" name="menu_items[<?php echo $item_index; ?>][text]" value="<?php echo esc_attr( $item['text'] ?? '' ); ?>" class="form-control" placeholder="Nome do item">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label><strong><?php _e( 'Tem mega menu?', 'patropi-addon' ); ?></strong></label>
                                            <label class="patropi-toggle" style="margin-top: 5px;">
                                                <input type="checkbox" name="menu_items[<?php echo $item_index; ?>][has_mega]" value="1" <?php checked( $item['has_mega'] ?? false, true ); ?> class="patropi-has-mega-toggle">
                                                <span class="patropi-toggle-switch"></span>
                                                <span class="patropi-toggle-label"><?php _e( 'Ativar mega menu', 'patropi-addon' ); ?></span>
                                            </label>
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
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <span><?php _e( 'Coluna', 'patropi-addon' ); ?> <?php echo $col_index + 1; ?></span>
                                                        <button type="button" class="btn btn-danger btn-sm patropi-remove-column"><?php _e( 'Remover', 'patropi-addon' ); ?></button>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-4 col-md-4">
                                                                <label><strong><?php _e( 'Largura (%)', 'patropi-addon' ); ?></strong></label>
                                                                <input type="number" name="menu_items[<?php echo $item_index; ?>][columns][<?php echo $col_index; ?>][width]" value="<?php echo esc_attr( $col['width'] ?? '25' ); ?>" class="form-control" min="1" max="100">
                                                            </div>
                                                            <div class="col-4 col-md-4">
                                                                <label><strong><?php _e( 'Tem título?', 'patropi-addon' ); ?></strong></label>
                                                                <label class="patropi-toggle" style="margin-top: 5px;">
                                                                    <input type="checkbox" name="menu_items[<?php echo $item_index; ?>][columns][<?php echo $col_index; ?>][has_title]" value="1" <?php checked( $col['has_title'] ?? false, true ); ?> class="patropi-has-title-toggle">
                                                                    <span class="patropi-toggle-switch"></span>
                                                                    <span class="patropi-toggle-label"><?php _e( 'Mostrar', 'patropi-addon' ); ?></span>
                                                                </label>
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
        <?php
        echo '</div>';
    }
}