<?php
/**
 * Shopper template functions.
 *
 * @package Shopper
 */

if ( ! function_exists( 'shopper_is_woocommerce_activated' ) ) {
	/**
	 * Query WooCommerce activation
	 */
	function shopper_is_woocommerce_activated() {
		return class_exists( 'woocommerce' ) ? true : false;
	}
}

if ( ! function_exists( 'shopper_pro_is_activated' ) ) {

	function shopper_pro_is_activated() {

		return class_exists( 'Shopper_Pro' ) ? true : false;
	}

}

/**
 * Checks if the current page is a product archive
 *
 * @since  1.0.0
 * @return boolean
 */
function shopper_is_product_archive() {
	if ( shopper_is_woocommerce_activated() ) {
		if ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

/**
 * Call a shortcode function by tag name.
 *
 * @since  1.0.0
 *
 * @param string $tag     The shortcode whose function to call.
 * @param array  $atts    The attributes to pass to the shortcode function. Optional.
 * @param array  $content The shortcode's content. Default is null (none).
 *
 * @return string|bool False on failure, the result of the shortcode on success.
 */
function shopper_do_shortcode( $tag, array $atts = array(), $content = null ) {
	global $shortcode_tags;

	if ( ! isset( $shortcode_tags[ $tag ] ) ) {
		return false;
	}

	return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
}

/**
 * Apply inline style to the shopper header.
 *
 * @uses  get_header_image()
 * @since  1.0.0
 */
function shopper_header_styles() {
	$is_header_image = get_header_image();

	if ( $is_header_image ) {
		$header_bg_image = 'url(' . esc_url( $is_header_image ) . ')';
	} else {
		$header_bg_image = 'none';
	}

	$styles = apply_filters( 'shopper_header_styles', array(
		'background-image' => $header_bg_image,
	) );

	foreach ( $styles as $style => $value ) {
		echo esc_attr( $style . ': ' . $value . '; ' );
	}
}

/**
 * Apply inline style to the shopper homepage content.
 *
 * @uses  get_the_post_thumbnail_url()
 * @since  1.0.0
 */
function shopper_homepage_content_styles() {
	$featured_image = get_the_post_thumbnail_url( get_the_ID() );

	if ( $featured_image ) {
		$background_image = 'url(' . esc_url( $featured_image ) . ')';
	} else {
		$background_image = 'none';
	}

	$styles = apply_filters( 'shopper_homepage_content_styles', array(
		'background-image' => $background_image,
	) );

	foreach ( $styles as $style => $value ) {
		echo esc_attr( $style . ': ' . $value . '; ' );
	}
}
/**
 * Get the content background color
 * Accounts for the shopper Designer and shopper Powerpack content background option.
 *
 * @since  1.0.0
 * @return string the background color
 */
function shopper_get_content_background_color() {

	return get_theme_mod( 'background_color' );
}

/**
 * Adjust a hex color brightness
 * Allows us to create hover styles for custom link colors
 *
 * @param  strong  $hex   hex color e.g. #111111.
 * @param  integer $steps factor by which to brighten/darken ranging from -255 (darken) to 255 (brighten).
 * @return string        brightened/darkened hex color
 * @since  1.0.0
 */
function shopper_adjust_color_brightness( $hex, $steps ) {
	// Steps should be between -255 and 255. Negative = darker, positive = lighter.
	$steps  = max( -255, min( 255, $steps ) );

	// Format the hex color string.
	$hex    = str_replace( '#', '', $hex );

	if ( 3 == strlen( $hex ) ) {
		$hex    = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
	}

	// Get decimal values.
	$r  = hexdec( substr( $hex, 0, 2 ) );
	$g  = hexdec( substr( $hex, 2, 2 ) );
	$b  = hexdec( substr( $hex, 4, 2 ) );

	// Adjust number of steps and keep it inside 0 to 255.
	$r  = max( 0, min( 255, $r + $steps ) );
	$g  = max( 0, min( 255, $g + $steps ) );
	$b  = max( 0, min( 255, $b + $steps ) );

	$r_hex  = str_pad( dechex( $r ), 2, '0', STR_PAD_LEFT );
	$g_hex  = str_pad( dechex( $g ), 2, '0', STR_PAD_LEFT );
	$b_hex  = str_pad( dechex( $b ), 2, '0', STR_PAD_LEFT );

	return '#' . $r_hex . $g_hex . $b_hex;
}

/**
 * Sanitizes choices (selects / radios)
 * Checks that the input matches one of the available choices
 *
 * @param array $input the available choices.
 * @param array $setting the setting object.
 * @since  1.3.0
 */
function shopper_sanitize_choices( $input, $setting ) {
	// Ensure input is a slug.
	$input = sanitize_key( $input );

	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;

	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Checkbox sanitization callback.
 *
 * Sanitization callback for 'checkbox' type controls. This callback sanitizes `$checked`
 * as a boolean value, either TRUE or FALSE.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 * @since  1.5.0
 */
function shopper_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * Get shopper homepage hooks functions
 *
 * @since 1.0.0
 * @return array
 */
function shopper_homepage_control_get_hooks() {

	global $wp_filter;

	$response = array();

	if ( isset( $wp_filter['shopper_homepage'] ) ) {
		
		$_hp = $wp_filter['shopper_homepage'];

		foreach ( $_hp as $k => $v ) {
			
			if ( is_array( $v ) ) {
				foreach ( $v as $i => $j ) {
					if ( is_array( $j['function'] ) ) {
						$i = get_class( $j['function'][0] ) . '@' . $j['function'][1];
						$response[$i] = shopper_homepage_control_format_title($j['function'][1]);
					} else {
						$response[$i] = shopper_homepage_control_format_title($i);
					}
				}
			}
		}
	}

	return $response;
}

/**
 * Format hook title
 *
 * @param  string
 * @since  1.0.0
 * @return string
 */
function shopper_homepage_control_format_title ( $key ) {

		$title = $key;

		$title = str_replace( '_', ' ', $title );
		$title = ucwords( $title );

		return $title;
}

/**
 * Default homepage function hookeds
 *
 * @since  1.0.0
 * @return string
 */
function shopper_homepage_control_format_defaults () {

		$components = shopper_homepage_control_get_hooks();

		$defaults = array();

		foreach ( $components as $k => $v ) {
			if ( apply_filters( 'shopper_homepage_control_hide_' . $k, false ) ) {
				$defaults[] = '[disabled]' . $k;
			} else {
				$defaults[] = $k;
			}
		}

		return $defaults;
}

/**
 * Sanitize homepage control
 *
 * @since 1.0.0
 *
 */
function shopper_homepage_contro_sanitize ( $values ) {

    $multi_values = !is_array( $values ) ? explode( ',', $values ) : $values;

    return !empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
}

/**
 * Image sanitization callback example.
 *
 * Checks the image's file extension and mime type against a whitelist. If they're allowed,
 * send back the filename, otherwise, return the setting default.
 *
 * - Sanitization: image file extension
 * - Control: text, WP_Customize_Image_Control
 *
 * @author WPTRT <https://github.com/WPTRT>
 * @author Shopper Team
 * @see wp_check_filetype() https://developer.wordpress.org/reference/functions/wp_check_filetype/
 * @since 1.0.0
 * @param string               $image   Image filename.
 * @param WP_Customize_Setting $setting Setting instance.
 * @link  https://github.com/WPTRT/code-examples
 * @return string The image filename if the extension is allowed; otherwise, the setting default.
 */
function shopper_sanitize_image( $image, $setting ) {
	/*
	 * Array of valid image file types.
	 *
	 * The array includes image mime types that are included in wp_get_mime_types()
	 */
    $mimes = array(
        'jpg|jpeg|jpe' => 'image/jpeg',
        'gif'          => 'image/gif',
        'png'          => 'image/png',
        'bmp'          => 'image/bmp',
        'tif|tiff'     => 'image/tiff',
        'ico'          => 'image/x-icon'
    );
	// Return an array with file extension and mime_type.
    $file = wp_check_filetype( $image, $mimes );
	// If $image has a valid mime_type, return it; otherwise, return the default.
    return ( $file['ext'] ? $image : $setting->default );
}

/**
* URL sanitization callback.
*
* - Sanitization: url
* - Control: text, url
*
* Sanitization callback for 'url' type text inputs. This callback sanitizes `$url` as a valid URL.
*
* NOTE: esc_url_raw() can be passed directly as `$wp_customize->add_setting()` 'sanitize_callback'.
* It is wrapped in a callback here merely for example purposes.
*
* @author WPTRT <https://github.com/WPTRT>
* @author Shopper Team
* @see    esc_url_raw() https://developer.wordpress.org/reference/functions/esc_url_raw/
* @since  1.0.0
* @param  [string] $url URL to sanitize.
* @return string Sanitized URL.
*/
function shopper_sanitize_url( $url ) {
		return esc_url_raw( $url );
}
