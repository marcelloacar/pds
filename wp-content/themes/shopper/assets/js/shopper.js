(function ($) {
	'use strict';
	var ShopperJS = {

		// Load all function on ready
		initReady: function() {
			// Recent Product Slider
			//this.slickity('.shopper-recent-products .products', '.product');
			this.cattoggle();
			this.scrollTop();
			this.searchform();
			this.quantity();
			this.updatedQuantity();
			this.mobileNavigation();
			this.slideUpDown('.cart-toggle','.header-myacc-link, .site-header-cart');
			this.slideUpDownFooter('.site-footer .widget-title');
			this.calVerticalHeight();
			this.generalFunc();
		},

		mobileNavigation: function() {
			var $top_menu = jQuery('.primary-navigation');
			var $secondary_menu = jQuery('.shopper-secondary-navigation');
			var $first_menu = '';
			var $second_menu = '';

			if ( $top_menu.length === 0 && $secondary_menu.length === 0 ) {
				return;
			} else {
				if ($top_menu.length) {
					$first_menu = $top_menu;
					if($secondary_menu.length) {
						$second_menu = $secondary_menu;
						jQuery('.top-nav').addClass('has-second-menu');
					}
				} else {
					$first_menu = $secondary_menu;
				}
			}
			$first_menu.clone().attr('class', 'mobile-menu').wrap('<div id="mobile-menu-wrapper" class="mobile-only"></div>').parent().hide().appendTo('body');

			// Add items from the other menu
			if ($second_menu.length) {
				$second_menu.find('ul.menu').clone().appendTo('.mobile-menu');
			}

			$('.menu-toggle').click(function(e) {
				e.preventDefault();
				e.stopPropagation();
				$('#mobile-menu-wrapper').show(); // only required once
				$('body').toggleClass('mobile-menu-active');
			});

			$('.container').click(function() {
				if ($('body').hasClass('mobile-menu-active')) {
					$('body').removeClass('mobile-menu-active');
				}
				if($('.menu-item-has-children .arrow-sub-menu').hasClass('fa-minus-square-o')) {
					$('.menu-item-has-children .arrow-sub-menu').removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
				}
			});

			$('<i class="fa arrow-sub-menu fa-plus-square-o"></i>').insertAfter($('#mobile-menu-wrapper .menu-item-has-children > a'));

			if($('#wpadminbar').length) {
				$('#mobile-menu-wrapper').addClass('wpadminbar-active');
			}

			$('.menu-item-has-children .arrow-sub-menu').click(function(e) {
				e.preventDefault();
				var active = $(this).hasClass('fa-minus-square-o');
				if(!active) {
					$(this).removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
					$(this).next().slideDown();
				} else {
					$(this).removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
					$(this).next().slideUp();
				}
			});

			// Add primary widget
			this.primaryWidget();
		},

		primaryWidget: function() {
			var pw = jQuery('#primary-widget-region');
			pw.clone().appendTo('.mobile-menu');
		},
		slideUpDown: function(btnClick, idClass) {
			jQuery(btnClick).on('click', function() {
				var hasActive = jQuery(idClass).hasClass('active');

				if ( !hasActive ) {
					jQuery(this).addClass('active');
					jQuery(idClass).slideDown().addClass('active');
				} else {
					jQuery(this).removeClass('active');
					jQuery(idClass).slideUp().removeClass('active');
				}

			});
		},

		slideUpDownFooter: function(btnClick) {

			jQuery(btnClick).on('click', function() {
				var hasActive = jQuery(this).hasClass('active');

				if ( !hasActive ) {
					jQuery(this).next().slideDown();
					jQuery(this).addClass('active');
				} else {
					jQuery(this).next().slideUp();
					jQuery(this).removeClass('active');
				}

			});

		},

		calVerticalHeight: function() {

			if ( jQuery('.feature-banner').length ) {

				var bLeft = jQuery('.feature-banner').position();

				jQuery('.feature-banner').css({
					'margin-left' : - bLeft.left,
					'margin-right' : - bLeft.left
				});

				var vHeight1 = jQuery('.feature-banner').height();
				var vHeight2 = jQuery('.feature-banner .banner-desc').height();

				var vHeight3 = (vHeight1 - vHeight2)/2;

				jQuery('.feature-banner .banner-desc').css( 'margin-top', vHeight3 );
			}

		},

		cattoggle: function( toggle, toggle_cat, toggle_nav ) {

				//Toggle Nav Sidebar
				toggle_nav = jQuery('.widget_nav_menu ul > li.menu-item-has-children');
				toggle_nav.append('<span class="toggle"><i class="fa fa-plus"></i></span>');

				//Toggle Categories Blog Sidebar
				toggle_cat = jQuery('.widget_categories > ul li.cat-item').find('ul.children');
				toggle_cat.parent().append('<span class="toggle"><i class="fa fa-plus"></i></span>');

				//Toggle Categories Product Sidebar
				toggle = jQuery('.widget_product_categories > ul li.cat-parent');
				toggle.append('<span class="toggle"><i class="fa fa-plus"></i></span>');


				$('.current-cat-parent, .current-cat-ancestor, .current-menu-item, .current_page_item, .current-page-ancestor, .current-menu-parent').addClass('active');

				jQuery('.toggle').on('click', function(e) {
					e.preventDefault();

					var hasActive = jQuery(this).parent('li').hasClass('active');


					if ( !hasActive ) {
						jQuery(this).parent('li').addClass('active');
					} else {
						jQuery(this).parent('li').removeClass('active');
					}
				});
		},

		scrollTop : function() {
			jQuery('.back-to-top').click(function () {
				jQuery('html, body').animate({scrollTop : 0},800);
				return false;
			});

			jQuery(document).scroll ( function() {
				var topPositionScrollBar = jQuery(document).scrollTop();
				if ( topPositionScrollBar < '150' ) {
					jQuery('.back-to-top').fadeOut();
				} else {
					jQuery('.back-to-top').fadeIn();
				}
			});
		},

		// Flickity Slider
		slickity: function( slideEl, cellSelector) {
			$(slideEl).flickity({
				// options
				cellAlign: 'left',
				contain: true,
				groupCells: true,
				cellSelector: cellSelector,
				pageDots: false,
				freeScroll: true,
				wrapAround: true
			});
		},

		searchform: function() {
			jQuery('.shopper-product-search').each(function() {
				var defaultVal = jQuery('.shopper-cat-list').val();
				if ( defaultVal === '') {
					jQuery('.nav-search-label').text('All');
				} else {
					jQuery('.nav-search-label').text(defaultVal);
				}
				jQuery('.shopper-cat-list').on('change', function() {
					var selectText = $(this).val();
					if ( selectText === '') {
						jQuery('.nav-search-label').text('All');
					} else {
						jQuery('.nav-search-label').text(selectText);
					}
					jQuery('.shopper-product-search input[type="text"]').focus();
				});
			});
		},

		quantity: function() {
			jQuery('.qty-minus, .qty-add').on('click', function () {
				var qty = $(this);
				var oldValue = qty.closest('.quantity').find('input.qty').val();
				var newVal = 0;

				if (qty.val() === '+') {
					newVal = parseFloat(oldValue) + 1;
				} else {

					if (oldValue > 0) {
						newVal = parseFloat(oldValue) - 1;
					} else {
						newVal = 0;
					}
				}

				qty.closest('.quantity').find('input.qty').val(newVal);
				jQuery('.actions input[type="submit"]').prop('disabled', false);

			});
    	},
    	updatedQuantity: function() {

    		// Trigger event updated_wc_div
			jQuery( document.body ).on( 'updated_wc_div', function() {

				// Call quantity (+) & (-) again
				ShopperJS.quantity();

			} );
		},

		generalFunc: function() {

			jQuery('.woocommerce-message').append( '<span class="fa fa-close woocommerce-close"></span>' );

			jQuery('.woocommerce-close').on('click', function() {

				var hasActive = jQuery(this).hasClass('active');

				if ( !hasActive ) {
					jQuery(this).addClass('active');
					jQuery('.woocommerce .woocommerce-message').addClass('hide');
				} else {
					jQuery(this).removeClass('active');
					jQuery('.woocommerce .woocommerce-message').removeClass('hide');
				}

			});

			//jQuery('.woocommerce-message').addClass('hide');
		}
	};

	jQuery(document).ready(function () {

		ShopperJS.initReady();

	});


	jQuery(window).resize(function() {
		var windowW = jQuery(window).width();

		if (windowW >= 1024) {
			jQuery('.header-myacc-link, .site-header-cart, .site-footer .widget > div, .site-footer .widget > ul').removeAttr('style');
			jQuery('.header-myacc-link, .site-header-cart, .site-footer .widget-title').removeClass('active');
		}

		ShopperJS.calVerticalHeight();

	});


	jQuery( window ).load(function() {

			$('.products .product').each(function(index, el) {
				var img = jQuery(el).find('img.size-woocommerce_thumbnail');
				var  addCartTop = jQuery(img).height();
				jQuery(el).find('.loop-addtocart-btn-wrapper').css('top', addCartTop);
			});
	});




})(jQuery);
