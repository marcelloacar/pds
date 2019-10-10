<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Shopper
 */

?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked in to shopper_page add_action
	 *
	 * @hooked shopper_page_header          - 10
	 * @hooked shopper_page_content         - 20
	 * @hooked shopper_init_structured_data - 30
	 */
	do_action( 'shopper_page' );
	?>
</div><!-- #post-## -->
