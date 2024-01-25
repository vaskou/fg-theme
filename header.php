<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Fremediti_Guitars
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body id="body" <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <!--    <a class="skip-link screen-reader-text" href="#content">--><?php //esc_html_e( 'Skip to content', 'fremediti-guitars' ); ?><!--</a>-->

    <header id="masthead" class="site-header">
        <div class="fg-navbar-sticky" uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky">
            <div class="uk-container">
                <nav id="site-navigation" class="main-navigation uk-navbar-container" uk-navbar="offset:1;">
                    <div class="uk-navbar-left uk-width-expand">
                        <a class="uk-navbar-toggle uk-hidden@m uk-margin-auto-right" uk-navbar-toggle-icon uk-toggle href="#offcanvas" title="offcanvas"></a>
                        <div class="site-branding uk-logo">
							<?php the_custom_logo(); ?>
                        </div><!-- .site-branding -->

						<?php
						if ( has_nav_menu( 'primary' ) ):
							wp_nav_menu( array(
								'theme_location' => 'primary',
								'menu_id'        => 'primary-menu',
								'menu_class'     => 'uk-navbar-nav uk-visible@m',
								'walker'         => new Fremediti_Guitars_Nav_Walker(),
								'fg_menu_type'   => 'main'
							) );
						endif;
						?>
                    </div>
                </nav><!-- #site-navigation -->

            </div>
        </div>
        <div id="offcanvas" uk-offcanvas="overlay: true;">
            <div class="uk-offcanvas-bar">
                <button class="uk-offcanvas-close" type="button" uk-close></button>
				<?php
				if ( has_nav_menu( 'offcanvas' ) ):
					wp_nav_menu( array(
						'theme_location' => 'offcanvas',
						'menu_id'        => 'offcanvas-menu',
						'menu_class'     => 'uk-nav',
						'walker'         => new Fremediti_Guitars_Nav_Walker(),
						'fg_menu_type'   => 'offcanvas'
					) );
				endif;
				?>
            </div>
        </div>
    </header><!-- #masthead -->
	<?php
	$container_class = '';
	if ( ! Fremediti_Guitars_Metaboxes::is_full_width( get_the_ID() ) ) {
		$container_class = 'uk-container';
	}
	?>
    <div id="content" class="site-content <?php echo $container_class; ?>">
