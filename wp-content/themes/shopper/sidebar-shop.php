<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Shopper
 */

if ( ! is_active_sidebar( 'shop-1' ) ) {
	return;
}
?>

<aside id="secondary" class="<?php echo esc_attr(apply_filters('shopper_widget_area_class', 'widget-area')) ?>" role="complementary">
	<?php dynamic_sidebar( 'shop-1' ); ?>
</aside><!-- #secondary -->
