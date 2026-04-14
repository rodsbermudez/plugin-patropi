<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Patropi_Addon_Admin {
    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_init', array( $this, 'process_forms' ) );
    }

    public function process_forms() {
        if ( ! is_admin() || empty( $_POST ) ) {
            return;
        }

        // Process FAQ Save
        if ( isset( $_POST['patropi_faq_save'] ) && check_admin_referer( 'patropi_faq_settings' ) ) {
            $new_settings = array(
                'faq_enabled' => ! empty( $_POST['faq_enabled'] ) ? 1 : 0,
                'faq_open_first' => ! empty( $_POST['faq_open_first'] ) ? 1 : 0,
                'faq_close_others' => ! empty( $_POST['faq_close_others'] ) ? 1 : 0,
                'faq_icon_rotation' => ! empty( $_POST['faq_icon_rotation'] ) ? 1 : 0,
                'faq_icon_closed' => isset( $_POST['faq_icon_closed'] ) ? sanitize_text_field( $_POST['faq_icon_closed'] ) : '',
                'faq_icon_open' => isset( $_POST['faq_icon_open'] ) ? sanitize_text_field( $_POST['faq_icon_open'] ) : ''
            );
            
            $settings_obj = Patropi_Addon_Settings::get_instance();
            $settings_obj->update_settings( $new_settings );
            
            if ( $new_settings['faq_enabled'] ) {
                update_option( 'patropi_addon_flush_needed', true );
            }
            
            wp_safe_redirect( admin_url( 'admin.php?page=patropi-faq&updated=true' ) );
            exit;
        }

        // Process FAQ CSS Save
        if ( isset( $_POST['patropi_faq_css_save'] ) && check_admin_referer( 'patropi_faq_css_save', 'patropi_faq_css_nonce' ) ) {
            if ( isset( $_POST['faq_custom_css'] ) ) {
                $css = wp_strip_all_tags( wp_unslash( $_POST['faq_custom_css'] ) );
                $settings_obj = Patropi_Addon_Settings::get_instance();
                $settings_obj->update_settings( array( 'faq_custom_css' => $css ) );
            }
            wp_safe_redirect( admin_url( 'admin.php?page=patropi-faq&updated=true' ) );
            exit;
        }

        // Process FAQ CSS Reset
        if ( isset( $_POST['patropi_faq_css_reset'] ) && check_admin_referer( 'patropi_faq_css_save', 'patropi_faq_css_nonce' ) ) {
            $settings_obj = Patropi_Addon_Settings::get_instance();
            $settings_obj->reset_faq_css();
            
            wp_safe_redirect( admin_url( 'admin.php?page=patropi-faq&updated=true' ) );
            exit;
        }

        // Process Mega Menu Save
        if ( isset( $_POST['patropi_mega_menu_save'] ) && check_admin_referer( 'patropi_mega_menu_save' ) ) {
            $new_settings = array(
                'enabled' => ! empty( $_POST['mega_enabled'] ) ? 1 : 0
            );

            if ( isset( $_POST['main_menu'] ) && is_array( $_POST['main_menu'] ) ) {
                $new_settings['main_menu'] = array_map( 'sanitize_text_field', wp_unslash( $_POST['main_menu'] ) );
            }

            if ( isset( $_POST['mega_menu'] ) && is_array( $_POST['mega_menu'] ) ) {
                $new_settings['mega_menu'] = array_map( 'sanitize_text_field', wp_unslash( $_POST['mega_menu'] ) );
            }

            if ( isset( $_POST['menu_items'] ) && is_array( $_POST['menu_items'] ) ) {
                $menu_items = wp_unslash( $_POST['menu_items'] );
                $new_settings['menu_items'] = patropi_sanitize_array( $menu_items );
            } else {
                $new_settings['menu_items'] = array();
            }

            update_option( 'patropi_mega_menu_settings', $new_settings );
            
            wp_safe_redirect( admin_url( 'admin.php?page=patropi-mega-menu&updated=true' ) );
            exit;
        }
    }

    public function add_menu_page() {
        add_menu_page(
            __( 'Patropi Add-ons', 'patropi-addon' ),
            __( 'Patropi Add-ons', 'patropi-addon' ),
            'manage_options',
            'patropi-addon',
            array( $this, 'render_dashboard' ),
            PATROPI_ADDON_PLUGIN_URL . 'assets/images/patropi-favicon.png',
            99
        );

        add_submenu_page(
            'patropi-addon',
            __( 'Módulo FAQ', 'patropi-addon' ),
            __( 'FAQ', 'patropi-addon' ),
            'manage_options',
            'patropi-faq',
            array( $this, 'render_faq' )
        );

        // Mega Menu submenu page
        add_submenu_page(
            'patropi-addon',
            __( 'Mega Menu', 'patropi-addon' ),
            __( 'Mega Menu', 'patropi-addon' ),
            'manage_options',
            'patropi-mega-menu',
            array( $this, 'render_mega_menu' )
        );

        add_submenu_page(
            'patropi-addon',
            __( 'Atualizações', 'patropi-addon' ),
            __( 'Atualizações', 'patropi-addon' ),
            'manage_options',
            'patropi-atualizacoes',
            array( $this, 'render_atualizacoes' )
        );
    }

    public function enqueue_styles( $hook ) {
        if ( strpos( $hook, 'patropi' ) !== false ) {
            wp_enqueue_style( 'patropi-admin-bootstrap', 'https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/flatly/bootstrap.min.css', array(), '5.3.0' );
            wp_enqueue_style( 'patropi-admin-style', PATROPI_ADDON_PLUGIN_URL . 'assets/css/admin.css', array(), PATROPI_ADDON_VERSION );
            
            // Mega menu custom styles
            $custom_css = "
                .patropi-wrap {
                    max-width: 1200px;
                    margin: 20px auto;
                }
                .patropi-header {
                    background: #fff;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                    margin-bottom: 20px;
                }
                .patropi-branding {
                    display: flex;
                    align-items: center;
                    gap: 15px;
                    margin-bottom: 20px;
                }
                .patropi-branding img {
                    height: 40px;
                }
                .patropi-branding h2 {
                    margin: 0;
                    color: #2c3e50;
                    font-size: 24px;
                }
                .patropi-tabs-wrap {
                    margin-bottom: -20px;
                }
                .patropi-tab {
                    display: inline-block;
                    padding: 10px 20px;
                    text-decoration: none;
                    color: #555;
                    border-bottom: 3px solid transparent;
                    transition: all 0.3s ease;
                }
                .patropi-tab:focus {
                    box-shadow: none;
                }
                .patropi-tab:hover {
                    color: #3498db;
                }
                .patropi-tab.nav-tab-active {
                    color: #3498db;
                    border-bottom-color: #3498db;
                    font-weight: 600;
                }
                .patropi-content {
                    background: #fff;
                    padding: 30px;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                }
                .patropi-title {
                    margin-top: 0;
                    margin-bottom: 20px;
                    color: #2c3e50;
                    font-size: 22px;
                }
                .patropi-card {
                    background: #f8f9fa;
                    border: 1px solid #e9ecef;
                    border-radius: 6px;
                    padding: 20px;
                    margin-bottom: 20px;
                }
                .patropi-card-title {
                    margin-top: 0;
                    margin-bottom: 15px;
                    font-size: 16px;
                    color: #2c3e50;
                    padding-bottom: 10px;
                    border-bottom: 1px solid #e9ecef;
                }
                .patropi-toggle {
                    position: relative;
                    display: inline-flex;
                    align-items: center;
                    cursor: pointer;
                }
                .patropi-toggle input {
                    opacity: 0;
                    width: 0;
                    height: 0;
                    position: absolute;
                }
                .patropi-toggle-switch {
                    position: relative;
                    width: 44px;
                    height: 24px;
                    background-color: #ccc;
                    border-radius: 24px;
                    transition: .4s;
                    margin-right: 10px;
                }
                .patropi-toggle-switch:before {
                    position: absolute;
                    content: '';
                    height: 18px;
                    width: 18px;
                    left: 3px;
                    bottom: 3px;
                    background-color: white;
                    border-radius: 50%;
                    transition: .4s;
                }
                .patropi-toggle input:checked + .patropi-toggle-switch {
                    background-color: #3498db;
                }
                .patropi-toggle input:checked + .patropi-toggle-switch:before {
                    transform: translateX(20px);
                }
                .patropi-toggle-label {
                    font-weight: 500;
                    color: #555;
                }
            ";
            wp_add_inline_style( 'patropi-admin-style', $custom_css );
        }
    }

    public function enqueue_scripts( $hook ) {
        if ( strpos( $hook, 'patropi' ) !== false ) {
            wp_enqueue_script( 'patropi-admin-script', PATROPI_ADDON_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery', 'jquery-ui-sortable' ), PATROPI_ADDON_VERSION, true );
        }
    }

    private function render_layout_start() {
        echo '<div class="wrap patropi-wrap">';
        $this->render_header();
    }

    private function render_header() {
        $current_page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : 'patropi-addon';
        
        $tabs = array(
            'patropi-addon' => __( 'Dashboard', 'patropi-addon' ),
            'patropi-faq' => __( 'Módulo FAQ', 'patropi-addon' ),
            'patropi-mega-menu' => __( 'Mega Menu', 'patropi-addon' ),
            'patropi-atualizacoes' => __( 'Atualizações', 'patropi-addon' )
        );
        ?>
        <div class="patropi-header">
            <div class="patropi-branding">
                <img src="<?php echo esc_url( PATROPI_ADDON_PLUGIN_URL . 'assets/images/patropi-logo.png' ); ?>" alt="Patropi Logo" class="patropi-logo">
            </div>
            <div class="patropi-nav-wrap">
                <ul class="nav nav-pills" style="gap: 10px;">
                    <?php foreach ( $tabs as $id => $label ) : ?>
                        <li class="nav-item">
                            <a href="<?php echo admin_url( 'admin.php?page=' . $id ); ?>" class="nav-link <?php echo $current_page === $id ? 'active' : ''; ?>">
                                <?php echo esc_html( $label ); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php
    }

    private function render_layout_end() {
        echo '</div><!-- .patropi-wrap -->';
    }

    public function render_dashboard() {
        $settings_obj = Patropi_Addon_Settings::get_instance();
        $faq_enabled = $settings_obj->is_faq_enabled();
        
        $mega_settings = get_option( 'patropi_mega_menu_settings', array() );
        $mega_enabled = ! empty( $mega_settings['enabled'] );
        $menu_item_count = ! empty( $mega_settings['menu_items'] ) ? count( $mega_settings['menu_items'] ) : 0;
        
        // Count FAQs
        $faq_count_obj = wp_count_posts( 'faq-patropi' );
        $faq_count = isset( $faq_count_obj->publish ) ? $faq_count_obj->publish : 0;
        
        $this->render_layout_start();
        include PATROPI_ADDON_PLUGIN_DIR . 'templates/admin-dashboard.php';
        $this->render_layout_end();
    }

    public function render_faq() {
        $settings_obj = Patropi_Addon_Settings::get_instance();
        $current_settings = $settings_obj->get_settings();
        $settings = $settings_obj;
        $faq_custom_css = $settings_obj->get_faq_custom_css();
        $icon_options = $settings_obj->get_icon_options();

        $this->render_layout_start();
        include PATROPI_ADDON_PLUGIN_DIR . 'templates/admin-faq.php';
        $this->render_layout_end();
    }

    public function render_mega_menu() {
        $settings = get_option( 'patropi_mega_menu_settings', array() );
        
        if ( ! is_array( $settings ) ) {
            $settings = array();
        }
        
        $settings = wp_parse_args( $settings, array(
            'enabled' => false,
            'main_menu' => array(
                'text_color' => '#333333',
                'text_hover' => '#2c3e50',
                'bg_color' => '#ffffff',
                'bg_hover' => '#f8f9fa',
                'border_width' => '0px',
                'border_color' => '#eeeeee',
                'border_hover_color' => '#dddddd',
                'align' => 'flex-start',
                'item_gap' => '0px',
                'item_padding_y' => '15px',
                'item_padding_x' => '20px',
            ),
            'mega_menu' => array(
                'width' => '100%',
                'max_width' => '1200px',
                'trigger' => 'hover',
                'animation' => 'fade',
                'padding_y' => '20px',
                'padding_x' => '20px',
                'shadow' => true,
                'dropdown_bg' => '#ffffff',
                'mobile_icon_color' => '#333333',
                'mobile_bg_color' => '#ffffff',
                'mobile_width' => '85',
            ),
            'menu_items' => array()
        ));

        // Get all pages for link selection
        $pages = get_pages( array(
            'sort_column' => 'post_title',
            'sort_order' => 'ASC'
        ));

        $this->render_layout_start();
        include PATROPI_ADDON_PLUGIN_DIR . 'templates/admin-mega-menu.php';
        $this->render_layout_end();
    }

    public function render_atualizacoes() {
        $changelog = array();
        $readme_path = PATROPI_ADDON_PLUGIN_DIR . 'README.txt';
        
        if ( file_exists( $readme_path ) ) {
            $readme_content = file_get_contents( $readme_path );
            $parts = explode( '== Changelog ==', $readme_content );
            
            if ( isset( $parts[1] ) ) {
                $lines = explode( "\n", trim( $parts[1] ) );
                $current_version = '';
                
                foreach ( $lines as $line ) {
                    $line = trim( $line );
                    if ( empty( $line ) ) continue;
                    
                    if ( preg_match( '/^=\s*([^=]+)\s*=$/', $line, $matches ) ) {
                        $current_version = trim( $matches[1] );
                        $changelog[ $current_version ] = array();
                    } elseif ( ! empty( $current_version ) ) {
                        $line = ltrim( $line, '* -' );
                        $changelog[ $current_version ][] = trim( $line );
                    }
                }
            }
        }

        $this->render_layout_start();
        include PATROPI_ADDON_PLUGIN_DIR . 'templates/admin-atualizacoes.php';
        $this->render_layout_end();
    }
}

// Extra function to handle recursive sanitization specifically used in process_forms
if(!function_exists('patropi_sanitize_array')) {
    function patropi_sanitize_array($array) {
        if (!is_array($array)) return sanitize_text_field($array);
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = patropi_sanitize_array($value);
            } else {
                $array[$key] = sanitize_text_field($value);
            }
        }
        return $array;
    }
}