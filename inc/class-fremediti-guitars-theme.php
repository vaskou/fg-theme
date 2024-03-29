<?php

defined( 'ABSPATH' ) or die();

class Fremediti_Guitars_Theme {

	private static $instance = null;

	public static function instance() {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'init', array( $this, 'add_editor_style' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'fremediti_guitars_scripts' ) );
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_action( 'after_setup_theme', array( $this, 'content_width' ), 0 );
		add_action( 'widgets_init', array( $this, 'register_sidebar' ) );
		add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'guitar_menu_enable' ), 10, 5 );
		add_action( 'wp_update_nav_menu_item', array( $this, 'update_custom_menu_options' ), 10, 3 );
		add_action( 'walker_nav_menu_start_el', array( $this, 'guitar_menu' ), 10, 4 );
		add_filter( 'nav_menu_css_class', array( $this, 'guitar_menu_class' ), 10, 2 );
		add_filter( 'get_the_archive_title', array( $this, 'get_the_archive_title' ) );

		// Widget Menu
		add_filter( 'widget_nav_menu_args', array( $this, 'widget_nav_menu_args' ) );

		add_action( 'wp_head', array( $this, 'pingback_header' ) );
		add_filter( 'body_class', array( $this, 'body_classes' ) );

		// uk-img
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'add_image_class' ) );

		// Read more posts button
		add_filter( 'the_content_more_link', array( $this, 'the_content_more_link' ) );

		// Pagination
		add_filter( 'navigation_markup_template', array( $this, 'pagination_template' ), 10, 2 );
		add_filter( 'the_posts_pagination_args', array( $this, 'the_posts_pagination_args' ) );

		// Google Tag Manager
		add_action( 'wp_head', array( $this, 'gtm_head_script' ), 1 );
		add_action( 'wp_body_open', array( $this, 'gtm_body_script' ), 1 );

		// Favicon
		add_action( 'wp_head', array( $this, 'add_favicon' ) );

		// Contact form
		add_filter( 'shortcode_atts_wpcf7', [ $this, 'add_contact_form_attribute_support' ], 10, 3 );
		add_filter( 'wpcf7_autop_or_not', '__return_false' );

		// Sidebar
		add_filter( 'fremediti_guitars_has_sidebar', array( $this, 'fremediti_guitars_has_sidebar' ) );
		add_action( 'fremediti_guitars_page_before', array( $this, 'markup_for_sidebar_before' ) );
		add_action( 'fremediti_guitars_page_after', array( $this, 'markup_for_sidebar_after' ) );
		add_action( 'fremediti_guitars_single_before', array( $this, 'markup_for_sidebar_before' ) );
		add_action( 'fremediti_guitars_single_after', array( $this, 'markup_for_sidebar_after' ) );
		add_action( 'fremediti_guitars_archive_before', array( $this, 'markup_for_sidebar_before' ) );
		add_action( 'fremediti_guitars_archive_after', array( $this, 'markup_for_sidebar_after' ) );

		Fremediti_Guitars_Accessibility::instance();
		Fremediti_Guitars_Customizer::instance();
		Fremediti_Guitars_Metaboxes::instance();
		Fremediti_Guitars_Available_Guitars_Post_Type::instance();
		Fremediti_Guitars_Gallery_Post_Type::instance();
		Fremediti_Guitars_Videos_Post_Type::instance();
		Fremediti_Guitars_Settings::instance();
		Fremediti_Guitars_Multilanguage::instance();
		Fremediti_Guitars_FG_Pickups::instance();
	}

	public function add_editor_style() {
		add_editor_style( '/assets/admin/css/styles.css' );

		if ( function_exists( 'register_block_type' ) ) {

			wp_register_script(
				'fremediti-guitars-grid-column',
				FREMEDITI_GUITARS_THEME_URL . '/assets/admin/js/block-editor/grid-column.js',
				array( 'wp-block-editor', 'wp-blocks', 'wp-element', 'wp-polyfill' ),
				filemtime( FREMEDITI_GUITARS_THEME_PATH . '/assets/admin/js/block-editor/grid-column.js' )
			);

			register_block_type( 'fremediti-guitars/grid-column', array(
				'editor_script' => 'fremediti-guitars-grid-column',
			) );

			wp_register_script(
				'fremediti-guitars-grid',
				FREMEDITI_GUITARS_THEME_URL . '/assets/admin/js/block-editor/grid.js',
				array( 'wp-block-editor', 'wp-blocks', 'wp-element', 'wp-polyfill' ),
				filemtime( FREMEDITI_GUITARS_THEME_PATH . '/assets/admin/js/block-editor/grid.js' )
			);

			register_block_type( 'fremediti-guitars/grid', array(
				'editor_script' => 'fremediti-guitars-grid',
			) );
		}
	}

	public function admin_scripts() {
		wp_enqueue_script( 'fremediti-guitars-admin-scripts', FREMEDITI_GUITARS_THEME_URL . '/assets/admin/js/scripts.js', array( 'jquery' ) );
	}

	/**
	 * Enqueue scripts and styles.
	 */
	function fremediti_guitars_scripts() {

		$prefix = defined( 'WP_DEBUG' ) && true === WP_DEBUG ? '' : '.min';

		wp_enqueue_style( 'ubuntu-fonts', 'https://fonts.googleapis.com/css?family=Ubuntu:300,300i,400,400i,500,500i,700,700i&display=swap' );

		wp_enqueue_script( 'js-cookie', 'https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js', array(), '2', true );

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

		add_theme_support( 'editor-styles' );
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
			'before_title'  => '<div class="widget-title uk-h3">',
			'after_title'   => '</div>',
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
				'description' => __( 'Widgets in this area will be displayed in the third column in the footer.', 'fremediti-guitars' ),
			),
			array(
				'name'        => __( 'Footer #4', 'fremediti-guitars' ),
				'id'          => 'footer-4',
				'description' => __( 'Widgets in this area will be displayed in the fourth column in the footer.', 'fremediti-guitars' ),
			),
			array(
				'name'        => __( 'Footer #5', 'fremediti-guitars' ),
				'id'          => 'footer-5',
				'description' => __( 'Widgets in this area will be displayed in the fifth column in the footer.', 'fremediti-guitars' ),
			),
			array(
				'name'        => __( 'Pre Footer', 'fremediti-guitars' ),
				'id'          => 'pre-footer',
				'description' => __( 'Widgets in this area will be displayed before the footer.', 'fremediti-guitars' ),
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

		$guitars = FG_Guitars_Post_Type::instance();

		$categories_items_array = $guitars->get_categories_items_array();

		if ( empty( $categories_items_array ) ) {
			return $item_output;
		}

		ob_start();
		?>
        <div class="uk-navbar-dropdown megamenu-wrapper fg-guitar-menu-guitars"
             uk-dropdown="boundary: .fg-navbar-sticky; boundary-align: true; delay-hide: 100; animation: uk-animation-slide-top-small; offset: 1;">
            <div class="uk-container">
                <div class="uk-grid" uk-grid>
                    <div class="uk-width-1-5@m">
                        <ul class="uk-tab uk-tab-left fg-guitar-menu-tabs" uk-tab="connect: #menu-categories;">
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
                                <li class="uk-animation-fade">
									<?php
									if ( ! empty( $categories['items'] ) ):
										$items_count = count( $categories['items'] );
										$items = $items_count > 4 ? $items_count : 4;
										$width_class = 'uk-child-width-1-' . $items . '@m';
										?>
                                        <!--                                        <div uk-slider>-->
                                        <ul class="uk-slider-items uk-grid <?php echo $width_class; ?>" uk-grid>
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
                                        <!--                                        </div>-->
									<?php
									endif;
									?>
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

	/**
	 * @param $classes array
	 * @param $item WP_Post
	 *
	 * @return mixed
	 */
	public function guitar_menu_class( $classes, $item ) {

		$is_guitar_mega_menu = get_post_meta( $item->ID, '_menu_item_guitar_menu_enable', true );

		if ( $is_guitar_mega_menu ) {
			$classes[] = 'megamenu';
			$classes   = array_unique( $classes );
		}

		return $classes;
	}

	public function get_the_archive_title( $title ) {
		// From get_the_archive_title function
		if ( is_tax() ) {
			$queried_object = get_queried_object();
			if ( $queried_object ) {
				$title = sprintf( __( '%1$s' ), single_term_title( '', false ) );
			}
		} elseif ( is_post_type_archive() ) {
			/* translators: Post type archive title. %s: Post type name. */
			$title = sprintf( __( '%s' ), post_type_archive_title( '', false ) );
		}

		return $title;
	}

	public function widget_nav_menu_args( $args ) {

		$args['menu_class'] = 'uk-nav';
		$args['walker']     = new Fremediti_Guitars_Nav_Walker();

		return $args;
	}

	/**
	 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
	 */
	public function pingback_header() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 *
	 * @return array
	 */
	public function body_classes( $classes ) {
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		// Adds a class of no-sidebar when there is no sidebar present.
		if ( ! is_active_sidebar( 'sidebar-1' ) ) {
			$classes[] = 'no-sidebar';
		}

		return $classes;
	}

	public function add_image_class( $attr ) {
		if ( is_admin() ) {
			return $attr;
		}

		if ( isset( $attr['class'] ) && 'custom-logo' == $attr['class'] ) {
			return $attr;
		}

		if ( empty( $attr['uk-img'] ) ) {
			$attr['uk-img'] = '';
		}
		if ( empty( $attr['data-src'] ) && ! empty( $attr['src'] ) ) {
			$attr['data-src'] = $attr['src'];
			$attr['src']      = '';
		}
		if ( empty( $attr['data-srcset'] ) && ! empty( $attr['srcset'] ) ) {
			$attr['data-srcset'] = $attr['srcset'];
			$attr['srcset']      = '';
		}

		return $attr;
	}

	public function the_content_more_link( $link ) {
		ob_start();
		?>
        <a href="<?php the_permalink(); ?>" class="uk-button uk-button-primary"><?php _e( 'Read More', 'fremediti-guitars' ); ?></a>
		<?php
		return ob_get_clean();
	}

	public function pagination_template( $template, $class ) {

		if ( 'pagination' == $class ) {
			ob_start();
			?>
            <nav class="navigation %1$s uk-container uk-margin-top" role="navigation" aria-label="%4$s">
                <h2 class="screen-reader-text uk-hidden">%2$s</h2>
                <div class="nav-links">%3$s</div>
            </nav>
			<?php
			$template = ob_get_clean();
		}

		return $template;
	}

	public function the_posts_pagination_args( $args ) {

		$args['prev_text'] = '<span uk-pagination-previous><span class="uk-hidden">' . __( 'Previous', 'fremediti-guitars' ) . '</span></span>';
		$args['next_text'] = '<span uk-pagination-next><span class="uk-hidden">' . __( 'Next', 'fremediti-guitars' ) . '</span></span>';

		return $args;
	}

	public function gtm_head_script() {
		$gtm_code = Fremediti_Guitars_Settings::get_gtm();

		if ( ! empty( $gtm_code ) ) {
			echo "<!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                })(window,document,'script','dataLayer','" . $gtm_code . "');</script>
            <!-- End Google Tag Manager -->";

		}
	}

	public function gtm_body_script() {
		$gtm_code = Fremediti_Guitars_Settings::get_gtm();

		if ( ! empty( $gtm_code ) ) {
			echo '<!-- Google Tag Manager (noscript) -->
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=' . $gtm_code . '"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->';
		}
	}

	public function add_favicon() {
		?>
        <link rel="apple-touch-icon" sizes="57x57" href="<?php echo FREMEDITI_GUITARS_THEME_URL; ?>/assets/images/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="<?php echo FREMEDITI_GUITARS_THEME_URL; ?>/assets/images/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="<?php echo FREMEDITI_GUITARS_THEME_URL; ?>/assets/images/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo FREMEDITI_GUITARS_THEME_URL; ?>/assets/images/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="<?php echo FREMEDITI_GUITARS_THEME_URL; ?>/assets/images/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?php echo FREMEDITI_GUITARS_THEME_URL; ?>/assets/images/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="<?php echo FREMEDITI_GUITARS_THEME_URL; ?>/assets/images/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo FREMEDITI_GUITARS_THEME_URL; ?>/assets/images/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo FREMEDITI_GUITARS_THEME_URL; ?>/assets/images/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192" href="<?php echo FREMEDITI_GUITARS_THEME_URL; ?>/assets/images/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo FREMEDITI_GUITARS_THEME_URL; ?>/assets/images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="<?php echo FREMEDITI_GUITARS_THEME_URL; ?>/assets/images/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo FREMEDITI_GUITARS_THEME_URL; ?>/assets/images/favicon/favicon-16x16.png">
        <link rel="manifest" href="<?php echo FREMEDITI_GUITARS_THEME_URL; ?>/assets/images/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="<?php echo FREMEDITI_GUITARS_THEME_URL; ?>/assets/images/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
		<?php
	}

	public function add_contact_form_attribute_support( $out, $pairs, $atts ) {
		$my_attr = 'selected-guitar';

		if ( isset( $atts[ $my_attr ] ) ) {
			$out[ $my_attr ] = $atts[ $my_attr ];
		}

		return $out;
	}

	public function fremediti_guitars_has_sidebar( $has_sidebar ) {
		if ( is_singular( 'page' ) ) {
			$has_sidebar = Fremediti_Guitars_Metaboxes::has_sidebar( get_the_ID() );
		}

		return $has_sidebar;
	}

	public function markup_for_sidebar_before() {
		$has_sidebar = apply_filters( 'fremediti_guitars_has_sidebar', false );

		if ( $has_sidebar ):
			?>
            <div class="uk-grid" uk-grid>
		<?php
		endif;
	}

	public function markup_for_sidebar_after() {
		$has_sidebar = apply_filters( 'fremediti_guitars_has_sidebar', false );

		if ( $has_sidebar ) :
			get_sidebar();
			?>
            </div>
		<?php
		endif;
	}

	private function _get_file_version( $filename ) {

		$filetime = file_exists( $filename ) ? filemtime( $filename ) : '';

		return FREMEDITI_GUITARS_THEME_VERSION . ( ! empty( $filetime ) ? '-' . $filetime : '' );
	}
}