<?php
/**
 * Shopper Admin Class
 *
 * @author   ShopperWP
 * @package  shopper
 * @since    1.2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Shopper_Admin' ) ) :

	/**
	 * Shopper Admin Class
	 */

	class Shopper_Admin {

		/**
		 * Setup class.
		 *
		 */
		public function __construct() {
			
			add_action( 'admin_enqueue_scripts', 	array( $this, 'scripts' ) );
			add_action( 'customize_controls_enqueue_scripts',      array( $this, 'customize_scripts' ) );

		}

		public function scripts() {

			global $shopper_version;

			// Admin Style
			wp_enqueue_style( 'shopper-admin-style', get_template_directory_uri() . '/assets/sass/admin/admin.css', array(), $shopper_version, 'all' );
		}

		public function customize_scripts() {

			global $shopper_version;

			wp_enqueue_style( 'shopper-customize-style', get_template_directory_uri() . '/assets/sass/admin/customizer/customizer.css', array(), $shopper_version, 'all' );
		}

	}

endif;

return new Shopper_Admin();