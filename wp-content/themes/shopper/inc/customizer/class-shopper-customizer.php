<?php
/**
 * Shopper Customizer Class
 *
 *
 * @package  Shopper
 * @author   WooThemes
 * @author   ShopperWP
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Shopper_Customizer' ) ) :

	/**
	 * The Shopper Customizer class
	 */
	class Shopper_Customizer {

		/**
		 * Setup class.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_action( 'customize_register',              array( $this, 'customize_register' ), 10 );
			add_filter( 'body_class',                      array( $this, 'layout_class' ), 40 );
			add_action( 'wp_enqueue_scripts',              array( $this, 'add_customizer_css' ), 130 );
			add_action( 'after_setup_theme',               array( $this, 'custom_header_setup' ) );
			add_action( 'customize_controls_print_styles', array( $this, 'customizer_custom_control_css' ) );
			add_action('customize_controls_print_scripts', array( $this, 'customizer_custom_control_js' ) );
			add_action( 'customize_register',              array( $this, 'edit_default_customizer_settings' ), 99 );
			add_action( 'init',                            array( $this, 'default_theme_mod_values' ), 10 );

			add_action( 'after_switch_theme',              array( $this, 'set_shopper_style_theme_mods' ) );
			add_action( 'customize_save_after',            array( $this, 'set_shopper_style_theme_mods' ) );


			/* Render Components. */
			if ( ! is_admin() ) {

				add_action( 'get_header', array( $this, 'maybe_apply_render_homepage_component' ) );

				$layout = get_theme_mod( 'shopper_layout' );
				if ( $layout === 'none' ) {
					add_action( '', array( $this, 'remove_sidebars' ) );

				}
			}
		}

		/**
		 * Returns an array of the desired default shopper Options
		 *
		 * @since 1.0.0
		 * @return array
		 */
		public static function get_shopper_default_setting_values() {
			return apply_filters( 'shopper_setting_default_values', $args = array(

				'shopper_heading_color'                      => '#484c51',
				'shopper_text_color'                         => '#43454b',
				'shopper_accent_color'                       => '#ff6600',
				'shopper_header_background_color'            => '#ffffff',
				'shopper_header_text_color'                  => '#9aa0a7',
				'shopper_header_link_color'                  => '#666666',
				'shopper_header_link_hover_color'            => '#ff6600',
				'shopper_footer_background_color'            => '#333333',
				'shopper_widget_footer_background_color'     => '#666666',
				'shopper_footer_heading_color'               => '#ffffff',
				'shopper_footer_text_color'                  => '#cccccc',
				'shopper_button_background_color'            => '#ff6600',
				'shopper_button_text_color'                  => '#ffffff',
				'shopper_button_alt_background_color'        => '#2c2d33',
				'shopper_button_alt_text_color'              => '#ffffff',
				'shopper_layout'                             => 'left',
				'background_color'                           => '#ffffff',
			) );
		}

		/**
		 * Adds a value to each shopper setting if one isn't already present.
		 *
		 * @since 1.0.0
		 * @uses get_shopper_default_setting_values()
		 */
		public function default_theme_mod_values() {
			foreach ( self::get_shopper_default_setting_values() as $mod => $val ) {
				add_filter( 'theme_mod_' . $mod, array( $this, 'get_theme_mod_value' ), 10 );
			}
		}

		/**
		 * Get theme mod value.
		 *
		 * @since 1.0.0
		 * @param string $value
		 * @return string
		 */
		public function get_theme_mod_value( $value ) {
			$key = substr( current_filter(), 10 );

			$set_theme_mods = get_theme_mods();

			if ( isset( $set_theme_mods[ $key ] ) ) {
				return $value;
			}

			$values = $this->get_shopper_default_setting_values();

			return isset( $values[ $key ] ) ? $values[ $key ] : $value;
		}

		/**
		 * Set Customizer setting defaults.
		 * These defaults need to be applied separately as child themes can filter shopper_setting_default_values
		 *
		 * @since 1.0.0
		 * @param  array $wp_customize the Customizer object.
		 * @uses   get_shopper_default_setting_values()
		 */
		public function edit_default_customizer_settings( $wp_customize ) {
			foreach ( self::get_shopper_default_setting_values() as $mod => $val ) {
				$wp_customize->get_setting( $mod )->default = $val;
			}
		}

		/**
		 * Setup the WordPress core custom header feature.
		 *
		 * @uses shopper_header_style()
		 * @uses shopper_admin_header_style()
		 * @uses shopper_admin_header_image()
		 */
		public function custom_header_setup() {
			add_theme_support( 'custom-header', apply_filters( 'shopper_custom_header_args', array(
				'default-image' => '',
				'header-text'   => false,
				'width'         => 1950,
				'height'        => 500,
				'flex-width'    => true,
				'flex-height'   => true,
			) ) );
		}

		/**
		 * Add postMessage support for site title and description for the Theme Customizer along with several other settings.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 * @since  1.0.0
		 */
		public function customize_register( $wp_customize ) {

			// Move background color setting alongside background image.
			$wp_customize->get_control( 'background_color' )->section   = 'background_image';
			$wp_customize->get_control( 'background_color' )->priority  = 20;

			// Change background image section title & priority.
			$wp_customize->get_section( 'background_image' )->title     = __( 'Background', 'shopper' );
			$wp_customize->get_section( 'background_image' )->priority  = 30;

			// Change header image section title & priority.
			$wp_customize->get_section( 'header_image' )->title         = __( 'Header', 'shopper' );
			$wp_customize->get_section( 'header_image' )->priority      = 25;

			// Selective refresh.
			if ( function_exists( 'add_partial' ) ) {
				$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
				$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

				$wp_customize->selective_refresh->add_partial( 'custom_logo', array(
					'selector'        => '.site-branding',
					'render_callback' => array( $this, 'get_site_logo' ),
				) );

				$wp_customize->selective_refresh->add_partial( 'blogname', array(
					'selector'        => '.site-title.beta a',
					'render_callback' => array( $this, 'get_site_name' ),
				) );

				$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
					'selector'        => '.site-description',
					'render_callback' => array( $this, 'get_site_description' ),
				) );
			}

			/**
			 * Custom controls
			 */
			require_once dirname( __FILE__ ) . '/class-shopper-control-multicheck.php';
			require_once dirname( __FILE__ ) . '/class-shopper-customizer-control-radio-image.php';
			require_once dirname( __FILE__ ) . '/class-shopper-customizer-control-arbitrary.php';

			if ( apply_filters( 'shopper_customizer_more', true ) ) {
				require_once dirname( __FILE__ ) . '/class-shopper-customizer-control-more.php';
			}

			/**
			 * Add the typography section
			 */
			$wp_customize->add_section( 'shopper_typography' , array(
				'title'      			=> __( 'Color', 'shopper' ),
				'priority'   			=> 45,
			) );

			/**
			 * Heading color
			 */
			$wp_customize->add_setting( 'shopper_heading_color', array(
				'default'           	=> apply_filters( 'shopper_default_heading_color', '#484c51' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'shopper_heading_color', array(
				'label'	   				=> __( 'Heading color', 'shopper' ),
				'section'  				=> 'shopper_typography',
				'settings' 				=> 'shopper_heading_color',
				'priority' 				=> 20,
			) ) );

			/**
			 * Text Color
			 */
			$wp_customize->add_setting( 'shopper_text_color', array(
				'default'           	=> apply_filters( 'shopper_default_text_color', '#43454b' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'shopper_text_color', array(
				'label'					=> __( 'Text color', 'shopper' ),
				'section'				=> 'shopper_typography',
				'settings'				=> 'shopper_text_color',
				'priority'				=> 30,
			) ) );

			/**
			 * Accent Color
			 */
			$wp_customize->add_setting( 'shopper_accent_color', array(
				'default'           	=> apply_filters( 'shopper_default_accent_color', '#ff6600' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'shopper_accent_color', array(
				'label'	   				=> __( 'Link / accent color', 'shopper' ),
				'section'  				=> 'shopper_typography',
				'settings' 				=> 'shopper_accent_color',
				'priority' 				=> 40,
			) ) );

			$wp_customize->add_control( new Arbitrary_shopper_Control( $wp_customize, 'shopper_header_image_heading', array(
				'section'  				=> 'header_image',
				'type' 					=> 'heading',
				'label'					=> __( 'Header background image', 'shopper' ),
				'priority' 				=> 6,
			) ) );

			/**
			 * Header Background
			 */
			$wp_customize->add_setting( 'shopper_header_background_color', array(
				'default'           	=> apply_filters( 'shopper_default_header_background_color', '#ffffff' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'shopper_header_background_color', array(
				'label'	   				=> __( 'Background color', 'shopper' ),
				'section'  				=> 'header_image',
				'settings' 				=> 'shopper_header_background_color',
				'priority' 				=> 15,
			) ) );

			/**
			 * Header text color
			 */
			$wp_customize->add_setting( 'shopper_header_text_color', array(
				'default'           	=> apply_filters( 'shopper_default_header_text_color', '#9aa0a7' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'shopper_header_text_color', array(
				'label'	   				=> __( 'Text color', 'shopper' ),
				'section'  				=> 'header_image',
				'settings' 				=> 'shopper_header_text_color',
				'priority' 				=> 20,
			) ) );

			/**
			 * Header link color
			 */
			$wp_customize->add_setting( 'shopper_header_link_color', array(
				'default'           	=> apply_filters( 'shopper_default_header_link_color', '#666666' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'shopper_header_link_color', array(
				'label'	   				=> __( 'Link color', 'shopper' ),
				'section'  				=> 'header_image',
				'settings' 				=> 'shopper_header_link_color',
				'priority' 				=> 30,
			) ) );

				/**
			 * Header link Hover color
			 */
			$wp_customize->add_setting( 'shopper_header_link_hover_color', array(
				'default'           	=> apply_filters( 'shopper_default_header_link_hover_color', '#ff6600' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'shopper_header_link_hover_color', array(
				'label'	   				=> __( 'Link Hover color', 'shopper' ),
				'section'  				=> 'header_image',
				'settings' 				=> 'shopper_header_link_hover_color',
				'priority' 				=> 40,
			) ) );

			/**
			 * Homepage Section
			 */
			if ( shopper_is_woocommerce_activated() ) {
				$wp_customize->add_section( 'shopper_homepage_banner' , array(
					'title'      			=> __( 'Shopper Homepage', 'shopper' ),
					'priority'   			=> 27,
				) );


				$wp_customize->add_setting(
			        'shopper_homepage_control',
			        array(
			            'default'           => shopper_homepage_control_format_defaults(),
			            'sanitize_callback' => 'shopper_homepage_contro_sanitize'
			        )
			    );

			    $wp_customize->add_control(
			        new Shopper_Customize_Control_Checkbox_Multiple(
			            $wp_customize,
			            'shopper_homepage_control',
			            array(
			                'section' => 'shopper_homepage_banner',
			                'label'   => __( 'Homepage Components', 'shopper' ),
			                'priority'	=> 80,
			                'choices' => shopper_homepage_control_get_hooks()
			            )
			        )
			    );
			}
			/**
			 * Footer section
			 */
			$wp_customize->add_section( 'shopper_footer' , array(
				'title'      			=> __( 'Footer', 'shopper' ),
				'priority'   			=> 28,
				'description' 			=> __( 'Customise the look & feel of your web site footer.', 'shopper' ),
			) );

			/**
			 * Footer Background
			 */
			$wp_customize->add_setting( 'shopper_footer_background_color', array(
				'default'           	=> apply_filters( 'shopper_default_footer_background_color', '#333333' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'shopper_footer_background_color', array(
				'label'	   				=> __( 'Background color', 'shopper' ),
				'section'  				=> 'shopper_footer',
				'settings' 				=> 'shopper_footer_background_color',
				'priority'				=> 10,
			) ) );


			/**
			 * Widget Footer Background
			 */
			$wp_customize->add_setting( 'shopper_widget_footer_background_color', array(
				'default'           	=> apply_filters( 'shopper_default_widget_footer_background_color', '#666666' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'shopper_widget_footer_background_color', array(
				'label'	   				=> __( 'Widget Background color', 'shopper' ),
				'section'  				=> 'shopper_footer',
				'settings' 				=> 'shopper_widget_footer_background_color',
				'priority'				=> 10,
			) ) );


			/**
			 * Footer heading color
			 */
			$wp_customize->add_setting( 'shopper_footer_heading_color', array(
				'default'           	=> apply_filters( 'shopper_default_footer_heading_color', '#ffffff' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'shopper_footer_heading_color', array(
				'label'	   				=> __( 'Heading color', 'shopper' ),
				'section'  				=> 'shopper_footer',
				'settings' 				=> 'shopper_footer_heading_color',
				'priority'				=> 20,
			) ) );

			/**
			 * Footer text color
			 */
			$wp_customize->add_setting( 'shopper_footer_text_color', array(
				'default'           	=> apply_filters( 'shopper_default_footer_text_color', '#cccccc' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'shopper_footer_text_color', array(
				'label'	   				=> __( 'Text color', 'shopper' ),
				'section'  				=> 'shopper_footer',
				'settings' 				=> 'shopper_footer_text_color',
				'priority'				=> 30,
			) ) );

			/**
			 * Buttons section
			 */
			$wp_customize->add_section( 'shopper_buttons' , array(
				'title'      			=> __( 'Buttons', 'shopper' ),
				'priority'   			=> 45,
				'description' 			=> __( 'Customise the look & feel of your web site buttons.', 'shopper' ),
			) );

			/**
			 * Button background color
			 */
			$wp_customize->add_setting( 'shopper_button_background_color', array(
				'default'           	=> apply_filters( 'shopper_default_button_background_color', '#ff6600' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'shopper_button_background_color', array(
				'label'	   				=> __( 'Background color', 'shopper' ),
				'section'  				=> 'shopper_buttons',
				'settings' 				=> 'shopper_button_background_color',
				'priority' 				=> 10,
			) ) );

			/**
			 * Button text color
			 */
			$wp_customize->add_setting( 'shopper_button_text_color', array(
				'default'           	=> apply_filters( 'shopper_default_button_text_color', '#ffffff' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'shopper_button_text_color', array(
				'label'	   				=> __( 'Text color', 'shopper' ),
				'section'  				=> 'shopper_buttons',
				'settings' 				=> 'shopper_button_text_color',
				'priority' 				=> 20,
			) ) );

			/**
			 * Button alt background color
			 */
			$wp_customize->add_setting( 'shopper_button_alt_background_color', array(
				'default'           	=> apply_filters( 'shopper_default_button_alt_background_color', '#2c2d33' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'shopper_button_alt_background_color', array(
				'label'	   				=> __( 'Alternate button background color', 'shopper' ),
				'section'  				=> 'shopper_buttons',
				'settings' 				=> 'shopper_button_alt_background_color',
				'priority' 				=> 30,
			) ) );

			/**
			 * Button alt text color
			 */
			$wp_customize->add_setting( 'shopper_button_alt_text_color', array(
				'default'           	=> apply_filters( 'shopper_default_button_alt_text_color', '#ffffff' ),
				'sanitize_callback' 	=> 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'shopper_button_alt_text_color', array(
				'label'	   				=> __( 'Alternate button text color', 'shopper' ),
				'section'  				=> 'shopper_buttons',
				'settings' 				=> 'shopper_button_alt_text_color',
				'priority' 				=> 40,
			) ) );

			/**
			 * Layout
			 */
			$wp_customize->add_section( 'shopper_layout' , array(
				'title'      			=> __( 'Layout', 'shopper' ),
				'priority'   			=> 50,
			) );

			$wp_customize->add_setting( 'shopper_layout', array(
				'default'    			=> apply_filters( 'shopper_default_layout', $layout = is_rtl() ? 'left' : 'right' ),
				'sanitize_callback' 	=> 'shopper_sanitize_choices',
			) );

			$wp_customize->add_control( new shopper_Custom_Radio_Image_Control( $wp_customize, 'shopper_layout', array(
				'settings'				=> 'shopper_layout',
				'section'				=> 'shopper_layout',
				'label'					=> __( 'General Layout', 'shopper' ),
				'priority'				=> 1,
				'choices'				=> array(
											'left'  => get_template_directory_uri() . '/assets/images/customizer/controls/col-2cr.png',
											'none'  => get_template_directory_uri() . '/assets/images/customizer/controls/col-1cl.png',
											'right' => get_template_directory_uri() . '/assets/images/customizer/controls/col-2cl.png',
				),
			) ) );

			/**
			 * More
			 */
			if ( apply_filters( 'shopper_customizer_more', true ) ) {
				$wp_customize->add_section( 'shopper_more' , array(
					'title'      		=> __( 'More', 'shopper' ),
					'priority'   		=> 999,
				) );

				$wp_customize->add_setting( 'shopper_more', array(
					'default'    		=> null,
					'sanitize_callback' => 'sanitize_text_field',
				) );

				$wp_customize->add_control( new More_shopper_Control( $wp_customize, 'shopper_more', array(
					'label'    			=> __( 'Looking for more options?', 'shopper' ),
					'section'  			=> 'shopper_more',
					'settings' 			=> 'shopper_more',
					'priority' 			=> 1,
				) ) );
			}

			// Remove control hooks
			$this->_remove_controls( $wp_customize );
		}

		/**
		 * Hook to remove some controls
		 *
		 * @param  WP_Customize
		 * @return void
		 */
		private function _remove_controls( $wp_customize ) {

			$controls = apply_filters('shopper_remove_customize_control', array() );

			foreach ($controls as $control ) {

				$wp_customize->remove_control($control);

			}
		}

		/**
		 * Get all of the shopper theme mods.
		 *
		 *@since 1.0.0
		 * @return array $shopper_theme_mods The shopper Theme Mods.
		 */
		public function get_shopper_theme_mods() {
			$shopper_theme_mods = array(
				'background_color'               => shopper_get_content_background_color(),
				'accent_color'                   => get_theme_mod( 'shopper_accent_color' ),
				'header_background_color'        => get_theme_mod( 'shopper_header_background_color' ),
				'header_link_color'              => get_theme_mod( 'shopper_header_link_color' ),
				'header_link_hover_color'        => get_theme_mod( 'shopper_header_link_hover_color' ),
				'header_text_color'              => get_theme_mod( 'shopper_header_text_color' ),
				'footer_background_color'        => get_theme_mod( 'shopper_footer_background_color' ),
				'widget_footer_background_color' => get_theme_mod( 'shopper_widget_footer_background_color' ),
				'footer_heading_color'           => get_theme_mod( 'shopper_footer_heading_color' ),
				'footer_text_color'              => get_theme_mod( 'shopper_footer_text_color' ),
				'text_color'                     => get_theme_mod( 'shopper_text_color' ),
				'heading_color'                  => get_theme_mod( 'shopper_heading_color' ),
				'button_background_color'        => get_theme_mod( 'shopper_button_background_color' ),
				'button_text_color'              => get_theme_mod( 'shopper_button_text_color' ),
				'button_alt_background_color'    => get_theme_mod( 'shopper_button_alt_background_color' ),
				'button_alt_text_color'          => get_theme_mod( 'shopper_button_alt_text_color' ),
			);

			return apply_filters( 'shopper_theme_mods', $shopper_theme_mods );
		}

		/**
		 * Get Customizer css.
		 *
		 * @see get_shopper_theme_mods()
		 * @since 1.0.0
		 * @return array $styles the css
		 */
		public function get_css() {
			$shopper_theme_mods = $this->get_shopper_theme_mods();
			$brighten_factor       = apply_filters( 'shopper_brighten_factor', 25 );
			$darken_factor         = apply_filters( 'shopper_darken_factor', -25 );

			$styles                = '
			.main-navigation ul li a,
			.site-title a,
			.site-branding h1 a,
			.site-footer .shopper-handheld-footer-bar a:not(.button) {
				color: ' . $shopper_theme_mods['header_link_color'] . ';
			}

			.main-navigation ul li a:hover,
			.main-navigation ul li:hover > a,
			.site-title a:hover,
			a.cart-contents:hover,
			.header-myacc-link a:hover,
			.site-header-cart .widget_shopping_cart a:hover,
			.site-header-cart:hover > li > a,
			.site-header ul.menu li.current-menu-item > a,
			.site-header ul.menu li.current-menu-parent > a {
				color: ' . $shopper_theme_mods['header_link_hover_color'] . ';
			}

			table th {
				background-color: ' . shopper_adjust_color_brightness( $shopper_theme_mods['background_color'], -7 ) . ';
			}

			table tbody td {
				background-color: ' . shopper_adjust_color_brightness( $shopper_theme_mods['background_color'], -2 ) . ';
			}

			table tbody tr:nth-child(2n) td {
				background-color: ' . shopper_adjust_color_brightness( $shopper_theme_mods['background_color'], -4 ) . ';
			}

			.site-header,
			.main-navigation ul.menu > li.menu-item-has-children:after,
			.shopper-handheld-footer-bar,
			.shopper-handheld-footer-bar ul li > a,
			.shopper-handheld-footer-bar ul li.search .site-search {
				background-color: ' . $shopper_theme_mods['header_background_color'] . ';
			}

			p.site-description,
			.site-header,
			.shopper-handheld-footer-bar {
				color: ' . $shopper_theme_mods['header_text_color'] . ';
			}

			.shopper-handheld-footer-bar ul li.cart .count {
				background-color: ' . $shopper_theme_mods['header_link_color'] . ';
			}

			.shopper-handheld-footer-bar ul li.cart .count {
				color: ' . $shopper_theme_mods['header_background_color'] . ';
			}

			.shopper-handheld-footer-bar ul li.cart .count {
				border-color: ' . $shopper_theme_mods['header_background_color'] . ';
			}

			h1, h2, h3, h4, h5, h6 {
				color: ' . $shopper_theme_mods['heading_color'] . ';
			}
			.widget .widget-title, .widget .widgettitle, .shopper-latest-from-blog .recent-post-title, .entry-title a {
				color: ' . $shopper_theme_mods['heading_color'] . ';
			}

			.widget h1 {
				border-bottom-color: ' . $shopper_theme_mods['heading_color'] . ';
			}

			body,
			.page-numbers li .page-numbers:not(.current),
			.page-numbers li .page-numbers:not(.current) {
				color: ' . $shopper_theme_mods['text_color'] . ';
			}

			.widget-area .widget a,
			.hentry .entry-header .posted-on a,
			.hentry .entry-header .byline a {
				color: ' . shopper_adjust_color_brightness( $shopper_theme_mods['text_color'], 50 ) . ';
			}
			.site-main nav.navigation .nav-previous a, .widget_nav_menu ul.menu li.current-menu-item > a, .widget ul li.current-cat-ancestor > a, .widget_nav_menu ul.menu li.current-menu-ancestor > a, .site-main nav.navigation .nav-next a, .widget ul li.current-cat > a, .widget ul li.current-cat-parent > a, a  {
				color: ' . $shopper_theme_mods['accent_color'] . ';
			}
			button, input[type="button"], input[type="reset"], input[type="submit"], .button, .widget a.button, .site-header-cart .widget_shopping_cart a.button, .back-to-top, .page-numbers li .page-numbers:hover,
				.shopper-hero-box .hero-box-wrap.owl-carousel .owl-controls .owl-next,
				.shopper-hero-box .hero-box-wrap.owl-carousel .owl-controls .owl-prev
			 {
				background-color: ' . $shopper_theme_mods['button_background_color'] . ';
				border-color: ' . $shopper_theme_mods['button_background_color'] . ';
				color: ' . $shopper_theme_mods['button_text_color'] . ';
			}



			.button.alt:hover, button.alt:hover, widget a.button.checkout:hover, button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, .button:hover, .widget a.button:hover, .site-header-cart .widget_shopping_cart a.button:hover, .back-to-top:hover, input[type="submit"]:disabled:hover {
				background-color: ' . shopper_adjust_color_brightness( $shopper_theme_mods['button_background_color'], $darken_factor ) . ';
				border-color: ' . shopper_adjust_color_brightness( $shopper_theme_mods['button_background_color'], $darken_factor ) . ';
				color: ' . $shopper_theme_mods['button_text_color'] . ';
			}

			button.alt, input[type="button"].alt, input[type="reset"].alt, input[type="submit"].alt, .button.alt, .added_to_cart.alt, .widget-area .widget a.button.alt, .added_to_cart, .pagination .page-numbers li .page-numbers.current, .woocommerce-pagination .page-numbers li .page-numbers.current, .widget a.button.checkout {
				background-color: ' . $shopper_theme_mods['button_background_color'] . ';
				border-color: ' . $shopper_theme_mods['button_background_color'] . ';
				color: ' . $shopper_theme_mods['button_alt_text_color'] . ';
			}

			 input[type="button"].alt:hover, input[type="reset"].alt:hover, input[type="submit"].alt:hover,  .added_to_cart.alt:hover, .widget-area .widget a.button.alt:hover {
				background-color: ' . shopper_adjust_color_brightness( $shopper_theme_mods['button_alt_background_color'], $darken_factor ) . ';
				border-color: ' . shopper_adjust_color_brightness( $shopper_theme_mods['button_alt_background_color'], $darken_factor ) . ';
				color: ' . $shopper_theme_mods['button_alt_text_color'] . ';
			}

			.site-footer {
				background-color: ' . $shopper_theme_mods['footer_background_color'] . ';
				color: ' . $shopper_theme_mods['footer_text_color'] . ';
			}

			.footer-widgets {
				background-color: ' . $shopper_theme_mods['widget_footer_background_color'] . ';
			}

			.footer-widgets .widget-title {
				color: ' . $shopper_theme_mods['footer_heading_color'] . ';
			}

			.site-footer h1, .site-footer h2, .site-footer h3, .site-footer h4, .site-footer h5, .site-footer h6 {
				color: ' . $shopper_theme_mods['footer_heading_color'] . ';
			}


			.site-info,
			.footer-widgets .product_list_widget a:hover,
			.site-footer a:not(.button) {
				color: ' . $shopper_theme_mods['footer_text_color'] . ';
			}

			#order_review,
			#payment .payment_methods > li .payment_box {
				background-color: ' . $shopper_theme_mods['background_color'] . ';
			}

			#payment .payment_methods > li {
				background-color: ' . shopper_adjust_color_brightness( $shopper_theme_mods['background_color'], -5 ) . ';
			}

			#payment .payment_methods > li:hover {
				background-color: ' . shopper_adjust_color_brightness( $shopper_theme_mods['background_color'], -10 ) . ';
			}

			.hentry .entry-content .more-link {
				border-color: ' . $shopper_theme_mods['button_background_color'] . ';
				color: ' . $shopper_theme_mods['button_background_color'] . ';
			}
			.hentry .entry-content .more-link:hover {
				background-color: ' . $shopper_theme_mods['button_background_color'] . ';
			}

			@media screen and ( min-width: 768px ) {
				/*
				.secondary-navigation ul.menu a:hover {
					color: ' . shopper_adjust_color_brightness( $shopper_theme_mods['header_text_color'], $brighten_factor ) . ';
				}

				.secondary-navigation ul.menu a {
					color: ' . $shopper_theme_mods['header_text_color'] . ';
				}*/

				.site-header-cart .widget_shopping_cart,
				.main-navigation ul.menu ul.sub-menu,
				.main-navigation ul.nav-menu ul.children {
					background-color: ' . shopper_adjust_color_brightness( $shopper_theme_mods['header_background_color'], -8 ) . ';
				}

			}';

			return apply_filters( 'shopper_customizer_css', $styles );
		}

		/**
		 * Get Customizer css associated with WooCommerce.
		 *
		 * @see get_shopper_theme_mods()
		 * @return array $woocommerce_styles the WooCommerce css
		 */
		public function get_woocommerce_css() {
			$shopper_theme_mods = $this->get_shopper_theme_mods();
			$brighten_factor       = apply_filters( 'shopper_brighten_factor', 25 );
			$darken_factor         = apply_filters( 'shopper_darken_factor', -25 );

			$woocommerce_styles    = '
			a.cart-contents,
			.header-myacc-link a,
			.site-header-cart .widget_shopping_cart a {
				color: ' . $shopper_theme_mods['header_link_color'] . ';
			}



			table.cart td.product-remove,
			table.cart td.actions {
				border-top-color: ' . $shopper_theme_mods['background_color'] . ';
			}

			.woocommerce-tabs ul.tabs li.active a,
			ul.products li.product .price,
			.widget_search form:before,
			.widget_product_search form:before {
				color: ' . $shopper_theme_mods['text_color'] . ';
			}

			.woocommerce-breadcrumb a,
			a.woocommerce-review-link,
			.product_meta a {
				color: ' . shopper_adjust_color_brightness( $shopper_theme_mods['text_color'], 50 ) . ';
			}

			.star-rating span:before,
			.quantity .plus, .quantity .minus,
			p.stars a:hover:after,
			p.stars a:after,
			.star-rating span:before,
			#payment .payment_methods li input[type=radio]:first-child:checked+label:before {
				color: ' . $shopper_theme_mods['button_background_color'] . ';
			}

			.widget_price_filter .ui-slider .ui-slider-range,
			.widget_price_filter .ui-slider .ui-slider-handle {
				background-color: ' . $shopper_theme_mods['accent_color'] . ';
			}

			.woocommerce-breadcrumb,
			#reviews .commentlist li .comment_container {
				background-color: ' . shopper_adjust_color_brightness( $shopper_theme_mods['background_color'], -7 ) . ';
			}

			.order_details {
				background-color: ' . shopper_adjust_color_brightness( $shopper_theme_mods['background_color'], -7 ) . ';
			}

			.order_details > li {
				border-bottom: 1px dotted ' . shopper_adjust_color_brightness( $shopper_theme_mods['background_color'], -28 ) . ';
			}

			.order_details:before,
			.order_details:after {
				background: -webkit-linear-gradient(transparent 0,transparent 0),-webkit-linear-gradient(135deg,' . shopper_adjust_color_brightness( $shopper_theme_mods['background_color'], -7 ) . ' 33.33%,transparent 33.33%),-webkit-linear-gradient(45deg,' . shopper_adjust_color_brightness( $shopper_theme_mods['background_color'], -7 ) . ' 33.33%,transparent 33.33%)
			}

			p.stars a:before,
			p.stars a:hover~a:before,
			p.stars.selected a.active~a:before {
				color: ' . $shopper_theme_mods['text_color'] . ';
			}

			p.stars.selected a.active:before,
			p.stars:hover a:before,
			p.stars.selected a:not(.active):before,
			p.stars.selected a.active:before {
				color: ' . $shopper_theme_mods['accent_color'] . ';
			}

			.single-product div.product .woocommerce-product-gallery .woocommerce-product-gallery__trigger {
				background-color: ' . $shopper_theme_mods['button_background_color'] . ';
				color: ' . $shopper_theme_mods['button_text_color'] . ';
			}

			.single-product div.product .woocommerce-product-gallery .woocommerce-product-gallery__trigger:hover {
				background-color: ' . shopper_adjust_color_brightness( $shopper_theme_mods['button_background_color'], $darken_factor ) . ';
				border-color: ' . shopper_adjust_color_brightness( $shopper_theme_mods['button_background_color'], $darken_factor ) . ';
				color: ' . $shopper_theme_mods['button_text_color'] . ';
			}


			.site-main ul.products li.product:hover .woocommerce-loop-category__title,
			.site-header-cart .cart-contents .count,
			.added_to_cart, .onsale {
				background-color: ' . $shopper_theme_mods['button_background_color'] . ';
				color: ' . $shopper_theme_mods['button_text_color'] . ';
			}
			.added_to_cart:hover {
					background-color: ' . shopper_adjust_color_brightness( $shopper_theme_mods['button_background_color'], $darken_factor ) . ';
			}
			.widget_price_filter .ui-slider .ui-slider-range, .widget_price_filter .ui-slider .ui-slider-handle,
			.widget .tagcloud a:hover, .widget_price_filter .ui-slider .ui-slider-range, .widget_price_filter .ui-slider .ui-slider-handle, .hentry.type-post .entry-header:after {
				background-color: ' . $shopper_theme_mods['button_background_color'] . ';
			}
			.widget .tagcloud a:hover {
				border-color:  ' . $shopper_theme_mods['button_background_color'] . ';
			}

			.widget_product_categories > ul li.current-cat-parent > a, .widget_product_categories > ul li.current-cat > a {
				color: ' . $shopper_theme_mods['accent_color'] . ';
			}

			@media screen and ( min-width: 768px ) {
				.site-header-cart .widget_shopping_cart,
				.site-header .product_list_widget li .quantity {
					color: ' . $shopper_theme_mods['header_text_color'] . ';
				}
			}';

			return apply_filters( 'shopper_customizer_woocommerce_css', $woocommerce_styles );
		}

		/**
		 * Assign shopper styles to individual theme mods.
		 *
		 * @return void
		 */
		public function set_shopper_style_theme_mods() {
			set_theme_mod( 'shopper_styles', $this->get_css() );
			set_theme_mod( 'shopper_woocommerce_styles', $this->get_woocommerce_css() );
		}

		/**
		 * Add CSS in <head> for styles handled by the theme customizer
		 * If the Customizer is active pull in the raw css. Otherwise pull in the prepared theme_mods if they exist.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function add_customizer_css() {
			$shopper_styles             = get_theme_mod( 'shopper_styles' );
			$shopper_woocommerce_styles = get_theme_mod( 'shopper_woocommerce_styles' );

			if ( is_customize_preview() || ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) || ( false === $shopper_styles && false === $shopper_woocommerce_styles ) ) {
				wp_add_inline_style( 'shopper-style', $this->get_css() );
				wp_add_inline_style( 'shopper-woocommerce-style', $this->get_woocommerce_css() );
			} else {
				wp_add_inline_style( 'shopper-style', get_theme_mod( 'shopper_styles' ) );
				wp_add_inline_style( 'shopper-woocommerce-style', get_theme_mod( 'shopper_woocommerce_styles' ) );
			}
		}

		/**
		 * Layout classes
		 * Adds 'right-sidebar' and 'left-sidebar' classes to the body tag
		 *
		 * @param  array $classes current body classes.
		 * @return string[]          modified body classes
		 * @since  1.0.0
		 */
		public function layout_class( $classes ) {

			$left_or_right = get_theme_mod( 'shopper_layout' );

			$classes[] = $left_or_right . '-sidebar';

			return $classes;
		}

		/**
		 * Add CSS for custom controls
		 *
		 * This function incorporates CSS from the Kirki Customizer Framework
		 *
		 * The Kirki Customizer Framework, Copyright Aristeides Stathopoulos (@aristath),
		 * is licensed under the terms of the GNU GPL, Version 2 (or later)
		 *
		 * @link https://github.com/reduxframework/kirki/
		 * @since  1.0.0
		 */
		public function customizer_custom_control_css() {
			?>
			<style>
			.customize-control-radio-image .image.ui-buttonset input[type=radio] {
				height: auto;
			}

			.customize-control-radio-image .image.ui-buttonset label {
				display: inline-block;
				width: auto;
				padding: 1%;
				box-sizing: border-box;
			}

			.customize-control-radio-image .image.ui-buttonset label.ui-state-active {
				background: none;
			}

			.customize-control-radio-image .customize-control-radio-buttonset label {
				background: #f7f7f7;
				line-height: 35px;
			}

			.customize-control-radio-image label img {
				opacity: 0.5;
			}

			#customize-controls .customize-control-radio-image label img {
				height: auto;
			}

			.customize-control-radio-image label.ui-state-active img {
				background: #dedede;
				opacity: 1;
			}

			.customize-control-radio-image label.ui-state-hover img {
				opacity: 1;
				box-shadow: 0 0 0 3px #f6f6f6;
			}
			</style>
			<?php
		}

		/**
		 *
		 */
		public function customizer_custom_control_js() {


		}
		/**
		 * Get site logo.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function get_site_logo() {
			return shopper_site_title_or_logo( false );
		}

		/**
		 * Get site name.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function get_site_name() {
			return get_bloginfo( 'name', 'display' );
		}

		/**
		 * Get site description.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function get_site_description() {
			return get_bloginfo( 'description', 'display' );
		}

		/**
		 * Remove Sidebar for one column layout
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function remove_sidebars() {
			// Shop Sidebar
			remove_action( 'shopper_shop_sidebar', 'shopper_shop_sidebar', 10 );

			// General Sidebar
			add_action( 'shopper_sidebar',        'shopper_get_sidebar',   10 );
		}

		/**
		 * Render homepage components
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function maybe_apply_render_homepage_component() {

			// Skip if woocommerce doesn't activate
			if ( ! shopper_is_woocommerce_activated() ) {

				return false;
			}

			$options = get_theme_mod( 'shopper_homepage_control' );


			// Use pro options if it is available
			if ( function_exists ('shopper_pro_get_homepage_hooks') ) {

				$options = shopper_pro_get_homepage_hooks();
			}

			$components = array();

			if ( isset( $options ) && '' != $options ) {

				$components = $options ;

				// Remove all existing actions on shopper_homepage.
				remove_all_actions( 'shopper_homepage' );

				foreach ($components as $k => $v) {

					if ( function_exists( $v ) ) {
							add_action( 'shopper_homepage', esc_attr( $v ), $k );
					}
				}

			}

		}

	}

endif;

return new Shopper_Customizer();
