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
		add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'guitar_menu_enable' ), 10, 5 );
		add_action( 'wp_update_nav_menu_item', array( $this, 'update_custom_menu_options' ), 10, 3 );
		add_action( 'walker_nav_menu_start_el', array( $this, 'guitar_menu' ), 10, 4 );
		add_filter( 'get_the_archive_title', array( $this, 'get_the_archive_title' ) );
		//TODO: uk-img
//		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'add_image_class' ) );

		Fremediti_Guitars_Customizer::getInstance();
		Fremediti_Guitars_Metaboxes::getInstance();
	}

	/**
	 * Enqueue scripts and styles.
	 */
	function fremediti_guitars_scripts() {

		$prefix = defined( 'WP_DEBUG' ) && true === WP_DEBUG ? '' : '.min';

		wp_enqueue_style( 'ubuntu-fonts', 'https://fonts.googleapis.com/css?family=Ubuntu:300,300i,400,400i,500,500i,700,700i&display=swap' );

		$version = $this->_get_file_version( FREMEDITI_GUITARS_THEME_PATH . '/style' . $prefix . '.css' );
		wp_enqueue_style( 'fremediti-guitars-style', FREMEDITI_GUITARS_THEME_URL . '/style' . $prefix . '.css', array(), $version );

		$version = $this->_get_file_version( FREMEDITI_GUITARS_THEME_PATH . '/assets/js/scripts' . $prefix . '.js' );
		wp_enqueue_script( 'fremediti-guitars-script', FREMEDITI_GUITARS_THEME_URL . '/assets/js/scripts' . $prefix . '.js', array( 'jquery' ), $version, true );

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
			'primary'   => esc_html__( 'Primary', 'fremediti-guitars' ),
			'offcanvas' => esc_html__( 'Offcanvas', 'fremediti-guitars' ),
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
		$shared_args = array(
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		);

		$sidebars = array(
			array(
				'name'        => esc_html__( 'Sidebar', 'fremediti-guitars' ),
				'id'          => 'sidebar-1',
				'description' => esc_html__( 'Add widgets here.', 'fremediti-guitars' ),
			),
			array(
				'name'        => __( 'Footer #1', 'fremediti-guitars' ),
				'id'          => 'footer-1',
				'description' => __( 'Widgets in this area will be displayed in the second column in the footer.', 'fremediti-guitars' ),
			),
			array(
				'name'        => __( 'Footer #2', 'fremediti-guitars' ),
				'id'          => 'footer-2',
				'description' => __( 'Widgets in this area will be displayed in the second column in the footer.', 'fremediti-guitars' ),
			),
			array(
				'name'        => __( 'Footer #3', 'fremediti-guitars' ),
				'id'          => 'footer-3',
				'description' => __( 'Widgets in this area will be displayed in the second column in the footer.', 'fremediti-guitars' ),
			),
			array(
				'name'        => __( 'Footer #4', 'fremediti-guitars' ),
				'id'          => 'footer-4',
				'description' => __( 'Widgets in this area will be displayed in the second column in the footer.', 'fremediti-guitars' ),
			),
			array(
				'name'        => __( 'Footer #5', 'fremediti-guitars' ),
				'id'          => 'footer-5',
				'description' => __( 'Widgets in this area will be displayed in the second column in the footer.', 'fremediti-guitars' ),
			),
		);

		foreach ( $sidebars as $sidebar ) {
			register_sidebar( array_merge( $shared_args, $sidebar ) );
		}

	}

	public function guitar_menu_enable( $item_id, $item, $depth, $args, $id ) {
		$value = get_post_meta( $item_id, '_menu_item_guitar_menu_enable', true );
		?>
        <p class="">
            <label for="edit-menu-item-guitar-menu-enable-<?php echo $item_id; ?>">
                <input type="checkbox" id="edit-menu-item-guitar-menu-enable-<?php echo $item_id; ?>" value="_blank" name="menu-item-guitar-menu-enable[<?php echo $item_id; ?>]"<?php checked( $value, '_blank' ); ?> />
				<?php _e( 'Enable Guitar Menu', 'fremediti-guitars' ); ?>
            </label>
        </p>
		<?php
	}

	public function update_custom_menu_options( $menu_id, $menu_item_db_id, $args ) {
		if ( ! empty( $_REQUEST['menu-item-guitar-menu-enable'][ $menu_item_db_id ] ) ) {
			update_post_meta( $menu_item_db_id, '_menu_item_guitar_menu_enable', sanitize_key( $_REQUEST['menu-item-guitar-menu-enable'][ $menu_item_db_id ] ) );
		} else {
			delete_post_meta( $menu_item_db_id, '_menu_item_guitar_menu_enable' );
		}
	}

	/**
	 * @param $item_output string
	 * @param $item WP_Post
	 * @param $depth integer
	 * @param $args stdClass
	 *
	 * @return mixed
	 */
	public function guitar_menu( $item_output, $item, $depth, $args ) {

		if ( ! class_exists( 'FG_Guitars_Post_Type' ) ) {
			return $item_output;
		}

		$is_guitar_mega_menu = get_post_meta( $item->ID, '_menu_item_guitar_menu_enable', true );

		if ( ! $is_guitar_mega_menu ) {
			return $item_output;
		}

		$guitars = FG_Guitars_Post_Type::getInstance();

//		var_dump( $guitars->get_categories_items_array() );
		$categories_items_array = $guitars->get_categories_items_array();

		if ( empty( $categories_items_array ) ) {
			return $item_output;
		}

		ob_start();
		?>
        <div class="uk-navbar-dropdown megamenu-wrapper fg-guitar-menu-guitars" uk-dropdown="boundary: .fg-navbar-sticky; boundary-align: true;">
            <div class="uk-container">
                <div uk-grid>
                    <div class="uk-width-1-5@m">
                        <ul class="uk-tab-left fg-guitar-menu-tabs" uk-tab="connect: #menu-categories;animation: uk-animation-fade;">
							<?php
							$i = 0;
							foreach ( $categories_items_array as $categories ):
								$i ++;
								?>
                                <li class="fg-cat-<?php echo $i; ?>"><a href="<?php echo get_term_link( $categories['cat_id'] ); ?>" class="fg-category-menu-link"><?php echo esc_html( $categories['cat_name'] ); ?></a></li>
							<?php
							endforeach;
							?>
                        </ul>
                    </div>
                    <div class="uk-width-4-5@m">
                        <ul id="menu-categories" class="uk-switcher">
							<?php
							foreach ( $categories_items_array as $categories ):
								?>
                                <li class="">
                                    <div uk-slider>
                                        <ul class="uk-slider-items uk-child-width-1-4@m uk-grid">
											<?php
											foreach ( $categories['items'] as $guitar ):
												?>
                                                <li class="uk-text-center">
                                                    <a href="<?php echo get_permalink( $guitar['id'] ); ?>">
														<?php echo $guitar['image']; ?>
                                                        <div><?php echo esc_html( $guitar['title'] ); ?></div>
                                                    </a>
                                                </li>
											<?php
											endforeach;
											?>
                                        </ul>
                                    </div>
                                </li>
							<?php
							endforeach;
							?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
		<?php
		$html = ob_get_clean();

		$item_output .= $html;

		return $item_output;
	}

	public function get_the_archive_title( $title ) {
		// From get_the_archive_title function
		if ( is_tax() ) {
			$queried_object = get_queried_object();
			if ( $queried_object ) {
				$title = sprintf( __( '%1$s' ), single_term_title( '', false ) );
			}
		}

		return $title;
	}

	public function add_image_class( $attr ) {
		if ( empty( $attr['uk-img'] ) ) {
			$attr['uk-img'] = '';
		}
		if ( empty( $attr['data-src'] ) ) {
			$attr['data-src'] = $attr['src'];
		}

		return $attr;
	}

	private function _get_file_version( $filename ) {

		$filetime = file_exists( $filename ) ? filemtime( $filename ) : '';

		return FREMEDITI_GUITARS_THEME_VERSION . ( ! empty( $filetime ) ? '-' . $filetime : '' );
	}
}