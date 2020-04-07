<?php

defined( 'ABSPATH' ) or die();

class Fremediti_Guitars_Theme {

	private static $instance = null;

	public static function getInstance() {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
	}

	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'fremediti_guitars_scripts' ) );
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_action( 'after_setup_theme', array( $this, 'content_width' ), 0 );
		add_action( 'widgets_init', array( $this, 'register_sidebar' ) );

		Fremediti_Guitars_Customizer::getInstance();
	}

	/**
	 * Enqueue scripts and styles.
	 */
	function fremediti_guitars_scripts() {

		$version = $this->_get_file_version( FREMEDITI_GUITARS_THEME_PATH . '/style.css' );
		wp_enqueue_style( 'fremediti-guitars-style', FREMEDITI_GUITARS_THEME_URL . '/style.css', array(), $version );

		$version = $this->_get_file_version( FREMEDITI_GUITARS_THEME_PATH . '/assets/js/scripts.js' );
		wp_enqueue_script( 'fremediti-guitars-script', FREMEDITI_GUITARS_THEME_URL . '/assets/js/scripts.js', array( 'jquery' ), $version, true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	public function setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Fremediti Guitars, use a find and replace
		 * to change 'fremediti-guitars' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'fremediti-guitars', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
//		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'fremediti-guitars' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'fremediti_guitars_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}

	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * Priority 0 to make it available to lower priority callbacks.
	 *
	 * @global int $content_width
	 */
	public function content_width() {
		// This variable is intended to be overruled from themes.
		// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
		$GLOBALS['content_width'] = apply_filters( 'fremediti_guitars_content_width', 640 );
	}

	/**
	 * Register sidebar.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 */
	public function register_sidebar() {
		register_sidebar( array(
			'name'          => esc_html__( 'Sidebar', 'fremediti-guitars' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'fremediti-guitars' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
	}

	private function _get_file_version( $filename ) {

		$filetime = file_exists( $filename ) ? filemtime( $filename ) : '';

		return FREMEDITI_GUITARS_THEME_VERSION . ( ! empty( $filetime ) ? '-' . $filetime : '' );
	}
}