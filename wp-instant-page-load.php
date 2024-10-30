<?php
/**
 * Plugin Name: Instant Page Load
 * Description: Instant Page Load By WPPlugines
 * Plugin URI:  https://wpplugines.com/
 * Author:      Al Imran Akash
 * Author URI:  https://profiles.wordpress.org/al-imran-akash/
 * Version: 1.0.8
 * Text Domain: wp-instant-page-load
 * Domain Path: /languages
 *
 * WP_Instant_Page_Load is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * WP_Instant_Page_Load is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

namespace wpplugines\WP_Instant_Page_Load;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'ipl_fs' ) ) {
    // Create a helper function for easy SDK access.
    function ipl_fs() {
        global $ipl_fs;

        if ( ! isset( $ipl_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $ipl_fs = fs_dynamic_init( array(
                'id'                  => '11491',
                'slug'                => 'instant-page-load',
                'type'                => 'plugin',
                'public_key'          => 'pk_356e84a632b0bd538742beabc689b',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'slug'           => 'instant-page-load',
                    'account'        => false,
                    'support'        => false,
                ),
            ) );
        }

        return $ipl_fs;
    }

    // Init Freemius.
    ipl_fs();
    // Signal that SDK was initiated.
    do_action( 'ipl_fs_loaded' );
}

/**
 * Main class for the plugin
 * @package Plugin
 * @author Al Imran Akash <alimranakash.bd@gmail.com>
 */
final class Plugin {
	
	public static $_instance;

	public function __construct() {
		self::include();
		self::define();
		self::hook();
	}

	/**
	 * Includes files
	 */
	public function include() {
		require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );
	}

	/**
	 * Define variables and constants
	 */
	public function define() {
		// constants
		define( 'AIOP', __FILE__ );
		define( 'AIOP_DIR', dirname( AIOP ) );
		define( 'AIOP_DEBUG', false );

		// plugin data
		$this->plugin				= get_plugin_data( AIOP );
		$this->plugin['basename']	= plugin_basename( AIOP );
		$this->plugin['file']		= AIOP;
		$this->plugin['server']		= apply_filters( 'wpplugines-plugin_server', 'https://wpplugines.com' );;
		$this->plugin['min_php']	= '5.6';
		$this->plugin['min_wp']		= '4.0';
		$this->plugin['depends']	= [ '' ];
	}

	/**
	 * Hooks
	 */
	public function hook() {

		if( is_admin() ) :

			/**
			 * Admin facing hooks
			 *
			 * To add an action, use $admin->action()
			 * To apply a filter, use $admin->filter()
			 */
			$admin = new Admin( $this->plugin );
			$admin->action( 'plugins_loaded', 'i18n' );
			$admin->action( 'admin_enqueue_scripts', 'enqueue_scripts' );
			$admin->action( 'admin_menu', 'menu' );

			/**
			 * Asks to participate in a survey
			 * 
			 * @package wpplugines\Plugin
			 * 
 			 * @author Al Imran Akash <alimranakash.bd@gmail.com>
			 */
			$survey = new Survey( $this->plugin );

		else : // is_admin() ?

		endif;
	}

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() { }

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() { }

	/**
	 * Instantiate the plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}

Plugin::instance();