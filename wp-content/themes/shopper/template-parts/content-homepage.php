<?php
/**
 * The template used for displaying page content in tpl-page-homepage.php
 *
 * @package Shopper
 */

$featured_image = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="<?php shopper_homepage_content_styles(); ?>" data-featured-image="<?php echo $featured_image; ?>">
	<div class="col-full">
		<?php
		/**
		 * Functions hooked in to shopper_page add_action
		 *
		 * @hooked shopper_homepage_header      - 10
		 * @hooked shopper_page_content         - 20
		 * @hooked shopper_init_structured_data - 30
		 */
		do_action( 'shopper_content_homepage' );
		?>
	</div>
</div><!-- #post-## -->
