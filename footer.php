<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Fremediti_Guitars
 */

$has_footers = array();
$footer_count = 0;
for ( $i = 1; $i <= 5; $i ++ ) {
	if ( is_active_sidebar( 'footer-' . $i ) ) {
		$has_footers[ $i ] = true;
		$footer_count ++;
	} else {
		$has_footers[ $i ] = false;
	}
}

$year = date("Y");
?>

	</div><!-- #content -->

    <div class="uk-container fg-pre-footer">
        <div class="uk-margin-medium-top uk-margin-medium-bottom">
            <?php dynamic_sidebar( 'pre-footer' ); ?>
        </div>
    </div>
	<footer id="colophon" class="site-footer">
		<div class="footer-widgets uk-container">
			<div class="uk-child-width-1-<?php echo $footer_count; ?>@m uk-child-width-1-<?php echo ( $footer_count / 2 >= 1 ) ? '2' : '1'; ?>@s uk-child-width-1-1 uk-text-center uk-text-left@s" uk-grid>
				<?php
				for ( $i = 1; $i <= 5; $i ++ ) :
					if ( $has_footers[ $i ] ) :
						?>
						<div class="footer-widget">
							<?php dynamic_sidebar( 'footer-' . $i ); ?>
						</div>
					<?php
					endif;
				endfor;
				?>
			</div>
		</div>
		<div class="site-info">
			<div class="uk-container">
				<div class="uk-grid-small uk-flex-right@m uk-flex-center" uk-grid>
					<span class="">© <?php echo esc_attr( $year ); ?> Fremediti Guitars - All rights reserved. </span>
					<span class="">::website by <a title="" href="http://www.yesinternet.gr" target="_blank" rel="noreferrer noopener">YES Internet</a></span>
				</div>
			</div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
