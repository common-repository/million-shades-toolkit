<?php
/**
 *
 * Plugin Name:       Million Shades Toolkit
 * Plugin URI:        http://themenextlevel.com/million/
 * Description:       This plugin registers custom post types(client,portfolio,staff,testimonials) to the Million Shades theme
 * Version:           1.0.9
 * Author:            Kudrat E Khuda
 * Author URI:        http://themenextlevel.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       million-shades-toolkit
 * Domain Path:       /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

/**
 * Set up and initialize
 */
class MSTK_Custom_Post {

	private static $_instance;

	/**
	 * Actions setup
	 */
	public function __construct() {

		$this->init_hooks();
	}
	
	private function init_hooks() {
		add_action( 'plugins_loaded', array( $this, 'constants' ), 2 );
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 3 );
		add_action( 'plugins_loaded', array( $this, 'includes' ), 4 );
		add_action( 'admin_notices', array( $this, 'admin_notice' ), 4 );
	}

	/**
	 * Constants
	 */
	function constants() {

		define( 'MSTK_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'MSTK_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
	}

	/**
	 * Includes
	 */
	function includes() {

		//Post types
		require_once( MSTK_DIR . 'inc/client.php' );
		require_once( MSTK_DIR . 'inc/portfolio.php' );
		require_once( MSTK_DIR . 'inc/testimonials.php' );	
		require_once( MSTK_DIR . 'inc/staff.php' );
	
	}

	/**
	 * Translations
	 */
	function i18n() {
		load_plugin_textdomain( 'million-shades-toolkit', false, 'million-shades-toolkit/languages' );
	}

	/**
	 * Admin notice
	 */
	function admin_notice() {
		$theme  = wp_get_theme();
		$parent = wp_get_theme()->parent();
		if ( ($theme != 'Million Shades' ) && ($theme != 'Million Shades Pro' ) && ($parent != 'Million Shades') && ($parent != 'Million Shades Pro') ) {
		    echo '<div class="error">';
			
			echo 	'<p>' . __('<strong>Million Shades Toolkit</strong> plugin should be used for the <a href="http://wordpress.org/themes/million-shades/" target="_blank">Million Shades theme only</a></p>', 'Million Shades Toolkit');
		    
			echo '</div>';			
		}
	}
	
	
	/**
	 * Returns the instance.
	 */

	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}

function mstk_toolbox_plugin() {
	
		return MSTK_Custom_Post::get_instance();
}
add_action('plugins_loaded', 'mstk_toolbox_plugin', 1);