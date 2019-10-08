<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Shopper
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	do_action( 'shopper_single_post_top' );

	/**
	 * Functions hooked into shopper_single_post add_action
	 *
	 * @hooked shopper_post_header          - 10
	 * @hooked shopper_post_meta            - 20
	 * @hooked shopper_post_content         - 30
	 * @hooked shopper_footer_meta          - 40
	 * @hooked shopper_init_structured_data - 50
	 */
	do_action( 'shopper_single_post' );

	/**
	 * Functions hooked in to shopper_single_post_bottom action
	 *
	 * @hooked shopper_post_nav         - 10
	 * @hooked shopper_display_comments - 20
	 */
	do_action( 'shopper_single_post_bottom' );
	?>

</article><!-- #post-## -->