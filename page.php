<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Fremediti_Guitars
 */

get_header();

$has_sidebar = Fremediti_Guitars_Metaboxes::has_sidebar( get_the_ID() );
if ( $has_sidebar ):
	?>
    <div class="uk-grid" uk-grid>
<?php
endif;
?>
    <div id="primary" class="content-area <?php echo $has_sidebar ? 'uk-width-2-3@s uk-width-1-1' : ''; ?>">
        <main id="main" class="site-main">

			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
if ( $has_sidebar ) :
	get_sidebar();
	?>
    </div>
<?php
endif;
get_footer();
