<?php
/**
 * Plugin Name: Soundmento
 * Description: A plugin to manage sound settings.
 * Version: 1.0.0
 * Author: Hemant Jodhani
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SOUNDMENTO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'SOUNDMENTO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main plugin class for Soundmento.
 *
 * Handles initialization, asset loading, and Elementor widget registration.
 *
 * @since 1.0.0
 * @package Soundmento
 */
class Soundmento {

	/**
	 * Singleton instance.
	 *
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * Get plugin instance.
	 *
	 * @return self
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
    	add_action( 'elementor/widgets/register', array( $this, 'register_elementor_widgets' ) );
	}

	/**
	 * Enqueue frontend JS and CSS.
	 */
	public function enqueue_assets() {
		wp_enqueue_script(
			'soundmento-script',
			SOUNDMENTO_PLUGIN_URL . 'assets/script.js',
			array( 'jquery' ),
			time(),
			true
		);

		wp_enqueue_style(
			'soundmento-style',
			SOUNDMENTO_PLUGIN_URL . 'assets/style.css',
			array(),
			time()
		);
	}

	/**
	 * Register Elementor widgets.
	 */
	public function register_elementor_widgets() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		require_once SOUNDMENTO_PLUGIN_PATH . 'widgets/soundmento-widget.php';

		\Elementor\Plugin::instance()->widgets_manager->register( new \Soundmento_Widget() );
	}
}

Soundmento::get_instance();
