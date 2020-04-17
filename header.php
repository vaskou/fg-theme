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

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
<!--    <a class="skip-link screen-reader-text" href="#content">--><?php //esc_html_e( 'Skip to content', 'fremediti-guitars' ); ?><!--</a>-->

    <header id="masthead" class="site-header">
        <div class="fg-navbar-sticky" uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky">
            <div class="uk-container">
                <nav id="site-navigation" class="main-navigation uk-navbar-container" uk-navbar="offset:1;">
                    <div class="uk-navbar-left">
                        <div class="site-branding uk-logo">
							<?php the_custom_logo(); ?>
                        </div><!-- .site-branding -->

						<?php
						wp_nav_menu( array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
							'menu_class'     => 'uk-navbar-nav uk-visible@m',
							'walker'         => new Fremediti_Guitars_Nav_Walker(),
							'fg_menu_type'   => 'main'
						) );
						?>
                        <a class="uk-navbar-toggle uk-hidden@m" uk-navbar-toggle-icon uk-toggle href="#offcanvas"></a>
                    </div>
                </nav><!-- #site-navigation -->

            </div>
        </div>
        <div id="offcanvas" uk-offcanvas="overlay: true;">
            <div class="uk-offcanvas-bar">
                <button class="uk-offcanvas-close" type="button" uk-close></button>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'offcanvas',
					'menu_id'        => 'primary-menu',
					'menu_class'     => 'uk-nav',
		            'walker'         => new Fremediti_Guitars_Nav_Walker(),
					'fg_menu_type'   => 'offcanvas'
				) );
				?>
            </div>
        </div>
    </header><!-- #masthead -->

    <div id="content" class="site-content uk-container">
