<?php
/**
 * Plugin Name: Soundmento
 * Description: A plugin to manage sound settings.
 * Version: 1.0.0
 * Author: Hemant Jodhani
 * License: GPL2
 */

if ( !defined('ABSPATH') ) {
    exit;
}

define('SOUNDMENTO_PLUGIN_PATH', plugin_dir_path(__FILE__));


function soundmento_enqueue_scripts() {
    wp_enqueue_script( 'soundmento-script', plugins_url('assets/script.js', __FILE__), array('jquery'), rand(), true );
    wp_enqueue_style( 'soundmento-style', plugins_url('assets/style.css', __FILE__), array(), rand() );
}

add_action('wp_enqueue_scripts', 'soundmento_enqueue_scripts');

function soundmento_register_widgets() {
    
    if ( ! did_action( 'elementor/loaded' ) ) {
        return;
    }

    // Include the widget file
    require_once( __DIR__ . '/widgets/soundmento-widget.php' );

    // Register the widget
    \Elementor\Plugin::instance()->widgets_manager->register( new \Soundmento_Widget() );
}
add_action( 'elementor/widgets/register', 'soundmento_register_widgets' );
