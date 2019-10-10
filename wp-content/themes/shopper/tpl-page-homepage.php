<?php
/**
 * The template for displaying the homepage.
 *
 * This page template will display any functions hooked into the `shopper_homepage` action.
 * By default this includes a variety of product displays and the page content itself. To change the order or toggle these components
 * use the Homepage Control plugin.
 * https://wordpress.org/plugins/homepage-control/
 *
 * Template name: Homepage
 *
 * @package shopper
 */

get_header(); ?>	

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			/**
			 * Functions hooked in to homepage action
			 *
			 * @hooked shopper_homepage_content      - 10
			 * @hooked shopper_recent_products       - 20
			 * @hooked shopper_best_selling_products - 30
			 * @hooked shopper_featured_products     - 40
			 * @hooked shopper_popular_products      - 50
			 * @hooked shopper_on_sale_products      - 60
			 * @hooked shopper_product_categories    - 70
			 */
			do_action( 'shopper_homepage' ); ?>

		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_footer();