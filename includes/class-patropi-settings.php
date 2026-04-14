<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Patropi_Addon_Settings {
    private static $instance = null;
    private $option_name = 'patropi_addon_settings';
    
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {}

    private $defaults = array(
        'faq_enabled' => false,
        'faq_open_first' => false,
        'faq_close_others' => true,
        'faq_icon_rotation' => true,
        'faq_icon_closed' => 'dashicons-arrow-up-alt2',
        'faq_icon_open' => 'dashicons-arrow-down-alt2',
        'faq_custom_css' => ''
    );

    public function get_icon_options() {
        return array(
            'dashicons-insert' => array( 'label' => 'Insert', 'closed' => 'dashicons-insert', 'open' => 'dashicons-insert' ),
            'dashicons-remove' => array( 'label' => 'Remove', 'closed' => 'dashicons-remove', 'open' => 'dashicons-remove' ),
            'dashicons-arrow-up' => array( 'label' => 'Arrow Up', 'closed' => 'dashicons-arrow-up', 'open' => 'dashicons-arrow-up' ),
            'dashicons-arrow-down' => array( 'label' => 'Arrow Down', 'closed' => 'dashicons-arrow-down', 'open' => 'dashicons-arrow-down' ),
            'dashicons-arrow-up-alt2' => array( 'label' => 'Arrow Up (Alt2)', 'closed' => 'dashicons-arrow-up-alt2', 'open' => 'dashicons-arrow-up-alt2' ),
            'dashicons-arrow-down-alt2' => array( 'label' => 'Arrow Down (Alt2)', 'closed' => 'dashicons-arrow-down-alt2', 'open' => 'dashicons-arrow-down-alt2' ),
            'dashicons-plus-alt2' => array( 'label' => 'Plus (Alt2)', 'closed' => 'dashicons-plus-alt2', 'open' => 'dashicons-plus-alt2' ),
            'dashicons-minus' => array( 'label' => 'Minus', 'closed' => 'dashicons-minus', 'open' => 'dashicons-minus' ),
        );
    }

    public function get_settings() {
        $settings = get_option( $this->option_name, array() );
        if ( ! is_array( $settings ) ) {
            $settings = array();
        }
        
        foreach ( $this->defaults as $key => $value ) {
            if ( ! array_key_exists( $key, $settings ) || $settings[ $key ] === '' || is_null( $settings[ $key ] ) ) {
                if ( $key === 'faq_custom_css' ) {
                    $settings[ $key ] = $this->get_css_template();
                } else {
                    $settings[ $key ] = $value;
                }
            }
        }
        
        return $settings;
    }

    public function get_setting( $key, $default = null ) {
        $settings = $this->get_settings();
        return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
    }

    public function update_settings( $new_settings ) {
        $current = get_option( $this->option_name, array() );
        
        if ( ! is_array( $current ) ) {
            $current = array();
        }
        
        if ( array_key_exists( 'faq_enabled', $new_settings ) ) {
            $current['faq_enabled'] = (bool) $new_settings['faq_enabled'];
        }
        if ( array_key_exists( 'faq_open_first', $new_settings ) ) {
            $current['faq_open_first'] = (bool) $new_settings['faq_open_first'];
        }
        if ( array_key_exists( 'faq_close_others', $new_settings ) ) {
            $current['faq_close_others'] = (bool) $new_settings['faq_close_others'];
        }
        
        // Handle icon rotation
        if ( array_key_exists( 'faq_icon_rotation', $new_settings ) ) {
            $rotation_enabled = ! empty( $new_settings['faq_icon_rotation'] );
            $current['faq_icon_rotation'] = $rotation_enabled;
        }
        
        // Always save icon_closed if provided
        if ( isset( $new_settings['faq_icon_closed'] ) && $new_settings['faq_icon_closed'] !== '' ) {
            $current['faq_icon_closed'] = sanitize_text_field( $new_settings['faq_icon_closed'] );
        }
        
        // Always save icon_open if provided  
        if ( isset( $new_settings['faq_icon_open'] ) && $new_settings['faq_icon_open'] !== '' ) {
            $current['faq_icon_open'] = sanitize_text_field( $new_settings['faq_icon_open'] );
        }
        
        // If rotation is enabled, set icon_open to match icon_closed
        if ( ! empty( $current['faq_icon_rotation'] ) && isset( $current['faq_icon_closed'] ) ) {
            $current['faq_icon_open'] = $current['faq_icon_closed'];
        }
        
        if ( array_key_exists( 'faq_custom_css', $new_settings ) ) {
            $current['faq_custom_css'] = $new_settings['faq_custom_css'];
        }
        
        return update_option( $this->option_name, $current );
    }

    public function is_faq_enabled() {
        return (bool) $this->get_setting( 'faq_enabled', false );
    }

    public function is_faq_open_first() {
        return (bool) $this->get_setting( 'faq_open_first', false );
    }

    public function is_faq_close_others() {
        return (bool) $this->get_setting( 'faq_close_others', true );
    }

    public function get_faq_custom_css() {
        $css = $this->get_setting( 'faq_custom_css', '' );
        if ( empty( $css ) ) {
            $css = $this->get_css_template();
        }
        return $css;
    }

    public function get_css_template() {
        $css_file = PATROPI_ADDON_PLUGIN_DIR . 'assets/css/faq.css';
        if ( file_exists( $css_file ) ) {
            return file_get_contents( $css_file );
        }
        return '';
    }

    public function reset_faq_css() {
        $settings = $this->get_settings();
        $settings['faq_custom_css'] = $this->get_css_template();
        return update_option( $this->option_name, $settings );
    }
}