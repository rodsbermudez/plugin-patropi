<?php
/**
 * Plugin Name: Patropi Add-ons
 * Plugin URI: https://patropi.com.br
 * Description: Plugin utilitário com funcionalidades extras para WordPress
 * Version: 0.2.6
 * Author: Rodrigo Bermudez
 * Author URI: https://patropicomunica.com.br
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: patropi-addon
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'PATROPI_ADDON_VERSION', '0.2.6' );
define( 'PATROPI_ADDON_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PATROPI_ADDON_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once PATROPI_ADDON_PLUGIN_DIR . 'includes/class-patropi-settings.php';
require_once PATROPI_ADDON_PLUGIN_DIR . 'includes/class-patropi-admin.php';
require_once PATROPI_ADDON_PLUGIN_DIR . 'includes/class-patropi-mega-menu.php';

function patropi_addon_load_admin() {
    if ( is_admin() ) {
        Patropi_Addon_Admin::get_instance();
    }
}
add_action( 'plugins_loaded', 'patropi_addon_load_admin' );

function patropi_addon_enqueue_faq_styles() {
    $settings = Patropi_Addon_Settings::get_instance();
    $custom_css = $settings->get_faq_custom_css();
    
    if ( ! empty( $custom_css ) ) {
        wp_register_style( 'patropi-faq-custom', false, array(), PATROPI_ADDON_VERSION );
        wp_enqueue_style( 'patropi-faq-custom' );
        wp_add_inline_style( 'patropi-faq-custom', $custom_css );
    }
}

function patropi_addon_init() {
    if ( get_option( 'patropi_addon_flush_needed' ) ) {
        delete_option( 'patropi_addon_flush_needed' );
        flush_rewrite_rules();
    }
    
    require_once PATROPI_ADDON_PLUGIN_DIR . 'includes/class-patropi-faq.php';
    new Patropi_Addon_FAQ();
    Patropi_Mega_Menu::get_instance();
}

add_action( 'wp_enqueue_scripts', 'patropi_addon_enqueue_faq_styles', 20 );
add_action( 'init', 'patropi_addon_init', 0 );