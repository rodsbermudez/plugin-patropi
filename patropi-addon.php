<?php
/**
 * Plugin Name: Patropi Add-ons
 * Plugin URI: https://patropi.com.br
 * Description: Plugin utilitário com funcionalidades extras para WordPress
 * Version: 0.1.1
 * Author: Patropi
 * Author URI: https://patropi.com.br
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: patropi-addon
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'PATROPI_ADDON_VERSION', '1.0.0' );
define( 'PATROPI_ADDON_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PATROPI_ADDON_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once PATROPI_ADDON_PLUGIN_DIR . 'includes/class-patropi-settings.php';
require_once PATROPI_ADDON_PLUGIN_DIR . 'includes/class-patropi-admin.php';

function patropi_addon_process_form() {
    if ( is_admin() && isset( $_POST['patropi_faq_save'] ) && check_admin_referer( 'patropi_faq_settings' ) ) {
        $new_settings = array(
            'faq_enabled' => ! empty( $_POST['faq_enabled'] ) ? 1 : 0,
            'faq_open_first' => ! empty( $_POST['faq_open_first'] ) ? 1 : 0,
            'faq_close_others' => ! empty( $_POST['faq_close_others'] ) ? 1 : 0
        );
        
        $current = get_option( 'patropi_addon_settings', array() );
        $updated = array_merge( $current, $new_settings );
        update_option( 'patropi_addon_settings', $updated );
        
        if ( $new_settings['faq_enabled'] ) {
            update_option( 'patropi_addon_flush_needed', true );
        }
    }
}

function patropi_addon_admin_menu() {
    $admin = new Patropi_Addon_Admin();
    $admin->add_menu_page();
}

function patropi_addon_enqueue_scripts( $hook ) {
    $admin = new Patropi_Addon_Admin();
    $admin->enqueue_styles( $hook );
    $admin->enqueue_scripts( $hook );
}

function patropi_addon_enqueue_faq_styles() {
    $settings = new Patropi_Addon_Settings();
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
}

add_action( 'plugins_loaded', 'patropi_addon_process_form' );
add_action( 'admin_menu', 'patropi_addon_admin_menu' );
add_action( 'admin_enqueue_scripts', 'patropi_addon_enqueue_scripts' );
add_action( 'wp_enqueue_scripts', 'patropi_addon_enqueue_faq_styles', 20 );
add_action( 'init', 'patropi_addon_init', 0 );