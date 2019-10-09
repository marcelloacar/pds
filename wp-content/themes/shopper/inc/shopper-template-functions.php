<?php
/**
 * Shopper template functions.
 *
 * @package shopper
 */

function add_image_class($class){
    $class .= ' additional-class';
    return $class;
}
add_filter('get_image_tag_class','add_image_class');


if ( ! function_exists( 'shopper_display_comments' ) ) {
	/**
	 * shopper display comments
	 *
	 * @since  1.0.0
	 */
	function shopper_display_comments() {
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || '0' != get_comments_number() ) :
			comments_template();
		endif;
	}
}

if ( ! function_exists( 'shopper_comment' ) ) {
	/**
	 * shopper comment template
	 *
	 * @param array $comment the comment array.
	 * @param array $args the comment args.
	 * @param int   $depth the comment depth.
	 * @since 1.0.0
	 */
	function shopper_comment( $comment, $args, $depth ) {
		if ( 'div' == $args['style'] ) {
			$tag = 'div';
			$add_below = 'comment';
		} else {
			$tag = 'li';
			$add_below = 'div-comment';
		}
		?>
		<<?php echo esc_attr( $tag ); ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
		<div class="comment-body">
		<div class="comment-meta commentmetadata">
			<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 70 ); ?>
			
			</div>


		
		</div>
		<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID() ?>" class="comment-content">
		<?php endif; ?>

		<?php printf( wp_kses_post( '<cite class="fn">%s</cite>', 'shopper' ), get_comment_author_link() ); ?>

		<?php if ( '0' == $comment->comment_approved ) : ?>
			<em class="comment-awaiting-moderation"><?php esc_attr_e( 'Your comment is awaiting moderation.', 'shopper' ); ?></em>
			<br />
		<?php endif; ?>


		<div class="comment-text">
		<?php comment_text(); ?>
		</div>
		<div class="commentmetadata clear">
			<a href="<?php echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); ?>" class="comment-date">
			<?php echo '<time datetime="' . get_comment_date( 'c' ) . '">' . get_comment_date() . '</time>'; ?>
			</a>
			<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			<?php edit_comment_link( __( 'Edit', 'shopper' ), '  ', '' ); ?>
			</div>

		</div><!-- #comment-meta -->
		
		</div>
		<?php if ( 'div' != $args['style'] ) : ?>
		</div>
		<?php endif; ?>
	<?php
	}
}

if ( ! function_exists( 'shopper_site_branding' ) ) {
	/**
	 * Site branding wrapper and display
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function shopper_site_branding() {
		?>
		<div class="site-branding">
			<?php shopper_site_title_or_logo(); ?>
		</div>
		<?php
	}
}


if ( ! function_exists( 'shopper_site_branding_wrapper' ) ) {

	function shopper_site_branding_wrapper() {
		echo '<div class="header-middle clear">';
	}

}

if ( ! function_exists( 'shopper_site_branding_wrapper_close' ) ) {

	function shopper_site_branding_wrapper_close() {
		echo '</div>';
	}
	
}

if ( ! function_exists( 'shopper_primary_navigation' ) ) {
	/**
	 * Display Primary Navigation
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function shopper_primary_navigation() {
		?>
		<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_html_e( 'Primary Navigation', 'shopper' ); ?>">			

			<?php

			if ( has_nav_menu( 'primary' ) ) :

				?>

				<button class="menu-toggle"><i class="fa fa-bars"></i></button>

				<?php

				wp_nav_menu(
					array(
						'theme_location'	=> 'primary',
						'container_class'	=> 'primary-navigation',
						)
				);

			endif;

			?>
		</nav><!-- #site-navigation -->
		<?php
	}
}

if ( ! function_exists( 'shopper_widget_primary_nav' ) ) {
	/**
	 * Render widget on the primary navigation
	 *
	 * @since 1.3.2
	 * @return void
	 */
	function shopper_widget_primary_nav() {
		if ( is_active_sidebar( 'primary-1' ) ) {
		?>
		<div id="primary-widget-region" class="primary-widget-region">
				<?php dynamic_sidebar( 'primary-1' ); ?>
		</div>
		<?php
		}
	}
}

if ( ! function_exists( 'shopper_secondary_navigation_wrapper' ) ) {
	/**
	 * The second navigation wrapper
	 */
	function shopper_secondary_navigation_wrapper() {
		echo '<div class="shopper-secondary-navigation">';
	}
}

if ( ! function_exists( 'shopper_secondary_navigation_wrapper_close' ) ) {
	/**
	 * The second navigation wrapper close
	 */
	function shopper_secondary_navigation_wrapper_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'shopper_top_secondary_social_wrapper' ) ) {
	/**
	 * The second navigation wrapper
	 */
	function shopper_top_secondary_social_wrapper() {
		echo '<div class="shopper-secondary-social-navigation">';
	}
}

if ( ! function_exists( 'shopper_top_secondary_social_wrapper_close' ) ) {
	/**
	 * The second navigation wrapper close
	 */
	function shopper_top_secondary_social_wrapper_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'shopper_top_header_leftbox' ) ) {
	/**
	 * The special info on the top left
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function shopper_top_header_leftbox() {
		echo '<div class="topbox-left">';
		do_action('shopper_top_header_left');
		echo '</div>';
	}
}

if ( ! function_exists( 'shopper_top_header_rightbox' ) ) {
	/**
	 * The special info on the top left
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function shopper_top_header_rightbox() {
		echo '<div class="topbox-right">';
		do_action('shopper_top_header_right');
		echo '</div>';
	}
}

if ( ! function_exists( 'shopper_secondary_navigation' ) ) {
	/**
	 * Display Secondary Navigation
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function shopper_secondary_navigation() {
	    if ( has_nav_menu( 'secondary' ) ) {
		    ?>
		    <nav class="secondary-navigation" role="navigation" aria-label="<?php esc_html_e( 'Secondary Navigation', 'shopper' ); ?>">
			    <?php
				    wp_nav_menu(
					    array(
						    'theme_location'	=> 'secondary',
						    'fallback_cb'		=> '',
					    )
				    );
			    ?>
		    </nav><!-- #site-navigation -->
		    <?php
		}
	}
}

if ( ! function_exists( 'shopper_social_navigation' ) ) {
	/**
	 * Display social menu
	 */
	function shopper_social_navigation() {
		if ( has_nav_menu( 'social' ) ) {
		    ?>
		    <nav class="social-navigation" role="navigation" aria-label="<?php esc_html_e( 'Social Navigation', 'shopper' ); ?>">
			    <?php
				    wp_nav_menu(
					    array(
						    'theme_location'	=> 'social',
						    'fallback_cb'		=> '',
					    )
				    );
			    ?>
		    </nav><!-- #site-navigation -->
		    <?php
		}
	}
}
if ( ! function_exists( 'shopper_skip_links' ) ) {
	/**
	 * Skip links
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function shopper_skip_links() {
		?>
		<a class="skip-link screen-reader-text" href="#site-navigation"><?php esc_attr_e( 'Skip to navigation', 'shopper' ); ?></a>
		<a class="skip-link screen-reader-text" href="#content"><?php esc_attr_e( 'Skip to content', 'shopper' ); ?></a>
		<?php
	}
}
if ( ! function_exists( 'shopper_site_title_or_logo' ) ) {
	/**
	 * Display the site title or logo
	 *
	 * @since 1.0.0
	 * @param bool $echo Echo the string or return it.
	 * @return string
	 */
	function shopper_site_title_or_logo( $echo = true ) {
		if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
			$logo = get_custom_logo();
			$html = is_home() ? '<h1 class="logo">' . $logo . '</h1>' : $logo;
		} elseif ( function_exists( 'jetpack_has_site_logo' ) && jetpack_has_site_logo() ) {
			// Copied from jetpack_the_site_logo() function.
			$logo    = site_logo()->logo;
			$logo_id = get_theme_mod( 'custom_logo' ); // Check for WP 4.5 Site Logo
			$logo_id = $logo_id ? $logo_id : $logo['id']; // Use WP Core logo if present, otherwise use Jetpack's.
			$size    = site_logo()->theme_size();
			$html    = sprintf( '<a href="%1$s" class="site-logo-link" rel="home" itemprop="url">%2$s</a>',
				esc_url( home_url( '/' ) ),
				wp_get_attachment_image(
					$logo_id,
					$size,
					false,
					array(
						'class'     => 'site-logo attachment-' . $size,
						'data-size' => $size,
						'itemprop'  => 'logo'
					)
				)
			);

			$html = apply_filters( 'jetpack_the_site_logo', $html, $logo, $size );
		} else {
			$tag = is_home() ? 'h1' : 'div';

			$html = '<' . esc_attr( $tag ) . ' class="beta site-title"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_html( get_bloginfo( 'name' ) ) . '</a></' . esc_attr( $tag ) .'>';

			if ( '' !== get_bloginfo( 'description' ) ) {
				$html .= '<p class="site-description">' . esc_html( get_bloginfo( 'description', 'display' ) ) . '</p>';
			}
		}

		if ( ! $echo ) {
			return $html;
		}

		echo $html;
	}
}

if ( ! function_exists( 'shopper_page_header' ) ) {
	/**
	 * Display the post header with a link to the single post
	 *
	 * @since 1.0.0
	 */
	function shopper_page_header() {
		?>
		<header class="entry-header">
			<?php
			shopper_post_thumbnail( 'full' );
			the_title( '<h1 class="entry-title">', '</h1>' );
			?>
		</header><!-- .entry-header -->
		<?php
	}
}

if ( ! function_exists( 'shopper_page_content' ) ) {
	/**
	 * Display the post content with a link to the single post
	 *
	 * @since 1.0.0
	 */
	function shopper_page_content() {
		?>
		<div class="entry-content">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'shopper' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
		<?php
	}
}

if ( ! function_exists( 'shopper_post_header' ) ) {
	/**
	 * Display the post header with a link to the single post
	 *
	 * @since 1.0.0
	 */
	function shopper_post_header() {
		?>
		<header class="entry-header">
		<?php
		if ( is_single() ) {
		
			the_title( '<h1 class="entry-title">', '</h1>' );

		} else {		

			the_title( sprintf( '<h2 class="alpha entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );

		}
		?>
		</header><!-- .entry-header -->
		<?php
	}
}

if ( ! function_exists( 'shopper_footer_widgets' ) ) {
	/**
	 * Display the footer widget regions
	 *
	 * @since  1.0.0
	 * @return  void
	 */
	function shopper_footer_widgets() {
		if ( is_active_sidebar( 'footer-4' ) ) {
			$widget_columns = apply_filters( 'shopper_footer_widget_regions', 4 );
		} elseif ( is_active_sidebar( 'footer-3' ) ) {
			$widget_columns = apply_filters( 'shopper_footer_widget_regions', 3 );
		} elseif ( is_active_sidebar( 'footer-2' ) ) {
			$widget_columns = apply_filters( 'shopper_footer_widget_regions', 2 );
		} elseif ( is_active_sidebar( 'footer-1' ) ) {
			$widget_columns = apply_filters( 'shopper_footer_widget_regions', 1 );
		} else {
			$widget_columns = apply_filters( 'shopper_footer_widget_regions', 0 );
		}

		if ( $widget_columns > 0 ) : ?>

			<div class="footer-widgets col-<?php echo intval( $widget_columns ); ?> fix">

				<?php
				$i = 0;
				while ( $i < $widget_columns ) : $i++;
					if ( is_active_sidebar( 'footer-' . $i ) ) : ?>

						<div class="block footer-widget-<?php echo intval( $i ); ?>">
							<?php dynamic_sidebar( 'footer-' . intval( $i ) ); ?>
						</div>

					<?php endif;
				endwhile; ?>

			</div><!-- /.footer-widgets  -->

		<?php endif;
	}
}

if ( ! function_exists( 'shopper_credit' ) ) {
	/**
	 * Display the theme credit
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function shopper_credit() {
		?>
		<div class="site-info">			
			<?php echo esc_html( apply_filters( 'shopper_copyright_text', $content = '' . get_bloginfo( 'name' ) . ' &copy;' . date( 'Y' ) ) ); ?>.
			<?php if ( apply_filters( 'shopper_credit_link', true ) ) { ?>
				<?php printf( esc_attr__( ' %1$s Designed by %2$s.', 'shopper' ), '<a href="https://alltopstuffs.com" title="Shopper" target="_blank">Shopper</a>', '<a href="https://shopperwp.io" title="Shopper - The Best Free WooCommerce for WordPress" rel="author">ShopperWP</a>' ); ?>
			<?php } ?>

			
		</div><!-- .site-info -->
		<?php
	}
}

if ( ! function_exists( 'shopper_footer_menu' ) ) {

	/**
	 * Display Footer Menu
	 *
	 * @since  1.0.0
	 */

	function shopper_footer_menu() {

		if ( has_nav_menu( 'footer' ) ) : ?>
			<?php
				wp_nav_menu( array(
					'theme_location'	=> 'footer',
					'container_class'	=> 'footer-menu',
				));
			?>
			<?php endif;

	}


}

if ( ! function_exists( 'shopper_backtotop ') ) {
	/**
	 * Display Footer Back To Top
	 *
	 * @since  1.0.0
	 */

	function shopper_backtotop() {
		?>

		<span class="back-to-top"><i class="fa fa-chevron-up" aria-hidden="true"></i></span>

		<?php
	}
}


if ( ! function_exists( 'shopper_header_widget_region' ) ) {
	/**
	 * Display header widget region
	 *
	 * @since  1.0.0
	 */
	function shopper_header_widget_region() {
		if ( is_active_sidebar( 'header-1' ) ) {
		?>
		<div class="header-widget-region" role="complementary">
			<div class="col-full">
				<?php dynamic_sidebar( 'header-1' ); ?>
			</div>
		</div>
		<?php
		}
	}
}

if ( ! function_exists( 'shopper_post_content' ) ) {
	/**
	 * Display the post content with a link to the single post
	 *
	 * @since 1.0.0
	 */
	function shopper_post_content() {
		?>
		<div class="entry-content">
		<?php

		$size = apply_filters('shopper_thunmbnail_size', 'large');
		$shopper_display_excerpt = apply_filters('shopper_display_excerpt', true);

		/**
		 * Functions hooked in to shopper_post_content_before action.
		 *
		 * @hooked shopper_post_thumbnail - 10
		 */
		do_action( 'shopper_post_content_before' );



		if ( ($shopper_display_excerpt) && ( is_search() || is_archive() || is_front_page() || is_home() )  ) {

			the_excerpt();


		} else {

			the_content( sprintf(
				/* translators: %s: Name of current post. */
				wp_kses( __( 'Continue Reading %s <span class="meta-nav">&rarr;</span>', 'shopper' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )

			) );
		}

		do_action( 'shopper_post_content_after' );

		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'shopper' ),
			'after'  => '</div>',
		) );
		?>
		</div><!-- .entry-content -->
		<?php
	}
}

if ( ! function_exists( 'shopper_footer_meta' ) ) {
	/**
	 * Display the Footer meta
	 *
	 * @since 1.0.0
	 */
	function shopper_footer_meta() {

		if ( 'post' == get_post_type() ) : 

		?>
		<div class="entry-footer">

		<?php

			if ( is_single() ) {

				shopper_posted_on();

			} else {

				if ( 'post' == get_post_type() ) {

					shopper_posted_on();
				}
			}

			?>

			<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
								
				<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'shopper' ), __( '1 Comment', 'shopper' ), __( '% Comments', 'shopper' ) ); ?></span>
				
			<?php endif; ?>


		</div>

	<?php

	endif; 


	}
}

if ( ! function_exists( 'shopper_post_content_after' ) ) {
	/**
	 * Display the Continue Reading
	 *
	 * @since 1.0.0
	 */
	function shopper_post_content_after() {
		?>
		<a href="<?php echo esc_url( get_permalink() ) ?>"  title="<?php the_title() ?>" class="more-link"><?php esc_html_e( '[Continue Reading ...]', 'shopper' ); ?></a>


		<?php
	}
}


if ( ! function_exists( 'shopper_post_meta' ) ) {
	/**
	 * Display the post meta
	 *
	 * @since 1.0.0
	 */
	function shopper_post_meta() {
		?>
		<aside class="entry-meta">
			<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search.

			?>
			<div class="author">
				<?php
					//echo get_avatar( get_the_author_meta( 'ID' ), 128 );
					echo '<div class="label">' . esc_attr( __( 'By', 'shopper' ) ) . '</div>';
					the_author_posts_link();
				?>
			</div>
			<?php
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( __( ', ', 'shopper' ) );

			if ( $categories_list ) : ?>
				<div class="cat-links">
					<?php
					echo '<div class="label">' . esc_attr( __( 'Posted in', 'shopper' ) ) . '</div>';
					echo wp_kses_post( $categories_list );
					?>
				</div>
			<?php endif; // End if categories. ?>

			<?php
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', __( ', ', 'shopper' ) );

			if ( $tags_list ) : ?>
				<div class="tags-links">
					<?php
					echo '<div class="label">' . esc_attr( __( 'Tagged', 'shopper' ) ) . '</div>';
					echo wp_kses_post( $tags_list );
					?>
				</div>
			<?php endif; // End if $tags_list. ?>

		<?php endif; // End if 'post' == get_post_type(). ?>


		</aside>
		<?php
	}
}

if ( ! function_exists( 'shopper_paging_nav' ) ) {
	/**
	 * Display navigation to next/previous set of posts when applicable.
	 */
	function shopper_paging_nav() {
		global $wp_query;

		$args = array(
			'type' 	    => 'list',
			'next_text' => _x( 'Next', 'Next post', 'shopper' ),
			'prev_text' => _x( 'Previous', 'Previous post', 'shopper' ),
			);

		the_posts_pagination( $args );
	}
}

if ( ! function_exists( 'shopper_post_nav' ) ) {
	/**
	 * Display navigation to next/previous post when applicable.
	 */
	function shopper_post_nav() {
		$args = array(
			'next_text' => '%title',
			'prev_text' => '%title',
			);
		the_post_navigation( $args );
	}
}

if ( ! function_exists( 'shopper_posted_on' ) ) {
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function shopper_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time> <time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			_x( 'Posted on %s', 'post date', 'shopper' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo wp_kses( apply_filters( 'shopper_single_post_posted_on_html', '<span class="posted-on">' . $posted_on . '</span>', $posted_on ), array(
			'span' => array(
				'class'  => array(),
			),
			'a'    => array(
				'href'  => array(),
				'title' => array(),
				'rel'   => array(),
			),
			'time' => array(
				'datetime' => array(),
				'class'    => array(),
			),
		) );
	}
}

if ( ! function_exists( 'shopper_get_sidebar' ) ) {
	/**
	 * Display shopper sidebar
	 *
	 * @uses get_sidebar()
	 * @since 1.0.0
	 */
	function shopper_get_sidebar() {

		$enable_sidebar = apply_filters( 'shopper_enable_sidebar', true);

		if ( $enable_sidebar ) {

			get_sidebar();
		}
	}
}

if ( ! function_exists( 'shopper_post_thumbnail' ) ) {
	/**
	 * Display post thumbnail
	 *
	 * @var $size thumbnail size. thumbnail|medium|large|full|$custom
	 * @uses has_post_thumbnail()
	 * @uses the_post_thumbnail
	 * @param string $size the post thumbnail size.
	 * @since 1.0.0
	 */
	function shopper_post_thumbnail( $size = 'full' ) {
		if ( has_post_thumbnail() ) {
			?>
			<div class="thumbnail-blog">

				<a href="<?php the_permalink() ?>" rel="bookmark" class="featured-thumbnail">

					<?php the_post_thumbnail( $size ); ?>
				</a>

			</div>

			<?php

		}
	}
}

if ( ! function_exists( 'shopper_primary_navigation_wrapper' ) ) {
	/**
	 * The primary navigation wrapper
	 */
	function shopper_primary_navigation_wrapper() {
		echo '<div class="shopper-primary-navigation clear">';
	}
}


if ( ! function_exists( 'shopper_primary_navigation_wrapper_close' ) ) {
	/**
	 * The primary navigation wrapper close
	 */
	function shopper_primary_navigation_wrapper_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'shopper_init_structured_data' ) ) {
	/**
	 * Generates structured data.
	 *
	 * Hooked into the following action hooks:
	 *
	 * - `shopper_loop_post`
	 * - `shopper_single_post`
	 * - `shopper_page`
	 *
	 * Applies `shopper_structured_data` filter hook for structured data customization :)
	 */
	function shopper_init_structured_data() {

		// Post's structured data.
		if ( is_home() || is_category() || is_date() || is_search() || is_single() && ( shopper_is_woocommerce_activated() && ! is_woocommerce() ) ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'normal' );
			$logo  = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );

			$json['@type']            = 'BlogPosting';

			$json['mainEntityOfPage'] = array(
				'@type'                 => 'webpage',
				'@id'                   => get_the_permalink(),
			);

			$json['publisher']        = array(
				'@type'                 => 'organization',
				'name'                  => get_bloginfo( 'name' ),
				'logo'                  => array(
					'@type'               => 'ImageObject',
					'url'                 => $logo[0],
					'width'               => $logo[1],
					'height'              => $logo[2],
				),
			);

			$json['author']           = array(
				'@type'                 => 'person',
				'name'                  => get_the_author(),
			);

			if ( $image ) {
				$json['image']            = array(
					'@type'                 => 'ImageObject',
					'url'                   => $image[0],
					'width'                 => $image[1],
					'height'                => $image[2],
				);
			}

			$json['datePublished']    = get_post_time( 'c' );
			$json['dateModified']     = get_the_modified_date( 'c' );
			$json['name']             = get_the_title();
			$json['headline']         = $json['name'];
			$json['description']      = get_the_excerpt();

		// Page's structured data.
		} elseif ( is_page() ) {
			$json['@type']            = 'WebPage';
			$json['url']              = get_the_permalink();
			$json['name']             = get_the_title();
			$json['description']      = get_the_excerpt();
		}

		if ( isset( $json ) ) {
			shopper::set_structured_data( apply_filters( 'shopper_structured_data', $json ) );
		}
	}
}
if ( ! function_exists( 'shopper_homepage_content' ) ) {
	/**
	 * Display homepage content
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @since  1.0.0
	 * @return  void
	 */
	function shopper_homepage_content() {
		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/content', 'homepage' );

		} // end of the loop.
	}
}

if ( ! function_exists( 'shopper_homepage_header' ) ) {
	/**
	 * Display the page header without the featured image
	 *
	 * @since 1.0.0
	 */
	function shopper_homepage_header() {
		edit_post_link( __( 'Edit this section', 'shopper' ), '', '', '', 'button shopper-hero__button-edit' );
		?>
		<header class="entry-header">
			<?php
			the_title( '<h1 class="entry-title">', '</h1>' );
			?>
		</header><!-- .entry-header -->
		<?php
	}
}

if ( ! function_exists( 'shopper_product_categories' ) ) {
	/**
	 * Display Product Categories
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @since  1.0.0
	 * @param array $args the product section args.
	 * @return void
	 */
	function shopper_product_categories( $args ) {

		if ( shopper_is_woocommerce_activated() ) {

			$args = apply_filters( 'shopper_product_categories_args', array(
				'limit' 			=> 4,
				'columns' 			=> 4,
				'child_categories' 	=> 0,
				'orderby' 			=> 'name',
				'title'				=> __( 'Shop by Category', 'shopper' ),
			) );

			echo '<section class="shopper-product-section shopper-product-categories" aria-label="Product Categories">';

			do_action( 'shopper_homepage_before_product_categories' );

			echo '<h2 class="section-title"><span>' . wp_kses_post( $args['title'] ) . '</span></h2>';

			do_action( 'shopper_homepage_after_product_categories_title' );

			echo shopper_do_shortcode( 'product_categories', array(
				'number'  => intval( $args['limit'] ),
				'columns' => intval( $args['columns'] ),
				'orderby' => esc_attr( $args['orderby'] ),
				'parent'  => esc_attr( $args['child_categories'] ),
			) );

			do_action( 'shopper_homepage_after_product_categories' );

			echo '</section>';
		}
	}
}

if ( ! function_exists( 'shopper_recent_products' ) ) {
	/**
	 * Display Recent Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @since  1.0.0
	 * @param array $args the product section args.
	 * @return void
	 */
	function shopper_recent_products( $args ) {

		if ( shopper_is_woocommerce_activated() ) {

			$args = apply_filters( 'shopper_recent_products_args', array(
				'limit' 			=> 8,
				'columns' 			=> 4,
				'title'				=> __( 'Recent Products', 'shopper' ),
			) );

			echo '<section class="shopper-product-section shopper-recent-products" aria-label="Recent Products">';

			do_action( 'shopper_homepage_before_recent_products' );

			echo '<h2 class="section-title"><span>' . wp_kses_post( $args['title'] ) . '</span></h2>';

			do_action( 'shopper_homepage_after_recent_products_title' );

			echo shopper_do_shortcode( 'recent_products', array(
				'per_page' => intval( $args['limit'] ),
				'columns'  => intval( $args['columns'] ),
			) );

			do_action( 'shopper_homepage_after_recent_products' );

			echo '</section>';
		}
	}
}

if ( ! function_exists( 'shopper_featured_products' ) ) {
	/**
	 * Display Featured Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @since  1.0.0
	 * @param array $args the product section args.
	 * @return void
	 */
	function shopper_featured_products( $args ) {

		if ( shopper_is_woocommerce_activated() ) {

			$args = apply_filters( 'shopper_featured_products_args', array(
				'limit'   => 4,
				'columns' => 4,
				'orderby' => 'date',
				'order'   => 'desc',
				'title'   => __( 'We Recommend', 'shopper' ),
			) );

			echo '<section class="shopper-product-section shopper-featured-products" aria-label="Featured Products">';

			do_action( 'shopper_homepage_before_featured_products' );

			echo '<h2 class="section-title"><span>' . wp_kses_post( $args['title'] ) . '</span></h2>';

			do_action( 'shopper_homepage_after_featured_products_title' );

			echo shopper_do_shortcode( 'featured_products', array(
				'per_page' => intval( $args['limit'] ),
				'columns'  => intval( $args['columns'] ),
				'orderby'  => esc_attr( $args['orderby'] ),
				'order'    => esc_attr( $args['order'] ),
			) );

			do_action( 'shopper_homepage_after_featured_products' );

			echo '</section>';
		}
	}
}

if ( ! function_exists( 'shopper_popular_products' ) ) {
	/**
	 * Display Popular Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @since  1.0.0
	 * @param array $args the product section args.
	 * @return void
	 */
	function shopper_popular_products( $args ) {

		if ( shopper_is_woocommerce_activated() ) {

			$args = apply_filters( 'shopper_popular_products_args', array(
				'limit'   => 4,
				'columns' => 4,
				'title'   => __( 'Fan Favorites', 'shopper' ),
			) );

			echo '<section class="shopper-product-section shopper-popular-products" aria-label="Popular Products">';

			do_action( 'shopper_homepage_before_popular_products' );

			echo '<h2 class="section-title"><span>' . wp_kses_post( $args['title'] ) . '</span></h2>';

			do_action( 'shopper_homepage_after_popular_products_title' );

			echo shopper_do_shortcode( 'top_rated_products', array(
				'per_page' => intval( $args['limit'] ),
				'columns'  => intval( $args['columns'] ),
			) );

			do_action( 'shopper_homepage_after_popular_products' );

			echo '</section>';
		}
	}
}

if ( ! function_exists( 'shopper_on_sale_products' ) ) {
	/**
	 * Display On Sale Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @param array $args the product section args.
	 * @since  1.0.0
	 * @return void
	 */
	function shopper_on_sale_products( $args ) {

		if ( shopper_is_woocommerce_activated() ) {

			$args = apply_filters( 'shopper_on_sale_products_args', array(
				'limit'   => 4,
				'columns' => 4,
				'title'   => __( 'On Sale', 'shopper' ),
			) );

			echo '<section class="shopper-product-section shopper-on-sale-products" aria-label="On Sale Products">';

			do_action( 'shopper_homepage_before_on_sale_products' );

			echo '<h2 class="section-title"><span>' . wp_kses_post( $args['title'] ) . '</span></h2>';

			do_action( 'shopper_homepage_after_on_sale_products_title' );

			echo shopper_do_shortcode( 'sale_products', array(
				'per_page' => intval( $args['limit'] ),
				'columns'  => intval( $args['columns'] ),
			) );

			do_action( 'shopper_homepage_after_on_sale_products' );

			echo '</section>';
		}
	}
}

if ( ! function_exists( 'shopper_best_selling_products' ) ) {
	/**
	 * Display Best Selling Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @since 2.0.0
	 * @param array $args the product section args.
	 * @return void
	 */
	function shopper_best_selling_products( $args ) {
		if ( shopper_is_woocommerce_activated() ) {
			$args = apply_filters( 'shopper_best_selling_products_args', array(
				'limit'   => 4,
				'columns' => 4,
				'title'	  => esc_attr__( 'Best Sellers', 'shopper' ),
			) );
			echo '<section class="shopper-product-section shopper-best-selling-products" aria-label="Best Selling Products">';
			do_action( 'shopper_homepage_before_best_selling_products' );
			echo '<h2 class="section-title"><span>' . wp_kses_post( $args['title'] ) . '</span></h2>';
			do_action( 'shopper_homepage_after_best_selling_products_title' );
			echo shopper_do_shortcode( 'best_selling_products', array(
				'per_page' => intval( $args['limit'] ),
				'columns'  => intval( $args['columns'] ),
			) );
			do_action( 'shopper_homepage_after_best_selling_products' );
			echo '</section>';
		}
	}
}

if ( ! function_exists( 'shopper_latest_from_blog' ) ) {
	/**
	 * Get the latest posts from blog
	 * 
	 * @return [type] [description]
	 */
	function shopper_latest_from_blog() {

		$args = apply_filters( 'shopper_latest_from_blog_args', array(
				'limit'   => 4,
				'columns' => 4,
				'title'   => __( 'The latest news', 'shopper' ),
			) );

			echo '<section class="shopper-product-section shopper-latest-from-blog">';

			do_action( 'shopper_homepage_before_latest_from_blog_title' );

			echo '<h2 class="section-title"><span>' . wp_kses_post( $args['title'] ) . '</span></h2>';

			do_action( 'shopper_homepage_after_latest_from_blog_title' );

			shopper_the_recent_posts( array(
				'numberposts' => intval( $args['limit'] ),
				'columns'  => intval( $args['columns'] ),
			) );

			do_action( 'shopper_homepage_after_latest_from_blog' );

			echo '</section>';
	}

}

if ( ! function_exists( 'shopper_the_recent_posts' )) {

	function shopper_the_recent_posts( $args ) {

		global $post;

		$default = array(
				'numberposts'			=> 4,
				'columns'				=> 4,
			);

		$args = wp_parse_args ($args, $default);

		$recent_posts = wp_get_recent_posts( $args, OBJECT);
		

		if ( $recent_posts ) {

			echo '<div class="columns-'. $args['columns'] .'"><ul class="shopper-recent-posts products">';

				foreach($recent_posts as $post) :

					setup_postdata( $post );

					?>
						<li <?php post_class(); ?>>
							<a href="<?php the_permalink(); ?>" class="link-recent-post">
								<div class="post-thumbnail">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail('medium') ?>
								<?php else : ?>
									<img src="<?php echo get_template_directory_uri().'/assets/images/placeholder.png'; ?>" alt="<?php the_title() ?>">
								<?php endif; ?>
								</div>
								<h4 class="recent-post-title"><?php the_title() ?></h4>
								
							</a>
							<div class="rec-post-excerpt">
								<?php the_excerpt() ?>
							</div>

							
							<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
												
								<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'shopper' ), __( '1 Comment', 'shopper' ), __( '% Comments', 'shopper' ) ); ?></span>
								
							<?php endif; ?>

						</li>
					<?php 
				endforeach;

				wp_reset_postdata();

			echo '</ul></div>';

		}
	}
}