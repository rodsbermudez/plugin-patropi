<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Patropi_Mega_Menu {
    private $option_name = 'patropi_mega_menu_settings';
    private $defaults = array();

    public function __construct() {
        $this->defaults = $this->get_default_settings();
        add_shortcode( 'patropi-mega-menu', array( $this, 'render_shortcode' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
    }

    private function get_default_settings() {
        return array(
            'enabled' => false,
            'main_menu' => array(
                'text_color' => '#333333',
                'text_hover' => '#2c3e50',
                'bg_color' => 'transparent',
                'bg_hover' => 'transparent',
                'border_width' => '0px',
                'border_color' => 'transparent',
                'border_hover_color' => 'transparent'
            ),
            'mega_menu' => array(
                'width' => '100%',
                'max_width' => '1200px',
                'trigger' => 'hover'
            ),
            'menu_items' => array()
        );
    }

    public function get_settings() {
        $settings = get_option( $this->option_name, array() );
        if ( ! is_array( $settings ) ) {
            $settings = array();
        }

        foreach ( $this->defaults as $key => $default_value ) {
            if ( ! array_key_exists( $key, $settings ) ) {
                $settings[ $key ] = $default_value;
            } elseif ( is_array( $default_value ) && is_array( $settings[ $key ] ) ) {
                foreach ( $default_value as $sub_key => $sub_value ) {
                    if ( ! array_key_exists( $sub_key, $settings[ $key ] ) ) {
                        $settings[ $key ][ $sub_key ] = $sub_value;
                    }
                }
            }
        }

        return $settings;
    }

    public function get_setting( $key, $default = null ) {
        $settings = $this->get_settings();
        return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
    }

    public function is_enabled() {
        return (bool) $this->get_setting( 'enabled', false );
    }

    public function update_settings( $new_settings ) {
        $current = $this->get_settings();

        if ( isset( $new_settings['enabled'] ) ) {
            $current['enabled'] = (bool) $new_settings['enabled'];
        }

        if ( isset( $new_settings['main_menu'] ) && is_array( $new_settings['main_menu'] ) ) {
            foreach ( $new_settings['main_menu'] as $key => $value ) {
                $current['main_menu'][ $key ] = sanitize_text_field( $value );
            }
        }

        if ( isset( $new_settings['mega_menu'] ) && is_array( $new_settings['mega_menu'] ) ) {
            foreach ( $new_settings['mega_menu'] as $key => $value ) {
                $current['mega_menu'][ $key ] = sanitize_text_field( $value );
            }
        }

        if ( isset( $new_settings['menu_items'] ) && is_array( $new_settings['menu_items'] ) ) {
            $current['menu_items'] = $this->sanitize_menu_items( $new_settings['menu_items'] );
        }

        return update_option( $this->option_name, $current );
    }

    private function sanitize_menu_items( $items ) {
        $sanitized = array();

        foreach ( $items as $item ) {
            $sanitized_item = array(
                'id' => isset( $item['id'] ) ? sanitize_text_field( $item['id'] ) : uniqid( 'menu_item_' ),
                'text' => isset( $item['text'] ) ? sanitize_text_field( $item['text'] ) : '',
                'has_mega' => ! empty( $item['has_mega'] ),
                'link_type' => isset( $item['link_type'] ) ? sanitize_text_field( $item['link_type'] ) : 'page',
                'link_page_id' => isset( $item['link_page_id'] ) ? absint( $item['link_page_id'] ) : 0,
                'link_url' => isset( $item['link_url'] ) ? esc_url_raw( $item['link_url'] ) : '',
                'columns' => array()
            );

            if ( $sanitized_item['has_mega'] && isset( $item['columns'] ) && is_array( $item['columns'] ) ) {
                foreach ( $item['columns'] as $col ) {
                    $sanitized_col = array(
                        'width' => isset( $col['width'] ) ? sanitize_text_field( $col['width'] ) : '25%',
                        'has_title' => ! empty( $col['has_title'] ),
                        'title' => isset( $col['title'] ) ? sanitize_text_field( $col['title'] ) : '',
                        'layout' => isset( $col['layout'] ) ? sanitize_text_field( $col['layout'] ) : 'links',
                        'links' => array(),
                        'image_data' => array()
                    );

                    if ( $sanitized_col['layout'] === 'links' && isset( $col['links'] ) && is_array( $col['links'] ) ) {
                        foreach ( $col['links'] as $link ) {
                            $sanitized_col['links'][] = array(
                                'type' => isset( $link['type'] ) ? sanitize_text_field( $link['type'] ) : 'page',
                                'page_id' => isset( $link['page_id'] ) ? absint( $link['page_id'] ) : 0,
                                'text' => isset( $link['text'] ) ? sanitize_text_field( $link['text'] ) : '',
                                'url' => isset( $link['url'] ) ? esc_url_raw( $link['url'] ) : ''
                            );
                        }
                    }

                    if ( $sanitized_col['layout'] === 'image' && isset( $col['image_data'] ) && is_array( $col['image_data'] ) ) {
                        $sanitized_col['image_data'] = array(
                            'icon' => isset( $col['image_data']['icon'] ) ? sanitize_text_field( $col['image_data']['icon'] ) : '',
                            'title' => isset( $col['image_data']['title'] ) ? sanitize_text_field( $col['image_data']['title'] ) : '',
                            'description' => isset( $col['image_data']['description'] ) ? sanitize_textarea_field( $col['image_data']['description'] ) : '',
                            'image_url' => isset( $col['image_data']['image_url'] ) ? esc_url_raw( $col['image_data']['image_url'] ) : '',
                            'link_url' => isset( $col['image_data']['link_url'] ) ? esc_url_raw( $col['image_data']['link_url'] ) : ''
                        );
                    }

                    $sanitized_item['columns'][] = $sanitized_col;
                }
            }

            $sanitized[] = $sanitized_item;
        }

        return $sanitized;
    }

    public function enqueue_assets() {
        if ( ! $this->is_enabled() ) {
            return;
        }

        global $post;
        if ( ! is_a( $post, 'WP_Post' ) ) {
            return;
        }

        $settings = $this->get_settings();
        $has_mega_items = false;

        if ( ! empty( $settings['menu_items'] ) ) {
            foreach ( $settings['menu_items'] as $item ) {
                if ( ! empty( $item['has_mega'] ) ) {
                    $has_mega_items = true;
                    break;
                }
            }
        }

        if ( ! $has_mega_items ) {
            return;
        }

        wp_enqueue_style( 'dashicons' );
        wp_enqueue_style(
            'patropi-mega-menu',
            PATROPI_ADDON_PLUGIN_URL . 'assets/css/mega-menu.css',
            array( 'dashicons' ),
            PATROPI_ADDON_VERSION
        );

        wp_enqueue_script(
            'patropi-mega-menu',
            PATROPI_ADDON_PLUGIN_URL . 'assets/js/mega-menu.js',
            array( 'jquery' ),
            PATROPI_ADDON_VERSION,
            true
        );

        wp_localize_script( 'patropi-mega-menu', 'patropiMegaMenuSettings', array(
            'trigger' => $settings['mega_menu']['trigger'] ?? 'hover',
            'animation' => $settings['mega_menu']['animation'] ?? 'fade'
        ) );
    }

    public function render_shortcode() {
        if ( ! $this->is_enabled() ) {
            return '';
        }

        $settings = $this->get_settings();
        $menu_items = $settings['menu_items'] ?? array();

        if ( empty( $menu_items ) ) {
            return '';
        }

        $main_menu = $settings['main_menu'] ?? array();
        $text_color = $main_menu['text_color'] ?? '#333333';
        $text_hover = $main_menu['text_hover'] ?? '#2c3e50';
        $bg_color = $main_menu['bg_color'] ?? 'transparent';
        $bg_hover = $main_menu['bg_hover'] ?? 'transparent';
        $border_width = $main_menu['border_width'] ?? '0px';
        $border_color = $main_menu['border_color'] ?? 'transparent';
        $border_hover = $main_menu['border_hover_color'] ?? '#2c3e50';

        ob_start();
        ?>
        <style>
            .patropi-mega-menu-wrapper {
                --mm-text-color: <?php echo esc_attr( $text_color ); ?>;
                --mm-text-color-hover: <?php echo esc_attr( $text_hover ); ?>;
                --mm-bg-color: <?php echo esc_attr( $bg_color ); ?>;
                --mm-bg-hover: <?php echo esc_attr( $bg_hover ); ?>;
                --mm-border-width: <?php echo esc_attr( $border_width ); ?>;
                --mm-border-color: <?php echo esc_attr( $border_color ); ?>;
                --mm-border-hover: <?php echo esc_attr( $border_hover ); ?>;
            }
        </style>
        <div class="patropi-mega-menu-wrapper">
            <nav class="patropi-mega-menu-main">
                <ul class="patropi-mega-menu-list">
                    <?php foreach ( $menu_items as $item ) : ?>
                        <?php 
                        $item_classes = 'patropi-mega-menu-item';
                        if ( ! empty( $item['has_mega'] ) ) {
                            $item_classes .= ' has-mega-menu';
                        }
                        
                        $link = '#';
                        if ( empty( $item['has_mega'] ) ) {
                            if ( $item['link_type'] === 'page' && ! empty( $item['link_page_id'] ) ) {
                                $link = get_permalink( $item['link_page_id'] );
                            } elseif ( $item['link_type'] === 'custom' && ! empty( $item['link_url'] ) ) {
                                $link = $item['link_url'];
                            }
                        }
                        ?>
                        <li class="<?php echo esc_attr( $item_classes ); ?>">
                            <a href="<?php echo esc_url( $link ); ?>" class="patropi-mega-menu-link">
                                <?php echo esc_html( $item['text'] ); ?>
                            </a>
                            
                            <?php if ( ! empty( $item['has_mega'] ) && ! empty( $item['columns'] ) ) : ?>
                                <div class="patropi-mega-menu-dropdown">
                                    <div class="patropi-mega-menu-dropdown-inner">
                                        <div class="patropi-mega-menu-columns" style="display: flex;">
                                            <?php foreach ( $item['columns'] as $col_index => $col ) : ?>
                                                <div class="patropi-mega-menu-col" style="width: <?php echo esc_attr( $col['width'] ); ?>%;">
                                                    <?php if ( ! empty( $col['has_title'] ) && ! empty( $col['title'] ) ) : ?>
                                                        <div class="patropi-mega-menu-col-title">
                                                            <?php echo esc_html( $col['title'] ); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ( $col['layout'] === 'links' && ! empty( $col['links'] ) ) : ?>
                                                        <ul class="patropi-mega-menu-links">
                                                            <?php foreach ( $col['links'] as $link_item ) : 
                                                                $link_href = '#';
                                                                $link_text = '';
                                                                
                                                                if ( $link_item['type'] === 'page' && ! empty( $link_item['page_id'] ) ) {
                                                                    $link_href = get_permalink( $link_item['page_id'] );
                                                                    $link_text = get_the_title( $link_item['page_id'] );
                                                                } elseif ( $link_item['type'] === 'custom' ) {
                                                                    $link_href = $link_item['url'];
                                                                    $link_text = $link_item['text'];
                                                                }
                                                                
                                                                if ( $link_text ) :
                                                                ?>
                                                                <li>
                                                                    <a href="<?php echo esc_url( $link_href ); ?>">
                                                                        <?php echo esc_html( $link_text ); ?>
                                                                    </a>
                                                                </li>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php elseif ( $col['layout'] === 'image' && ! empty( $col['image_data'] ) ) : ?>
                                                        <div class="patropi-mega-menu-image-layout">
                                                            <?php if ( ! empty( $col['image_data']['image_url'] ) ) : ?>
                                                                <a href="<?php echo esc_url( $col['image_data']['link_url'] ); ?>" class="patropi-mega-menu-image-link">
                                                                    <img src="<?php echo esc_url( $col['image_data']['image_url'] ); ?>" alt="<?php echo esc_attr( $col['image_data']['title'] ); ?>" class="patropi-mega-menu-image">
                                                                </a>
                                                            <?php endif; ?>
                                                            
                                                            <?php if ( ! empty( $col['image_data']['icon'] ) ) : ?>
                                                                <span class="dashicons <?php echo esc_attr( $col['image_data']['icon'] ); ?> patropi-mega-menu-icon"></span>
                                                            <?php endif; ?>
                                                            
                                                            <?php if ( ! empty( $col['image_data']['title'] ) ) : ?>
                                                                <div class="patropi-mega-menu-image-title">
                                                                    <?php echo esc_html( $col['image_data']['title'] ); ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            
                                                            <?php if ( ! empty( $col['image_data']['description'] ) ) : ?>
                                                                <div class="patropi-mega-menu-image-desc">
                                                                    <?php echo esc_html( $col['image_data']['description'] ); ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
        <?php
        return ob_get_clean();
    }
}

new Patropi_Mega_Menu();