<?php
/**
 * Shopper WooCommerce Class
 *
 * @package  Shopper
 * @author   WooThemes
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Shopper_WooCommerce' ) ) :

	/**
	 * The Shopper WooCommerce Integration class
	 */
	class Shopper_WooCommerce {

		/**
		 * Setup class.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_filter( 'loop_shop_columns', 						array( $this, 'loop_columns' ) );
			add_filter( 'body_class', 								array( $this, 'woocommerce_body_class' ) );
			add_action( 'wp_enqueue_scripts', 						array( $this, 'woocommerce_scripts' ),	20 );
			add_filter( 'woocommerce_enqueue_styles', 				'__return_empty_array' );
			add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_args' ) );
			add_filter( 'woocommerce_product_thumbnails_columns', 	array( $this, 'thumbnail_columns' ) );
			add_filter( 'loop_shop_per_page', 						array( $this, 'products_per_page' ) );
			

			if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.5', '<' ) ) {
				add_action( 'wp_footer', 							array( $this, 'star_rating_script' ) );
			}

			// Integrations.
			
			add_action( 'wp_enqueue_scripts',                       array( $this, 'add_customizer_css' ), 140 );

			
		}

		/**
		 * Add CSS in <head> for styles handled by the theme customizer
		 * If the Customizer is active pull in the raw css. Otherwise pull in the prepared theme_mods if they exist.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function add_customizer_css() {

			$shopper_woocommerce_extension_styles = get_theme_mod( 'shopper_woocommerce_extension_styles' );


			wp_add_inline_style( 'shopper-woocommerce-style', $shopper_woocommerce_extension_styles );
			
		}

		/**
		 * Default loop columns on product archives
		 *
		 * @return integer products per row
		 * @since  1.0.0
		 */
		public function loop_columns() {

			$layout = get_theme_mod( 'shopper_layout' );

			if ( $layout == 'none' ) {

				$item = 4;

			} else {

				$item = 3;

			}
			return apply_filters( 'shopper_loop_columns', $item ); // 3 products per row
		}

		/**
		 * Add 'woocommerce-active' class to the body tag
		 *
		 * @param  array $classes css classes applied to the body tag.
		 * @return array $classes modified to include 'woocommerce-active' class
		 */
		public function woocommerce_body_class( $classes ) {
			if ( shopper_is_woocommerce_activated() ) {
				$classes[] = 'woocommerce-active';
			}

			return $classes;
		}

		/**
		 * WooCommerce specific scripts & stylesheets
		 *
		 * @since 1.0.0
		 */
		public function woocommerce_scripts() {
			global $shopper_version;

			wp_enqueue_style( 'shopper-woocommerce-style', get_template_directory_uri() . '/assets/sass/woocommerce/woocommerce.css', $shopper_version );
			//wp_style_add_data( 'shopper-woocommerce-style', 'rtl', 'replace' );

			wp_register_script( 'shopper-header-cart', get_template_directory_uri() . '/assets/js/woocommerce/header-cart.min.js', array(), $shopper_version, true );
			wp_enqueue_script( 'shopper-header-cart' );

			wp_register_script( 'shopper-sticky-payment', get_template_directory_uri() . '/assets/js/woocommerce/checkout.min.js', 'jquery', $shopper_version, true );

			if ( is_checkout() && apply_filters( 'shopper_sticky_order_review', true ) ) {
				wp_enqueue_script( 'shopper-sticky-payment' );
			}
		}

		/**
		 * Star rating backwards compatibility script (WooCommerce <2.5).
		 *
		 * @since 1.0.0
		 */
		public function star_rating_script() {
			if ( wp_script_is( 'jquery', 'done' ) && is_product() ) {
		?>
			<script type="text/javascript">
				jQuery( function( $ ) {
					$( 'body' ).on( 'click', '#respond p.stars a', function() {
						var $container = $( this ).closest( '.stars' );
						$container.addClass( 'selected' );
					});
				});
			</script>
		<?php
			}
		}

		/**
		 * Related Products Args
		 *
		 * @param  array $args related products args.
		 * @since 1.0.0
		 * @return  array $args related products args
		 */
		public function related_products_args( $args ) {
			$args = apply_filters( 'shopper_related_products_args', array(
				'posts_per_page' => 3,
				'columns'        => 3,
			) );

			return $args;
		}

		/**
		 * Product gallery thumnail columns
		 *
		 * @return integer number of columns
		 * @since  1.0.0
		 */
		public function thumbnail_columns() {
			return intval( apply_filters( 'shopper_product_thumbnail_columns', 4 ) );
		}

		/**
		 * Products per page
		 *
		 * @return integer number of products
		 * @since  1.0.0
		 */
		public function products_per_page() {
			return intval( apply_filters( 'shopper_products_per_page', 12 ) );
		}

		/**
		 * Query WooCommerce Extension Activation.
		 *
		 * @param string $extension Extension class name.
		 * @return boolean
		 */
		public function is_woocommerce_extension_activated( $extension = 'WC_Bookings' ) {
			return class_exists( $extension ) ? true : false;
		}

		public function shopper_subcategory_count_html( $str, $category ) {

			$html = ' <span class="count">' . sprintf( _n( '%s Product', '%s Products', $category->count, 'shopper' ), $category->count )  . '</span>';

			return $html;
		}
	}

endif;

return new Shopper_WooCommerce();
