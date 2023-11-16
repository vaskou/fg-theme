<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Fremediti_Guitars
 */

get_header();
?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">

			<?php
			while ( have_posts() ) :
				the_post();

				if ( ! Fremediti_Guitars_Helpers::show_new_layout() ) {
					get_template_part( 'template-parts/content', 'single-fg_guitars' );
				} else {
					get_template_part( 'template-parts/new-guitar-layout/content', 'single-fg_guitars' );
				}

			endwhile; // End of the loop.
			?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
//get_sidebar();
get_footer();
