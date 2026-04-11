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

        wp_enqueue_style(
            'patropi-bootstrap',
            'https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/flatly/bootstrap.min.css',
            array(),
            '5.3.2'
        );

        wp_enqueue_style(
            'patropi-admin',
            PATROPI_ADDON_PLUGIN_URL . 'assets/css/admin.css',
            array( 'patropi-bootstrap' ),
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
            'patropi-addon',
            array( $this, 'render_dashboard' ),
            PATROPI_ADDON_PLUGIN_URL . 'assets/images/patropi-favicon.png',
            3
        );

        add_submenu_page(
            'patropi-addon',
            __( 'Dashboard', 'patropi-addon' ),
            __( 'Dashboard', 'patropi-addon' ),
            'manage_options',
            'patropi-addon',
            array( $this, 'render_dashboard' )
        );

        add_submenu_page(
            'patropi-addon',
            __( 'FAQ', 'patropi-addon' ),
            __( 'FAQ', 'patropi-addon' ),
            'manage_options',
            'patropi-faq',
            array( $this, 'render_faq' )
        );

        add_submenu_page(
            'patropi-addon',
            __( 'Atualizações', 'patropi-addon' ),
            __( 'Atualizações', 'patropi-addon' ),
            'manage_options',
            'patropi-atualizacoes',
            array( $this, 'render_atualizacoes' )
        );

        remove_submenu_page( 'patropi-addon', 'patropi-addon' );
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
                    <a class="nav-link <?php echo $active_tab === 'dashboard' ? 'active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=patropi-addon' ); ?>">
                        <?php _e( 'Dashboard', 'patropi-addon' ); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $active_tab === 'faq' ? 'active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=patropi-faq' ); ?>">
                        <?php _e( 'FAQ', 'patropi-addon' ); ?>
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
                'faq_close_others' => ! empty( $_POST['faq_close_others'] ) ? 1 : 0
            );
            $settings->update_settings( $new_settings );
            
            update_option( 'patropi_addon_flush_needed', true );
            
            $current_settings = $new_settings;
        } else {
            $current_settings = $settings->get_settings();
        }

        $this->render_layout_start();
        $this->render_header( 'faq' );
        
        if ( isset( $_POST['patropi_faq_save'] ) && check_admin_referer( 'patropi_faq_settings' ) ) {
            $new_settings = array(
                'faq_enabled' => ! empty( $_POST['faq_enabled'] ) ? 1 : 0,
                'faq_open_first' => ! empty( $_POST['faq_open_first'] ) ? 1 : 0,
                'faq_close_others' => ! empty( $_POST['faq_close_others'] ) ? 1 : 0
            );
            $settings->update_settings( $new_settings );
            
            if ( $new_settings['faq_enabled'] ) {
                update_option( 'patropi_addon_flush_needed', true );
            }
            
            echo '<div class="notice notice-success is-dismissible"><p>Configurações salvas com sucesso!</p></div>';
        }
        
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
}