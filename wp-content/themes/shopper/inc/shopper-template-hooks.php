<?php
/**
 * Shopper hooks
 *
 * @package Shopper
 */
/**
 * General
 *
 * @see  shopper_header_widget_region()
 * @see  shopper_get_sidebar()
 */
add_action( 'shopper_before_content', 'shopper_header_widget_region', 		10 );
add_action( 'shopper_sidebar',        'shopper_get_sidebar',          		10 );

/**
 * Header
 *
 * @see  shopper_skip_links()
 * @see  shopper_secondary_navigation()
 * @see  shopper_site_branding()
 * @see  shopper_primary_navigation()
 * 
 */

add_action( 'shopper_header', 'shopper_skip_links',                       	 0 );
add_action( 'shopper_header', 'shopper_secondary_navigation_wrapper',        2 );
add_action( 'shopper_header', 'shopper_top_header_leftbox',             	 5 );
add_action( 'shopper_header', 'shopper_top_header_rightbox',             	10 );
add_action( 'shopper_header', 'shopper_secondary_navigation_wrapper_close', 28 );

add_action( 'shopper_header', 'shopper_site_branding_wrapper',              29 );
add_action( 'shopper_header', 'shopper_site_branding',                    	32 );
add_action( 'shopper_header', 'shopper_site_branding_wrapper_close',        60 );

add_action( 'shopper_header', 'shopper_primary_navigation_wrapper',       	65 );
add_action( 'shopper_header', 'shopper_primary_navigation',               	70 );
add_action( 'shopper_header', 'shopper_widget_primary_nav',         		75 );
add_action( 'shopper_header', 'shopper_primary_navigation_wrapper_close', 	80 );

add_action( 'shopper_top_header_left', 'shopper_secondary_navigation',   	10 );
add_action(	'shopper_top_header_right', 'shopper_social_navigation',		10 );

/**
 * Footer
 *
 * @see  shopper_footer_widgets()
 * @see  shopper_credit()
 * @see  shopper_backtotop()
 */
add_action( 'shopper_footer', 			'shopper_footer_widgets', 	10 );
add_action( 'shopper_footer', 			'shopper_credit',         	20 );
add_action( 'shopper_footer', 			'shopper_footer_menu',     	30 );
add_action( 'shopper_after_footer',		'shopper_backtotop',		10 );

/**
 * Homepage
 *
 * @see  shopper_homepage_content()
 * @see  shopper_product_categories()
 * @see  shopper_recent_products()
 * @see  shopper_featured_products()
 * @see  shopper_popular_products()
 * @see  shopper_on_sale_products()
 * @see  shopper_best_selling_products()
 */

add_action( 'shopper_homepage', 'shopper_homepage_content',      10 );
add_action( 'shopper_homepage', 'shopper_recent_products',       20 );
add_action( 'shopper_homepage', 'shopper_best_selling_products', 30 );
add_action( 'shopper_homepage', 'shopper_featured_products',     40 );
add_action( 'shopper_homepage', 'shopper_popular_products',      50 );
add_action( 'shopper_homepage', 'shopper_on_sale_products',      60 );
add_action( 'shopper_homepage', 'shopper_product_categories',    70 );
add_action( 'shopper_homepage', 'shopper_latest_from_blog', 	 80 );



/**
 * Posts
 *
 * @see  shopper_post_header()
 * @see  shopper_post_meta()
 * @see  shopper_post_content()
 * @see  shopper_init_structured_data()
 * @see  shopper_paging_nav()
 * @see  shopper_single_post_header()
 * @see  shopper_post_nav()
 * @see  shopper_display_comments()
 * @see  shopper_post_thumbnail()
 */
add_action( 'shopper_loop_post',           'shopper_post_header',          10 );
add_action( 'shopper_loop_post',           'shopper_post_meta',            20 );
add_action( 'shopper_loop_post',           'shopper_post_content',         30 );
add_action( 'shopper_loop_post',           'shopper_init_structured_data', 40 );
add_action( 'shopper_loop_post',  		   'shopper_footer_meta',  		   50 );


add_action( 'shopper_loop_after',          'shopper_paging_nav',           10 );
add_action( 'shopper_single_post',         'shopper_post_header',          10 );
add_action( 'shopper_single_post',         'shopper_post_meta',            20 );
add_action( 'shopper_single_post',         'shopper_post_content',         30 );
add_action( 'shopper_single_post',  	   'shopper_footer_meta',  		   40 );
add_action( 'shopper_single_post',         'shopper_init_structured_data', 50 );
add_action( 'shopper_single_post_bottom',  'shopper_post_nav',             10 );
add_action( 'shopper_single_post_bottom',  'shopper_display_comments',     20 );
add_action( 'shopper_post_content_before', 'shopper_post_thumbnail',       10 );



/**
 * Pages
 *
 * @see  shopper_page_header()
 * @see  shopper_page_content()
 * @see  shopper_init_structured_data()
 * @see  shopper_display_comments()
 */
add_action( 'shopper_page',       'shopper_page_header',          			  10 );
add_action( 'shopper_page',       'shopper_page_content',         			  20 );
add_action( 'shopper_page',       'shopper_init_structured_data', 			  30 );
add_action( 'shopper_page_after', 'shopper_display_comments',     			  10 );

add_action( 'shopper_content_homepage',       'shopper_homepage_header',      10 );
add_action( 'shopper_content_homepage',       'shopper_page_content',         20 );
add_action( 'shopper_content_homepage',       'shopper_init_structured_data', 30 );