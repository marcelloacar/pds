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
	/**
	 * Functions hooked in to shopper_loop_post action.
	 *
	 * @hooked shopper_post_header          - 10
	 * @hooked shopper_post_meta            - 20
	 * @hooked shopper_post_content         - 30	 
	 * @hooked shopper_init_structured_data - 40
	 * @hooked shopper_footer_meta			- 50
	 */
	do_action( 'shopper_loop_post' );
	?>

</article><!-- #post-## -->