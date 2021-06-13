/*global wc_add_to_cart_variation_params */
;(function ( $, window, document, undefined ) {
	
	jQuery(document).ready(function($) {
		
		$( '.variations_form' ).each( function( index, el ) {
			
			var $form                  = $( this );
				$product               = $form.closest( '.product' ),
				$product_gallery       = $product.find( '.ciyashop-product-gallery' ),
				$product_gallery_dummy = $product.find( '.images' ),
				$singleVariationWrap   = $form.find( '.single_variation_wrap' ),
				// $attributeFields       = $form.find( '.variations select' ),
				variationsData         = $form.data( 'product_variations' );
				
				var $gallery_nav          = $product.find( '.ciyashop-product-thumbnails .slick-track' ),
					$gallery_nav_img      = $gallery_nav.find( '.slick-slide:eq(0) img' ),
					$gallery_nav_img_wrap = $gallery_nav.find( '.slick-slide:eq(0) .ciyashop-product-thumbnail__image' ),
					
					$product_img_wrap = $product_gallery.find( '.ciyashop-product-gallery__image, .ciyashop-product-gallery__image--placeholder' ).eq( 0 ),
					$product_img      = $product_img_wrap.find( 'a img' ),
					$product_link     = $product_img_wrap.find( 'a' ).eq( 0 );

			$product_gallery_dummy.on( "woocommerce_gallery_init_zoom", function ( event, variation ) {
				wc_variations_image_update( cs_actvars_get_current_variation() );
			} );
			
			/**
			 * Sets product images for the chosen variation
			 */
			function wc_variations_image_update( variation ) {
				
				if ( variation && variation.image && variation.image.src && variation.image.src.length > 1 ) {
					
					if ( $gallery_nav.find( '.ciyashop-product-thumbnail__image[data-image_id="' + variation.image_id + '"]' ).length > 0 ) {
						$gallery_nav.find( 'div img[src="' + variation.image.gallery_thumbnail_src + '"]' ).trigger( 'click' );
						$gallery_nav.find( '.ciyashop-product-thumbnail__image[data-image_id="' + variation.image_id + '"]' ).trigger( 'click' );
						$form.attr( 'current-image', variation.image_id );
						return;
					} else {
						wc_set_variation_attr( $product_img, 'src', variation.image.src );
						wc_set_variation_attr( $product_img, 'height', variation.image.src_h );
						wc_set_variation_attr( $product_img, 'width', variation.image.src_w );
						wc_set_variation_attr( $product_img, 'srcset', variation.image.srcset );
						wc_set_variation_attr( $product_img, 'sizes', variation.image.sizes );
						wc_set_variation_attr( $product_img, 'title', variation.image.title );
						wc_set_variation_attr( $product_img, 'alt', variation.image.alt );
						wc_set_variation_attr( $product_img, 'data-src', variation.image.full_src );
						wc_set_variation_attr( $product_img, 'data-large_image', variation.image.full_src );
						wc_set_variation_attr( $product_img, 'data-large_image_width', variation.image.full_src_w );
						wc_set_variation_attr( $product_img_wrap, 'data-thumb', variation.image.src );
						wc_set_variation_attr( $gallery_nav_img, 'src', variation.image.gallery_thumbnail_src );
						wc_set_variation_attr( $product_link, 'href', variation.image.full_src );
						
						$( $gallery_nav_img_wrap).data( 'image_id', variation.image_id );
						
						$product_gallery.trigger( 'cs_woocommerce_gallery_reset_slide_position' );
					}
				} else {
					wc_variations_image_reset();
				}
			};
			
			/**
			 * Reset main image to defaults.
			 */
			function wc_variations_image_reset() {
				var $gallery_nav      = $product.find( '.ciyashop-product-thumbnails .slick-track' ),
					$gallery_nav_img  = $gallery_nav.find( '.slick-slide:eq(0) img' ),
					$product_img_wrap = $product_gallery.find( '.ciyashop-product-gallery__image, .ciyashop-product-gallery__image--placeholder' ).eq( 0 ),
					$product_img      = $product_img_wrap.find( 'a img' ),
					$product_link     = $product_img_wrap.find( 'a' ).eq( 0 );

				wc_reset_variation_attr( $product_img, 'src' );
				wc_reset_variation_attr( $product_img, 'width' );
				wc_reset_variation_attr( $product_img, 'height' );
				wc_reset_variation_attr( $product_img, 'srcset' );
				wc_reset_variation_attr( $product_img, 'sizes' );
				wc_reset_variation_attr( $product_img, 'title' );
				wc_reset_variation_attr( $product_img, 'alt' );
				wc_reset_variation_attr( $product_img, 'data-src' );
				wc_reset_variation_attr( $product_img, 'data-large_image' );
				wc_reset_variation_attr( $product_img, 'data-large_image_width' );
				wc_reset_variation_attr( $product_img, 'data-large_image_height' );
				wc_reset_variation_attr( $product_img_wrap, 'data-thumb' );
				wc_reset_variation_attr( $gallery_nav_img, 'src' );
				wc_reset_variation_attr( $product_link, 'href' );
				
				$( $gallery_nav_img_wrap).data( 'image_id', $gallery_nav_img_wrap.data('o_image_id') );
				
				$product_gallery.trigger( 'cs_woocommerce_gallery_reset_slide_position' );
			};
			
			/**
			 * Reset a default attribute for an element so it can be reset later
			 */
			function wc_reset_variation_attr( ele, attr ) {
				if ( undefined !== ele.attr( 'data-o_' + attr ) ) {
					ele.attr( attr, ele.attr( 'data-o_' + attr ) );
				}
			};
			
			/**
			 * Stores a default attribute for an element so it can be reset later
			 */
			function wc_set_variation_attr( ele, attr, value ) {
				if ( undefined === ele.attr( 'data-o_' + attr ) ) {
					ele.attr( 'data-o_' + attr, ( ! ele.attr( attr ) ) ? '' : ele.attr( attr ) );
				}
				if ( false === value ) {
					ele.removeAttr( attr );
				} else {
					ele.attr( attr, value );
				}
			};
			
			function cs_actvars_get_current_variation(){
				
				var variation_id = cs_actvars_get_current_variation_id();
				
				if( false === variation_id ) return false;
				
				var current_variation = variationsData.filter(function (current_variation) {
					return current_variation.variation_id == cs_actvars_get_current_variation_id();
				});
				
				if(typeof current_variation[0] == "undefined" || !current_variation[0]) return false;
				
				return current_variation[0];
			}
			
			function cs_actvars_get_current_variation_id(){
				
				var variation_id = $($singleVariationWrap).find('.variations_button input[name="variation_id"]').val();
				
				if( variation_id == '' ) return false;
				
				return variation_id;
			}
		});

	});

})( jQuery, window, document );