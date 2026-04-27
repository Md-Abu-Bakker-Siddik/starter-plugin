<?php
/**
 * Plugin Name: Dynamic Web Lab Pricing Widget
 * Description: High-performance Elementor Pricing Table with JSON fetching & Caching.
 * Version: 1.0.0
 * Author: Md Abu Bakker Siddik
 * Text Domain: dwl-pricing
 */

if ( ! defined( 'ABSPATH' ) ) exit;

final class DWL_Dynamic_Pricing_Plugin {

    public function __construct() {
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    public function init() {
        if ( ! did_action( 'elementor/loaded' ) ) {
            return;
        }

        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
        add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_assets' ] );
    }

    public function register_widgets( $widgets_manager ) {
        require_once( __DIR__ . '/includes/class-pricing-data.php' );
        require_once( __DIR__ . '/includes/class-widget.php' );
        $widgets_manager->register( new \DWL_Pricing_Widget() );
    }

    public function enqueue_assets() {
        wp_enqueue_style( 'dwl-pricing-style', plugins_url( '/includes/style.css', __FILE__ ) );
    }
}

new DWL_Dynamic_Pricing_Plugin();