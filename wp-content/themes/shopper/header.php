<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Shopper
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	
	<?php
	do_action( 'shopper_before_header' ); ?>

	<header id="masthead" class="site-header" role="banner" style="<?php shopper_header_styles(); ?>">
		<div class="col-full">
			<?php
			/**
			 * Functions hooked into shopper_header action
			 *
			 * @hooked shopper_skip_links                       - 0
			 * @hooked shopper_social_icons                     - 10
			 * @hooked shopper_site_branding                    - 20
			 * @hooked shopper_secondary_navigation             - 30
			 * @hooked shopper_product_search                   - 40
			 * @hooked shopper_primary_navigation_wrapper       - 42
			 * @hooked shopper_primary_navigation               - 50
			 * @hooked shopper_header_cart                      - 60
			 * @hooked shopper_primary_navigation_wrapper_close - 68
			 */
			do_action( 'shopper_header' ); ?>
			
		</div>
	</header><!-- #masthead -->

	<?php
	/**
	 * Functions hooked in to shopper_before_content
	 *
	 * @hooked shopper_header_widget_region - 10
	 */
	do_action( 'shopper_before_content' ); ?>

	<div id="content" class="site-content">
		<div class="col-full">

		<?php
		/**
		 * Functions hooked in to shopper_content_top
		 *
		 * @hooked woocommerce_breadcrumb - 10
		 */
		do_action( 'shopper_content_top' );
