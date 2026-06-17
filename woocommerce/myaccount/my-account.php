<?php
/**
 * My Account page
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="fg-my-account-layout">

	<?php do_action( 'woocommerce_account_navigation' ); ?>

	<div class="woocommerce-MyAccount-content">
		<?php do_action( 'woocommerce_account_content' ); ?>
	</div>

</div>
