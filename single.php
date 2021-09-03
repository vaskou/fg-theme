<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Fremediti_Guitars
 */

get_header();

do_action( 'fremediti_guitars_single_before' );
$has_sidebar = apply_filters( 'fremediti_guitars_has_sidebar', false );
?>

    <div id="primary" class="content-area <?php echo $has_sidebar ? 'uk-width-2-3@s uk-width-1-1' : ''; ?>">
        <main id="main" class="site-main">

			<?php
			while ( have_posts() ) :
				the_post();

				$custom_post_types = array(
					'fg_features',
					'fg_pickups'
				);

				get_template_part( 'template-parts/content', get_post_type() );

				if ( ! in_array( get_post_type(), $custom_post_types ) ):

//					the_post_navigation();

					// If comments are open or we have at least one comment, load up the comment template.
//					if ( comments_open() || get_comments_number() ) :
//						comments_template();
//					endif;

				endif;

			endwhile; // End of the loop.
			?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php

do_action( 'fremediti_guitars_single_after' );

get_footer();
