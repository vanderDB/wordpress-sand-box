/*================================================
[  Table of contents  ]
================================================
:: Lazy Load

Window -> Load
:: Preloader
:: Owl Carousel
:: Isotope
:: Blog - Masonry
:: WpBakery - Full width Row
:: Megamenu - Dropdown Position

Document -> Ready
:: Ajax filter for product listing Page
:: Wishlist
:: Compare
:: Product action buttons display fix on mobile devices
:: Mega Menu on click
:: Nano Scroll
:: WooCommerce Promo Popup
:: Auto complete Search
:: Magnific Popup
:: Popup Gallery
:: Size Guide
:: Progress bar
:: Counter
:: Sticky Menu
:: Sticky header elements
:: Select 2
:: Clone Primary Menu
:: SlickNav
:: One Page Menu
:: Mobile Buttons
:: Inline Hover
:: WooCommerce
	:: Cookies info
	:: Product Load More
	:: Product Infinite Scroll
	:: WooCommerce Quick View
	:: Ajax After add to cart
	:: WooCommerce - Compare (Product Listing & Details Page) - Manage Loader
	:: Ajax Add To Cart On The Quick View
	:: WooCommerce - Woo Tools - Cart (Header)
	:: WooCommerce - Woo Tools - Compare (Header)
	:: Single Page Sticky Content
	:: WooCommerce Gallery
	:: Product Type - Sticky Gallery
	:: WooCommerce - Quantity Input
	:: WooCommerce Product Details - Related Products
	:: WooCommerce Product Details - Up Sell Products
	:: WooCommerce Product Details - Cross Sell Products
	:: WooCommerce Product Grid/List Switch
	:: WooCommerce Off Canvas Shop Sidebar
	:: WooCommerce Shop Filters
	:: WooCommerce - Filters-open
	:: WooCommerce Hover effect
	:: WooCommerce Video Popup
	:: WooCommerce Product Variations
	:: WooCommerce Product List Description - List "Hover Summary" style
	:: WooCommerce - Compare and WishList
:: Blog
	:: Blog Load More
:: Portfolio
	:: Portfolio Load More
	:: Portfolio Infinite Scroll
	:: Portfolio Single Page sticky Column
:: Tabs
:: Accordion
:: Back to top
:: Sticky Footer
:: Commingsoon countdown
:: Call youtube and vimeo video function
:: Shortcodes
	:: Slick-slider for testimonials shortcode
	:: Hot Deal / Banner (Deal)
	:: Newsletter Mailchimp
	:: PGSCore : Banner
	:: Multi Tab Product Listing - Carousel
	:: Image Slider : Popup
	:: Vertical Menu : SlickNav Menu
	:: Hotspot
======================================
[ End table content ]
======================================*/
(function($){
	"use strict";

	/*************************
	:: Lazy Load
	*************************/
	ciyashop_lazyload();

	jQuery( window ).load(function() {

		/*************************
		:: Preloader
		*************************/

		jQuery( '#load' ).fadeOut();
		jQuery( '#preloader' ).delay( 200 ).fadeOut( 'slow' );

		/*************************
		:: Owl Carousel
		*************************/

		cs_owl_carousel();

		/*************************
		:: Isotope
		*************************/

		cs_isotope();

		/*************************
		:: Blog - Masonry
		*************************/

		cs_blog_masonry();
		
		/******************************
		:: WpBakery - Full width Row
		******************************/
		
		ciyashop_vc_fullwidthrow();
		
		/*********************************
		:: Megamenu - Dropdown Position
		*********************************/

		setTimeout( function() {
			cs_megamenu_dropdown_position();
		}, 200 );

		/*********************************
		:: ciyashop WooCommerce quantity input
		*********************************/

		setTimeout( function() {
			ciyashop_WooCommerce_Quantity_Input();
		}, 300 );
	});

	jQuery( document ).ready(function() {

		ciyashop_lazyload();

		/*********************************************
		:: Ajax filter for product listing Page
		*********************************************/

		if ( $( 'body' ).hasClass( 'cs-filter-with-ajax' ) ) {

			$( document.body ).on( 'cs_shop_ajax_filter', function ( event, url, pagination ) {
				cs_shop_ajax_filter( url, pagination );
			});

			$( document ).on( 'submit', '.woocommerce-product-search', function( e ) {
			   e.preventDefault(e);

				var search  = jQuery( this ).find( '.search-field' ).val();
				var new_url = cs_update_query_string_parameter( window.location.href, 's', search )
				$( document.body ).trigger( 'cs_shop_ajax_filter', [ new_url, false ] );
			});

			$( document ).on( 'click', '.woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item a, .pgs-woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item a, .ciyashop-clear-filters, .widget_layered_nav_filters ul li a, .widget_product_categories ul li a, .widget_rating_filter ul li a, .woocommerce-categories-slider .item a, .ciyashop-products-per-page .per-page-variation', function (e) {
				e.preventDefault(e);
				var new_url = $(this).attr('href');
				$( document.body ).trigger( 'cs_shop_ajax_filter', [ new_url, false ] );
			});

			$( document ).on( 'click', '.woocommerce-pagination ul li a', function (e) {
				e.preventDefault(e);
				var new_url = $(this).attr('href');
				$( document.body ).trigger( 'cs_shop_ajax_filter', [ new_url, true ] );
			});

			$( document ).on( 'change', 'select.woocommerce-widget-layered-nav-dropdown', function( e ) {
				var slug = jQuery( this ).val();
				jQuery( ':input[name^="filter_"]' ).val( slug );
				if ( ! jQuery( this ).attr( 'multiple' ) ) {
					jQuery( this ).closest( 'form' ).submit();
				}
			});

			$( document ).on( 'submit', '.woocommerce-ordering', function( e ) {
				e.preventDefault();
			});

			$( document ).on( 'submit', '.loop-header-filters-form', function( e ) {
				e.preventDefault();
			});

			$( document ).on( 'change', '.woocommerce-ordering .orderby', function( e ) {
				e.preventDefault();

				var orderby = jQuery( this ).val();
				var new_url = cs_update_query_string_parameter( window.location.href, 'orderby', orderby )
				$( document.body ).trigger( 'cs_shop_ajax_filter', [ new_url, false ] );
			});

			$( document ).on( 'change', '.dropdown_layered_nav_taxonomy_filter, .shop-filter #shop-filter-search, .shop-filter .dropdown_product_cat, .shop-filter .dropdown_layered_nav_rating', function( e ) {
				e.preventDefault();

				var taxonomy_name  = jQuery( this ).attr( 'data-filter-name' );
				
				if ( ! taxonomy_name ) {
					taxonomy_name = jQuery( this ).attr( 'name' );
				}

				var taxonomy_value = jQuery( this ).val();
				var new_url = cs_update_query_string_parameter( window.location.href, taxonomy_name, taxonomy_value )
				$( document.body ).trigger( 'cs_shop_ajax_filter', [ new_url, false ] );
			});

			$( document ).on( 'click', '.price_slider_wrapper .button', function( e ) {
				e.preventDefault();

				var min_price = jQuery( '#min_price' ).val(),
					max_price = jQuery( '#max_price' ).val(),			
					new_url   = cs_update_query_string_parameter( window.location.href, 'min_price', min_price ),
					new_url   = cs_update_query_string_parameter( new_url, 'max_price', max_price );

				$( document.body ).trigger( 'cs_shop_ajax_filter', [ new_url, false ] );
			});
		}
		
		/************
		:: Wishlist
		*************/

		jQuery(document).on('click', '.cs-wcwl-add-button .add_to_wishlist', function(e) {
			e.preventDefault();

			var $this      = jQuery(this);
			var product_id = $this.data('product-id');

			if( jQuery(this).hasClass('added-wishlist') ) {
				var href = jQuery(this).attr('href');
				window.location.replace(href);
				return false;
			}

			jQuery.ajax({
				url: ciyashop_l10n.ajax_url,
				type: 'POST',
				dataType: "json",
				data: { 
					'action': 'add_ciyashop_wishlist',
					'product_id': product_id,
					ajax_nonce: ciyashop_l10n.ciyashop_nonce
				},
				beforeSend: function(){
					$this.addClass('cs-loading');
				},
				success: function( resp ) {

					$this.removeClass( 'cs-loading' );
					$this.addClass( 'added-wishlist' );

					jQuery( '.post-' + product_id ).find( '.cs-wcwl-add-button .add_to_wishlist' ).each( function () {
						if ( ! jQuery( this ).hasClass( 'added-wishlist' ) ) {
							jQuery( this ).addClass( 'added-wishlist' );
						}
					});

					if ( resp.added ) {
						
						if ( jQuery( $this ).parents( '.product-quick-view' ).length > 0 ) {
							jQuery( $this ).parents( '.cs-wcwl-add-button' ).append( '<span class="feedback">' + ciyashop_l10n.product_added_text  + '</span>' );
						};

						jQuery( '.ciyashop-wishlist-count' ).html( resp.count );
						jQuery( '.product-added' ).show();
						jQuery( '.product-added' ).fadeTo( 200, 1 );
						setTimeout(function() {
							jQuery( '.product-added' ).fadeTo( 200, 0 );
						}, 1700 );
						
					} else {
						
						if ( jQuery( $this ).parents( '.product-quick-view' ).length > 0 ) {
							jQuery( $this ).parents( '.cs-wcwl-add-button' ).append( '<span class="feedback">' + ciyashop_l10n.already_in_wishlist_text  + '</span>' );
						};
						
						jQuery( '.already-in-wishlist' ).show();
						jQuery( '.already-in-wishlist' ).fadeTo( 200, 1 );
						setTimeout( function() {
							jQuery( '.already-in-wishlist' ).fadeTo( 200, 0 );
						}, 1700 );
					}

					$this.closest( '.product-action-wishlist' ).attr( 'data-original-title', ciyashop_l10n.browse_wishlist );
					$this.find( 'span' ).html( ciyashop_l10n.browse_wishlist );
				}
			});
		});

		jQuery(document).on('click', '.cs-remove-wishlist', function(e){
			e.preventDefault();
			var $this = jQuery(this);
			var product_id = $this.data('product-id');

			jQuery.ajax({
				url: ciyashop_l10n.ajax_url,
				type: 'POST',
				dataType: "json",
				data: { 
					'action': 'remove_ciyashop_wishlist',
					'product_id': product_id,
					ajax_nonce: ciyashop_l10n.ciyashop_nonce
				},
				beforeSend: function(){
					jQuery('.wishlist_table').addClass('cs-loading');
				},
				success: function( resp ) {
					jQuery('.ciyashop-wishlist-count').html(resp);
					$this.closest('.cs-wcwl-row').addClass('remove-wishlist');
					$this.closest('.cs-wcwl-row').fadeOut();
					if ( resp <= 0 ) {
						jQuery('.wishlist-empty').removeClass('d-none');
					}
				}
			}).done( function(){
				jQuery('.wishlist_table').removeClass('cs-loading');
				jQuery('.remove-wishlist').remove();
			});

		});

		/************
		:: Compare
		*************/

		jQuery(document).on('click', '.ciyashop-compare', function(e) {
			e.preventDefault();

			var $this      = jQuery(this);
			var product_id = $this.data('product-id');
			var product_action = $this.data('product-action');
			var data = { 
				'action': 'ciyashop_get_compare',
				'product_id': product_id,
				ajax_nonce: ciyashop_l10n.ciyashop_nonce
			};
			
			if ( product_action ) {
				data.product_action = product_action;
			}

			jQuery.ajax({
				url: ciyashop_l10n.ajax_url,
				type: 'POST',
				data: data,
				beforeSend: function(){
					$this.addClass('cs-loading');
				},
				success: function( resp ) {
					$this.removeClass( 'cs-loading' );
					jQuery('#cs-comparelist').html( resp );
					jQuery('#cs-comparelist').show();
					if ( ! jQuery('#cs-comparelist').hasClass( 'popup-open' ) ) {
						jQuery('#cs-comparelist').addClass( 'popup-open' );
					}
					if ( jQuery( '.cs-compare-list-content .cs-product-list-column' ).length > 0 ) {
						jQuery( '.cs-compare-list-wrapper' ).removeClass( 'd-none' );
						jQuery( '.cs-woocompare-popup-content .compare-empty-text' ).addClass( 'd-none' );
						cs_compare_popup_row_height();
						var viewport = $(window).width();
						if ( viewport >= 576 ) {
							jQuery( '.cs-compare-list-content' ).sortable({
								cursor: 'move',
							});
						}
					}
				}
			});
		});

		jQuery( document ).on( 'click', '#cs-compare-popup-close', function( e ) {
			e.preventDefault();

			jQuery('#cs-comparelist').removeClass( 'popup-open' );
			jQuery('#cs-comparelist').hide();
		});

		jQuery(document).on('click', '.cs-product-compare-remove', function(e) {
			e.preventDefault();

			var $this      = jQuery(this);
			var product_id = jQuery(this).attr( 'data-product_id' );
			var data = { 
				'action': 'ciyashop_remove_compare_product',
				'product_id': product_id,
				ajax_nonce: ciyashop_l10n.ciyashop_nonce
			};

			jQuery.ajax({
				url: ciyashop_l10n.ajax_url,
				type: 'POST',
				dataType: 'json',
				data: data,
				beforeSend: function(){
					$this.parents( '.cs-product-list-column' ).addClass('cs-loading');
				},
				success: function( resp ) {
					$this.parents( '.cs-product-list-column' ).fadeOut( 'slow', function() {
						$(this).remove();
						if ( ! jQuery( '.cs-compare-list-content .cs-product-list-column' ).length ) {
							jQuery( '.cs-compare-list-wrapper' ).addClass( 'd-none' );
							jQuery( '.cs-woocompare-popup-content .compare-empty-text' ).removeClass( 'd-none' );
						} else {
							cs_compare_popup_row_height();
						}
					});
				}
			});
		});

		jQuery( document ).on( 'click', '.quantity .quantity-up', function( e ) {
			var input   = $(this).parents( '.quantity' ).find( 'input.qty' ),
				max     = input.attr('max'),
				new_val = '';

			if( ! max ) max = 999999;

			var old_value = parseFloat( input.val() );

			if ( isNaN( old_value ) ) {
				old_value = 0;
			}

			if ( old_value >= max ) {
				new_val = old_value;
			} else {
				new_val = old_value + 1;
			}
			input.val( new_val );
			input.trigger( 'change' );
		});

		jQuery( document ).on( 'click', '.quantity .quantity-down', function( e ) {
			var input   = $(this).parents( '.quantity' ).find( 'input[type="number"]' ),
				min     = input.attr( 'min' ),
				new_val = '';

			var old_value = parseFloat( input.val() );

			if ( isNaN( old_value ) ) {
				old_value = 0;
			}

			if ( old_value <= min ) {
				new_val = old_value;
			} else {
				new_val = old_value - 1;
			}

			input.val( new_val );
			input.trigger( 'change' );
		});

		/***********************************************************
		:: Product action buttons display fix on mobile devices
		************************************************************/

		if ( jQuery('body').hasClass('device-type-mobile') ) {
			$( '.products .product' ).on( 'click', function( event ) {
				var current_product = this;
				var current_product_id = $( current_product ).find( '.add_to_cart_button' ).data('product_id');

				$( '.products .product' ).each(function( index ) {
					var product = this;
					var product_id = $( product ).find( '.add_to_cart_button' ).data('product_id');

					if ( current_product_id !== product_id || typeof this.touched == 'undefined' ) {
						this.touched = false;
					}
				});

				if ( ! jQuery('body').hasClass( 'hover-effect-mobile-disabled' ) ) {
					if ( this.touched === false ) {
						event.stopPropagation();
						event.preventDefault();
					}

					if ( this.touched === true ) {
						this.touched = false;
						event.returnValue = true;
						$( this ).click();
					} else {
						this.touched = true;
					}
				}
			} );
		}

		/*************************
		:: Mega Menu on click
		*************************/

		$(document).on('click', 'ul.pgs_megamenu-enable > li.pgs-menu-item-open-on-click > a', function(e){
			if( !$(this).parent('.pgs-menu-item-open-on-click').hasClass('pgs-menu-item-link-enable') || $(this).parent('.pgs-menu-item-open-on-click').hasClass('pgs-menu-item-link-enable') && !$(this).parent('.pgs-menu-item-open-on-click').hasClass('pgs-menu-item-clicked')){
				e.preventDefault();
				if(!$(this).parent('.pgs-menu-item-open-on-click').hasClass('pgs-menu-item-clicked')){
					$('.pgs-menu-item-open-on-click').removeClass('pgs-menu-item-clicked');
					$(this).parent('.pgs-menu-item-open-on-click').addClass('pgs-menu-item-clicked');
				}else{
					$(this).parent('.pgs-menu-item-open-on-click').removeClass('pgs-menu-item-clicked');
				}
			}
		});

		$(document).click(function(e){
			var target = e.target;
			if(!$(target).parents().is('.pgs-menu-item-open-on-click')){
				if($( "ul.pgs_megamenu-enable > li.pgs-menu-item-open-on-click" ).hasClass('pgs-menu-item-clicked')){
					$("ul.pgs_megamenu-enable > li.pgs-menu-item-open-on-click").removeClass('pgs-menu-item-clicked');
				}
			}
		});

		/***************
		:: Nano Scroll
		****************/

		cs_widget_cart_nanoscroll();

		cs_widgets_nanoscroll();

		$( 'body' ).bind( 'wc_fragments_refreshed wc_fragments_loaded', function() {
			ciyashop_WooCommerce_Quantity_Input();
			cs_widget_cart_nanoscroll();
		});

		/*****************************
		:: WooCommerce Promo Popup
		******************************/

		setTimeout( function() {
			if ( $( '.ciyashop-promo-popup' ).length > 0 ) {

				// Bail early if popup is already displayed
				if ( Cookies.get( 'woocommerce_popup' ) == 'shown' ) {
					return;
				}

				// Bail early if popup is disabled
				if ( ciyashop_l10n.main_promopopup == 0 ) {
					return;
				}

				// Bail early if, in mobile device and popup is disabled
				if ( $(window).width() < 768 && ciyashop_l10n.promopopup_hide_mobile == 1 ) {
					return;
				}

				$.magnificPopup.open({
					items: {
						src: '.ciyashop-promo-popup',
						type: 'inline',
					},
					removalDelay: 400, //delay removal by X to allow out-animation
					callbacks: {
						beforeOpen: function() {
							this.st.mainClass = 'ciyashop-popup-effect';
						},
						close: function() {

							var promo_popup_checked = $( "input[id='hide_promo_popup']:checked" ).length;
							var hide_promo_popup    = $( '#hide_promo_popup' ).length;

							if ( hide_promo_popup ) {
								if ( promo_popup_checked ) {
									Cookies.set( 'woocommerce_popup', 'shown', { expires: 7, path: '/' } );
								}
							} else {
								Cookies.set( 'woocommerce_popup', 'shown', { expires: 7, path: '/' } );
							}
						}
					}
				});
			}
		}, 1000 );

		/*************************
		:: Auto complete Search
		*************************/

		cs_search_autocomplete();

		jQuery( document ).bind( 'click', function ( event ) {
			// Check if we have not clicked on the search box
			if ( !( $( event.target ).parents().andSelf().is( 'input.search-form' ) ) ) {
				jQuery( 'ul.search_form-autocomplete .ui-menu-item' ).remove();
				jQuery( '.ciyashop-auto-compalte-default' ).addClass( 'ciyashop-empty' );
				// Hide/collapse your search box, autocomplete or whatever you need to do
			}
		});

		$( 'input.search-form' ).keyup( function () {
			if ( jQuery( this ).val().length < 1 ) {
				jQuery( '.ciyashop-auto-compalte-default ul' ).empty();
				jQuery( '.ciyashop-auto-compalte-default' ).addClass( 'ciyashop-empty' );
			}
		});

		/*************************
		:: Magnific Popup
		*************************/

		$(".mfp-popup-link").each(function () {
			var $mfp_popup_link = $(this),
				$mfp_popup_option = ( $mfp_popup_link.attr('data-mfp_options')) ? $mfp_popup_link.data('mfp_options') : {};

			$($mfp_popup_link).magnificPopup($mfp_popup_option);
		});

		$('.pgs-qrcode-popup-link').magnificPopup({
			type: 'image',
			mainClass: 'mfp-pgs-qrcode',
			image: {
				markup: '<div class="mfp-figure">'+
							'<div class="mfp-close"></div>'+
							'<div class="mfp-pgs-qrcode-img">'+
								'<img class="mfp-img">'+
							'</div>'+
						'</div>', // Popup HTML markup. `.mfp-img` div will be replaced with img tag, `.mfp-close` by close button
			}
		});

		/*************************
		:: Popup Gallery
		*************************/
		
		cs_popup_gallery();

		/*************************
		:: Size Guide
		*************************/

		if ( $('.pgs-sizeguide-popup').length > 0 ) {
			$('.pgs-sizeguide-popup').magnificPopup({
				type: 'inline',
				midClick: true,
				mainClass: 'mfp-fade'
			});
		}

		/*************************
		:: Progress bar
		*************************/

		// Show animated progress bar
		if ( $(".progress-bar").length ) {
			cs_progress_bar_run();
			jQuery( window ).scroll(function() {
				cs_progress_bar_run();
			});
		}

        /*************************
		:: Counter
		*************************/

        // Show animated counter
		if ( $(".counter-number").length ) {
			cs_counter_run();
			jQuery( window ).scroll(function() {
				cs_counter_run();
			});
		}

		/*************************
		:: Sticky Menu
		*************************/

		var header_sticky_el = $("#header-sticky"),
			sticky_top = 0,
			header_sticky_adminbar = $('#wpadminbar');

		$(document).ready(function(){
			ciyashop_sticky_menu();
		});
		function ciyashop_sticky_menu(update){
			if(
				( ciyashop_l10n.device_type == 'desktop' && ciyashop_l10n.sticky_header == '1' )
				|| ( ciyashop_l10n.device_type == 'mobile' && ciyashop_l10n.sticky_header_mobile == '1' )
			){
				if( $('body').hasClass('admin-bar') && header_sticky_adminbar.length > 0 ){
					if( header_sticky_adminbar.css('position') == 'fixed' ){
						sticky_top = $('#wpadminbar').outerHeight();
					}else{
						sticky_top = 0;
					}
				}

				header_sticky_el.sticky({
					topSpacing:sticky_top
				});

			}
			if( typeof update == 'boolean' && update ){
				header_sticky_el.sticky('update');
			}

			// Nano scroll for sticky header
			cs_widget_cart_nanoscroll();
		}

		$(window).on('resize', function(event){
			var windowSize = $(window).width(); // Could've done $(this).width()

			if( ciyashop_l10n.sticky_header == '1' && ciyashop_l10n.device_type == 'desktop' && ciyashop_l10n.sticky_header_mobile == '0' ){
				if( windowSize <= 992 ){
					header_sticky_el.unstick();
				}else if( windowSize > 992 ){
					ciyashop_sticky_menu(true);
				}
			}else{
				ciyashop_sticky_menu(true);
			}
		});

		/*************************
		:: Sticky header elements
		*************************/

		if( $( '.header-style-custom' ).length > 0 ){
			chl_sticky_header();
		}

		$(window).on('resize', function(event){
			if( $( '.header-style-custom' ).length > 0 ){
				chl_sticky_header();
			}
			cs_megamenu_dropdown_position();
			ciyashop_vc_fullwidthrow();
		});

		$( window ).on( 'scroll', function(){
			if( $( '.header-style-custom' ).length > 0 ){
				chl_sticky_header();
			}
			cs_megamenu_dropdown_position();
			ciyashop_vc_fullwidthrow();
		});

		/*************************
		:: Select 2
		*************************/

		cs_select2_init();
		ciyashop_search_form_category();

		/*************************
		:: Clone Primary Menu
		*************************/

		var primary_menu_to_be_cloned,
			site_navigation_sticky = $('#site-navigation-sticky');

		if( $('#mega-menu-wrap-primary').length > 0 ){
			primary_menu_to_be_cloned = $('#mega-menu-wrap-primary');
		}else{
			primary_menu_to_be_cloned = $('#primary-menu');
		}

		var primary_menu_cloned = $(primary_menu_to_be_cloned).clone();
		$(site_navigation_sticky).append(primary_menu_cloned);


		/*************************
		:: SlickNav
		*************************/

		$('#site-navigation-mobile').slicknav({
			label : '',
			appendTo : '#site-navigation-sticky-mobile',
			allowParentLinks: true,
			'closedSymbol': '&#43;', // Character after collapsed parents.
			'openedSymbol': '&#45;', // Character after expanded parents.
			'init': function(){
				var init_trigger = $('#site-navigation-sticky-mobile').find('.slicknav_btn');
				if( $(init_trigger).hasClass('slicknav_collapsed') ){
					$('.mobile-menu-trigger').removeClass('mobile-menu-trigger-closed mobile-menu-trigger-opened').addClass('mobile-menu-trigger-closed');
				}else{
					$('.mobile-menu-trigger').removeClass('mobile-menu-trigger-closed mobile-menu-trigger-opened').addClass('mobile-menu-trigger-opened');
				}
			},
			afterOpen: function(trigger){
				if( $(trigger).hasClass('slicknav_collapsed') ){
					$('.mobile-menu-trigger').removeClass('mobile-menu-trigger-closed mobile-menu-trigger-opened').addClass('mobile-menu-trigger-closed');
				}else{
					$('.mobile-menu-trigger').removeClass('mobile-menu-trigger-closed mobile-menu-trigger-opened').addClass('mobile-menu-trigger-opened');
				}
			},
			afterClose: function(trigger){
				if( $(trigger).hasClass('slicknav_collapsed') ){
					$('.mobile-menu-trigger').removeClass('mobile-menu-trigger-closed mobile-menu-trigger-opened').addClass('mobile-menu-trigger-closed');
				}else{
					$('.mobile-menu-trigger').removeClass('mobile-menu-trigger-closed mobile-menu-trigger-opened').addClass('mobile-menu-trigger-opened');
				}
			}
		});

		$(document).on('click', '.mobile-menu-trigger', function (event) {
			event.stopPropagation();
			$('#site-navigation-mobile').slicknav('toggle');
		});

		/******************
		:: One page Menu
		*******************/

		var primary_menu = $('.primary-menu');
		var primary_menu_mobile = $('.primary-menu-mobile');

		if( primary_menu.length > 0 || primary_menu_mobile.length > 0 ){

			var current_url = window.location.href;
			var curl =  current_url.split('#');

			if(curl[1]){
				if(document.querySelector('#'+curl[1]) != null){
					document.querySelector('#'+curl[1]).scrollIntoView();
				}
			}

			$('ul.primary-menu li a, ul.primary-menu-mobile li a').each(function( index ){
				this.addEventListener( 'click', cs_one_navigation );
			});

			$( document ).on( 'scroll', cs_onScroll );
		}

		/*************************
		:: Mobile Buttons
		*************************/

		var mobile_search_open = false;

		$(document).on( "click", function(event) {
			event.stopPropagation();
			if( $(event.target).is(".mobile-search-trigger, .mobile-search-trigger > i, .search-button-wrap > .search-element-mobile-view > .mobile-search-button > i") ){
				if( $( '.header-style-custom' ).length > 0 ){
					$(event.target).parents('.header-element-item').find('.mobile-search-wrap').toggleClass('active');
				} else {
					$('.mobile-search-wrap').toggleClass('active');
				}
			}else if( $(event.target).is(".mobile-search-wrap, .mobile-search-wrap *") ){
			}else{
				if( $('.mobile-search-wrap').hasClass('active') ){
					$('.mobile-search-wrap').removeClass('active');
				}
			}
		});

		/*************************
		:: Inline Hover
		*************************/

		$( '.inline_hover' ).on( "mouseenter", function() {
			var $this = $( this );
			var attr = $(this).attr('data-trigger_ele');
			var hover_styles = '';
			if (typeof attr !== typeof undefined && attr !== false) {
				var $element = $this.find( '.' + attr );
				hover_styles = $element.data( 'hover_styles');
				$.each(hover_styles, function(index, value) {
					if( $element.css(index) != null )  {
						/*success*/
						$element.data( 'prehover_'+index, $element.css( index ) );
					}else {
						/*does not have*/
						$element.data( 'prehover_'+index, 'remove' );
					}
					$element.css( index, value );
				});
			}

			hover_styles = $this.data( 'hover_styles');
			$.each(hover_styles, function(index, value) {
				if( $this.css(index) != null )  {
					/*success*/
					$this.data( 'prehover_'+index, $this.css( index ) );
				}else {
					/*does not have*/
					$this.data( 'prehover_'+index, 'remove' );
				}

				$this.css( index, value );
			});
		});

		$( '.inline_hover' ).on( "mouseleave", function(){
			var $this = $( this );

			var attr = $(this).attr('data-trigger_ele');
			if (typeof attr !== typeof undefined && attr !== false) {
				var $element = $this.find( '.' + attr );
				var hover_styles = $element.data( 'hover_styles');
				$.each(hover_styles, function(index, value) {
					$element.css( index, '' );
				});
			}

			hover_styles = $this.data( 'hover_styles');
			$.each(hover_styles, function(index, value) {
				if( $this.data( 'prehover_'+index ) != 'remove' ){
					$this.css( index, $this.data( 'prehover_'+index ) );
				}else{
					$this.css( index, '' );
				}
			});

			var prehover_styles = $this.data('prehover_style');
			$.each(prehover_styles, function(index, value) {
				$this.css( index, value );
			});
		});

		/********************************************************************************
		 *
		 :: WooCommerce
		 *
		 ********************************************************************************/

		/*************************
		:: Cookies info
		*************************/

		ciyashop_cookiesinfo();
		function ciyashop_cookiesinfo(){
			if( Cookies.get('ciyashop_cookies') != 'accepted' ){
				$('.ciyashop-cookies-info').show();
			}
			$( '.ciyashop-cookies-info' ).on('click', '.cookies-info-accept-btn', function(e) {
				e.preventDefault();
				ciyashop_acceptCookies();
			});
			var ciyashop_acceptCookies = function() {
				$('.ciyashop-cookies-info').hide();
				Cookies.set('ciyashop_cookies', 'accepted', { expires: 60, path: '/' } );
			};
		}


		/*************************
		:: Product Load More
		*************************/

		jQuery('.product-more-button a').on('click', function(e) {
		    e.preventDefault();
			var more_btn     = this;
			var next_link    = $(this).data( 'next_link' );
			var max_pages    = $(this).data( 'max_pages' );
			var current_page = $(this).data( 'current_page' );
			var next_page    = $(this).data( 'next_page' );

			// Disable button click if loaded all pages and added disabled class
			if( $(more_btn).hasClass( 'disabled' ) ){
				return;
			}

			// Call ajax to fetch data
			$.ajax({
				url: next_link,
				beforeSend: function( xhr ) {
					$(more_btn).html( ciyashop_l10n.loading );
					$(more_btn).addClass('content-loading');
				}
			})
			.done(function( data ) {

				$(more_btn).removeClass('content-loading');
				// Load data in temp
				var temp = $(data);

				// Get button from ajax data
				var next_btn = temp.find('.product-more-button').html();
				var products = temp.find('ul.products-loop');

				// Get button data from button in returned data
				var next_btn_link         = $( next_btn ).data('next_link');
				var next_btn_max_pages    = $( next_btn ).data('max_pages');
				var next_btn_next_page    = $( next_btn ).data('next_page');
				var next_btn_current_page = $( next_btn ).data('current_page');

				// Check if current page count is less than max page count
				if( next_btn_current_page < next_btn_max_pages ){

					// Set 'Load more...' back after ajax completed
					$( more_btn ).html( ciyashop_l10n.load_more );

					// Set returned button data to button
					$( more_btn ).data( 'next_link', next_btn_link);
					$( more_btn ).data( 'max_pages', next_btn_max_pages);
					$( more_btn ).data( 'next_page', next_btn_next_page);
					$( more_btn ).data( 'current_page', next_btn_current_page);
				}else{
					$( more_btn ).html( ciyashop_l10n.no_more_product_to_load ).addClass( 'disabled' );
				}

				// Append the new data to product
				$( 'li.product' ).last().after( products.html() );

				// Set the grid column
				cs_product_grid_set();
				ciyashop_lazyload();

			});
		});

        /****************************
		:: Product Infinite Scroll
		****************************/

		var counter = 1;
		jQuery( window ).scroll(function() {
			if( $('.product-infinite_scroll').length > 0 ){
				if( $('.product-more-button a').length > 0 ) {
					var load_more_button = $('.product-more-button a');

					var scrollHeight = Math.round( load_more_button.offset().top );
					var scrollOuterHeight = load_more_button.height();
					var scrollPosition = $(window).height() + $(window).scrollTop();
					var total = load_more_button.data('current_page');

					if ( scrollPosition > scrollHeight + scrollOuterHeight ) {
						setTimeout(function () {
							if( total == counter ){
								if(!load_more_button.hasClass('content-loading')){
									counter++;
									load_more_button.trigger( 'click' );
								}
							}
						}, 200);
					}
				}
			}
		});

		/****************************
		:: WooCommerce Quick View
		****************************/

		$( document ).on( 'click', '.open-quick-view', function(e) {
			e.preventDefault();

			var productId = $(this).data('id'),btn = $(this);
			btn.addClass('loading');
			var data = {
					id: productId,
					action: 'ciyashop_quick_view',
					lang: ciyashop_l10n.lang,
					ajax_nonce: ciyashop_l10n.ciyashop_nonce
				};

			$.ajax({
				url: ciyashop_l10n.ajax_url,
				data: data,
				method: 'get',
				success: function(data) {
					// Open directly via API
					$.magnificPopup.open({
						items: {
							src: '<div class="mfp-with-anim ciyashop-popup ciyashop-popup-quick-view">' + data + '</div>', // can be a HTML string, jQuery object, or CSS selector
							type: 'inline'
						},
						removalDelay: 500, //delay removal by X to allow out-animation
						callbacks: {
							beforeOpen: function() {
							},
							open: function() {
								$( '.quantity' ).trigger( 'init' );

								if( $( '.variations_form' ).length > 0 ){

									$( '.product-quick-view .variations_form' ).wc_variation_form();

									$(document).on('change', '.variations_form', function(){

										var variationsData = $( '.variations_form' ).data('product_variations'),
											$singleVariationWrap   = $( '.variations_form' ).find( '.single_variation_wrap' ),
											variation_id = $($singleVariationWrap).find('.variations_button input[name="variation_id"]').val();

										var current_variation = variationsData.filter(function (current_variation) {
											return current_variation.variation_id == cs_actvars_get_current_variation_id();
										});

										function cs_actvars_get_current_variation_id(){
											var variation_id = $($singleVariationWrap).find('.variations_button input[name="variation_id"]').val();
											if( variation_id == '' ) return false;
											return variation_id;
										}

										if(typeof current_variation[0] != "undefined" || current_variation[0]){
											if ( current_variation[0] && current_variation[0].image && current_variation[0].image.src && current_variation[0].image.src.length > 1 ) {
												$('img#product-zoom').attr('src', current_variation[0].image.url);
											}

											$(document).on('click', '.reset_variations', function(){
												$('img#product-zoom').attr('src', $('img#product-zoom').attr('data-zoom-image'));
											});
										}
									});
								}
							}
						},
					});

				},
				complete: function() {
					ciyashop_WooCommerce_Quantity_Input();
					btn.removeClass('loading');
					ciyashop_lazyload();
				},
				error: function() {
				},
			});

		});


		/****************************
		:: Ajax After add to cart
		****************************/

		$( document.body ).on( 'added_to_cart', function() {
			if ( $( '.after_add_to_cart_message-wrapper' ).length != 0 ) {
				 $.magnificPopup.open({
					 items: {
						removalDelay: 500,
						src: '#after_add_to_cart_message-popup',
						type: 'inline'
					}
				});
			}

			if ( $( '.ciyashop-popup-quick-view .mfp-close' ).length > 0 ) {
				$( '.ciyashop-popup-quick-view .mfp-close' ).trigger( 'click' );
			}

			if ( $( '#cs-comparelist' ).length > 0 ) {
				jQuery( '#cs-comparelist' ).removeClass( 'popup-open' );
				jQuery( '#cs-comparelist' ).hide();
			}

			if ( $( '.after_add_to_cart_message-wrapper' ).length != 0 ) {
				$( '.after_add_to_cart_message-wrapper' ).addClass( 'side_cart-show' );
			}

			if ( $( '.side_shopping_cart-wrapper' ).length != 0 ) {
				$( '.side_shopping_cart-overlay' ).addClass( 'side_cart-show' );
				$( '.side_shopping_cart-wrapper' ).addClass( 'side_shopping_cart-show' );
			}
		});

		$( '.close-popup' ).on( 'click', function(e) {
			e.preventDefault();
			$.magnificPopup.close();
		});

		$( '.close-side_shopping_cart' ).on( 'click', function() {
			if( $( '.after_add_to_cart_message-wrapper' ).hasClass( 'side_cart-show' ) ) {
				$( '.after_add_to_cart_message-wrapper' ).removeClass( 'side_cart-show' );
			}
		});
		
		/************************************
		:: Update cart on quantity change
		************************************/

		var ajax_update_timeout;
		$( document ).on( 'change input', '.woocommerce-mini-cart .quantity .qty', function() {
			var $this         = $( this );
			var qty           = $this.val();
			var item_id       = $this.parents( '.woocommerce-mini-cart-item' ).attr( 'data-cart_item_key' );
			var cart_hash_key = ciyashop_l10n.cart_hash_key;
			var fragment_name = ciyashop_l10n.fragment_name;

			clearTimeout( ajax_update_timeout );

			ajax_update_timeout = setTimeout( function () {
				$this.parents( '.mini_cart_item' ).addClass( 'cs-loading' );

				$.ajax({
					url: ciyashop_l10n.ajax_url,
					dataType: 'json',
					method: 'GET',
					data: {
						action: 'ciyashop_update_cart_item_details',
						item_id: item_id,
						qty: qty,
						ajax_nonce: ciyashop_l10n.ciyashop_nonce
					},
					success: function ( response ) {
						if ( response.fragments ) {
							$.each( response.fragments, function( key, val ) {
								if ( $( key ).hasClass( 'widget_shopping_cart_content' ) ) {

									var item_val      = $( val ).find( '.woocommerce-mini-cart-item[data-cart_item_key="' + item_id + '"]' );
									var cart_total   = $( val ).find( '.woocommerce-mini-cart__total' )

									if ( ! response.cart_hash ) {
										$( key ).replaceWith( val );
									} else {
										$( key ).find( '.woocommerce-mini-cart-item[data-cart_item_key="' + item_id + '"]' ).replaceWith( item_val );
										$( '.woocommerce-mini-cart__total' ).replaceWith( cart_total );
									}

								} else {
									$( key ).replaceWith( val );
								}
							});
						}
					},
					complete: function() {
						ciyashop_WooCommerce_Quantity_Input();
					},
				});
			}, 600 );
		});

		/*********************************************************************************
		:: WooCommerce - Compare (Product Listing & Details Page) - Manage Loader
		**********************************************************************************/

		$(document).on('click', '.product-action a.compare, .product-summary-actions a.compare, .yith-wcwl-add-button .add_to_wishlist', function (e) {
			$(this).addClass('cs-loading');
		});

		// remove class once ajax loaded for compare
		$( document ).ajaxComplete(function() {
			$( '.product-action a.compare, .product-summary-actions a.compare' ).each(function(){
				if( $(this).hasClass('added') ){
					$(this).removeClass('cs-loading');
				}
			});
			$( '.yith-wcwl-add-button' ).each(function(){
				if( $(this).hasClass('hide') ){
					$(this).find('.cs-loading').removeClass('cs-loading');
				}
			});
			/*Compare : Remove product issue*/
			$(".DTFC_LeftBodyLiner").empty();
			$(".DTFC_Cloned").empty();
		});

		$(document).on('click', '#cboxClose, #cboxOverlay', function (e) {
			setTimeout(function() {
				cs_widget_cart_nanoscroll();
			},500);
		});

		/******************************************
		:: Ajax Add To Cart On The Quick View
		*******************************************/

		if ( typeof wc_cart_fragments_params !== 'undefined' ) {

			var $warp_fragment_refresh = {
				url: wc_cart_fragments_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'get_refreshed_fragments' ),
				type: 'POST',
				success: function( data ) {
					if ( data && data.fragments ) {
						jQuery.each( data.fragments, function( key, value ) {
							jQuery( key ).replaceWith( value );
						});
						jQuery( document.body ).trigger( 'wc_fragments_refreshed' );
					}
				}
			};

			jQuery(document).on('submit','form.cart',function(e){
				if($(this).parents('.ciyashop-popup-quick-view').length > 0){
					e.preventDefault();
					jQuery('.single_add_to_cart_button').block({
						message: null,
						overlayCSS: {
							cursor: 'none'
						}
					});

					var current_url = window.location.toString();
					if( current_url.indexOf('?') > -1 ){
						current_url = current_url.substring( 0, current_url.indexOf('?') );
					}

					var product_url = current_url+"/?add-to-cart="+jQuery(".single_add_to_cart_button").val();
					var form = jQuery(this);
					jQuery.post(product_url, form.serialize() + '&_wp_http_referer=' + product_url, function (result){
						jQuery.ajax($warp_fragment_refresh);
						jQuery('.single_add_to_cart_button').unblock();
					});
				}
			});
		}

		/******************************************
		:: WooCommerce - Woo Tools - Cart (Header)
		*******************************************/

		$(document.body).on("adding_to_cart", function() {
			if(!$('body').hasClass('scroll_to_cart-on'))
				return;

			if( $('.woo-tools-action.woo-tools-cart').length != 0 && $('.after_add_to_cart_message-wrapper').length == 0 && $('.side_shopping_cart-wrapper').length == 0 ){
				$('body,html').animate({scrollTop:0},1000);
				$('.woo-tools-action.woo-tools-cart').addClass('woo-tools-cart-show');
			}
		 });

		$('.close-side_shopping_cart').on( 'click', function( event ) {
			event.preventDefault();
			if($('.side_shopping_cart-wrapper').hasClass('side_shopping_cart-show')){
				$('.side_shopping_cart-wrapper').removeClass('side_shopping_cart-show');
				$( 'div.side_shopping_cart-overlay' ).removeClass('side_cart-show');
			}
		});

		$( document.body ).on( 'added_to_cart', function() {
			if ( ! $( document.body ).hasClass( 'scroll_to_cart-on' ) ) {
				return;
			}
			if ( $( '.woo-tools-action.woo-tools-cart' ).length != 0 && $( '.after_add_to_cart_message-wrapper' ).length == 0 && $( '.side_shopping_cart-wrapper' ).length == 0 ) {
				setTimeout( function() {
					$( '.woo-tools-action.woo-tools-cart' ).removeClass( 'woo-tools-cart-show' );
				}, 3500 );
			}
		});

		/************************************************
		:: WooCommerce - Woo Tools - Compare (Header)
		*************************************************/

		$(document).on('click', '.woo-tools-compare > a', function (e) {
			e.preventDefault();

			var table_url = this.href;

			if (typeof table_url == 'undefined')
				return;

			$('body').trigger('yith_woocompare_open_popup', {response: table_url, button: $(this)});
		});

		/*******************************
		:: Single Page Sticky Content
		*******************************/

		$(document).scroll(function () {

			if($('.product_title.entry-title').length != 0 ){

				var header_sticky_adminbar = $('#wpadminbar');
				var windows_height = $(window).height();
				var element_height = $('.product_title.entry-title').offset().top;
				var position = 10;

				if($("#header-sticky").is(":visible")){
					position =  position + $("#header-sticky").outerHeight();
				}

				$( '.desktop-sticky-on' ).each( function () {
					position =  position + $( this ).outerHeight();
				});

				if( $('body').hasClass('admin-bar') && header_sticky_adminbar.length > 0 ){
					if( header_sticky_adminbar.css('position') == 'fixed' ){
						position = position + $('#wpadminbar').outerHeight();
					}
				}

				var current_position = $(this).scrollTop();

				if(current_position > element_height){
					$(".woo-product-sticky-content").sticky({topSpacing:position});
				}else if((current_position < element_height)){
					 $(".woo-product-sticky-content").unstick();
				}
			}
		});

		/************************
		:: WooCommerce Gallery
		*************************/

		if ( $(".ciyashop-product-images-wrapper").length > 0 ) {

			if ( $(".ciyashop-product-images-wrapper").hasClass("ciyashop-gallery-style-wide_gallery") ) {
				var $single_product_gallery__wide = $(".ciyashop-product-gallery__wrapper");

				$single_product_gallery__wide.slick({
					arrows: true,
					centerMode: false,
					dots: false,
					draggable: true,
					focusOnSelect: true,
					infinite: false,
					respondTo: 'slider',
					slidesToShow: 3,
					slidesToScroll: 1,
					swipeToSlide: true,
					touchMove: true,
                    rtl: (jQuery( "body" ).hasClass( "rtl" )) ? true : false,
				});

			}else{
				if ( $( '.ciyashop-product-gallery__wrapper' ).length > 0 && $( '.ciyashop-product-thumbnails__wrapper' ).length > 0 ) {
					var $single_product_gallery__default = $(".ciyashop-product-gallery__wrapper");
					var $single_product_thumbnails__default = $(".ciyashop-product-thumbnails__wrapper");
					var vertical;

					if ( $( '.ciyashop-product-images-wrapper' ).hasClass( 'ciyashop-gallery-thumb_vh-vertical' ) ) {
						vertical = true;
					} else {
						vertical = false;
					}

					if ( $(window).width() < 768 ) {
						vertical = false;
					}

					$single_product_gallery__default.slick({
						arrows: false,
						asNavFor: '.ciyashop-product-thumbnails__wrapper',
						centerMode: false,
						dots: false,
						draggable: true,
						focusOnSelect: true,
						infinite: false,
						respondTo: 'slider',
						slidesToShow: 1,
						slidesToScroll: 1,
						swipeToSlide: true,
						touchMove: true,
	                    rtl: (jQuery( "body" ).hasClass( "rtl" )) ? true : false,
					});
					setTimeout(function(){
						$single_product_thumbnails__default.slick({
							arrows: true,
							asNavFor: '.ciyashop-product-gallery__wrapper',
							centerMode: false,
							dots: false,
							draggable: true,
							focusOnSelect: true,
							infinite: false,
							respondTo: 'slider',
							slidesToShow: 4,
							slidesToScroll: 1,
							swipeToSlide: true,
							touchMove: true,
							vertical: vertical,
							verticalSwiping: vertical,
							rtl: (jQuery( "body" ).hasClass( "rtl" )) ? true : false,
						});
					}, 200 );
				}
			}
		}

		/**********************************
		:: Product Type - Sticky Gallery
		***********************************/

		if ( $("body.single-product .product.type-product").length > 0 && $("body.single-product .product.type-product").hasClass('product_page_style-sticky_gallery') ) {

			var page_style_sticky_header_sticky_visible = false;

			jQuery(window).on( 'scroll', function(){
				var page_style_sticky_header_sticky_visible_new = jQuery('#header-sticky-sticky-wrapper.is-sticky').is(":visible");
				if( page_style_sticky_header_sticky_visible != page_style_sticky_header_sticky_visible_new ){
					page_style_sticky_header_sticky_visible = page_style_sticky_header_sticky_visible_new;

					if( jQuery('#header-sticky-sticky-wrapper.is-sticky').is(":visible") ){
						var product_style_sticky__sticky_header_height = jQuery('#header-sticky-sticky-wrapper.is-sticky').outerHeight();
						var product_style_sticky__adminbar_height = 0;
						if( $('body').hasClass('admin-bar') && $('#wpadminbar').length > 0 ){
							product_style_sticky__adminbar_height = $('#wpadminbar').outerHeight();
						}

						var product_sticky_top = product_style_sticky__sticky_header_height + product_style_sticky__adminbar_height + 15;

						$('.product-top-left-inner.sticky-top').addClass('product-top-left-sticky').css('top', product_sticky_top);

					}else{
						$('.product-top-left-inner.sticky-top').removeClass('product-top-left-sticky').css('top', '');
					}
				}
			});
		}

		/**********************************
		:: WooCommerce - Quantity Input
		***********************************/

		// On update the cart
		$( document.body ).on( 'updated_cart_totals', function(){
			ciyashop_WooCommerce_Quantity_Input();
		});

		/****************************************************
		:: WooCommerce Product Details - Related Products
		*****************************************************/

		if( $('.related.products').length != 0 ) {
			$('.related.products > .products-loop').owlCarousel({
				items:4,
				loop:false,
				margin:15,
				autoplay:true,
				autoplayTimeout:3000,
				autoplayHoverPause:true,
				dots:false,
				nav:true,
				smartSpeed:1000,
				navText:[
					"<i class='fas fa-angle-left fa-2x'></i>",
					"<i class='fas fa-angle-right fa-2x'></i>"
				],
				responsive:{
					0:{
						items:1
					},
					767:{
						items:2
					},
					992:{
						items:4
					}
				},
				rtl: (jQuery( "body" ).hasClass( "rtl" )) ? true : false
			});
		}

		/****************************************************
		:: WooCommerce Product Details - Up Sell Products
		*****************************************************/

		if( $('.up-sells.upsells.products').length != 0 ) {
			$('.up-sells.upsells.products > .products-loop').owlCarousel({
				items:4,
				loop:false,
				margin:15,
				autoplay:true,
				autoplayTimeout:3000,
				autoplayHoverPause:true,
				dots:false,
				nav:true,
				smartSpeed:1000,
				navText:[
					"<i class='fas fa-angle-left fa-2x'></i>",
					"<i class='fas fa-angle-right fa-2x'></i>"
				],
				responsive:{
					0:{
						items:1
					},
					767:{
						items:2
					},
					992:{
						items:4
					}
				},
				rtl: (jQuery( "body" ).hasClass( "rtl" )) ? true : false
			});
		}

		/****************************************************
		:: WooCommerce Product Details - Cross Sell Products
		*****************************************************/

		if( $('.cross-sells .products.products-loop').length != 0 ) {
			$('.cross-sells .products.products-loop').owlCarousel({
				items:3,
				loop:false,
				margin:15,
				autoplay:true,
				autoplayTimeout:3000,
				autoplayHoverPause:true,
				dots:false,
				nav:true,
				smartSpeed:1000,
				navText:[
					"<i class='fas fa-angle-left fa-2x'></i>",
					"<i class='fas fa-angle-right fa-2x'></i>"
				],
				responsive:{
					0:{
						items:1
					},
					767:{
						items:2
					},
					992:{
						items:4
					}
				},
				rtl: (jQuery( "body" ).hasClass( "rtl" )) ? true : false
			});
		}

		/****************************************
		:: WooCommerce Product Grid/List Switch
		*****************************************/

		$(document).on('click', '.gridlist-button-wrap .gridlist-toggle > a', function(event) {
			event.preventDefault();
			var column = '';

			if ($(this).is('[data-grid]')) {
				column = $(this).data('grid').column;
			}

			var classNames = [];
			var product_columns = [];

			$('.gridlist-button-wrap .gridlist-toggle > a').each( function( i, val ) {
				if ($(this).is('[data-grid]')) {
					product_columns.push($(this).data('grid').column);
				}

				if($(this).hasClass('active')){
					$(this).removeClass('active');
				}
			});

			classNames = $('ul.products').attr('class').toString().split(' ');
			$.each( product_columns, function (i, product_columns) {
				if( classNames.indexOf('products-loop-column-'+product_columns) > -1 ){
					$('ul.products').removeClass('products-loop-column-'+product_columns);
				}
			});

			if ( column ) {
				column = $(this).data('grid').column;

				if($('ul.products').hasClass('list')){
					$('ul.products').removeClass('list');
				}
				$('ul.products').addClass('grid');

				$('ul.products').fadeOut( 300, function() {
					cs_product_grid_set();
					$(this).addClass('products-loop-column-'+column).removeClass('list').fadeIn(300);
					Cookies.set('gridlist_view','products-loop-column-'+column, { path: '/' });
				});

				$(this).addClass('active');

			}else if($(this).hasClass('gridlist-toggle-list')){
				if($('ul.products').hasClass('grid')){
					$('ul.products').removeClass('grid');
				}

				$(this).addClass('active');

				$('ul.products').fadeOut( 300, function() {
					cs_product_grid_set();
					$('ul.products').addClass('list').fadeIn(300);
					Cookies.set('gridlist_view','list', { path: '/' });
				});
			}
		});


		/****************************************
		:: WooCommerce Off Canvas Shop Sidebar
		*****************************************/

		 $(document).on('click', '.ciyashop-show-shop-btn', function (event) {

			 event.preventDefault();
			 var $shop_sidebar = $('.sidebar-widget-heading').parents('.sidebar');
			 if($shop_sidebar.hasClass('ciyashop_shop_sidebar-open')){
				 $shop_sidebar.removeClass('ciyashop_shop_sidebar-open');
				 if($( '.shop-sidebar-widgets-overlay' ).hasClass('shop_sidebar_widgets-show')){
					$( '.shop-sidebar-widgets-overlay' ).removeClass( 'shop_sidebar_widgets-show' );
				 }
			 }else{
				$shop_sidebar.addClass('ciyashop_shop_sidebar-open');
				if(!$( '.shop-sidebar-widgets-overlay' ).hasClass('shop_sidebar_widgets-show')){
					$( '.shop-sidebar-widgets-overlay' ).addClass( 'shop_sidebar_widgets-show' );
				}

				$(".ciyashop_shop_sidebar-open").nanoScroller({
					paneClass: 'ciyashop-scroll-pane',
					sliderClass: 'ciyashop-scroll-slider',
					contentClass: 'shop-sidebar-widgets',
				});
			 }
		 });

		$(document).on('click', '.sidebar-widget-heading', function (event) {
			event.preventDefault();
			 var $shop_sidebar = $(this).parents('.sidebar');
			 if($shop_sidebar.hasClass('ciyashop_shop_sidebar-open')){
				 $shop_sidebar.removeClass('ciyashop_shop_sidebar-open');
				 $( '.shop-sidebar-widgets-overlay' ).removeClass( 'shop_sidebar_widgets-show' );
				 if($( '.shop-sidebar-widgets-overlay' ).hasClass('shop_sidebar_widgets-show')){
					$( '.shop-sidebar-widgets-overlay' ).removeClass( 'shop_sidebar_widgets-show' );
				 }
			 }
		});

		$(document).on('click', '.shop-sidebar-widgets-overlay', function (event) {
			event.preventDefault();
			 if($(this).hasClass('shop_sidebar_widgets-show')){
				 $(this).next('aside').removeClass('ciyashop_shop_sidebar-open');
				 $( '.shop-sidebar-widgets-overlay' ).removeClass( 'shop_sidebar_widgets-show' );
				 if($( '.shop-sidebar-widgets-overlay' ).hasClass('shop_sidebar_widgets-show')){
					$( '.shop-sidebar-widgets-overlay' ).removeClass( 'shop_sidebar_widgets-show' );
				 }
			 }
		});

		/*****************************
		:: WooCommerce - Filters-open
		******************************/

		$(".shop_filter_hide_show-btn").change(function() {

			if(this.checked) {
				Cookies.set('shop_filter_hide_show', 'shown', { expires: 7, path: '/' } );
				$( 'div.loop-header-filters' ).slideDown();
				$(this).addClass('pgs-shop-filter-visible');
			}else{
				Cookies.remove('shop_filter_hide_show');
				$( 'div.loop-header-filters' ).slideUp();
				$(this).addClass('pgs-shop-filter-hide');
			}

			if( $('.pgs-woocommerce-widget-layered-nav-list-container').length > 0 && $('.pgs-woocommerce-widget-layered-nav-list-container').length > 0 ){
				$(".pgs-woocommerce-widget-layered-nav-list-container").nanoScroller({
					paneClass: 'ciyashop-scroll-pane',
					sliderClass: 'ciyashop-scroll-slider',
					contentClass: 'pgs-woocommerce-widget-layered-nav-list',
				});
			}
		});


		/*****************************
		:: WooCommerce Video Popup
		******************************/

		if( $(".product-video-popup-link-html5_old").length > 0 ) {
			$(".product-video-popup-link-html5_old").each(function () {
				var $mfp_popup_link_html5_old = $(this);

				$($mfp_popup_link_html5_old).magnificPopup({
					type:'inline',
					midClick:true,
				});
			});
		}

		var $html5_vids = $('.product-video-popup-link-html5');
		if( $html5_vids.length > 0 ) {
			$html5_vids.each(function () {
				var $mfp_popup_link_html5 = $(this);

				$($mfp_popup_link_html5).magnificPopup({
					type: 'iframe',
					mainClass: 'mfp-fade product-video-popup',
					removalDelay: 160,
					preloader: false,
					fixedContentPos: false,
					iframe: {
						markup: '<div class="mfp-iframe-scaler">'+
								'<div class="mfp-close"></div>'+
								'<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
								'</div>',

						srcAction: 'iframe_src',
					}
				});
			});
		}

		var $ombed_vids = $(".product-video-popup-link-oembed");
		if( $ombed_vids.length > 0 ) {
			$ombed_vids.each(function () {
				var $mfp_popup_link_non_html5 = $(this);

				$($mfp_popup_link_non_html5).magnificPopup({
					disableOn: 700,
					type: 'iframe',
					mainClass: 'mfp-fade product-video-popup',
					removalDelay: 160,
					preloader: false,
					fixedContentPos: false,
					iframe: {
						patterns: {
							youtube: {
								index: 'youtube.com/',
								id: function(url) {
									var m = url.match(/[\\?\\&]v=([^\\?\\&]+)/);
									if ( !m || !m[1] ) return null;
									return m[1];
								},
								src: '//www.youtube.com/embed/%id%?autoplay=1&rel=0'
							},
							youtu: {
								index: 'youtu.be',
								id: '/',
								src: '//www.youtube.com/embed/%id%?autoplay=1&rel=0'
							},
							vimeo: {
								index: 'vimeo.com/',
								id: function(url) {
									var m = url.match(/(https?:\/\/)?(www.)?(player.)?vimeo.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/);
									if ( !m || !m[5] ) return null;
									return m[5];
								},
								src: '//player.vimeo.com/video/%id%?autoplay=1'
							},
						}
					}
				});
			});
		}

		/***********************************************************************************
		::			WooCommerce Product Variations - Variations Code Started		      ::
		************************************************************************************/

		// ToolTip
		jQuery('[data-toggle="tooltip"]').tooltip();

		$(document).on("click", ".swatches-select a.ciyashop-swatch", function(){

			var custom_fields = $(this).parents('.ciyashop-swatches').find('select#' + $(this).attr('data-cs_parent').replace(/[!"#$%&'()*+,.\/:;<=>?@[\\\]^`{|}~]/g, "\\\$&") +' option[value="'+$(this).attr('data-value')+'"]');
			var val_id = $(this).attr('data-cs_parent');
			$(this).parents('.ciyashop-swatches').find( 'select#' + val_id ).val( $(custom_fields).val() ).change();
			$(this).parent(".swatches-select").find(".cs-attr-selected").removeClass("cs-attr-selected");
			$(this).addClass('cs-attr-selected');
			$(this).parent().addClass('cs-selected');
			$('.cs-disabled').removeClass('cs-disabled');

			$('.swatches-select').each(function(){
				if( ! $(this).hasClass('cs-selected') ){
					var availableVariations = new Array();
					var select_main = $(this).next('select');
					$(select_main).find('option').each(function(){
						if($(this).val().length > 0){
							availableVariations.push($(this).val());
						}
					});

					$(this).find('a.ciyashop-swatch').each(function(){
						if( jQuery.inArray( $(this).data('value'), availableVariations ) == -1 ){
							$(this).addClass('cs-disabled');
						}
					});

				}
			});
			$(this).parent().removeClass('cs-selected');
			$(this).parents('form.variations_form').addClass('ciyashop-variation-selected');
		});

		$(document).on("change", "table.variations td.value select", function(){
			if( $(this).val().length ){
				$(this).parents('form.variations_form').addClass('ciyashop-variation-selected');
			}
		});

		/*********************************************************************
		:: 				Load Variations On Product Listing Page 	        ::
		**********************************************************************/

		$( document ).on( 'click', 'a.product_type_variable.add_to_cart_button', function( e ) {

			if ( ! $( this ).parents( '.product-inner' ).find( '.ciyashop-product-variations-wrapper' ).length ) { // return if variation option is disabled for listing page
				return;
			}

            e.preventDefault();

            var $this = $(this),
                $product = $this.parents('.product').first(),
                $variation_box = $product.find('.ciyashop-product-variations-wrapper'),
                id = $variation_box.data('id'),
                loadingClass = 'cs-loading';

            if( $this.hasClass(loadingClass) ) return;

            // Simply show variation form if it is already loaded with AJAX previously
            if( $product.hasClass('variations-loaded') ) {
                $product.addClass('variations-shown');
				$variation_box.fadeIn(500);
                return;
            }

            $.ajax({
                url: ciyashop_l10n.ajax_url,
                data: {
                    action: 'ciyashop_load_variations',
                    id: id,
                    lang: ciyashop_l10n.lang,
					ajax_nonce : ciyashop_l10n.ciyashop_nonce
                },
				beforeSend: function(){
					$this.addClass(loadingClass);
					$product.addClass('loading-variations');
				},
				method: 'post',
                success: function(data) {
					// insert variations form
                    $variation_box.find('.ciyashop-variations-form').append(data);
					$product.find( '.variations_form' ).wc_variation_form().find('.variations select:eq(0)').change();
					$product.find('.variations_form').trigger('wc_variation_form');
                },
                complete: function() {
					ciyashop_WooCommerce_Quantity_Input();
					$('[data-toggle="tooltip"]').tooltip();
                    $this.removeClass(loadingClass);
                    $product.removeClass('loading-variations');
                    $product.addClass('variations-shown variations-loaded');
                },
                error: function() {
                },
            });
        });

		/************************************************************************
		::			 Handle Add To Cart On Product Listing Page 			   ::
		*************************************************************************/

		$( document.body ).on( 'submit', 'form.cart', function( e ) {
			if ( ! $( document.body ).hasClass( 'cs-ajax-add-to-cart' ) ) {
				return;
			}

			if ( $( this ).closest( '.product' ).hasClass( 'product-type-external' ) ) {
				return;
			}

            e.preventDefault();
            var $form = $(this),
                $cs_add_cart = $form.find('.single_add_to_cart_button'),
                data = $form.serialize();

            data += '&action=ciyashop_ajax_add_to_cart_action';
            data += '&ajax_nonce='+ciyashop_l10n.ciyashop_nonce;

            if( $cs_add_cart.val() ) {
                data += '&add-to-cart=' + $cs_add_cart.val();
            }

			$cs_add_cart.addClass( 'loading' );

            // Trigger event
            $( document.body ).trigger( 'adding_to_cart', [ $cs_add_cart, data ] );

            $.ajax({
                url: ciyashop_l10n.ajax_url,
				type: "json",
                data: data,
                method: 'POST',
				beforeSend: function(){
					$('.widget_shopping_cart').addClass('cs-loading-cart');
				},
                success: function(response) {
                    if ( ! response ) {
                        return;
                    }

                    if ( response.error && response.product_url ) {
                        window.location = response.product_url;
                        return;
                    }

					var fragments = response.fragments;
					var cart_hash = response.cart_hash;
					// Block fragments class
					if ( fragments ) {
						$.each( fragments, function( key ) {
							$( key ).addClass( 'updating' );
						});
					}

					// Replace fragments
					if ( fragments ) {
						$.each( fragments, function( key, value ) {
							$( key ).replaceWith( value );
						});
					}

					// Show notices
					if( typeof response.notices != 'undefined' && response.notices.indexOf( 'error' ) > 0 ) {
						$('body').append(response.notices);
						$cs_add_cart.addClass( 'not-added' );
					} else {
						// Changes button classes
						$cs_add_cart.addClass( 'added' );

						// Redirect to cart option
						if ( wc_add_to_cart_params.cart_redirect_after_add === 'yes' ) {
							window.location = wc_add_to_cart_params.cart_url;
							return;
						}

						// Trigger event so themes can refresh other areas
						$( document.body ).trigger( 'added_to_cart', [ fragments, cart_hash, $cs_add_cart ] );
					}
                },
                error: function() {
                    console.log('Error while performing ajax.');
                },
                complete: function() {
					$cs_add_cart.removeClass( 'loading' );
					$( '.reset_variations' ).trigger( 'click' );
					$( '.ciyashop-variations-close' ).trigger( 'click' );
					$('.widget_shopping_cart').removeClass('cs-loading-cart');
				},
            });
        });

		$( document ).on( 'click', '.reset_variations', function() {
			$(this).parents('table.variations').find('.cs-attr-selected').removeClass('cs-attr-selected');
			$(this).parents('table.variations').find('.cs-disabled').removeClass('cs-disabled');
			$(this).parents('form.variations_form').removeClass('ciyashop-variation-selected');
		});

		$( document ).on( 'click', '.ciyashop-variations-close', function() {
			$(this).parent(".ciyashop-product-variations-wrapper").fadeOut(500);
			var $product = $(this).parents('.product').first();
			$product.removeClass('variations-shown');
		});

		// For product listing grid display variations
		ciyashop_grid_swatches();

		/***********************************************************************************
		::			WooCommerce Product Variations - Variations Code Ends		      	  ::
		************************************************************************************/

		/***********************************************************************************
		::	     WooCommerce Product List Description - List "Hover Summary" style	      ::
		************************************************************************************/

		ciyashop_set_description_height(); // added class to set height if load more button added

		// Set default margin on loading - product listing grid
		if( $('ul.products.products-loop li').length ){
			$('ul.products.products-loop li').each(function(){
				calculate_margin_list($(this).find('.product-inner')); // set div margin
			});
		}

		$(document).on("click", ".cs-more-btn", function(){
			$(this).parents('.ciyashop-product-description').removeClass('ciyashop-short-description');
			calculate_margin_list($(this).parents('.product-inner')); // set div margin
		});

		/*****************************************
		:: WooCommerce - Compare and WishList
		******************************************/

		$('body').on("click", ".product-summary-actions a.compare, .yith-wcwl-add-button a.add_to_wishlist", function() {
			$(this).addClass("cs-loading");
        });

		$('body').bind('added_to_wishlist removed_from_wishlist', function() {
			$.ajax({
				url: ciyashop_l10n.ajax_url,
				type: 'post',
				data: {
					action: 'ciyashop_get_wishlist_number',
					ajax_nonce: ciyashop_l10n.ciyashop_nonce
				},
				dataType: 'json',
				success: function(data) {
					$('.ciyashop-wishlist-count').html(data);
				}
			});
		});

		$(document.body).on('added_to_cart', function() {
			$.ajax({
				url: ciyashop_l10n.ajax_url,
				type: 'post',
				data: {
					action: 'ciyashop_get_wishlist_number',
					ajax_nonce: ciyashop_l10n.ciyashop_nonce
				},
				dataType: 'json',
				success: function(data) {
					$('.ciyashop-wishlist-count').html(data);
				}
			});
		});

		/********************************************************************************
		 *
		 :: Blog
		 *
		 ********************************************************************************/

		/*************************
		:: Blog Load More
		*************************/
		jQuery(".entry-date-bottom a").on('click', function(e) {
			e.preventDefault();

			var more_btn    = this;
			var next_link   = $(this).data('next_link');
			var max_pages   = $(this).data('max_pages');
			var current_page= $(this).data('current_page');
			var next_page   = $(this).data('next_page');

			// Disable button click if loaded all pages and added disabled class
			if( $(more_btn).hasClass('disabled' ) ){
				return;
			}

			// Call ajax to fetch data
			$.ajax({
				url: next_link,
				beforeSend: function( xhr ) {

					// Set "Loading..." while ajax in process
					$(more_btn).html( ciyashop_l10n.loading );
				}
			})
			.done(function( data ) {

				// Load data in temp
				var temp = $(data);

				// Get button from ajax data
				var next_btn = temp.find('.entry-date-bottom').html();

				// Remove unwanted content from data before appending
				temp.find('.entry-date').remove();
				temp.find('.entry-date-bottom').remove();
				temp.find('.clearfix.timeline-inverted').remove();

				// Extract timeline content for appening
				var new_timeline_items = temp.find('ul.timeline').html();

				// Get button data from button in returned data
				var next_btn_link        = $(next_btn).data('next_link');
				var next_btn_max_pages   = $(next_btn).data('max_pages');
				var next_btn_next_page   = $(next_btn).data('next_page');
				var next_btn_current_page= $(next_btn).data('current_page');

				// Check if current page count is less than max page count
				if( next_btn_current_page < next_btn_max_pages ){

					// Set "Load more..." back after ajax completed
					$(more_btn).html( ciyashop_l10n.load_more );

					// Set returned button data to button
					$(more_btn).data('next_link', next_btn_link);
					$(more_btn).data('max_pages', next_btn_max_pages);
					$(more_btn).data('next_page', next_btn_next_page);
					$(more_btn).data('current_page', next_btn_current_page);
				}else{
					$(more_btn).html( ciyashop_l10n.no_more_product_to_load ).addClass("disabled");
				}

				// appent extracted timeline data
				$( 'ul.timeline .timeline-item' ).last().after(new_timeline_items);

				// reinitialize the slider after ajax call
				jQuery( '.owl-carousel.owl-carousel-options' ).each( function () {
					var $carousel = jQuery(this),
						$carousel_option = ( $carousel.attr('data-owl_options')) ? $carousel.data('owl_options') : {};
						$carousel_option.navElement = 'div';
						$carousel_option.rtl = (jQuery( "body" ).hasClass( "rtl" )) ? true : false;

					jQuery(this).owlCarousel($carousel_option);
				});
				ciyashop_lazyload();

			})
			.fail(function() {
			})
			.always(function() {
			});
		});


		/*************************
		:: Portfolio Load More
		*************************/

		jQuery(".portfolio-more-button a").on('click', function(e) {
			e.preventDefault();

			var more_btn    = this;
			var next_link   = $(this).data('next_link');
			var max_pages   = $(this).data('max_pages');
			var current_page= $(this).data('current_page');
			var next_page   = $(this).data('next_page');

			// Disable button click if loaded all pages and added disabled class
			if( $(more_btn).hasClass('disabled' ) ){
				return;
			}

			// Call ajax to fetch data
			$.ajax({
				url: next_link,
				beforeSend: function( xhr ) {
					$(more_btn).html( ciyashop_l10n.loading );
					$(more_btn).addClass('content-loading');
				}
			})
			.done(function( data ) {
				$(more_btn).removeClass('content-loading');
				// Load data in temp
				var temp = $(data);

				// Get button from ajax data
				var next_btn = temp.find('.portfolio-more-button').html();
				var new_timeline_items = temp.find('div.portfolio-content-area').html();

				// Get button data from button in returned data
				var next_btn_link        = $(next_btn).data('next_link');
				var next_btn_max_pages   = $(next_btn).data('max_pages');
				var next_btn_next_page   = $(next_btn).data('next_page');
				var next_btn_current_page= $(next_btn).data('current_page');

				// Check if current page count is less than max page count
				if( next_btn_current_page < next_btn_max_pages ){

					// Set "Load more..." back after ajax completed
					if(!$('.div.portfolio-content-area').hasClass('portfolio-infinite_scroll')){
						$(more_btn).html( ciyashop_l10n.load_more );
					}
					// Set returned button data to button
					$(more_btn).data('next_link', next_btn_link);
					$(more_btn).data('max_pages', next_btn_max_pages);
					$(more_btn).data('next_page', next_btn_next_page);
					$(more_btn).data('current_page', next_btn_current_page);
				}else{
					 $(more_btn).hide();
				}

				$( "div.portfolio-grid-item" ).last().after( new_timeline_items );

				// Append the new data to the team
				if ( $( '.isotope-wrapper' ).length > 0 ) {
					setTimeout(function(){
						cs_isotope();
					}, 200 );
				}

				ciyashop_lazyload();
			})
			.fail(function() {
			})
			.always(function() {
			});
		});

		/****************************
		:: Portfolio Infinite Scroll
		****************************/

		var counter = 1;
		$( window ).scroll(function() {

			if( $('.portfolio-infinite_scroll').length > 0 ){
				var load_more_button = $('.portfolio-more-button a');

				var scrollHeight = Math.round( load_more_button.offset().top );
				var scrollOuterHeight = load_more_button.height();
				var scrollPosition = $(window).height() + $(window).scrollTop();
				var total = load_more_button.data('current_page');

				if ( scrollPosition > scrollHeight + scrollOuterHeight ) {
					setTimeout(function () {
						if( total == counter ){
							if(!load_more_button.hasClass('content-loading')){
								counter++;
								load_more_button.trigger( 'click' );
							}
						}
					}, 200);
				}
			}

			/*****************************************
			:: Portfolio Single Page sticky Column
			*****************************************/

			if( $('.wpb_column.sticky-top').length > 0 ){

				var page_header_sticky_visible = false;
				var page_header_sticky_visible_new = jQuery('#header-sticky-sticky-wrapper.is-sticky').is(":visible");

				if( page_header_sticky_visible != page_header_sticky_visible_new ){
					page_header_sticky_visible = page_header_sticky_visible_new;

					if( jQuery('#header-sticky-sticky-wrapper.is-sticky').is(":visible") ){
						var sticky_header_height = jQuery('#header-sticky-sticky-wrapper.is-sticky').outerHeight();
						var sticky__adminbar_height = 0;
						if( $('body').hasClass('admin-bar') && $('#wpadminbar').length > 0 ){
							sticky__adminbar_height = $('#wpadminbar').outerHeight();
						}
						var product_sticky_top = sticky_header_height + sticky__adminbar_height + 10;
						$('.wpb_column.sticky-top').css('top', product_sticky_top);
					}else{
						$('.wpb_column.sticky-top').css('top', '');
					}
				}
			}
		});

		/*********
		:: Tabs
		**********/
		jQuery( ".tabs_wrapper" ).each(function( index ) {
			var tabs_wrapper = jQuery(this);
			tabs_wrapper.find('li[data-tabs]').on('click', function () {
				var tab = jQuery(this).data('tabs');
				tabs_wrapper.find('li[data-tabs]').removeClass('active');
				jQuery(this).addClass('active');

				tabs_wrapper.find('.tabcontent.active').fadeOut().hide().removeClass('active').removeClass("pulse");
				jQuery('#' + tab).addClass('active').show().fadeIn('slow').addClass("pulse");
			});
		});

		/*************************
		:: Accordion
		*************************/
		var allPanels = $(".accordion > .accordion-content").hide();

		allPanels.first().slideDown("easeOutExpo");
		$(".accordion > .accordion-title > a").first().addClass("active");
		$(".accordion > .accordion-title > a").on("click",function(){
			var current = $(this).parent().next(".accordion-content");

			$(".accordion > .accordion-title > a").removeClass("active");
			$(this).addClass("active");
			allPanels.not(current).slideUp("easeInExpo");
			$(this).parent().next().slideDown("easeOutExpo");
			return false;
		});

		/*************************
		:: Back to top
		*************************/

		$("#back-to-top").hide();
		$(window).scroll(function(){
			if ($(window).scrollTop()>100){
				$("#back-to-top").fadeIn(1500);
			}else{
				$("#back-to-top").fadeOut(1500);
			}
		});

		//back to top
		$("#back-to-top").on( "click", function(){
			$('body,html').animate({scrollTop:0},1000);
			return false;
		});

		/****************************
		:: Sticky Footer
		******************************/

		$(window).on("load resize", function(){
			if($('.footer-wrapper').hasClass('ciyashop-sticky-footer')){
				var footer_height = $(".site-footer .footer-wrapper").height();
				$("footer.site-footer").height(footer_height);
			}
		});

		/****************************
		:: Commingsoon countdown
		******************************/
		if( $(".commingsoon_countdown").length != 0 ) {
			var cs_countdown      = $('.commingsoon_countdown'),
				cs_countdown_date = $(cs_countdown).data('countdown_date'),
				cs_counter_data   = $(cs_countdown).data('counter_data');

			$(cs_countdown).countdown( cs_countdown_date )
				.on('update.countdown', function(event) {
					var format = '';

					var display_weeks = false;

					if( display_weeks ){
						if(event.offset.weeks > 0) {
							format = format + '<li><span class="days">%-w</span><p class="days_ref">'+cs_counter_data.weeks+'</p></li>';
						}
						if(event.offset.totalDays > 0) {
							format = format + '<li><span class="days">%-d</span><p class="days_ref">'+cs_counter_data.days+'</p></li>';
						}
					}else{
						if(event.offset.totalDays > 0) {
							format = format + '<li><span class="days">%-D</span><p class="days_ref">'+cs_counter_data.days+'</p></li>';
						}
					}

					format = format + '<li><span class="hours">%H</span><p class="hours_ref">'+cs_counter_data.hours+'</p></li>';
					format = format + '<li><span class="minutes">%M</span><p class="minutes_ref">'+cs_counter_data.minutes+'</p></li>';
					format = format + '<li><span class="seconds">%S</span><p class="seconds_ref">'+cs_counter_data.seconds+'</p></li>';

					cs_countdown.html(event.strftime(format));
				});
		}

		/*******************************************
		:: Call youtube and vimeo video function
		********************************************/

		ciyashop_initVimeoVideoBackgrounds();

		/********************************************************************************
		 *
		 :: Shortcodes
		 *
		 ********************************************************************************/

		/*******************************************
		:: Slick-slider for testimonials shortcode
		********************************************/
		if(jQuery( '.testimonials.slick-carousel' ).length){
			jQuery('.testimonials.slick-carousel').each(function(idx, item) {
				var carouselId = "carousel" + idx,
					carousel_main = $(this).find('.slick-carousel-main'),
					carousel_nav = $(this).find('.slick-carousel-nav');

				var $slidesToShow = $(carousel_main).data('show');
				$(carousel_main).slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					arrows: false,
					asNavFor: carousel_nav,
                                        rtl: (jQuery( "body" ).hasClass( "rtl" )) ? true : false,
				});
				$(carousel_nav).slick({
					slidesToShow: $slidesToShow,
					slidesToScroll: 1,
					asNavFor: carousel_main,
					arrows: true,
					dots: false,
					centerMode: true,
					focusOnSelect: true,
                                        rtl: (jQuery( "body" ).hasClass( "rtl" )) ? true : false,
				});
			});
		}
		/******************************
		:: Hot Deal / Banner (Deal)
		*******************************/
		ciyashop_deal_counter();

		/***************************
		:: Newsletter Mailchimp
		****************************/
		widget_pgscore_newsletter();

		/*************************
		:: PGSCore : Banner
		*************************/
		if( $(".pgscore_banner_wrapper").length != 0 ) {
			var banner_class;
			$(window).on("load resize", function(e){
				var viewportWidth = $(window).width();

				$('.pgscore_banner').each( function( i, ele ) {
					var banner        	= this,
						banner_options  = $(banner).data('banner_options'),
						banner_padding  = $(banner).children('.pgscore_banner-content'),
						banner_padding_options  = $(banner_padding).data('banner_padding_options'),
						font_size       = banner_options.font_size_xl;

					if( banner_options.font_size_responsive ){

						if ( viewportWidth >= 1200 ) {
							font_size = banner_options.font_size_xl;
						}else if ( viewportWidth >= 992 ) {
							font_size = banner_options.font_size_lg;
						}else if ( viewportWidth >= 768 ) {
							font_size = banner_options.font_size_md;
						}else if ( viewportWidth >= 576 ) {
							font_size = banner_options.font_size_sm;
						}else if ( viewportWidth < 576 ) {
							font_size = banner_options.font_size_xs;
						}
						$(banner).css("font-size", parseInt(font_size) );
					}

					if( banner_class != null){
						if($(banner_padding).hasClass(banner_class)){
							$(banner_padding).removeClass(banner_class);
						}
					}

					if( banner_padding_options.banner_padding_responsive ){

						if ( viewportWidth >= 1200 ) {
							banner_class = banner_padding_options.banner_padding_xl;
						}else if ( viewportWidth >= 992 ) {
							banner_class = banner_padding_options.banner_padding_lg;
						}else if ( viewportWidth >= 768 ) {
							banner_class = banner_padding_options.banner_padding_md;
						}else if ( viewportWidth >= 576 ) {
							banner_class = banner_padding_options.banner_padding_sm;
						}else if ( viewportWidth < 576 ) {
							banner_class = banner_padding_options.banner_padding_xs;
						}
						$(banner_padding).addClass(banner_class);
					}
				});
			});
		}

		/****************************************
		:: Multi Tab Product Listing - Carousel
		*****************************************/

		if( $(".mtpl-tab-link").length > 0 ) {
			$('.mtpl-tab-link').on('shown.bs.tab', function (e) {
				var hide_arrow = $(e.relatedTarget).data('arrow_target');
				var show_arrow = $(e.target).data('arrow_target');
				$('#'+hide_arrow).removeClass('active');
				$('#'+show_arrow).addClass('active');

				if( $(e.target).hasClass('mtpl-intro-tab-link') ){
					$(e.target).css( "color", $(e.target).data('active_link_color') );
					$(e.relatedTarget).css( "color", $(e.relatedTarget).data('link_color') );
				}
			});

			//Initialise carousel on showing the tab
			$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
				$($(e.target).attr('href'))
					.find('.owl-carousel')
					.owlCarousel('invalidate', 'width')
					.owlCarousel('update');
			});
		}

		// add loader while tab change
		if( $(".mtpl-tab-link").length > 0 ) {
			$('.mtpl-tab-link').on('click', function (e) {
				if(!$(this).hasClass('active')){
					$(this).parents('.pgs-mtpl-wrapper').addClass('multi-tab-product-loader');
				}
				$('.mtpl-tab-link').on('shown.bs.tab', function (e) {
					$(this).parents('.pgs-mtpl-wrapper').removeClass('multi-tab-product-loader');
				});
			});
		}

		/*************************
		:: Single Product threesixty : Popup
		*************************/

		if ( jQuery('.smart_product_open').length > 0 ) {
			jQuery( '.smart_product_open' ).magnificPopup({
				callbacks: {
					beforeOpen: function() {
						window.CI360.destroy();
					},
					open: function() {
						window.CI360.init();
					},
				}
			});
		}
		if(jQuery('img').hasClass('threesixty-preview-image')){
			var $height = jQuery('.threesixty-preview-image').outerHeight();
			jQuery('.woocommerce-product-gallery + .ciyashop-product-images .ciyashop-gallery-style-default .ciyashop-product-gallery').css("height", $height);
		}

		jQuery(document).on('click', '.mfp-close i', function (e) {
			e.preventDefault();
			jQuery.magnificPopup.close();
		});


		/*************************
		:: Image Slider : Popup
		*************************/

		cs_slider_popup();

		/**********************************
		:: Vertical Menu : SlickNav Menu
		***********************************/

		if( $('.pgscore_v_menu__menu_wrap').length > 0 ){
			$('.pgscore_v_menu__menu_wrap').each(function(idx, item) {
				var v_menu_wrap   = this,
					v_menu        = $(v_menu_wrap).find('.pgscore_v_menu__nav'),
					v_menu_parent = $(v_menu_wrap).parent(),
					v_menu_branding = '';
					if( $(v_menu_parent).data('menu_title') !== undefined && $(v_menu_parent).data('menu_title') !== '' ){
						v_menu_branding = $(v_menu_parent).data('menu_title');
					}

				v_menu.slicknav({
					label : '<i class="fa fa-bars"></i>',
					brand : '<span class="slicknav_brand_icon"><i class="fa fa-bars"></i></span>'+v_menu_branding,
					appendTo : v_menu_parent,
					allowParentLinks: true,
					'closedSymbol': '&rsaquo;', // Character after collapsed parents.
					'openedSymbol': '&rsaquo;', // Character after expanded parents.
					'init': function(){
						$(v_menu_parent).find('.slicknav_menu').addClass('slicknav_menu-wrap').removeClass('slicknav_menu');
						$(v_menu_parent).find('.slicknav_nav').addClass('menu');
					},
					afterOpen: function(trigger){
						$(trigger).find('.fa').removeClass('fa-bars').addClass('fa-times');

					},
					afterClose: function(trigger){
						$(trigger).find('.fa').removeClass('fa-times').addClass('fa-bars');
					}
				});
			});
		}

		if( $('.ciyashop-sticky-btn').length > 0 ){
			var cart_sticky = $('.ciyashop-sticky-btn');
			$( window ).scroll( function() {

				var windowpos = $(window).scrollTop();
				var bottom = $('#colophon').position();
				var cart_top = $('form.cart').position();
				var sizetop;

				if ( typeof cart_top == 'undefined' ) {
					sizetop = 0;
				} else{
					sizetop = cart_top.top + $('form.cart').outerHeight();
				}

				if ( windowpos > sizetop && windowpos < bottom.top ) {
					cart_sticky.addClass('sticky');
				} else if ( bottom.top > windowpos ) {
					cart_sticky.removeClass('sticky');
				} else{
					cart_sticky.addClass('sticky');
				}
			});

			// Scroll to variation form for variable product
			$(document).on('click', '.ciyashop-sticky-add-to-cart.vaiable_button', function (event) {
				event.preventDefault();
				var variations_form = $('.variations_form').offset().top - $('.variations_form').outerHeight();

				$('html, body').animate({
					scrollTop: ( variations_form )
				}, 1000);

			});

			// Scroll to variation form for variable product
			$(document).on('click', '.ciyashop-sticky-add-to-cart.grouped_product', function (event) {
				event.preventDefault();
				var grouped_product = $('.grouped_form').offset().top - $('.grouped_form').outerHeight();

				$('html, body').animate({
					scrollTop: ( grouped_product )
				}, 1000);

			});
		}

		/************
		:: Hotspot
		*************/
		widget_pgscore_hotspot();
	});

	var calculate_margin_list = function ($el) {
		var heightHideInfo = $el.find('.ciyashop-product-description').outerHeight();
		$el.find('.content-hover-block').css({
			marginBottom: -heightHideInfo
		});
		$el.addClass('element-hovered');
	};

	function ciyashop_initVimeoVideoBackgrounds(){
		jQuery(".intro_header_video-bg").each(function() {
			var $video_bg_wrap  = $(this),
				$current_iframe = $video_bg_wrap.find('iframe'),
				$element        = $video_bg_wrap.parent(),
				video_type      = $video_bg_wrap.data("video_type"),
				video_link      = $video_bg_wrap.data("video_link"),
				iframe_src      = $current_iframe.attr('src'),
				iframe_src_new  = iframe_src,
				video_params    = {},
				query_string    = '';

			if( video_type == 'vimeo' ){
				video_params = {
					background: 1,
					autoplay: 1,
					muted: 1,
					loop: 1,
					quality: '540p',
				};
			}else if( video_type == 'youtube' ){
				video_params = {
					playlist: csExtractYoutubeId(video_link),
					iv_load_policy: 3,
					enablejsapi: 1,
					disablekb: 1,
					autoplay: 1,
					controls: 0,
					showinfo: 0,
					rel: 0,
					loop: 1,
					wmode: 'transparent',
					mute: 1,
					modestbranding: 1,
				};
			}

			query_string = getQueryString(video_params);

			if( iframe_src.indexOf('?') !== -1 ){
				iframe_src_new  = iframe_src+"&"+query_string;
			}else{
				iframe_src_new  = iframe_src+"?"+query_string;
			}

			jQuery($current_iframe).attr('src', iframe_src_new );

			jQuery($video_bg_wrap).css('opacity','1');

			ResizeVideoBackground($element);
			jQuery(window).on("resize", function() {
				ResizeVideoBackground($element);
			});
		});
	}

	function getQueryString(obj) {
		var url = '';
		Object.keys(obj).forEach(function (key) {
			url += key + '=' + obj[key] + '&';
		});
		return url.substr(0, url.length - 1);
    }

	function ResizeVideoBackground($element) {
		var iframeW, iframeH, marginLeft, marginTop, containerW = $element.innerWidth(),
			containerH = $element.innerHeight(),
			ratio1 = 16,
			ratio2 = 9;
		containerW / containerH < ratio1 / ratio2 ? (iframeW = containerH * (ratio1 / ratio2), iframeH = containerH, marginLeft = -Math.round((iframeW - containerW) / 2) + "px", marginTop = -Math.round((iframeH - containerH) / 2) + "px", iframeW += "px", iframeH += "px") : (iframeW = containerW, iframeH = containerW * (ratio2 / ratio1), marginTop = -Math.round((iframeH - containerH) / 2) + "px", marginLeft = -Math.round((iframeW - containerW) / 2) + "px", iframeW += "px", iframeH += "px"), $element.find(".intro_header_video-bg iframe").css({
			maxWidth: "1000%",
			marginLeft: marginLeft,
			marginTop: marginTop,
			width: iframeW,
			height: iframeH
		});
	}

	function csExtractYoutubeId(url) {
		if ("undefined" == typeof url) return !1;
		var id = url.match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/);
		return null !== id && id[1];
	}

	function csExtractVimeoId(url) {
		if ("undefined" == typeof url) return !1;
		var id = url.match(/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)\/videos\/|album\/(?:\d+)\/video\/|video\/|)(\d+)(?:[a-zA-Z0-9_\-]+)?/i);
		return null !== id && id[1];
	}

	function ciyashop_parse_url_parameters( url ){
		var result = {};

		if( url != '' ){
			var queryString = url.substring( url.indexOf('?') + 1 );

			if( queryString != url ){

				queryString.split("&").forEach(function(part) {
					var item = part.split("=");
					if( item[0] != '' ){
						if( typeof item[1] == 'undefined' ){
							result[item[0]] = '';
						}else{
							result[item[0]] = decodeURIComponent(item[1]);
						}
					}
				});
			}
		}
		return result;
	}

	/**************************************************
		Fix for Visual Composer RTL Resize Issue
		TODO: Attach this function to jQuery/Window	to make it available globally
		Check this : http://stackoverflow.com/questions/2223305/how-can-i-make-a-function-defined-in-jquery-ready-available-globally
	**************************************************/

	if( jQuery('html').attr('dir') == 'rtl' ){

		jQuery(window).load(function() {
			ciyashop_vc_rtl_fullwidthrow();
		});

		$( window ).resize(function() {
			ciyashop_vc_rtl_fullwidthrow();
		});

	}

	/* Hide Default menu toggle when mega menu activate */
	if(document.getElementById('mega-menu-wrap-primary')){
		jQuery('.site-header .main-navigation button.menu-toggle').hide();
	}

	// Make sure you run this code under Elementor.
	$( window ).on( 'elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_countdown.default', ciyashop_deal_counter );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_banner.default', ciyashop_deal_counter );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_product-deal.default', ciyashop_deal_counter );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_product-deals.default', widget_pgscore_product_deals );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_counter.default', cs_counter_run );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_smart-image-view.default', cs_smart_image_view );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_hotspot.default', widget_pgscore_hotspot );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_progress-bar.default', cs_progress_bar_run );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_portfolio.default', widget_pgscore_portfolio );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_search.default', widget_pgscore_search );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_single-product-slider.default', widget_pgscore_single_product_slider );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_multi-tab-products-listing.default', cs_owl_carousel );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_image-slider.default', widget_pgscore_image_slider );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_newsletter.default', widget_pgscore_newsletter );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_clients.default', cs_owl_carousel );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_image-gallery.default', widget_pgscore_image_gallery );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_recent-posts.default', cs_owl_carousel );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_team-members.default', cs_owl_carousel );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_testimonials.default', widget_pgscore_testimonials );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_product-showcase.default', cs_owl_carousel );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_product-category-items.default', cs_owl_carousel );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_instagram.default', cs_owl_carousel );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pgscore_product-listing.default', widget_pgscore_product_listing );
	} );

	// WooCommerce Product List Description
	function ciyashop_set_description_height(){
		jQuery('.ciyashop-product-description').each( function(){
			var $description_height = $(this).outerHeight();
			if($description_height > 90){
				var btnHTML = '<a href="javascript:void(0)" class="cs-more-btn"><span>load more</span></a>';
				jQuery(this).addClass('ciyashop-short-description');
				jQuery(this).append(btnHTML);
			}
		});
	}

	function widget_pgscore_search() {
		cs_search_autocomplete();
		ciyashop_search_form_category();
	}

	function ciyashop_search_form_category(){
		jQuery('.search_form-category').select2({
			containerCssClass: 'ciyashop-search_form_cat-container',
			dropdownCssClass: 'ciyashop-search_form_cat-dropdown',
		});
	}
	
	function cs_select2_init() {
		if ( $( '.shop-filter' ).length > 0 ) {
			$( '.shop-filter' ).each(function() {
				var $filter      = $(this),
					$placeholder = $(this).data('placeholder'),
					$select      = $filter.find('select');

				$select.select2({
					placeholder: $placeholder,
					allowClear: true,
				});
			});
		}

		if ( $( '.woocommerce-ordering select.orderby' ).length > 0 ) {
			$( '.woocommerce-ordering select.orderby' ).select2();
		}

		if ( $( '.variations select' ).length > 0 ) {
			$( '.variations select' ).select2();
		}
		
		if ( $( 'select.ciyashop-select2' ).length > 0 ) {
			$( 'select.ciyashop-select2' ).select2({
				containerCssClass: 'ciyashop-select2-container',
				dropdownCssClass: 'ciyashop-select2-dropdown',
			});
		}
	}

	function widget_pgscore_product_listing() {
		cs_owl_carousel();
		ciyashop_set_description_height();
	}

	function cs_testimonials_slick(){
		if(jQuery( '.testimonials.slick-carousel' ).length){
			jQuery('.testimonials.slick-carousel').each(function(idx, item) {
				var carouselId    = "carousel" + idx,
					carousel_main = jQuery(this).find('.slick-carousel-main'),
					carousel_nav  = jQuery(this).find('.slick-carousel-nav');

				var $slidesToShow = jQuery(carousel_main).data('show');
				jQuery(carousel_main).not('.slick-initialized').slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					arrows: false,
					asNavFor: carousel_nav,
					rtl: (jQuery( "body" ).hasClass( "rtl" )) ? true : false,
				});

				jQuery(carousel_nav).not('.slick-initialized').slick({
					slidesToShow: $slidesToShow,
					slidesToScroll: 1,
					asNavFor: carousel_main,
					arrows: true,
					dots: false,
					centerMode: true,
					focusOnSelect: true,
					rtl: (jQuery( "body" ).hasClass( "rtl" )) ? true : false,
				});
			});
		}
	}

	function widget_pgscore_testimonials() {
		cs_owl_carousel();
		cs_testimonials_slick();	
	}

	function widget_pgscore_image_gallery(){
		cs_owl_carousel();
		setTimeout(function(){
			cs_isotope();
		}, 200 );
		cs_popup_gallery();
	}

	function cs_isotope(){

		var $isotope_wrapper = jQuery('.isotope-wrapper');
		if( $isotope_wrapper.length != 0 ){
			$isotope_wrapper.each(function() {

				var $isotope_container = jQuery(this).find('.isotope');
				var $filters_container = jQuery(this).find('.isotope-filters');
				var $active_filter     = $filters_container.find('button.active');

				var isotope_container = new Shuffle( $isotope_container, {
					itemSelector: '.grid-item',
					easing: 'ease-out',
				});

				var data_filter = parseInt( $active_filter.attr('data-filter') );
				isotope_container.filter( data_filter );

				// bind filter button click
				$filters_container.on( 'click', 'button', function() {
					var filterValue = parseInt( jQuery( this ).attr('data-filter') );
					isotope_container.filter( filterValue );
				});

				// change active class on buttons
				$filters_container.each( function( i, buttonGroup ) {
					var $filters_buttongroup = jQuery( buttonGroup );
					$filters_buttongroup.on( 'click', 'button', function() {
						$filters_buttongroup.find('.active').removeClass('active');
						jQuery(this).addClass('active');
					});
				});
			});
		}
	}

	function widget_pgscore_newsletter() {
		if ( jQuery('.widget_pgs_newsletter_widget, .pgscore_newsletter_wrapper').length > 0 ) {
			jQuery('.widget_pgs_newsletter_widget, .pgscore_newsletter_wrapper').each(function(index, item) {
				var form = jQuery(this).find('form'),
					newsletter_msg      = jQuery(this).find('.newsletter-msg'),
					newsletter_btn      = jQuery(this).find('.newsletter-mailchimp'),
					newsletter_spinner  = jQuery(this).find('.newsletter-spinner'),
					newsletter_email    = jQuery(this).find('.newsletter-email'),
					form_id             = jQuery(this).attr('data-form-id'),
					newsletter_email_val= '';

				jQuery(newsletter_btn).on( "click", function(){

					newsletter_email_val = jQuery(newsletter_email).val();

					jQuery.ajax({
						url: ciyashop_l10n.ajax_url,
						type:'post',
						data:'action=mailchimp_singup&newsletter_email='+newsletter_email_val,
						beforeSend: function() {
							jQuery(newsletter_spinner).html('<i class="fa fa-refresh fa-spin"></i>');
							jQuery(newsletter_msg).hide().removeClass('error_msg').html('');
						},
						success: function(msg){
							jQuery(newsletter_msg).show().removeClass('error_msg').html(msg);
							jQuery(newsletter_email).val('');
							jQuery(newsletter_spinner).html('');
						},
						error: function(msg){
							jQuery(newsletter_spinner).html('');
							jQuery(newsletter_msg).addClass('error_msg').html(msg).show();
						}
					});
					return false;
				});

			});
		}
	}

	function cs_slider_popup(){
		if ( jQuery('.slider-popup').length > 0 ) {
			jQuery('.slider-popup').magnificPopup({
				type: 'image',
			});
		}
	}

	function widget_pgscore_image_slider() {
		cs_owl_carousel();
		cs_slider_popup();
	}

	function widget_pgscore_product_deals(){
		cs_owl_carousel();
		ciyashop_deal_counter();
	}

	function widget_pgscore_single_product_slider(){
		cs_owl_carousel();
	}

	function cs_search_autocomplete() {
		jQuery( 'input.search-form' ).each(function() {
			var $search_form_id = jQuery(this).attr( 'id' );
			jQuery( this ).autocomplete({
				search: function(event, ui) {
					jQuery('.ciyashop-auto-compalte-default ul').empty();
					jQuery('.ciyashop-auto-compalte-default').addClass('ciyashop-empty');
					jQuery( this ).parents('form.search-form').find( '.ciyashop-auto-compalte-default' ).addClass( $search_form_id + '-class-selector' );
				},
				source: function( request, response ) {
					var search_category = this.element.parents('div.search_form-input-wrap').prev().children().val();
					var search_keyword = this.element.val();
					var search_loader = this.element.parents('div.search_form-search-field');
					jQuery.ajax({
						url: ciyashop_l10n.ajax_url,
						type: 'POST',
						dataType: "json",
						data: {'action': 'ciyashop_auto_complete_search' , 'ajax_nonce': ciyashop_l10n.ciyashop_nonce, 'search_keyword' : search_keyword, 'search_category' : search_category},
						beforeSend: function(){
							search_loader.addClass('ui-autocomplete-loader');
						},
						success: function( resp ) {
							response( jQuery.map( resp, function( result ) {
								var return_data = {
									image: result.post_img,
									title: result.post_title,
									link: result.post_link
								};
								return return_data;
							}));
						}
					}).done( function(){
						search_loader.removeClass('ui-autocomplete-loader');
					});
				},
				minLength: 2,
			}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
				ul.remove();
				var html = '';
				html += '<a href="'+item.link+'">';
				html += '<div class="search-item-container">';
				if(item.image){
					html += item.image;
				}
				html += item.title;
				html += '</div>';
				html += '</a>';
				jQuery( $search_form_id + '-class-selector' ).removeClass('ciyashop-empty');
				return jQuery( "<li class='ui-menu-item'></li>" )
					.data( "ui-autocomplete-item", item )
					.append(html)
					.appendTo(jQuery( '.' + $search_form_id + '-class-selector ul' ));
		   };
		});
	}

	function cs_popup_gallery() {
		if ( jQuery( '.image_popup-gallery' ).length > 0 ) {
			jQuery( '.image_popup-gallery' ).each(function( index ) {
				jQuery( this ).magnificPopup({
					delegate: 'a.image_popup-img',
					type: 'image',
					tLoading: 'Loading image #%curr%...',
					mainClass: 'mfp-img-mobile',
					gallery: {
						enabled: true,
						navigateByImgClick: true,
						preload: [0,1] // Will preload 0 - before current, and 1 after the current image
					},
					image: {
						tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
					}
				});
			});
		}
	}

	function cs_progress_bar_run() {
		jQuery('.progress-bar').each( function ( i, elem ) {
			if ( ! jQuery(this).hasClass('progress-animated') ) {

				var elementPos  = jQuery(this).offset().top;
				var topOfWindow = jQuery(window).scrollTop();
					topOfWindow = topOfWindow + jQuery(window).height();

				var $elem       = jQuery(this),
					percent = $elem.attr('data-percent') || "100";
				var animate = $elem.attr('data-animate');
				if ((elementPos < topOfWindow)) {
					$elem.animate({
						'width': percent + '%'
					}).addClass('progress-animated');
				}
			}
		});
	};

	function cs_owl_carousel() {
		setTimeout( function(){
			jQuery( '.owl-carousel.owl-carousel-options' ).each( function () {
				var $carousel = jQuery(this),
					$carousel_option = ( $carousel.attr('data-owl_options')) ? $carousel.data('owl_options') : {};
					$carousel_option.navElement = 'div';
					$carousel_option.rtl = (jQuery( "body" ).hasClass( "rtl" )) ? true : false;

				jQuery(this).owlCarousel($carousel_option);
			});
		}, 300 );
	}

	function widget_pgscore_portfolio() {
		setTimeout(function(){
			cs_isotope();
		}, 200 );
		cs_owl_carousel();
		cs_popup_gallery();
	}

	function widget_pgscore_hotspot() {
		jQuery( document).on( 'click', function(e) {
			if ( jQuery( e.target ).parents( '.trigger-click' ).length === 0 ) {
				jQuery( '.trigger-click' ).removeClass( 'hotspot-visible' );
			}
		});

		jQuery( '.trigger-click' ).each(function() {
			jQuery( this ).on( 'click', function() {
				if ( jQuery( this ).hasClass( 'hotspot-visible' ) ) {
					return;
				}
				jQuery( '.trigger-click' ).removeClass( 'hotspot-visible' );
				jQuery( this ).addClass( 'hotspot-visible' );
			});

		});
	}

	function cs_smart_image_view(){	
		window.CI360.init();
	}

	function cs_counter_run() {
		jQuery('.counter-number').each(function () {
			if ( ! jQuery(this).hasClass('counter-animated') ) {
				var elementPos  = jQuery(this).offset().top;
				var topOfWindow = jQuery(window).scrollTop();
				topOfWindow = topOfWindow + jQuery(window).height() - 30;
				var $elem = jQuery(this);
				if ((elementPos < topOfWindow)) {
					$elem.addClass('counter-animated');
					jQuery(this).prop('Counter',0).animate({
						Counter: jQuery(this).text()
					}, {
						duration: 1500,
						easing: 'swing',
						step: function (now) {
							jQuery(this).text(Math.ceil(now));
						}
					});
				}
			}
		});
	};

	// Function for the deal counter
	function ciyashop_deal_counter() {
		if ( jQuery( '.deal-counter-wrapper' ).length ) {
			jQuery( '.deal-counter-wrapper' ).each( function() {
				var $deal_wrapper   = jQuery(this),
					$deal_counter   = $deal_wrapper.find( '.deal-counter' ),
					$countdown_date = $deal_counter.data( 'countdown-date' ),
					counter_data    = $deal_counter.data( 'counter_data' ),
					on_expire_btn,
					$deal_wrapper_grand,
					$deal_button;

					if ( typeof counter_data.on_expire_btn != 'undefined' ) {
						on_expire_btn = counter_data.on_expire_btn;
					} else {
						on_expire_btn = 'remove';
					}

					if ( $deal_wrapper.parent().hasClass( 'pgscore_banner-content-inner-wrapper' ) ) {
						$deal_wrapper_grand = $deal_wrapper.closest( '.pgscore_banner-content-inner-wrapper' );
						$deal_button        = $deal_wrapper_grand.find( '.pgscore_banner-btn' );
					} else {
						$deal_wrapper_grand = $deal_wrapper.closest( '.deal-banner' );
						$deal_button        = $deal_wrapper.find( '.deal-button' );
					}

				$deal_counter.countdown( $countdown_date )
					.on( 'update.countdown', function(event) {

						var format = '<ul class="countdown">';

							var display_weeks = false;
							if ( display_weeks ) {
								if ( event.offset.weeks > 0 ) {
									format = format + '<li><span class="days">%-w</span><p class="days_ref smalltxt">' + counter_data.weeks + '</p></li>';
								}
								if( event.offset.totalDays > 0 ) {
									format = format + '<li><span class="days">%-d</span><p class="days_ref smalltxt">' + counter_data.days + '</p></li>';
								}
							} else {
								if ( event.offset.totalDays > 0 ) {
									format = format + '<li><span class="days">%-D</span><p class="days_ref smalltxt">' + counter_data.days + '</p></li>';
								}
							}

							format = format + '<li><span class="hours">%H</span><p class="hours_ref smalltxt">' + counter_data.hours + '</p></li>';
							format = format + '<li><span class="minutes">%M</span><p class="minutes_ref smalltxt">' + counter_data.minutes + '</p></li>';
							format = format + '<li><span class="seconds">%S</span><p class="seconds_ref smalltxt">' + counter_data.seconds + '</p></li>';
							format = format + '</ul>';

						$deal_counter.html( event.strftime( format ) );
					})
					.on( 'finish.countdown', function(event) {
						$deal_wrapper.addClass( 'deal-expired' );
						$deal_counter.html( '<span class="deal-expire-message">' + counter_data.expiremsg + '</span>' ).addClass( 'deal-counter-expired' ).removeAttr(  'data-counter_data' ).removeAttr( 'data-countdown-date' );

						if ( on_expire_btn == 'remove' ) {
							$deal_button.remove();
						} else {
							$deal_button.addClass( 'disabled' ).attr( 'disabled', true );
							$deal_button.on('click', function(e){
								e.preventDefault();
							});
						}
					});
			});
		}
	}
	// Fix for form without action
	var topbar_currency_switcher_form = document.querySelector(".topbar_item.topbar_item_type-currency .woocommerce-currency-switcher-form");
	if(topbar_currency_switcher_form != null){
		topbar_currency_switcher_form.setAttribute("action", "");
	}

	function ciyashop_vc_fullwidthrow() {
		 var $elements = jQuery('[data-vc-full-width="true"]');
		jQuery.each($elements, function(key, item) {
			var $el = jQuery(this);
			$el.addClass("vc_hidden");
			var $el_full = $el.next(".vc_row-full-width");
			if ($el_full.length || ($el_full = $el.parent().next(".vc_row-full-width")), $el_full.length) {
				var padding, paddingRight, el_margin_left = parseInt($el.css("margin-left"), 10),
					el_margin_right = parseInt($el.css("margin-right"), 10),
					offset = 0 - $el_full.offset().left - el_margin_left,
					width = jQuery(window).width();
				if ("rtl" === $el.css("direction") && (offset -= $el_full.width(), offset += width, offset += el_margin_left, offset += el_margin_right), $el.css({
						position: "relative",
						left: offset,
						"box-sizing": "border-box",
						width: width
					}), !$el.data("vcStretchContent")) "rtl" === $el.css("direction") ? ((padding = offset) < 0 && (padding = 0), (paddingRight = offset) < 0 && (paddingRight = 0)) : ((padding = -1 * offset) < 0 && (padding = 0), (paddingRight = width - padding - $el_full.width() + el_margin_left + el_margin_right) < 0 && (paddingRight = 0)), $el.css({
					"padding-left": padding + "px",
					"padding-right": paddingRight + "px"
				});
				$el.attr("data-vc-full-width-init", "true"), $el.removeClass("vc_hidden"), jQuery(document).trigger("vc-full-width-row-single", {
					el: $el,
					offset: offset,
					marginLeft: el_margin_left,
					marginRight: el_margin_right,
					elFull: $el_full,
					width: width
				})
			}
		}), jQuery(document).trigger("vc-full-width-row", $elements)
	}

	function ciyashop_vc_rtl_fullwidthrow() {
		if( jQuery('html').attr('dir') == 'rtl' ){

			var $elements = jQuery('[data-vc-full-width="true"]');
			jQuery.each($elements, function(key, item) {
				var $el = jQuery(this);
				$el.addClass("vc_hidden");
				var $el_full = $el.next(".vc_row-full-width");
				if ($el_full.length || ($el_full = $el.parent().next(".vc_row-full-width")), $el_full.length) {

					var el_margin_left = parseInt($el.css("margin-left"), 10);
					var el_margin_right = parseInt($el.css("margin-right"), 10);
					var offset = 0 - $el_full.offset().left - el_margin_left;
					var width = jQuery(window).width();

					$el.css({
						left: 'auto',
						right: offset,
						width: width,
					});
				}
				$el.attr("data-vc-full-width-init", "true"), $el.removeClass("vc_hidden");
			});
		}
	}

	function ciyashop_WooCommerce_Quantity_Input() {
		jQuery( '.quantity' ).each( function() {
			jQuery('<div class="quantity-nav"><div class="quantity-button quantity-up">+</div><div class="quantity-button quantity-down">-</div></div>').insertAfter( jQuery(this).find( 'input[type="number"]' ));
		});
	}

	function cs_one_navigation(e) {

		var anch_hash_url = jQuery(this).attr('href');
		var current_url = window.location.href;

		if (typeof anch_hash_url == 'undefined')
		return;

		var anch_url = anch_hash_url.substr(0, anch_hash_url.lastIndexOf("#"));

		if(window.location.href.indexOf("#") > -1) {
			current_url = current_url.substr(0, current_url.lastIndexOf("#"));
		}

		var urlString = this.getAttribute('href');
		var urlArray = urlString.split('#');

		if(anch_url+'/' == current_url){

			e.preventDefault();
			document.querySelector('#'+urlArray[1]).scrollIntoView({
				behavior: 'smooth'
			});

		}else if(anch_hash_url){
			if ( anch_hash_url.indexOf('#') > -1 && anch_hash_url != '#') {
				anch_hash_urlarray = anch_hash_url.split('#');
				if(!anch_hash_urlarray[0]){
					if(document.querySelector(anch_hash_url) != null){
						e.preventDefault();
						document.querySelector(anch_hash_url).scrollIntoView({
							behavior: 'smooth'
						});
					}
				}
			}
		}
	}

	// Change the Active class on scroll
	function cs_onScroll(event){
		var scrollPos = jQuery(document).scrollTop();

		jQuery('.primary-menu a, .primary-menu-mobile a').each(function () {

			var currLink = jQuery(this);
			var urlString = currLink.attr("href");

			if( typeof urlString != "undefined" ){
				var currurl = urlString.split('#');
				if ( currurl[1] ) {
					var refElement = jQuery( '#' + currurl[1].replace(/[^a-z0-9\s]/gi, '' ) );
				}
			}

			var refElement_position;
			var refElement_height;

			// add click event
			this.addEventListener( 'click', cs_one_navigation );

			if( typeof refElement != "undefined" && refElement.position()){
				var refElement_position = refElement.position().top;
				var refElement_height = refElement.height();
			}

			if ( ( refElement_position < scrollPos || refElement_position == scrollPos) && ( refElement_position + refElement_height > scrollPos) ) {
				currLink.parent().addClass( "current-menu-item" );
			}else{
				if ( typeof urlString != "undefined" && urlString.indexOf('#') > -1 ) {
					currLink.parent().removeClass( "current-menu-item" );
				}
			}
		});
	}

	function cs_blog_masonry() {
		var container = document.querySelector('.masonry-main .masonry');
		if(container != null){
			var msnry = new Masonry( container, {
				itemSelector: '.masonry-item',
				columnWidth: '.masonry-item',
				isOriginLeft: (jQuery( "body" ).hasClass( "rtl" )) ? false :true,
			});
		}
	}

	function cs_product_grid_set(){
		if( jQuery( 'ul.products-loop' ).hasClass('grid') ){
			var index = 0;
			var column = jQuery( 'ul.products-loop' ).data('column');

			jQuery( 'ul.products-loop' ).find('li').each( function() {
				index++;
				if(jQuery(this).hasClass('first')){
					jQuery(this).removeClass('first');
				}else if(jQuery(this).hasClass('last')){
					jQuery(this).removeClass('last');
				}

				if( column == index ){
					jQuery(this).addClass('last');
					index = 0;
				}else if( index == 1 ){
					jQuery(this).addClass('first');
				}
			});
		}
	}


	function ciyashop_lazyload() {
		if(jQuery('.ciyashop-lazy-load').length) {
			jQuery('.ciyashop-lazy-load').lazy({
				afterLoad: function(element) {
					// called after an element was successfully handled
					element.removeClass('ciyashop-lazy-load');
					element.addClass('ciyashop-loaded');
					cs_isotope();
					cs_blog_masonry();
				}
			});
		}
	}
	/******************
	:: Magnific popup
	******************/
	jQuery('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
		disableOn: 300,
		type: 'iframe',
		mainClass: 'mfp-fade',
		removalDelay: 160,
		preloader: false,
		fixedContentPos: false
	});

	/**************
	:: youtube
	***************/
	jQuery.extend(true,jQuery.magnificPopup.defaults, {
		iframe: {
			patterns: {
				youtube: {
					index: 'youtube.com/',
					id: 'v=',
					src: 'https://www.youtube.com/embed/%id%?autoplay=1'
				}
			}
		}
	});

	/**************
	:: Vimeo
	***************/
	jQuery.extend(true,jQuery.magnificPopup.defaults, {
		iframe: {
			patterns: {
				vimeo: {
					index: 'vimeo.com/',
					id: '/',
					src: 'https://player.vimeo.com/video/%id%?autoplay=1'
				},
			}
		}
	});

	/*************************************************************************
	:: 			show products variations for product listing page 		   ::
	*************************************************************************/

	function ciyashop_grid_swatches() {
		jQuery(document).on('click', '.ciyashop-grid-swatch', function() {
			var src, srcset, image_sizes;

			var imageSrc    = jQuery(this).data('image-src'),
				imageSrcset = jQuery(this).data('image-srcset'),
				imageSizes  = jQuery(this).data('image-sizes');

			if( typeof imageSrc == 'undefined' ) return;

			// get all image attributes
			var $product        = jQuery(this).parents('.product-type-variable'),
				$image          = $product.find('.product-thumbnail-main img'),
				$src_original   = $image.data('original-src'),
				$srcsetOrig     = $image.data('original-srcset'),
				$sizes_original = $image.data('original-sizes');

			// set images attributes
			if( typeof $src_original == 'undefined' ) {
				$image.data('original-src', $image.attr('src'));
			}

			if( typeof $srcsetOrig == 'undefined' ) {
				$image.data('original-srcset', $image.attr('srcset'));
			}

			if( typeof $sizes_original == 'undefined' ) {
				$image.data('original-sizes', $image.attr('sizes'));
			}


			if( jQuery(this).hasClass('cs-attr-selected') ) {
				src         = $src_original;
				srcset      = $srcsetOrig;
				image_sizes = $sizes_original;
				jQuery(this).removeClass('cs-attr-selected');
				$product.removeClass('product-swatched');
			} else {
				jQuery(this).parent().find('.cs-attr-selected').removeClass('cs-attr-selected');
				jQuery(this).addClass('cs-attr-selected');
				$product.addClass('product-swatched');
				src         = imageSrc;
				srcset      = imageSrcset;
				image_sizes = imageSizes;
			}

			if( $image.attr('src') == src ) return;

			$product.addClass('cs-loading');
			$image.attr('src', src).attr('srcset', srcset).attr('image_sizes', image_sizes).one('load', function() {
				$product.removeClass('cs-loading');
			});
		});
	}

	function cs_widget_cart_nanoscroll(){
		if( jQuery('.pgs_product_list_widget-container').length > 0 && jQuery('.woocommerce-mini-cart').length > 0 ){		
			jQuery( '.pgs_product_list_widget-container' ).each(function() {
				if ( jQuery( this ).parents( '.header-sticky' ).length > 0 ) {
					jQuery( this ).nanoScroller({
						paneClass: 'ciyashop-scroll-pane',
						sliderClass: 'ciyashop-scroll-slider',
						contentClass: 'woocommerce-mini-cart',
						preventPageScrolling: false,
					});
				} else {
					jQuery( this ).nanoScroller({
						paneClass: 'ciyashop-scroll-pane',
						sliderClass: 'ciyashop-scroll-slider',
						contentClass: 'woocommerce-mini-cart',
						preventPageScrolling: false,
						flash: (jQuery( "body" ).hasClass( "rtl" )) ? true : false
					});
				}
			});
		}
	}

	function chl_sticky_header(){

		var chl_sticky_header_height = 0;
		var current_scroll = jQuery(window).scrollTop();
		var windowSize = jQuery(window).width();
		var wpadminbar = 0;
		var mobile_first_element_position = 0;
		var desktop_first_element_position = 0;
		var total_outerheight = 0;
		var element_outerheight = 0;
		var mobile_element_outerheight = 0;
		var first_element = true;
		var mobile_first_element = true;

		if( jQuery('body').hasClass('admin-bar') && jQuery('#wpadminbar').length > 0 ){
			if( jQuery('#wpadminbar').css('position') == 'fixed' ){
				wpadminbar = jQuery('#wpadminbar').outerHeight();
			}
		}

		jQuery.each( jQuery('.header-row'), function( index, value ) {
			chl_sticky_header_height = chl_sticky_header_height + jQuery(this).outerHeight();
		});

		if( windowSize <= 992 ){

			if( ! jQuery( '.header-main-wrapper' ).hasClass('chl-header-sticky') ){
				if( jQuery('.mobile-sticky-on').length > 0 ){
					mobile_first_element_position = jQuery('.mobile-sticky-on').first().offset().top;
				}
			}

			jQuery.each( jQuery('.header-row'), function( index, value ) {
				index++;
				total_outerheight = total_outerheight + jQuery(this).outerHeight();

				if( jQuery(this).hasClass('mobile-sticky-off') && index == 1 ){
					mobile_element_outerheight = jQuery(this).outerHeight();
					mobile_first_element_position = mobile_element_outerheight;
					mobile_first_element = false;
				} else if( jQuery(this).hasClass('mobile-sticky-off') && index == 2 ){
					mobile_element_outerheight = total_outerheight;
					if( ! mobile_first_element ){
						mobile_first_element_position = mobile_element_outerheight;
					}
				} else if( jQuery(this).hasClass('mobile-sticky-off') && index == 3 ){
					mobile_element_outerheight = total_outerheight;
				}
			});

			if( jQuery('.header-row.mobile-sticky-on').length == 0 ) return;
			jQuery( '.header-main-wrapper' ).addClass('chl-sticky-enabled');

			if( current_scroll <= mobile_element_outerheight && current_scroll <= mobile_first_element_position ){
				jQuery('.header-row.mobile-sticky-off').show();
				jQuery( '.header-main-wrapper' ).removeClass('chl-header-sticky');
			} else if ( current_scroll > mobile_first_element_position ) {
				jQuery( '.header-main-wrapper' ).addClass('chl-header-sticky');
				jQuery('.header-row.mobile-sticky-off').hide();
			} else {
				jQuery( '.header-main-wrapper' ).addClass('chl-header-sticky');
				jQuery('.header-row.mobile-sticky-off').hide();
			}

		} else if( windowSize > 992 ) {

			if( ! jQuery( '.header-main-wrapper' ).hasClass('chl-header-sticky') ){
				if( jQuery('.desktop-sticky-on').length > 0 ){
					desktop_first_element_position = jQuery('.desktop-sticky-on').first().offset().top - wpadminbar;
				}
			}

			jQuery.each( jQuery('.header-row'), function( index, value ) {
				index++;
				total_outerheight = total_outerheight + jQuery(this).outerHeight();

				if( jQuery(this).hasClass('desktop-sticky-off') && index == 1 ){
					element_outerheight = jQuery(this).outerHeight();
					desktop_first_element_position = element_outerheight;
					first_element = false;
				} else if( jQuery(this).hasClass('desktop-sticky-off') && index == 2 ){
					element_outerheight = total_outerheight;
					if( ! first_element ){
						desktop_first_element_position = element_outerheight;
					}
				} else if( jQuery(this).hasClass('desktop-sticky-off') && index == 3 ){
					element_outerheight = total_outerheight;
				}
			});

			if( jQuery('.header-row.desktop-sticky-on').length == 0 ) return;
			jQuery( '.header-main-wrapper' ).addClass('chl-sticky-enabled');

			if( current_scroll <= element_outerheight && current_scroll <= desktop_first_element_position ){

				jQuery( '.header-main-wrapper' ).css({ top: 0});
				jQuery('.header-row.desktop-sticky-off').show();
				jQuery( '.header-main-wrapper' ).removeClass('chl-header-sticky');

			} else if ( current_scroll > desktop_first_element_position ) {

				jQuery( '.header-main-wrapper' ).css({
					top: wpadminbar,
				});
				jQuery( '.header-main-wrapper' ).addClass('chl-header-sticky');
				jQuery('.header-row.desktop-sticky-off').hide();

			} else {
				jQuery( '.header-main-wrapper' ).css({ top: 0});
				jQuery('.header-row.desktop-sticky-off').show();
				jQuery( '.header-main-wrapper' ).removeClass('chl-header-sticky');
			}
		}

		jQuery( '#masthead-inner' ).css({
			paddingTop: chl_sticky_header_height,
		});
	}

	function cs_megamenu_dropdown_position(){

		/* Mega menu move left */
		var window_width = jQuery(window).width();

		jQuery( 'ul.pgs_megamenu-enable > li' ).each( function(e) {
			if( jQuery(this).hasClass('pgs-menu-item-mega-menu') ){
				if( jQuery(this).find('.pgs_menu_nav-sublist-dropdown').length > 0 ){
					var sublist_width = jQuery(this).find('.pgs_menu_nav-sublist-dropdown').outerWidth(),
						sublist_off = jQuery(this).offset(),
						sublist_length = sublist_off.left,
						isEntirelyVisible = ( sublist_length + sublist_width <= window_width );

					if (!isEntirelyVisible) {
						var left = sublist_length + sublist_width - window_width;

						if( !jQuery(this).hasClass('pgs-mega-menu-full-width') ) left = left + 15;
						jQuery(this).find('.pgs_menu_nav-sublist-dropdown').css("left", "-"+left+"px");
					}
				}

				if( jQuery(this).find('.pgs-menu-html-block').length > 0 ){
					var html_block = jQuery(this).find('.pgs-menu-html-block').outerWidth(),
						html_off = jQuery(this).offset(),
						html_length = html_off.left,
						isHtmlEntirelyVisible = ( html_length + html_block <= window_width );

					if (!isHtmlEntirelyVisible) {
						var left = html_length + html_block - window_width;

						if( !jQuery(this).hasClass('pgs-mega-menu-full-width') ) left = left + 15;
						jQuery(this).find('.pgs-menu-html-block').css("left", "-"+left+"px");
					}
				}
			}
		});
	}

	function cs_compare_popup_row_height() {

		jQuery( '.cs-compare-list-header .cs-compare-list-title' ).each( function( inner_index ) {
			jQuery(this).removeAttr( 'style' );
		});

		jQuery( '.cs-product-list-column' ).each( function( index ) {
			jQuery( this ).find( '.cs-product-list-row' ).each( function( inner_index ) {
				jQuery(this).removeAttr( 'style' );
			});
		});

		var height = [];
		jQuery( '.cs-product-list-column' ).each( function( index ) {
			var inner_height = [];
			jQuery( this ).find( '.cs-product-list-row' ).each( function( inner_index ) {
				inner_height.push( jQuery(this).height() );
			});
			height.push( inner_height );		
		});

		var max_height = [];
		for ( var i = 0; i < height.length; i++ ) {
			var mh;
			var pre_index;

			if ( i !== 0 ) {
				pre_index = i - 1;
			}

			for ( var j = 0; j < height[i].length; j++ ) {
				if ( i !== 0 ) {
					if ( height[i][j] > max_height[j] ) {
						max_height[j] = height[i][j];
					}
				} else {
					max_height.push( height[i][j] );
				}
			}
		}
		
		jQuery( '.cs-compare-list-header .cs-compare-list-title' ).each( function( inner_index ) {
			jQuery(this).height( max_height[inner_index] );
		});

		jQuery( '.cs-product-list-column' ).each( function( index ) {
			jQuery( this ).find( '.cs-product-list-row' ).each( function( inner_index ) {
				jQuery(this).height( max_height[inner_index] );
			});
		});
	}

	function cs_widgets_nanoscroll() {
		if( jQuery('.pgs-woocommerce-widget-layered-nav-list-container').length > 0 && jQuery('.pgs-woocommerce-widget-layered-nav-list-container').length > 0 ){
			jQuery( '.pgs-woocommerce-widget-layered-nav-list-container' ).nanoScroller({
				paneClass: 'ciyashop-scroll-pane',
				sliderClass: 'ciyashop-scroll-slider',
				contentClass: 'pgs-woocommerce-widget-layered-nav-list',
			});
		}

		if ( jQuery( '.widget_product_categories' ).length > 0 && jQuery( '.product-categories' ).length > 0 ) {
			jQuery( '.widget_product_categories' ).each( function () {
				if( ! jQuery( this ).find( 'select.dropdown_product_cat' ).length > 0 ) {
					jQuery( this ).nanoScroller({
						paneClass: 'ciyashop-scroll-pane',
						sliderClass: 'ciyashop-scroll-slider',
						contentClass: 'product-categories',
						preventPageScrolling: true
					});
				}
			});
		}
	}

	function cs_update_query_string_parameter( uri, key, value ) {
		var reg = new RegExp( "([?&])" + key + "=.*?(&|$)", "i" );
		var sep = uri.indexOf( '?' ) !== -1 ? "&" : "?";

		if ( uri.match( reg ) ) {
			return uri.replace( reg, '$1' + key + "=" + value + '$2' );
		} else {
			return uri + sep + key + "=" + value;
		}
	}

	function cs_shop_ajax_filter( url, pagination ) {
		if ( url.indexOf( '/page' ) != -1 && ! pagination ) {
			var wihout_pg  = url.split( '/page' )[0];
			var without_qs = url.split( '?' )[0];
			url = url.replace( without_qs, wihout_pg );
		}

		window.history.pushState( null, null, url );

		jQuery( '.products.products-loop' ).addClass( 'loading' );

		if( jQuery( '.woocommerce-categories-slider' ).length > 0 ){
			jQuery( '.woocommerce-categories-slider' ).addClass( 'loading' );
		}

		jQuery( document.body ).trigger( 'cs_shop_ajax_before_content_load' );

		jQuery.get( url ).success( function( data ) {
			jQuery( '.loading' ).removeClass( 'loading' );

			if( jQuery( '.woocommerce-categories-slider' ).length > 0 ){
				jQuery( '.woocommerce-categories-slider' ).removeClass( 'loading' );
			}

			var new_html = jQuery.parseHTML( data );
			jQuery( '#content' ).html( jQuery( new_html ).find( '#content' ).html() );
			jQuery( '.owl-carousel.owl-carousel-options' ).each( function () {
				var $carousel = jQuery(this),
					$carousel_option = ( $carousel.attr('data-owl_options')) ? $carousel.data('owl_options') : {};
					$carousel_option.navElement = 'div';
					$carousel_option.rtl = (jQuery( "body" ).hasClass( "rtl" )) ? true : false;

				jQuery(this).owlCarousel($carousel_option);
			});

			cs_select2_init();
			cs_widgets_nanoscroll();
			jQuery( document.body ).trigger( 'init_price_filter' );			
			if ( jQuery().selectWoo && jQuery( '.woocommerce-widget-layered-nav-dropdown' ).length > 0 ) {
				jQuery( 'select.woocommerce-widget-layered-nav-dropdown' ).selectWoo();
			}
		});

		jQuery( document.body ).trigger( 'cs_shop_ajax_after_content_load' );
	}

})(jQuery);
