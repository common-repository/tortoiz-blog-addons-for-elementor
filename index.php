<?php

/**
 *
 * @wordpress-plugin
 * Plugin Name:       Tortoiz Blog Addons for Elementor
 * Description:       Tortoiz Blog Addons is responsive blog addons plugin for Elementor page builder.
 * Version:           2.0.0
 * Author:            Tortoiz Themes
 * Author URI:        https://tortoizthemes.com
 * Text Domain:       tortoiz_blog
 * Domain Path:       /languages
 */



/* Activation */

require_once dirname( __FILE__ ) . '/lib/class-tgm-plugin-activation.php';


function tortoiz_blog_register_required_plugins() {
	
	$plugins = array(

		array(
			'name'      => 'Reading Time WP',
			'slug'      => 'reading-time-wp',
			'required'  => true,
		),

	);


	$config = array(
		'id'           => 'tortoiz_blog',
		'default_path' => '',
		'menu'         => 'tgmpa-install-plugins',
		'parent_slug'  => 'plugins.php',
		'capability'   => 'manage_options',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => false,
		'message'      => '',

	);

	tgmpa( $plugins, $config );
}

add_action( 'tgmpa_register', 'tortoiz_blog_register_required_plugins' );

// Enqueue Assets

function tortoiz_blog_assets() {
    wp_enqueue_style( "fontawesome-all-css", plugins_url( "/assets/css/fontawesome.all.min.css", __FILE__ ), null, "5.9.0" );
    wp_enqueue_style( "fontawesome-css", plugins_url( "/assets/css/fontawesome.min.css", __FILE__ ), null, "4.7.0" );
    wp_enqueue_style( "bootstrap-css", plugins_url( "/assets/css/plugins/bootstrap.min.css", __FILE__ ), null, "4.4.1" );
    wp_enqueue_style( "animate-css", plugins_url( "/assets/css/plugins/animate.min.css", __FILE__ ), null, "3.7.2" );
    wp_enqueue_style( "carousel-css", plugins_url( "/assets/css/plugins/owl.carousel.min.css", __FILE__ ), null, "2.2.1" );
    wp_enqueue_style( "plugin-style-css", plugins_url( "/assets/css/style.css", __FILE__ ), null, "2.2.1" );


    wp_enqueue_script( "bootstrap-js", plugins_url( "/assets/js/bootstrap.min.js", __FILE__ ), array( "jquery" ), "4.4.1" );
    wp_enqueue_script( "popper-js", plugins_url( "/assets/js/popper.min.js", __FILE__ ), array( "jquery" ), "1.0.0" );
    wp_enqueue_script( "carousel-js", plugins_url( "/assets/js/owl.carousel.min.js", __FILE__ ), array( "jquery" ), "2.2.1" );
    wp_enqueue_script( "masonary-js", plugins_url( "/assets/js/masonary.min.js", __FILE__ ), array( "jquery" ), "4.2.2" );
    wp_enqueue_script( "plugins-js", plugins_url( "/assets/js/plugins.js", __FILE__ ), array( "jquery" ), "1.0.0" );
    wp_enqueue_script( "tortoiz-blog-js", plugins_url( "/assets/js/main.js", __FILE__ ), array( "jquery" ), "1.0.0", true );
}

add_action( "wp_enqueue_scripts", "tortoiz_blog_assets" );

final class tortoiz_blog {

	// Widget Version

	const VERSION = '1.0.0';

	// Minimum Elementor Version

	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	// Minimum PHP Version

	const MINIMUM_PHP_VERSION = '7.0';

	private static $_instance = null;

	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	public function __construct() {

		add_action( 'init', [ $this, 'i18n' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );

	}

	public function i18n() {

		load_plugin_textdomain( 'tortoiz_blog' );

	}

	public function init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

		// Add Plugin actions
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'register_new_category'] );
	}

	public function register_new_category($manager){
		$manager->add_category('tortoiz_blog_addons', [
			'title'=>__('Tortoiz Blog Addons', 'tortoiz-blog'),
			'icon'=>'fa fa-image'
		]);
	}

	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'tortoiz-blog' ),
			'<strong>' . esc_html__( 'tortoiz-blog Elementor Extension', 'tortoiz-blog' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'tortoiz-blog' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'tortoiz-blog' ),
			'<strong>' . esc_html__( 'tortoiz-blog Elementor Extension', 'tortoiz-blog' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'tortoiz-blog' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
		/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'tortoiz-blog' ),
			'<strong>' . esc_html__( 'tortoiz-blog Elementor Extension', 'tortoiz-blog' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'tortoiz-blog' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function init_widgets() {

		// Include Widget files
		require_once( __DIR__ . '/addons/standard-post.php' );
		require_once( __DIR__ . '/addons/magazine-grid-one.php' );
		require_once( __DIR__ . '/addons/magazine-grid-two.php' );
		require_once( __DIR__ . '/addons/magazine-grid-three.php' );
		require_once( __DIR__ . '/addons/magazine-grid-four.php' );
		require_once( __DIR__ . '/addons/magazine-slider-one.php' );
		require_once( __DIR__ . '/addons/magazine-slider-two.php' );
		require_once( __DIR__ . '/addons/magazine-slider-three.php' );
		require_once( __DIR__ . '/addons/magazine-list-one.php' );
		require_once( __DIR__ . '/addons/magazine-list-two.php' );
		require_once( __DIR__ . '/addons/magazine-list-three.php' );
		require_once( __DIR__ . '/addons/blog-masonry-one.php' );
		require_once( __DIR__ . '/addons/blog-grid-one.php' );
		require_once( __DIR__ . '/addons/blog-grid-two.php' );
		require_once( __DIR__ . '/addons/blog-grid-three.php' );
		require_once( __DIR__ . '/addons/blog-grid-four.php' );
		require_once( __DIR__ . '/addons/blog-grid-five.php' );
		require_once( __DIR__ . '/addons/blog-slider-one.php' );
		require_once( __DIR__ . '/addons/blog-slider-two.php' );
		require_once( __DIR__ . '/addons/blog-slider-three.php' );
		require_once( __DIR__ . '/addons/blog-slider-four.php' );

		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \tortoiz_blog_Standard_Post() );
		
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \tortoiz_blog_Magazine_Grid_One() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \tortoiz_blog_Magazine_Grid_Two() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \tortoiz_blog_Magazine_Grid_Three() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \tortoiz_blog_Magazine_Grid_Four() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \tortoiz_blog_Magazine_Slider_One() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \tortoiz_blog_Magazine_Slider_Two() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \tortoiz_blog_Magazine_Slider_Three() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \tortoiz_blog_Magazine_List_One() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \tortoiz_blog_Magazine_List_Two() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \tortoiz_blog_Magazine_List_Three() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \tortoiz_blog_Blog_Masonry() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \tortoiz_blog_Blog_Grid_one() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \tortoiz_blog_Blog_Grid_Two() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \tortoiz_blog_Blog_Grid_Three() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \tortoiz_blog_Blog_Grid_Four() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \tortoiz_blog_Blog_Grid_Five() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \tortoiz_blog_Blog_Slider_One() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \tortoiz_blog_Blog_Slider_Two() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \tortoiz_blog_Blog_Slider_Three() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \tortoiz_blog_Blog_Slider_Four() );

	}

}

tortoiz_blog::instance();