<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Shopper
 */

get_header(); ?>

	<div id="primary" class="content-area">

		<main id="main" class="site-main" role="main">

			<div class="error-404 not-found">

				<div class="page-content">

					<header class="page-header">
						<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'shopper' ); ?></h1>
					</header><!-- .page-header -->

					<p><?php esc_html_e( 'Nothing was found at this location. Please check your URL or use the search form below.', 'shopper' ); ?></p>

					<?php
					echo '<section aria-label="Search">';

					if ( shopper_is_woocommerce_activated() ) {
						the_widget( 'WC_Widget_Product_Search' );
					} else {
						get_search_form();
					}

					echo '</section>';

					if ( shopper_is_woocommerce_activated() ) {

						echo '<div class="fourohfour-columns-2">';

							echo '<section class="col-1" aria-label="Promoted Products">';

								shopper_promoted_products();

							echo '</section>';

							echo '<nav class="col-2" aria-label="Product Categories">';

							echo '<h2 class="widget-title">' . esc_html__( 'Product Categories', 'shopper' ) . '</h2>';

							the_widget( 'WC_Widget_Product_Categories', array() );
							echo '</nav>';

							echo '</div>';

							echo '<section aria-label="Popular Products" >';

							echo '<h2 class="widget-title">' . esc_html__( 'Popular Products', 'shopper' ) . '</h2>';

							echo shopper_do_shortcode( 'best_selling_products', array(
								'per_page'  => 4,
								'columns'   => 4,
							) );

							echo '</section>';
					}
					?>

				</div><!-- .page-content -->
			</div><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
