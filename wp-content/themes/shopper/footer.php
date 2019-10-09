<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Shopper
 */

?>

		</div><!-- .col-full -->
	</div><!-- #content -->

	<?php do_action( 'shopper_before_footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="col-full">

			<?php
			/**
			 * Functions hooked in to shopper_footer action
			 *
			 * @hooked shopper_footer_widgets - 10
			 * @hooked shopper_credit         - 20
			 * @hooked shopper_footer_menu    - 30			 
			 */
			do_action( 'shopper_footer' ); ?>

		</div><!-- .col-full -->
	</footer><!-- #colophon -->

	<?php do_action( 'shopper_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
