<?php
/**
 * All admin facing functions
 */
namespace wpplugines\WP_Instant_Page_Load;
use codexpert\product\Base;
/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage Admin
 * @author Al Imran Akash <alimranakash.bd@gmail.com>
 */
class Admin extends Base {

	public $plugin;

	/**
	 * Constructor function
	 */
	public function __construct( $plugin ) {
		$this->plugin	= $plugin;
		$this->slug		= $this->plugin['TextDomain'];
		$this->name		= $this->plugin['Name'];
		$this->version	= $this->plugin['Version'];
	}

	/**
	 * Internationalization
	 */
	public function i18n() {
		load_plugin_textdomain( 'all-in-one-page', false, AIOP_DIR . '/languages/' );
	}
	
	/**
	 * Enqueue JavaScripts and stylesheets
	 */
	public function enqueue_scripts() {
		$min = defined( 'AIOP_DEBUG' ) && AIOP_DEBUG ? '' : '.min';
		
		wp_enqueue_style( $this->slug, plugins_url( "/assets/css/admin{$min}.css", AIOP ), '', $this->version, 'all' );

		wp_enqueue_script( $this->slug, plugins_url( "/assets/js/admin{$min}.js", AIOP ), [ 'jquery' ], $this->version, true );

		$localized = [
			'ajaxurl'	=> admin_url( 'admin-ajax.php' ),
            'nonce' 		=> wp_create_nonce( $this->slug ),
		];
		wp_localize_script( $this->slug, 'AIOP', apply_filters( "{$this->slug}-localized", $localized ) );
	}

	public function menu() {
		add_menu_page( 
			__( 'Instant Page Load', 'textdomain' ),
			__( 'Instant Page Load', 'textdomain' ),
			'manage_options',
			'instant-page-load',
			[ $this, 'menu_callback' ],
			'dashicons-controls-repeat',
			10
		); 
	}

	public function menu_callback()	{
		echo aiop_get_template( 'pro-features', 'views' );
	}
}