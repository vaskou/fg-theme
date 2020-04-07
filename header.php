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
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'fremediti-guitars' ); ?></a>

    <header id="masthead" class="site-header">
        <div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky">
            <div class="uk-container">
                <nav id="site-navigation" class="main-navigation uk-navbar-container" uk-navbar>
                    <div class="uk-navbar-left">
                        <div class="site-branding uk-navbar-item uk-logo">
							<?php the_custom_logo(); ?>
                        </div><!-- .site-branding -->

						<?php
						wp_nav_menu( array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
							'menu_class'     => 'uk-navbar-nav',
							'walker'         => new Fremediti_Guitars_Nav_Walker()
						) );
						?>
                        <a class="uk-navbar-toggle" uk-navbar-toggle-icon href="#"></a>
                    </div>
                </nav><!-- #site-navigation -->

            </div>
        </div>
    </header><!-- #masthead -->

    <div id="content" class="site-content uk-container">
