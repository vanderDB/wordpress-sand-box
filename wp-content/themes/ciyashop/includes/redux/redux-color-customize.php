<?php
/**
 * Save color customizer when sample data import
 *
 * @package CiyaShop
 */

add_action( 'pgs_core_sample_data_import_theme_options', 'ciyashop_sample_data_theme_option' );
/**
 * Sample data theme option
 *
 * @param string $ciyashop_options .
 */
function ciyashop_sample_data_theme_option( $ciyashop_options ) {
	$header_schema = '';

	$primary_color   = isset( $ciyashop_options['primary_color'] ) ? esc_html( $ciyashop_options['primary_color'] ) : '';
	$secondary_color = isset( $ciyashop_options['secondary_color'] ) ? esc_html( $ciyashop_options['secondary_color'] ) : '';
	$tertiary_color  = isset( $ciyashop_options['tertiary_color'] ) ? esc_html( $ciyashop_options['tertiary_color'] ) : '';

	if ( isset( $ciyashop_options['header_type_select'] ) && 'predefined' !== (string) $ciyashop_options['header_type_select'] ) {
		$header_schema = ciyashop_get_custom_header_schema();
	}

	if ( ! empty( $primary_color ) && ! empty( $secondary_color ) && ! empty( $tertiary_color ) ) {
		$color_customize = ciyashop_get_color_scheme_colors( $primary_color, $secondary_color, $tertiary_color, $header_schema );
		ciyashop_generate_color_customize_css( $color_customize );
	}
}

add_action( 'wp_ajax_ciyashop_options_ajax_save', 'ciyashop_color_customize_ajax_save' );
/**
 * Save Color Customizer when save theme option
 */
function ciyashop_color_customize_ajax_save() {
	global $ciyashop_options, $ciyashop_globals;

	$header_schema         = '';
	$ciyashop_options_name = $ciyashop_globals['theme_option'];

	$redux_data = get_plugin_data( WP_PLUGIN_DIR . '/redux-framework/redux-framework.php' );

	if ( version_compare( $redux_data['Version'], '4.0', '<=' ) ) {
		$redux  = new ReduxFramework();
		$values = isset( $_POST['data'] ) ? $redux->redux_parse_str( wp_unslash( $_POST['data'] ) ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	} else {
		$post_data = wp_unslash( $_POST['data'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$values    = Redux_Functions_Ex::parse_str( $post_data );
	}

	// bail early if no data found.
	if ( empty( $values ) ) {
		return;
	}

	$primary_color      = esc_html( $values[ $ciyashop_options_name ]['primary_color'] );
	$secondary_color    = esc_html( $values[ $ciyashop_options_name ]['secondary_color'] );
	$tertiary_color     = esc_html( $values[ $ciyashop_options_name ]['tertiary_color'] );
	$header_type_select = esc_html( $values[ $ciyashop_options_name ]['header_type_select'] );

	if ( 'predefined' === (string) $header_type_select ) {
		$header_schema = '';
	} else {
		$header_id     = isset( $values[ $ciyashop_options_name ]['custom_headers'] ) ? esc_html( $values[ $ciyashop_options_name ]['custom_headers'] ) : '';
		$header_schema = ciyashop_get_custom_header_schema( $header_id );
	}

	$color_customize = ciyashop_get_color_scheme_colors( $primary_color, $secondary_color, $tertiary_color, $header_schema );
	ciyashop_generate_color_customize_css( $color_customize );

}
/**
 * Write color customizer css property
 *
 * @param string $color_customize .
 */
function ciyashop_generate_color_customize_css( $color_customize = '' ) {

	if ( empty( $color_customize ) ) {
		return;
	}

	update_option( 'color_customize_version', time() );

	$upload         = wp_upload_dir();
	$style_blog_dir = $upload['basedir'] . '/ciyashop';

	global $wp_filesystem;
	if ( empty( $wp_filesystem ) ) {
		require_once ABSPATH . '/wp-admin/includes/file.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		WP_Filesystem();
	}

	if ( ! is_dir( $style_blog_dir ) ) {
		wp_mkdir_p( $style_blog_dir );
	}

	$wp_filesystem->put_contents(
		$style_blog_dir . '/color_customize.css',
		$color_customize,
		FS_CHMOD_FILE // predefined mode settings for WP files.
	);
}
/**
 * Primary Color Option
 *
 * @param string $primary_color .
 * @param string $secondary_color .
 * @param string $tertiary_color .
 * @param string $header_schema .
 */
function ciyashop_get_color_scheme_colors( $primary_color, $secondary_color, $tertiary_color, $header_schema ) {
	$primary_color_customize = '/*background*/
		.widget_calendar .calendar_wrap td#today, input[type=submit], .btn, .widget_tag_cloud .tagcloud a.tag-cloud-link:hover, .owl-carousel .owl-nav i:hover,	.row-background-dark .latest-post-wrapper .owl-carousel .owl-nav i:hover, .social-profiles ul li a:hover,	.widget_pgs_social_profiles .social-profiles ul li a:hover i, .woocommerce #respond input#submit, .woocommerce a.button, .yith-woocompare-widget a.compare, button.button, .woocommerce button.button, .woocommerce input.button,
		button.submit, input[type=submit], .woocommerce .widget_shopping_cart .buttons a.checkout:hover, .woocommerce.widget_shopping_cart .buttons a.checkout:hover, .woocommerce .widget_price_filter .ui-slider .ui-slider-range, .widget_product_tag_cloud .tagcloud a:hover, .yith-woocompare-widget a.clear-all:hover,
		.gform_wrapper .gform_page_footer .button.gform_next_button, .gform_wrapper .gform_page_footer .button.gform_previous_button, #buddypress #item-nav .item-list-tabs#object-nav ul li.selected a, #buddypress div.item-list-tabs ul li a span, #buddypress #item-body .item-list-tabs#subnav ul li.selected a, .bp_members #buddypress ul.button-nav li a, #buddypress .generic-button a, #buddypress input[type=submit], .bp_members #buddypress ul.button-nav li.current a:hover, #buddypress div.activity-comments form div.ac-reply-content a:hover,
		#buddypress div.item-list-tabs ul li.current a, #buddypress div.item-list-tabs ul li.selected a, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset], #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, .woocommerce .product-hover-style-image-bottom-bar .product-actions a:hover, .woocommerce .product-hover-style-info-bottom-bar .product-actions a:hover, .woocommerce #respond input#submit, .woocommerce a.button, .yith-woocompare-widget a.compare, button.button, .woocommerce button.button, .woocommerce input.button, button.submit, input[type=submit], table.compare-list .add-to-cart .product-action .button, .pgscore_instagram_v2_wrapper .insta_v2_header--button a, .entry-summary .button.compare:hover, #back-to-top .top, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce button.button.alt.disabled, .pgscore_v_menu-header, .pgs-mtpl-header-wrapper .nav li a.active, .pgs-mtpl-header-wrapper .nav li a:hover, .nav.mtpl-tabs--tabs_style-style-2 li a.active:before, .nav.mtpl-tabs--tabs_style-style-2 li a:hover:before, .team.shadow:hover .team-info, .owl-carousel.owl-theme .owl-dots .owl-dot.active span, .owl-carousel.owl-theme .owl-dots .owl-dot:hover span, .row-background-dark  .owl-carousel.owl-theme .owl-dots .owl-dot:hover span,
.row-background-dark .owl-carousel.owl-theme .owl-dots .owl-dot.active span, .team .team-social-icon, .faq-layout-layout_1 .accordion .accordion-title a.active, .faq-layout-layout_2 .accordion .accordion-title a.active, .error-search-box .search-button, .cookies-buttons a, .cookies-buttons a:focus, .cookies-buttons a.cookies-more-btn:hover, .loop-header-active-filters .widget_layered_nav_filters ul li a:hover, .select2-container--default.select2-container--open .select2-results__option--highlighted, .select2-container--default.select2-container--open li:hover, .woocommerce ul.products.list li.product .product-info .product-actions .product-action-add-to-cart a, .woocommerce nav.woocommerce-pagination ul li span:hover, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current, .woocommerce div.product .woocommerce-tabs-layout-default ul.tabs li:before, .woocommerce .products .open-quick-view, .product-nav-btn:hover .product-nav-arrow, .woocommerce-account .woocommerce-MyAccount-content  input.button, article.hentry .readmore:hover, .slick-slider .slick-prev:hover, .slick-slider .slick-prev:focus, .slick-slider .slick-next:hover, .slick-slider .slick-next:focus, ul.page-numbers li > span:hover, ul.page-numbers li > a:hover, ul.page-numbers li span.current, ol.commentlist .comment .comments-info .comment-reply-link,  .widget_pgs_testimonials_widget .testimonials, .woocommerce div.product .woocommerce-tabs-layout-left ul.tabs li:before, .blog .timeline li:hover .timeline-badge, .blog .timeline li.entry-date-bottom a:hover, .woocommerce .product-hover-style-info-bottom .product-actions .add_to_cart_button, .woocommerce .product-hover-style-info-bottom .product-actions .product_type_external, .woocommerce .product-hover-style-info-bottom .product-actions .added_to_cart, .woocommerce .product-hover-style-info-bottom .product-actions .product_type_grouped, .woocommerce .product-hover-style-image-bottom-bar.product-hover-bar-style-flat .product-actions .add_to_cart_button, .woocommerce .product-hover-style-image-bottom-bar.product-hover-bar-style-flat .product-actions .product_type_variable, .woocommerce .product-hover-style-info-bottom-bar.product-hover-bar-style-flat .product-actions .product_type_variable, .woocommerce .product-hover-style-info-bottom-bar.product-hover-bar-style-flat .product-actions .add_to_cart_button, .woocommerce .product-hover-style-image-bottom-bar.product-hover-bar-style-flat .product-actions .product_type_external, .woocommerce .product-hover-style-image-bottom-bar.product-hover-bar-style-flat .product-actions .added_to_cart, .woocommerce .product-hover-style-image-bottom-bar.product-hover-bar-style-border .product-actions .product_type_external, .woocommerce .product-hover-style-info-bottom-bar.product-hover-bar-style-flat .product-actions .product_type_external, .woocommerce .product-hover-style-info-bottom-bar.product-hover-bar-style-border .product-actions .product_type_external, .woocommerce .product-hover-style-image-bottom-bar.product-hover-bar-style-flat .product-actions .product_type_grouped,
.woocommerce .product-hover-style-info-bottom-bar.product-hover-bar-style-flat .product-actions .product_type_grouped, .woocommerce li.product-hover-button-style-border .product-thumbnail:after, .site-header .header-nav.header-nav-bg-color-default,
.header-main-bg-color-default .woo-tools-cart .cart-link .count, .header-main-bg-color-default .woo-tools-wishlist .ciyashop-wishlist-count, .woo-tools-cart .cart-link .count, .woo-tools-compare .ciyashop-compare-count, .woo-tools-wishlist .ciyashop-wishlist-count, .header-style-right-topbar-main .header-main-top .topbar, .header-style-right-topbar-main #masthead-inner > .topbar, .header-style-right-topbar-main #masthead-inner > .topbar.topbar-bg-color-default, .not-found #searchform .search-button,  .latest-post-wrapper.latest-post-style-3 .latest-post-item .post-date, .request-box .request-box-form .wpcf7-submit:hover, .search_form-keywords-list li a:hover, .ui-state-hover, .ui-widget-content .ui-state-hover, .ui-widget-header .ui-state-hover, .ui-state-focus, .ui-widget-content .ui-state-focus, .ui-widget-header .ui-state-focus, .header-style-menu-center .header-main-bg-color-default .primary-nav .primary-menu > li > a:after, .header-style-menu-right .header-main-bg-color-default .primary-nav .primary-menu > li > a:after, .select2-container .ciyashop-select2-dropdown .select2-results .select2-results__options li:hover, .pgs-mtpl-header-wrapper .nav.mtpl-tabs--tabs_style-style-4 li a.active, .pgs-mtpl-header-wrapper .nav.mtpl-tabs--tabs_style-style-4 li a:hover, .widget_product_search button[type=submit], .age-gate-form .age-gate-submit-yes, .age-gate-form .age-gate-submit, .product-summary-actions .sticky-wrapper.is-sticky .woo-product-cart_sticky .added_to_cart:hover, .address-block.flat i, .testimonial-style-2 .testimonials-carousel-nav > div:hover, .testimonial-style-3 .testimonials-carousel-nav > div:hover, .page-links a:hover, .page-links > span.page-number, .blog-entry-quote blockquote, .woocommerce.single-product .sidebar > .pgs_brand_filters .brand-products:hover, .footer-mobile-device .footer-mobile-device-actions > div .ciyashop-wishlist-count, .footer-mobile-device .footer-mobile-device-actions > div .cart-count, .dokan-seller-listing #dokan-seller-listing-wrap li .store-footer input[type="submit"].dokan-btn-theme:hover, .dokan-seller-listing #dokan-seller-listing-wrap li .store-footer a.dokan-btn-theme:hover, .dokan-seller-listing #dokan-seller-listing-wrap li .store-footer .dokan-btn-theme:hover, .dokan-store-sidebar .dokan-widget-area .widget .seller-form .dokan-btn, #asl-storelocator.asl-p-cont.asl-bg-0 #style_0.infoWindow a.action:hover, [class*=col-] > article.post .entry-meta-date a, .masonry-item article.post .entry-meta-date a, .newsletter-wrapper.newsletter-style-3 .newslatter-form .button-area button.submit:hover, .row-background-dark .newsletter-wrapper.newsletter-style-3 .newslatter-form .button-area button.submit, .newsletter-wrapper.newsletter-style-1.newsletter-design-3 .input-area input[type=text], .newsletter-wrapper.newsletter-style-1.newsletter-design-2 .button-area .btn.submit:hover, .row-background-dark .newsletter-wrapper.newsletter-style-1.newsletter-design-1 .button-area .btn.submit:hover, .row-background-dark .newsletter-wrapper.newsletter-style-1.newsletter-design-1 .button-area .btn.submit:focus, .row-background-dark .newsletter-wrapper.newsletter-style-1.newsletter-design-2 .button-area .btn.submit, .row-background-dark .newsletter-wrapper.newsletter-style-1.newsletter-design-2 .button-area .btn.submit:hover, .pgscore_banner-style-deal-1 .deal-counter-wrapper.counter-style-style-10 .countdown li, .pgscore_countdown_wrapper .deal-counter-wrapper.counter-style-style-10 .countdown li, .pgscore_banner-style-deal-1 .deal-counter-wrapper.counter-style-style-6.counter-color-light .countdown li p, .row-background-dark .pgscore_countdown_wrapper .deal-counter-wrapper.counter-style-style-6 .countdown li p, .site-footer .footer-widgets .widget_pgs_newsletter_widget .newsletter_form .button-area .input-group-btn > .btn, .header-search-wrap .search_form-inner.search-bg-theme input.form-control, .header-search-wrap .search_form-inner.search-bg-theme .search_form-category-wrap .select2-container--default .select2-selection--single, .portfolio-content-area.portfolio-style-2 .project-info .category-link, .portfolio-content-area.portfolio-style-3 .project-info .portfolio-control > a, .portfolio-content-area .project-info .portfolio-control > a:hover, .portfolio-content-area.portfolio-style-4 .project-info .portfolio-control > a:hover, .portfolio-section .isotope-filters button.active, .portfolio-section .isotope-filters button.active:hover, .woocommerce-checkout .woocommerce button.button.alt:hover, .woocommerce-checkout-layout-dark.woocommerce-checkout .woocommerce button.button.alt, .woocommerce-cart .cart-collaterals .wc-proceed-to-checkout a.checkout-button, .single-portfolio .navigation .portfolio-arrow, .latest-post-style-4 .latest-post-item .post-date, .latest-post-category span, .row-background-dark .latest-post-wrapper.latest-post-style-1 .latest-post-item .post-date, .row-background-dark .latest-post-wrapper.latest-post-style-2 .latest-post-item .post-date, .woocommerce ul.products li.product.product-category .woocommerce-loop-category__title, .woocommerce-widget-layered-nav .pgs-woocommerce-widget-layered-nav-list .wc-layered-nav-term:hover a > span, .woocommerce-widget-layered-nav .pgs-woocommerce-widget-layered-nav-list .wc-layered-nav-term.woocommerce_item-chosen a > span, .pgs-shop-filter-buttons input.default:checked + .slider, #after_add_to_cart_message-popup .cart_message-inner .view-cart, .woocommerce .product-hover-style-hover-summary .product-action-add-to-cart a, .product-hover-style-icons-left .product-action-add-to-cart .added_to_cart, .product-hover-style-icons-rounded .product-action-add-to-cart .added_to_cart, .product-hover-style-button-standard .product-action-add-to-cart .added_to_cart, .woocommerce .products .product .ciyashop-product-variations-wrapper .single_variation_wrap .button, .woocommerce .products .product .ciyashop-product-variations-wrapper .single_variation_wrap .added_to_cart, .woocommerce .product-hover-style-info-bottom-bar.product-hover-bar-style-flat .product-actions .added_to_cart, .woocommerce ul.products li.product-hover-style-standard-quick-shop .added_to_cart, .pgscore-image-hotspot .dot-style1 span, .hotspot-btn a, .pgscore-image-hotspot .dot-style2:before, .pgscore-hotspot-theme-bg .hotspot-content, .pgscore_divider_style2 .divider-title:after, .pgscore_pricing_wrapper .pgscore-pricing-style-3.active .pricing-title>h2, .pgscore_pricing_wrapper .pgscore-pricing-style-3.active .pricing-order, .pgscore_pricing_wrapper .pgscore-pricing-style-1.active .pricing-order .button, .pgscore_pricing_wrapper .pgscore-pricing-style-2.active .pricing-order .button, .product-category-style-4 .pgs-core-category-container .category-content, .pgscore_pricing_wrapper .pgscore-pricing-style-2 .pricing-order .button:hover, .pgscore_pricing_wrapper .pgscore-pricing-style-3 .pricing-order .button:hover, .pgscore_pricing_wrapper .pgscore-pricing-style-1 .pricing-order .button:hover, .testimonial-style-4 .owl-next:hover, .testimonial-style-4 .owl-prev:hover, .testimonial-style-6 .owl-next:hover, .testimonial-style-6 .owl-prev:hover, table.compare-list .add-to-cart td a, .pgscore_v_menu .pgscore_v_menu-main .slicknav_menu-wrap .slicknav_brand, .pgs-video-info .pgs-video-content .video-popup-btn, .pgs-video-info .pgs-video-content .video-popup-btn.btn-style-type-border:hover, .product-category-items.category-style-theme .pgs-core-category-container .category-content, .product-category-items.category-style-theme .category-img-container .category-overlay, .product-category-items.product-category-style-4.category-style-theme .pgs-core-category-container .category-content, .latest-post-style-5 .latest-post-entry-footer > a:hover:after, .theme-dark.woocommerce-checkout-layout-default .woocommerce #payment #place_order:hover, .theme-dark.woocommerce-checkout-layout-light_spiral .woocommerce #payment #place_order:hover, .comments-area .comment-respond #cancel-comment-reply-link, .select2-container--default .select2-results__option[aria-selected=true], .select2-container--default .select2-results__option[data-selected=true], .faq-layout-layout_1 .accordion .accordion-title a:hover, .wpb-js-composer .content-wrapper .vc_tta-accordion.vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-heading, .wpb-js-composer .content-wrapper .vc_tta-accordion.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-heading:focus, .wpb-js-composer .content-wrapper .vc_tta-accordion.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-heading:hover, .wpb-js-composer .vc_tta.vc_tta-style-classic.vc_tta-tabs .vc_tta-tab.vc_active>a, .wpb-js-composer .content-wrapper .vc_tta.vc_tta-style-classic .vc_tta-tab>a:focus, .wpb-js-composer .content-wrapper .vc_tta.vc_tta-style-classic .vc_tta-tab>a:hover, .wpb-js-composer .content-wrapper .vc_tta.vc_tta-style-modern .vc_tta-panel.vc_active .vc_tta-panel-heading, .wpb-js-composer .content-wrapper .vc_tta.vc_tta-style-modern .vc_tta-panel .vc_tta-panel-heading:hover, .wpb-js-composer .vc_tta.vc_tta-style-modern .vc_tta-panel .vc_tta-panel-heading:focus, .wpb-js-composer .content-wrapper .vc_tta.vc_general.vc_tta-style-modern .vc_tta-tab.vc_active>a, .wpb-js-composer .vc_tta.vc_general.vc_tta-style-modern .vc_tta-tabs-list:before, .wpb-js-composer .content-wrapper .vc_tta.vc_general.vc_tta-style-modern .vc_tta-tab:hover > a, .wpb-js-composer .vc_tta.vc_general.vc_tta-accordion.pgscore_boxed_icon.vc_tta-style-outline .vc_active .vc_tta-panel-title.vc_tta-controls-icon-position-left>a:before, .wpb-js-composer .vc_tta.vc_general.vc_tta-accordion.pgscore_boxed_icon.vc_tta-style-outline .vc_active .vc_tta-panel-title.vc_tta-controls-icon-position-right>a:before, .wpb-js-composer .vc_tta.vc_general.vc_tta-accordion.pgscore_boxed_icon.vc_tta-style-outline .vc_tta-panel-heading:hover .vc_tta-panel-title.vc_tta-controls-icon-position-left>a:before, .wpb-js-composer .vc_tta.vc_general.vc_tta-accordion.pgscore_boxed_icon.vc_tta-style-outline .vc_tta-panel-heading:hover .vc_tta-panel-title.vc_tta-controls-icon-position-right>a:before, body .big-btn,
.pgscore_search_wrapper.header-search-wrap .search_form-inner.search-bg-theme .search_form-category-wrap .select2-container--default .select2-selection--single, .pgscore_search_wrapper.header-search-wrap .search_form-inner.search-bg-theme input.form-control, .loop-header-active-filters .col .ciyashop-clear-filters-wrapp a:hover,
.pgs-content-popup table th,

.header-element-item .button-stype-border.button-color-scheme-theme a:hover,
.header-element-item .button-stype-default.button-color-scheme-theme a,
.header-element-item .search_form-inner.search-bg-theme input.form-control,
.header-element-item .search_form-inner.search-bg-theme .select2-container--default .select2-selection--single,
.header-style-custom .category-nav .category-nav-title,
.woocommerce .woocommerce-MyAccount-content table.shop_table.my_account_orders .button, .woocommerce .woocommerce-MyAccount-content table.shop_table.subscription_details .button, .woocommerce-account .woocommerce-MyAccount-content .woocommerce-EditAccountForm .woocommerce-Button, #ciyashop-360-view > button:hover, .cloudimage-360 > button:hover, .testimonial-style-7 .testimonials-carousel-nav>div:hover, .testimonial-style-5 .testimonials-carousel-nav>div:hover,
.cs-woocompare-popup .cs-product-list-column .add-to-cart .button,
.woocommerce-widget-layered-nav-dropdown .select2-selection--multiple .select2-selection__rendered .select2-selection__choice,
.woocommerce-widget-layered-nav-dropdown .woocommerce-widget-layered-nav-dropdown__submit,
.pgscore_progress_bar_wrapper_inner .progress-bar,
#site-navigation-sticky-mobile .slicknav_nav .current-menu-item > a .slicknav_arrow, 
  #site-navigation-sticky-mobile .slicknav_nav .slicknav_open > a .slicknav_arrow { background: ' . $primary_color . '; }

		/* Text Color */
		a, .bbp-forums a, .sidebar .widget.widget_recent_comments ul li a, .widget.widget_rss ul li a, .stars a:hover, .stars a:focus, div.bbp-template-notice a, .search_form-autocomplete .ui-menu-item:hover a, li.bbp-forum-freshness a:hover, #search_popup .modal-dialog .close:hover, .ciyashop-search-element-popup .modal-dialog .close:hover, li.bbp-reply-topic-title a:hover, a.bbp-topic-permalink:hover, li.bbp-topic-freshness a:hover, a.favorite-toggle:hover, a.subscription-toggle:hover, a.bbp-author-name:hover, .bbp-logged-in h4 a:hover, #bbp-user-navigation ul li a:hover, li.bbp-header div.bbp-topic-content span#subscription-toggle a, li.bbp-header div.bbp-topic-content span#favorite-toggle a, li.bbp-header div.bbp-reply-content span#subscription-toggle a, li.bbp-header div.bbp-reply-content span#favorite-toggle a, span.bbp-admin-links a:hover, .bbp-forum-header a.bbp-forum-permalink, .bbp-topic-header a.bbp-topic-permalink, .bbp-reply-header a.bbp-reply-permalink, body.woocommerce-compare h1, span.bbp-author-ip, #buddypress ul.activity-list li .activity-meta .button:focus, #buddypress ul.activity-list li .activity-meta .button:hover, #buddypress .acomment-options a, #buddypress .acomment-options a, #buddypress a.activity-time-since:hover, .kite-steps-wrapper .step-number, .woocommerce .product-hover-style-image-bottom-bar.product-hover-bar-style-border .product-actions .add_to_cart_button, .woocommerce .product-hover-style-info-bottom-bar.product-hover-bar-style-border .product-actions .add_to_cart_button, .woocommerce .product-hover-style-image-bottom-bar.product-hover-bar-style-border .product-actions .product_type_external, .woocommerce .product-hover-style-info-bottom-bar.product-hover-bar-style-border .product-actions .product_type_external, .woocommerce .product-hover-style-image-bottom-bar.product-hover-bar-style-border .product-actions .added_to_cart, .woocommerce .product-hover-style-info-bottom-bar.product-hover-bar-style-border .product-actions .added_to_cart, .woocommerce .product-hover-style-info-bottom-bar.product-hover-bar-style-border .product-actions .product_type_variable, .woocommerce .product-hover-style-image-bottom-bar .open-quick-view:hover, .woocommerce .product-hover-style-info-bottom .open-quick-view:hover, .woocommerce .product-hover-style-info-bottom-bar .open-quick-view:hover, .product-deal-content-price ins, .category-box h2, .latest-post-item .post-date, .latest-post-item .latest-post-meta ul li a:hover, .latest-post-wrapper.latest-post-style-1 .latest-post-entry-footer > a:hover, .category-box-link ul li a:hover, .common-link ul li a:hover i, .category-box ul li.view-all a, .latest-post-item .blog-title a:hover, .latest-post-social-share ul li a:hover, .pgscore_recent_posts_wrapper .latest-post-type-carousel .latest-post-nav > div, .pgscore_v_menu-main .menu > li > a:hover, .pgscore_info_box-layout-style_4 .pgscore_info_box-step, .testimonial .client-info .author-name, .pgs-mtpl-header-wrapper .nav.mtpl-tabs--tabs_style-style-2 li a.active, .pgs-mtpl-header-wrapper  .nav.mtpl-tabs--tabs_style-style-2 li a:hover, .price ins, ul.pgscore_list i, ul.pgscore_list li a:hover, .testimonial i.fa-quote-left, .row-background-dark .testimonial .slick-slider .slick-prev:hover:before, .row-background-dark .testimonial .slick-slider .slick-next:hover:before, .team .team-description span, .faqs-wrapper .tabs li.active, blockquote i, blockquote .fa, blockquote .quote-author, .page-header h1, .error-block h1, .tc_maintenance_mode-comingsoon .commingsoon_countdown li span, .woocommerce .woocommerce-checkout-review-order-table .order-total td, .woocommerce ul.products.list li.product .product-info .product-actions a, .woocommerce div.product .woocommerce-tabs-layout-default ul.tabs li:hover a, .woocommerce div.product .woocommerce-tabs-layout-default ul.tabs li.active a, .woocommerce div.product p.price, .woocommerce div.product span.price, .woocommerce table.shop_table .product-name a:hover, .woocommerce .woocommerce-error a:hover, .woocommerce .woocommerce-error a:focus, .woocommerce .woocommerce-info a:hover, .woocommerce .woocommerce-info a:focus, .woocommerce .woocommerce-message a:hover, .woocommerce .woocommerce-message a:focus, .woocommerce-account .woocommerce-MyAccount-content legend, article.hentry .entry-title a:hover, .entry-meta ul li a:hover, .entry-meta ul a:hover, .entry-meta ul a:hover span, .related-posts .related-post-info h5 a:hover, ol.commentlist .comment .comments-info span a:hover, .page-links, .widget.widget_recent_comments ul li a:hover, .widget_pgs_featured_products_widget .item-detail .amount, .widget_pgs_bestseller_widget .amount, .widget_products .amount, .widget_pgs_related_widget .amount, .widget_products .amount, .widget_top_rated_products .amount, .widget ul li:hover > a, .widget_archive ul li, .widget_categories ul li .widget_categories-post-count, .widget_search .search-button:hover, .woocommerce ul.cart_list li a:hover, .woocommerce ul.product_list_widget li a:hover, .widget_product_categories ul li .count, .widget_recently_viewed_products .amount, .widget_products .amount, .widget-woocommerce-currency-rates ul.woocs_currency_rates li, .WOOCS_SELECTOR .dd-desc, .woocommerce .widget_shopping_cart .total .amount, .woocommerce.widget_shopping_cart .total .amount, .widget_recent_entries .recent-post .recent-post-info a:hover, .widget_pgs_contact_widget ul li i, .widget_recent_entries .recent-post .recent-post-info .post-date i, .woocommerce div.product .woocommerce-tabs-layout-left ul.tabs li:hover a, .woocommerce div.product .woocommerce-tabs-layout-left ul.tabs li.active a, .blog .timeline > li > .timeline-badge a, .woocommerce .product-hover-style-info-bottom .product-actions a:hover, .inner-intro ul.page-breadcrumb li:hover a, .woocommerce .product-hover-button-style-border .product-actions a:hover, .ciyashop-product-thumbnails .slick-slider .slick-arrow:before, .woocommerce ul.products li.product .product-name a:hover, .header-main-bg-color-default .search_form-search-button:before, .product-deal-content .product-deal-title a:hover, .pgscore_product_showcase_wrapper .right-info .product_type-title a:hover, .pgscore_product_showcase_wrapper .right-info span.price, .woo-tools-action.woo-tools-cart .woocommerce-mini-cart li .quantity .amount, .woocommerce.single-product div.product .cart .group_table tr.product-type-variable > td.label a:hover, .pgscore_banner-text span, .inner-intro ul.page-breadcrumb li.home:hover:before, .inner-intro.woocommerce_inner-intro ul.page-breadcrumb li:hover a, .search_form-search-button:before, .address-block i, .inner-intro ul.page-breadcrumb li.current_item span, .inner-intro .yoast-breadcrumb > span a:hover, .header-style-right-topbar-main .header-main-bg-color-default .primary-nav .primary-menu > li.current-menu-item > a, .header-style-right-topbar-main .header-main-bg-color-default .primary-nav .primary-menu > li.current-menu-ancestor > a, .header-style-menu-center .header-main-bg-color-default .primary-nav .primary-menu > li > a:hover, .header-style-menu-right .header-main-bg-color-default .primary-nav .primary-menu > li > a:hover, .header-style-right-topbar-main .header-main-bg-color-default .primary-nav .primary-menu > li > a:hover, .header-style-menu-center .header-main-bg-color-default .primary-nav .primary-menu > li:hover > a, .header-style-menu-right .header-main-bg-color-default .primary-nav .primary-menu > li:hover > a, .header-style-right-topbar-main .header-main-bg-color-default .primary-nav .primary-menu > li:hover > a, .header-main-bg-color-default .primary-nav .primary-menu > li .sub-menu li.current-menu-item > a, .header-nav-bg-color-default .primary-nav .primary-menu > li .sub-menu li.current-menu-item > a, .header-main-bg-color-default .primary-nav .primary-menu > li .sub-menu li.current-menu-parent > a, .header-nav-bg-color-default .primary-nav .primary-menu > li .sub-menu li.current-menu-parent > a, .site-header .header-main-bg-color-default .primary-nav .primary-menu > li .sub-menu li:hover > a, .site-header .header-nav-bg-color-default .primary-nav .primary-menu > li .sub-menu li:hover > a, .pgscore_opening_hours_wrapper .pgs-opening-hours ul li i, .header-main-bg-color-default .woo-tools-actions > li i:hover, .header-main-bg-color-default .search-button-wrap .search-button:hover, .topbar-link > ul > li a:hover, .header-style-menu-center.header-above-content .topbar-bg-color-default .topbar-link > ul > li a:hover, .header-style-menu-right.header-above-content .topbar-bg-color-default .topbar-link > ul > li a:hover, .widget.pgs_brand_filters .pgs-brand-items li:hover .widget_brand-product-count,
.header-style-menu-center.header-above-content .header-main-bg-color-default .woo-tools-actions > li i:hover, .header-style-menu-right.header-above-content .header-main-bg-color-default .woo-tools-actions > li i:hover, #site-navigation-sticky-mobile .slicknav_nav .current-menu-item > a, #site-navigation-sticky-mobile .slicknav_nav .slicknav_open > a, #site-navigation-sticky-mobile .slicknav_nav .mega-sub-menu li a:hover, #site-navigation-sticky-mobile .slicknav_nav .sub-menu li a:hover, .woocommerce .widget_top_rated_products ul.product_list_widget li ins, #header-sticky-sticky-wrapper .primary-menu > li:before, .header-style-menu-center .header-main-bg-color-custom .primary-nav .primary-menu > li .sub-menu li:hover > a, .primary-nav .primary-nav-wrapper .primary-menu > li .sub-menu li:hover > a, .primary-nav .primary-nav-wrapper .primary-menu > li .sub-menu li.current-menu-ancestor > a, .primary-nav .primary-nav-wrapper .primary-menu > li .sub-menu li.current-menu-parent > a, .primary-nav .primary-nav-wrapper .primary-menu > li .sub-menu li.current-menu-parent > a, .primary-nav .primary-nav-wrapper .primary-menu > li .sub-menu li.current-menu-item > a, .pgs-mtpl-header-wrapper .nav.mtpl-tabs--tabs_style-style-1 li a:hover, .pgs-mtpl-header-wrapper .nav.mtpl-tabs--tabs_style-style-1 li:hover a, .pgs-mtpl-header-wrapper .nav.mtpl-tabs--tabs_style-style-1 li a.active, .widget_product_categories ul > li.current-cat > a, .sidebar .widget_yith_wc_category_accordion .ywcca_category_accordion_widget > li.current-cat > a, .sidebar .widget_yith_wc_category_accordion .ywcca_category_accordion_widget > li.current-cat-parent ul.children li.current-cat > a, .woo-tools-actions > li i:hover, .widget_pgs_bestseller_widget .item-detail h4 a:hover, .widget_pgs_related_widget .item-detail h4 a:hover, .widget_pgs_featured_products_widget .item-detail h4 a:hover, .categories-menu li a:hover, .categories-menu .sub-menu > li a:hover, .testimonial .slick-slider .slick-prev:hover:before, .testimonial .slick-slider .slick-next:hover:before, .address-block span a, .footer-mobile-device .footer-mobile-device-actions > .sticky-footer-active > a, .dokan-category-menu #cat-drop-stack > ul li a:hover, .asl-p-cont a, .hotspot-modal .modal-dialog .public-hotspot-info-holder .public-hotspot-info.public-hotspot-info--highlighted .public-hotspot-info__number, .hotspot-modal .modal-dialog .public-hotspot-info-holder .public-hotspot-info:hover .public-hotspot-info__number, .header-style-menu-center .header-main .primary-menu > li.current-menu-item > a,
.header-style-menu-center .header-main .primary-menu > li.current-menu-ancestor > a, .header-style-menu-right .header-main .primary-menu > li.current-menu-item > a, .header-style-menu-right .header-main .primary-menu > li.current-menu-ancestor > a, .header-style-menu-center.header-above-content .header-main.header-main-bg-color-default .primary-menu > li.current-menu-item > a, .header-style-menu-center.header-above-content .header-main.header-main-bg-color-default .primary-menu > li.current-menu-ancestor > a, .header-style-menu-right.header-above-content .header-main.header-main-bg-color-default .primary-menu > li.current-menu-item > a,
.header-style-menu-right.header-above-content .header-main.header-main-bg-color-default .primary-menu > li.current-menu-ancestor > a, .post-navigation .nav-links .nav-previous a:hover, .post-navigation .nav-links .nav-next a:hover, [class*=col-] > article.post .readmore, [class*=col-] > article.post .readmore:hover, .masonry-item article.post .readmore, .masonry-item article.post .readmore:hover, .entry-header-section .entry-meta-date .entry-date, .woocommerce .product.product-hover-style-default .open-quick-view:hover, .woocommerce .product-hover-style-default.product-hover-button-style-light .product-actions a:hover,
.woocommerce .product-hover-style-default.product-hover-button-style-light .product-actions .product-action-add-to-cart a:hover, .main-navigation-sticky .primary-menu > li .sub-menu li:hover > a, .main-navigation-sticky .primary-menu > li .sub-menu li.current-menu-item > a, .main-navigation-sticky .primary-menu > li .sub-menu li.current-menu-parent > a, .newsletter-wrapper.newsletter-style-1.newsletter-design-4 .button-area:hover:before, .newsletter-wrapper.newsletter-style-1.newsletter-design-4 .button-area .btn.submit:hover, .newsletter-wrapper.newsletter-style-1.newsletter-design-5 .button-area:hover:before,  .newsletter-wrapper.newsletter-style-1.newsletter-design-5 .button-area .btn.submit:hover, .pgscore_banner-style-deal-1 .deal-counter-wrapper.counter-style-style-3 .countdown li span, .pgscore_countdown_wrapper .deal-counter-wrapper.counter-style-style-3 .countdown li span, .pgscore_banner-style-deal-1 .deal-counter-wrapper.counter-style-style-5 .countdown li span, .pgscore_countdown_wrapper .deal-counter-wrapper.counter-style-style-5 .countdown li span, .row-background-dark .newsletter-wrapper.newsletter-style-1.newsletter-design-5 .button-area:hover:before, .newsletter-wrapper.newsletter-style-1.newsletter-design-6 .button-area:hover:before, .newsletter-wrapper.newsletter-style-1.newsletter-design-6 .button-area .btn.submit:hover, .single-product-carousel .product-name a:hover, .woocommerce.single-product div.product .cart .group_table tr.purchasable .woocommerce-grouped-product-list-item__price .amount, .woocommerce.single-product .product-summary-actions .yith-wcwl-add-button .add_to_wishlist:hover, .woocommerce.single-product .product-summary-actions .yith-wcwl-wishlistexistsbrowse > a:hover, .woocommerce.single-product .product-summary-actions .cs-wcwl-add-button .add_to_wishlist:hover, .woocommerce.single-product .product-summary-actions .compare:hover, .woocommerce.single-product .product-summary-actions .compare:before, .woocommerce.single-product .product-summary-actions a.add_to_wishlist:before, .woocommerce.single-product .product-summary-actions .yith-wcwl-wishlistaddedbrowse a:before,
.woocommerce.single-product .product-summary-actions .yith-wcwl-wishlistexistsbrowse a:before, .woocommerce .product-hover-style-image-bottom-2 .product-actions a:hover, .woocommerce ul.products .product-hover-style-image-bottom-2 .product-info .price .amount, .pgscore_v_menu .pgscore_v_menu-main .slicknav_menu-wrap .slicknav_nav > li > a:hover, .pgscore_v_menu .pgscore_v_menu-main .slicknav_menu-wrap .slicknav_nav > li .sub-menu li:hover > a, .woocommerce .product-hover-style-icon-top-left .product-actions a:hover, .header-search-wrap .search_form-inner.search-bg-white .search_form-search-button:before, .woocommerce ul.products.list li.product .price .amount, .ciyashop-popup-quick-view div.product p.price, .portfolio-content-area.portfolio-style-4 .project-info .category-link, .portfolio-content-area.portfolio-style-2 .project-info .entry-title:hover, .portfolio-section .isotope-filters button:hover, .portfolio-content-area .project-info .entry-title a:hover, .woocommerce-checkout-layout-dark.woocommerce-checkout .woocommerce table.shop_table .order-total td, .woocommerce-checkout-layout-dark.woocommerce-checkout .woocommerce .woocommerce-info a, .woocommerce.single-product .ciyashop-sticky-btn .yith-wcwl-wishlistaddedbrowse a:before, .woocommerce.single-product .ciyashop-sticky-btn .yith-wcwl-wishlistexistsbrowse a:before, .woocommerce-account .woocommerce-MyAccount-content p > a:hover, .myaccount-grid-navigation .grid-navigation a:hover i.fa, .woocommerce .woocommerce-MyAccount-content table.shop_table .button, .woocommerce-MyAccount-content table.shop_table a:hover, .woocommerce-categories-wrapper .carousel-wrapper a:hover .woo-category-name, .woocommerce-categories-wrapper .carousel-wrapper a:hover .woo-category-products-count .woo-cat-count, .latest-post-item .latest-post-meta ul li i, .latest-post-style-4 .latest-post-entry-footer > a:hover, .latest-post-style-6 .latest-post-entry-footer > a:hover, .latest-post-style-7 .latest-post-entry-footer > a:hover, .latest-post-style-5 .latest-post-item .post-date .fa, .latest-post-style-5 .latest-post-entry-footer > a:hover, .ciyashop-popup-quick-view .product_title a:hover, .ciyashop-popup-quick-view .product_meta > span a:hover, .woocommerce.single-product .product_meta > span a:hover, .woocommerce-cart .woocommerce .cart-collaterals .cart_totals table .order-total td, .woocommerce-widget-layered-nav .pgs-woocommerce-widget-layered-nav-list .wc-layered-nav-term.woocommerce_item-chosen > .count, .woocommerce-widget-layered-nav .pgs-woocommerce-widget-layered-nav-list .wc-layered-nav-term:hover > .count, .widget_price_filter .price_slider_wrapper .price_slider_amount .button:hover, .sidebar .pgs_shop_filters .pgs-shop-filters-wrapper .shop-filter .button:hover, #after_add_to_cart_message-popup .cart_message-inner a:hover, .side_shopping_cart-wrapper .woocommerce .widget_shopping_cart_content .quantity .amount, .woocommerce .product-hover-button-style-flat .product-actions a:hover, .woocommerce .product-hover-button-style-flat .product-actions .alt:hover, .woocommerce .product-hover-style-icons-top-right .product-actions a:hover,
.woocommerce .product-hover-style-button-standard .product-actions a:hover,
.woocommerce .product-hover-style-icons-left .product-actions a:hover,
.woocommerce .product-hover-style-icons-rounded .product-actions a:hover,
.woocommerce .product-hover-style-icons-bottom-right .product-actions a:hover,
.woocommerce .product-hover-style-hover-summary .product-actions a:hover,
.woocommerce .product-hover-style-minimal-hover-cart .product-actions a:hover,
.woocommerce .product-hover-style-minimal .product-actions a:hover,
.woocommerce ul.products li.product-hover-style-minimal .product-info .product-action-add-to-cart .button,
.woocommerce ul.products li.product-hover-style-minimal .product-info .product-action-add-to-cart .added_to_cart,
.woocommerce .product-hover-style-standard-info-transparent .product-actions a:hover,
.woocommerce .product-hover-style-standard-quick-shop .product-actions a:hover,
.woocommerce ul.products li.product-hover-style-image-left .product-actions a:hover,
.woocommerce ul.products .product-hover-style-image-left .product-action-add-to-cart .button:hover,
.woocommerce ul.products .product-hover-style-image-left .product-action-add-to-cart .added_to_cart:hover,
.woocommerce .products .product-hover-style-image-bottom.product-hover-button-style-border .open-quick-view:hover,
.woocommerce ul.products .product-hover-style-icon-top-left .product-info .price .amount,
.woocommerce ul.products .product-hover-style-icons-top-right .product-info .price .amount,
.woocommerce ul.products li.product-hover-style-image-center .product-actions a:hover,
.woocommerce .products .product.product-hover-style-hover-summary .product-info .product-name a:hover,
.theme-dark .products .product.product-hover-style-hover-summary.product-hover-button-style-light .product-info .product-name a:hover,
.row-background-dark .products .product.product-hover-style-hover-summary.product-hover-button-style-light .product-info .product-name a:hover, .woocommerce .product-hover-style-image-bottom-bar.product-hover-bar-style-border .product-actions .product_type_grouped, .woocommerce .product-hover-style-info-bottom-bar.product-hover-bar-style-border .product-actions .product_type_grouped, .pgscore-hotspot-dark-bg .hotspot-btn a:hover, .pgscore-hotspot-theme-bg .hotspot-btn a, .image-slider-style-2 .about-details .about-des, .pgscore_pricing_wrapper .pgscore-pricing-style-3 .pricing-order .button, .pgscore_pricing_wrapper .pgscore-pricing-style-1.active .pricing-title h2, .pgscore_pricing_wrapper .pgscore-pricing-style-2.active .pricing-prize h2, .pgscore_pricing_wrapper .pgscore-pricing-style-2.active .pricing-prize span, .pgscore_pricing_wrapper .pgscore-pricing-style-2.active .pricing-title h2, .pgscore_pricing_wrapper .pgscore-pricing-style-1.active .pricing-prize h2, .pgscore_pricing_wrapper .pgscore-pricing-style-1.active .pricing-prize span, .ciyashop-popup-quick-view .woocommerce-product-rating .woocommerce-review-link:hover,
.woocommerce.single-product div.product .woocommerce-product-rating .woocommerce-review-link:hover, table.compare-list span.woocommerce-Price-amount.amount, .pgs-video-info .pgs-video-content .video-popup-btn:hover, .product-category-items.category-style-none .pgs-core-category-container .category-content .category-title a:hover,
.product-category-items.category-style-light .pgs-core-category-container .category-content .category-title a:hover,
.product-category-items.category-style-dark .pgs-core-category-container .category-content .category-title a:hover,
.product-category-items.category-style-dark .pgs-core-category-container .category-content .product-count,
.product-category-items.category-style-none .pgs-core-category-container .category-content .product-count, .row-background-light .latest-post-wrapper .latest-post-item .latest-post-meta ul li a:hover,
.row-background-light .latest-post-wrapper .latest-post-item .blog-title a:hover,
.row-background-light .latest-post-wrapper.latest-post-style-1 .latest-post-entry-footer > a:hover, .theme-dark.woocommerce-checkout-layout-light_spiral .woocommerce table.shop_table .order-total td, .woocommerce #reviews #comments ol.commentlist li .comment-text .star-rating:before,
.woocommerce #reviews #comments ol.commentlist li .comment-text .star-rating span, .row-background-light .newsletter-wrapper.newsletter-style-1.newsletter-design-1 .button-area .btn.submit:hover, .row-background-light .newsletter-wrapper.newsletter-style-1.newsletter-design-2 .button-area .btn.submit:hover, .faqs-wrapper .accordion .accordion-content h5, .wpb-js-composer .entry-content .vc_tta.vc_tta-style-flat .vc_tta-panel.vc_active .vc_tta-panel-title>a, .wpb-js-composer .entry-content .vc_tta.vc_tta-style-flat .vc_tta-panel:hover .vc_tta-panel-title>a, .wpb-js-composer .entry-content .vc_tta.vc_general.vc_tta-style-flat .vc_tta-tab.vc_active>a, .wpb-js-composer .entry-content .vc_tta.vc_tta-style-outline .vc_tta-panel.vc_active .vc_tta-panel-title>a, .wpb-js-composer .content-wrapper .vc_tta.vc_tta-style-outline .vc_tta-panel .vc_tta-panel-title>a:hover, .wpb-js-composer .entry-content .vc_tta.vc_general.vc_tta-style-outline .vc_tta-tab.vc_active>a,
.theme-dark #site-navigation-sticky-mobile .slicknav_nav .current-menu-item>a, .theme-dark #site-navigation-sticky-mobile .slicknav_nav .slicknav_open>a, body .ciyashop-demos .demo-count, .latest-post-item .blog-title:hover,
.pgscore_search_wrapper.header-search-wrap .search_form-inner.search-bg-white .search_form-search-button:before,
.ciyashop-popup-quick-view .product-summary-actions .cs-wcwl-add-button .add_to_wishlist:focus,

.header-element-item .header-social_profiles li a:hover,
.header-element-item a:hover,
.header-element-item .button-stype-border.button-color-scheme-theme a,
.header-element-item .button-stype-link.button-color-scheme-theme a,
.header-element-item .button-stype-default.button-color-scheme-theme a:hover,
.header-element-item .button-stype-link.button-color-scheme-theme a:hover,
.header-element-item .search_form-inner.search-bg-white .search_form-search-button:before,
.header-style-custom .header-nav-wrapper .ciyashop-secondary-menu > li:hover > a,
.header-style-custom .header-nav-wrapper .ciyashop-secondary-menu > li > a:hover,
.header-style-custom .header-nav-wrapper .ciyashop-secondary-menu > li .sub-menu li:hover > a,
.header-style-custom .header-nav-wrapper .ciyashop-secondary-menu > li .sub-menu li a:hover,
.header-style-custom .header-nav-wrapper .primary-menu > li.current-menu-item > a,
.header-style-custom .header-nav-wrapper .primary-menu > li.current-menu-ancestor > a,
.header-style-custom .header-nav-wrapper .primary-menu > li:hover > a,
.header-style-custom .header-nav-wrapper .primary-menu > li > a:hover,
.header-style-custom .header-nav-wrapper .primary-menu > li .sub-menu li:hover > a,
.header-style-custom .header-nav-wrapper .primary-menu > li .sub-menu li a:hover,
.header-style-custom .header-nav-wrapper #site-navigation .pgs_megamenu-enable > li .pgs-mega-sub-menu li.current-menu-item > a,
.header-style-custom .language > a:hover,
header.header-style-custom .header-element-item .woo-tools-cart .woocommerce-mini-cart li .quantity .amount,
.site-header .pgs_megamenu-enable > li.pgs-menu-item-mega-menu.pgs-menu-item-color-scheme-dark > .pgs_menu_nav-sublist-dropdown .container > .sub-menu > li > a:hover,
.woocommerce-account .woocommerce-MyAccount-content .order-number,
.woocommerce-account .woocommerce-MyAccount-content .order-date,
.woocommerce-account .woocommerce-MyAccount-content .order-status,
#site-navigation-sticky-mobile .slicknav_nav .current_page_item a,
.main-navigation .current_page_item a, #cs-comparelist .cs-woocompare-popup-header .cs-compare-title,
.woocommerce-checkout .woocommerce .woocommerce-form-coupon-toggle .woocommerce-info:before, 
.woocommerce-checkout .woocommerce .woocommerce-form-login-toggle .woocommerce-info:before,
.woocommerce-categories-wrapper .carousel-wrapper .active-category .woo-category-name,
.woocommerce-categories-wrapper .woocommerce-categories-slider-style-1 .active-category .woo-category-products-count .woo-cat-count,
.woocommerce-categories-wrapper .woocommerce-categories-slider-style-2 .active-category .woo-category-products-count .woo-cat-count,
.woocommerce.single-product div.product form.cart .single_variation_wrap .woocs_price_code .amount,
.woocommerce .widget_shopping_cart .cart_list li .quantity .amount,
.woocommerce.widget_shopping_cart .cart_list li .quantity .amount,
.ciyashop-popup-quick-view div.product form.cart .price { color: ' . $primary_color . '; }


		/* Border */
		input[type=text]:focus, input[type=email]:focus, input[type=search]:focus, input[type=password]:focus, textarea:focus, .widget_tag_cloud .tagcloud a.tag-cloud-link:hover, 	.social-profiles ul li a:hover, .widget_product_tag_cloud .tagcloud a:hover, .pgs-mtpl-header-wrapper .nav.mtpl-tabs--tabs_style-style-3, #buddypress #item-nav .item-list-tabs#object-nav ul li.selected a, #buddypress #item-body .item-list-tabs#subnav ul li.selected a, #buddypress ul.activity-list li .activity-meta .button:focus, #buddypress ul.activity-list li .activity-meta .button:hover, #buddypress div.activity-comments form textarea:focus, .testimonial .testimonial-nav .slick-current img, .faq-layout-layout_1 .accordion .accordion-title a.active, .faq-layout-layout_2 .accordion .accordion-title a.active, .cookies-buttons a.cookies-more-btn:hover, .woocommerce .woocommerce-checkout .input-text:focus, .woocommerce .woocommerce-checkout textarea.input-text:focus, .woocommerce nav.woocommerce-pagination ul li span:hover, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current, .woocommerce form .form-row.woocommerce-validated .select2-container, .woocommerce form .form-row.woocommerce-validated input.input-text, .woocommerce form .form-row.woocommerce-validated select, ul.page-numbers li > span:hover, ul.page-numbers li > a:hover, ul.page-numbers li span.current, .blog .timeline li:hover .timeline-badge, .blog .timeline li:hover .timeline-panel, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .request-box .request-box-form .form-group .wpcf7-form-control:focus, .testimonial-style-3 .owl-carousel .owl-item img, .search_form-keywords-list li a:hover, .product-summary-actions .sticky-wrapper.is-sticky .woo-product-cart_sticky .added_to_cart, .address-block.border i, .testimonial-style-2 .testimonials-carousel-nav > div:hover, .row-background-dark .testimonial.testimonial-style-2 .testimonials-carousel-nav > div:hover, .pgscore_banner-style-deal-1 .deal-counter-wrapper.counter-style-style-3 .countdown li, .pgscore_countdown_wrapper .deal-counter-wrapper.counter-style-style-3 .countdown li, .pgscore_banner-style-deal-1 .deal-counter-wrapper.counter-style-style-4 .countdown, .pgscore_countdown_wrapper .deal-counter-wrapper.counter-style-style-4 .countdown, .pgscore_banner-style-deal-1 .deal-counter-wrapper.counter-style-style-9 .countdown, .pgscore_countdown_wrapper .deal-counter-wrapper.counter-style-style-9 .countdown, .widget_pgs_newsletter_widget .newsletter_form .input-area input.newsletter-email:focus, .myaccount-grid-navigation .grid-navigation a:hover, .woocommerce-categories-wrapper .carousel-wrapper .item a:hover, .woocommerce-categories-wrapper .carousel-wrapper a:hover .woo-category-products-count .woo-cat-count, .woocommerce-widget-layered-nav .pgs-woocommerce-widget-layered-nav-list .wc-layered-nav-term.woocommerce_item-chosen > .count, .woocommerce-widget-layered-nav .pgs-woocommerce-widget-layered-nav-list .wc-layered-nav-term:hover > .count, .pgscore-image-hotspot .dot-style1, .testimonial-style-4 .author-photo > img, .testimonial-style-5 .author-photo > img, .testimonial-style-7 .author-photo .rounded-circle, .testimonial-style-6 .owl-next:hover, .testimonial-style-6 .owl-prev:hover, .pgs-video-info .pgs-video-content .video-popup-btn.btn-style-type-border:hover, .latest-post-style-4 .latest-post-entry-footer > a:hover,
.latest-post-style-6 .latest-post-entry-footer > a:hover, .latest-post-style-7 .latest-post-entry-footer > a:hover, .newsletter-wrapper.newsletter-style-2.newsletter-bg-type-light .input-area input[type=text]:focus, .theme-dark .newsletter-wrapper.newsletter-style-3 .newslatter-form .input-area input[type=text]:focus, .wpb-js-composer .content-wrapper .vc_tta.vc_tta-style-modern .vc_tta-panel.vc_active .vc_tta-panel-heading, .wpb-js-composer .content-wrapper .vc_tta.vc_tta-style-outline .vc_tta-panel-heading:focus .vc_tta-controls-icon::after, .wpb-js-composer .content-wrapper .vc_tta.vc_tta-style-outline .vc_tta-panel-heading:focus .vc_tta-controls-icon::before, .wpb-js-composer .content-wrapper .vc_tta.vc_tta-style-outline .vc_tta-panel-heading:hover .vc_tta-controls-icon::after, .wpb-js-composer .content-wrapper .vc_tta.vc_tta-style-outline .vc_tta-panel-heading:hover .vc_tta-controls-icon::before,
.woocommerce-categories-wrapper .woocommerce-categories-slider-style-2 .active-category .woo-cat-count { border-color: ' . $primary_color . '; }

	/* Background Color Important */
	.snptwdgt-container .snptwdgt__item .snptlinked-item-ico, .ciyashop-buy { background: ' . $primary_color . ' !important; }

	/* SVG Color */
	.woocommerce .gridlist-toggle .gridlist-button:hover svg,
	.woocommerce .gridlist-toggle .gridlist-button.active svg { fill: ' . $primary_color . '; }

	/* Border Color */
	blockquote, .testimonial-style-4 .owl-next:hover, .testimonial-style-4 .owl-prev:hover, .wpb-js-composer .vc_tta.vc_tta-style-flat .vc_tta-panel-heading:hover .vc_tta-controls-icon.vc_tta-controls-icon-plus::after, .wpb-js-composer .vc_tta.vc_tta-style-flat .vc_tta-panel-heading:hover .vc_tta-controls-icon.vc_tta-controls-icon-plus::before, .wpb-js-composer .content-wrapper .vc_tta.vc_tta-style-outline .vc_tta-panel.vc_active .vc_tta-panel-heading, .wpb-js-composer .content-wrapper .vc_tta.vc_tta-style-outline .vc_active .vc_tta-panel-heading .vc_tta-controls-icon::after, .wpb-js-composer .content-wrapper .vc_tta.vc_tta-style-outline .vc_active .vc_tta-panel-heading .vc_tta-controls-icon::before, .wpb-js-composer .content-wrapper .vc_tta.vc_tta-style-outline .vc_tta-panel .vc_tta-panel-heading:focus, .wpb-js-composer .content-wrapper .vc_tta.vc_tta-style-outline .vc_tta-panel .vc_tta-panel-heading:hover, .wpb-js-composer .entry-content .vc_tta.vc_general.vc_tta-style-outline .vc_tta-tab.vc_active>a,
	.woocommerce-categories-wrapper .woocommerce-categories-slider-style-1 .active-category > a,
	.woocommerce-categories-wrapper .woocommerce-categories-slider-style-1 .active-category .woo-cat-count { border-color: ' . $primary_color . '; }

	/* Border Top Color */
	.pgscore-hotspot-theme-bg .hotspot-dropdown-up:after, .pgscore-hotspot-theme-bg .hotspot-dropdown-up:before, .pgscore_pricing_wrapper .pgscore-pricing-style-2.active, .wpb-js-composer .entry-content .vc_tta.vc_general.vc_tta-style-modern.vc_tta-tabs-position-bottom .vc_tta-tab>a { border-top-color: ' . $primary_color . '; }

	/* Border Bottom Color */
	.pgscore-hotspot-theme-bg .hotspot-dropdown-down:after, .pgscore-hotspot-theme-bg .hotspot-dropdown-down:before, .faqs-wrapper .tabs li.active, .wpb-js-composer .content-wrapper .vc_tta.vc_tta-style-modern .vc_tta-panel .vc_tta-panel-heading, .wpb-js-composer .entry-content .vc_tta.vc_general.vc_tta-style-modern .vc_tta-tab>a { border-bottom-color: ' . $primary_color . '; }

	/* Border Left Color */
	.blog .timeline li:hover .timeline-panel:before, .pgscore-hotspot-theme-bg .hotspot-dropdown-left:after, .pgscore-hotspot-theme-bg .hotspot-dropdown-left:before, .wpb-js-composer .vc_tta.vc_general.vc_tta-style-modern.vc_tta-tabs-position-right .vc_tta-tabs-list { border-left-color: ' . $primary_color . '; }

	/* Border Right Color */
	.blog .timeline li:hover .timeline-panel:before, .pgscore-hotspot-theme-bg .hotspot-dropdown-right:after, .pgscore-hotspot-theme-bg .hotspot-dropdown-right:before, .wpb-js-composer .vc_tta.vc_general.vc_tta-style-modern.vc_tta-tabs-position-left .vc_tta-tabs-list { border-right-color: ' . $primary_color . '; }


		';

	if ( empty( $primary_color ) ) {
		$primary_color_customize = '';
	}

	/*
	 * Secondary Color Option
	 */
	$secondary_color_customize = '/* Background Color */
		input[type=submit]:hover, .btn:hover, .btn:focus, .woocommerce #respond input#submit:hover, .yith-woocompare-widget a.compare:hover, button.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, button.submit:hover, input[type=submit]:hover, .woocommerce .widget_shopping_cart .buttons a.checkout, .woocommerce.widget_shopping_cart .buttons a.checkout, .yith-woocompare-widget a.clear-all, .gform_wrapper .gform_page_footer .button.gform_next_button:hover, .gform_wrapper .gform_page_footer .button.gform_previous_button:hover, #bbpress-forums li.bbp-header, #buddypress div.item-list-tabs ul li a:hover span, #buddypress div.item-list-tabs ul li.current a span, #buddypress div.item-list-tabs ul li.selected a span, .bp_members #buddypress ul.button-nav li a:hover, #buddypress div.generic-button a:hover, #buddypress input[type=submit]:hover, .bp_members #buddypress ul.button-nav li.current a, #buddypress div.activity-comments form div.ac-reply-content a, #buddypress #reply-title small a span, #buddypress a.bp-primary-action span, #buddypress .activity #reply-title small a:hover span, #buddypress .activity a.bp-primary-action:hover span, #bbpress-forums #bbp-search-form #bbp_search_submit:hover, #buddypress div.item-list-tabs ul li a, #buddypress div.item-list-tabs ul li span, #buddypress #item-body .profile .bp-widget h2, #buddypress .comment-reply-link:hover, #buddypress .standard-form button:hover, #buddypress a.button:focus, #buddypress a.button:hover, #buddypress div.generic-button a:hover, #buddypress input[type=button]:hover, #buddypress input[type=reset]:hover, #buddypress input[type=submit]:hover, #buddypress ul.button-nav li a:hover, #buddypress ul.button-nav li.current a, #buddypress ul.activity-list li .activity-meta .button:focus, #buddypress ul.activity-list li .activity-meta .button:hover, .woocommerce .product-hover-style-image-bottom-bar .product-actions .product_type_external:hover, .woocommerce .product-hover-style-info-bottom-bar .product-actions .product_type_external:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .yith-woocompare-widget a.compare:hover, button.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, button.submit:hover, input[type=submit]:hover, table.compare-list .add-to-cart .product-action .button:hover, .entry-summary .button.compare, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce button.button.alt.disabled:hover, .error-search-box .search-button:hover, .cookies-buttons a:hover, .woocommerce .products .open-quick-view:hover , .woocommerce.single-product .product-summary-actions .yith-wcwl-wishlistaddedbrowse a:hover, .woocommerce-account .woocommerce-MyAccount-content input.button:hover, article.hentry .readmore, ol.commentlist .comment .comments-info .comment-reply-link:hover, .woocommerce .product-hover-style-info-bottom .product-actions .product_type_external:hover, .pgscore_instagram_v2_wrapper .insta_v2_header--button a:hover, .team .team-description,  .not-found #searchform .search-button:hover, .woocommerce ul.products.list li.product .product-info .product-actions .product-action-add-to-cart a:hover, .request-box .request-box-form .wpcf7-submit, .woocommerce .products .product-hover-button-style-border .open-quick-view:hover, .pgs-mtpl-header-wrapper .nav.mtpl-tabs--tabs_style-style-4 li a, .widget_product_search button[type=submit]:hover, .age-gate-form .age-gate-submit-no, .woocommerce.single-product .sidebar > .pgs_brand_filters .brand-products, .dokan-seller-listing #dokan-seller-listing-wrap li .store-footer input[type="submit"].dokan-btn-theme, .dokan-seller-listing #dokan-seller-listing-wrap li .store-footer a.dokan-btn-theme, .dokan-seller-listing #dokan-seller-listing-wrap li .store-footer .dokan-btn-theme, .dokan-store-sidebar .dokan-widget-area .widget .seller-form .dokan-btn:hover, #asl-storelocator.asl-p-cont.asl-bg-0 .col-xs-12.search_filter p:last-child > span, #asl-storelocator.asl-p-cont.asl-bg-0 .col-xs-12.search_filter .asl-store-search > span, #asl-storelocator.asl-p-cont.asl-bg-0 #style_0.infoWindow h3, .testimonial-style-3 .testimonials-carousel-nav > div, .row-background-light .wpcf7-form input.wpcf7-submit, .portfolio-content-area.portfolio-style-2 .project-info .portfolio-control > a:hover,  .portfolio-content-area.portfolio-style-3 .project-info .portfolio-control > a:hover, .woocommerce-checkout .woocommerce button.button.alt, .woocommerce-cart .cart-collaterals .wc-proceed-to-checkout a.checkout-button:hover, .shop-off_canvas_sidebar .sidebar .sidebar-widget-heading .close-sidebar-widget:after, .shop-off_canvas_sidebar .sidebar .sidebar-widget-heading .close-sidebar-widget:before, #after_add_to_cart_message-popup .cart_message-inner .view-cart:hover, .side_shopping_cart-wrapper .close-side_shopping_cart:after, .side_shopping_cart-wrapper .close-side_shopping_cart:before, .hotspot-btn a:hover, .pgscore-hotspot-theme-bg .hotspot-btn a:hover,  table.compare-list .add-to-cart td a:hover, .wpb-js-composer .vc_tta.vc_general.vc_tta-accordion.pgscore_boxed_icon.vc_tta-style-outline .vc_tta-panel-title.vc_tta-controls-icon-position-left>a:before, .wpb-js-composer .vc_tta.vc_general.vc_tta-accordion.pgscore_boxed_icon.vc_tta-style-outline .vc_tta-panel-title.vc_tta-controls-icon-position-right>a:before, .woocommerce .woocommerce-MyAccount-content table.shop_table.my_account_orders .button:hover, .woocommerce .woocommerce-MyAccount-content table.shop_table.subscription_details .button:hover, .woocommerce-account .woocommerce-MyAccount-content .woocommerce-EditAccountForm .woocommerce-Button:hover, #pgs_login_form .modal-header, .testimonial-style-7 .testimonials-carousel-nav>div, .testimonial-style-5 .testimonials-carousel-nav>div,
		.cs-woocompare-popup .cs-product-list-column .add-to-cart .button:hover,
		.woocommerce-widget-layered-nav-dropdown .woocommerce-widget-layered-nav-dropdown__submit:hover { background: ' . $secondary_color . '; }

		/* Text Color */
		h1, h2, h3, h4, h5, h6, table th, a:focus, a:hover, .bbp-forums a:hover, .widget.widget_rss ul li a:hover, .widget.widget_rss ul li .rss-date, .error-block p, .search-results .page-header .page-title, article.hentry .entry-title, div.bbp-template-notice, div.indicator-hint, div.bbp-template-notice a:hover, li.bbp-forum-freshness a, li.bbp-reply-topic-title a, a.bbp-topic-permalink, li.bbp-topic-freshness a, a.favorite-toggle, a.subscription-toggle, a.bbp-author-name, .bbp-logged-in h4 a, #bbp-user-navigation ul li a, .bbp-forum-header a.bbp-forum-permalink:hover, .bbp-topic-header a.bbp-topic-permalink:hover, .bbp-reply-header a.bbp-reply-permalink:hover, #buddypress #item-nav .item-list-tabs#object-nav ul li a, #buddypress .acomment-options a:hover, #buddypress .acomment-options a:hover, #buddypress .activity-list li.load-more a, #buddypress .activity-list li.load-newest a, #buddypress div#message p, #sitewide-notice p, .kite-steps-wrapper .step-content .step-title, .woocommerce ul.products li.product .price .amount, .product-deal-content .product-deal-title, .category-box span.subhead, .woocommerce .products .product-name a, .countdown li, .category-box ul li.view-all a:hover, .latest-post-item .blog-title a, .ciyashop-popup-quick-view .product_title a, .pgs-mtpl-header-wrapper .nav li a, .team.shadow .team-description h4, .faqs-wrapper .tabs li, .faq-layout-layout_1 .accordion .accordion-title a, .faq-layout-layout_2 .accordion .accordion-title a, .faq-layout-layout_1 .accordion .accordion-title a:after, .faq-layout-layout_2 .accordion .accordion-title a:after, mark, .tc_maintenance_mode-comingsoon .commingsoon_countdown li, .loop-header-active-filters .widget_layered_nav_filters ul li a, .loop-header-active-filters .widget_layered_nav_filters ul li a:before, .woocommerce .gridlist-toggle a, .woocommerce nav.woocommerce-pagination ul li span, .woocommerce nav.woocommerce-pagination ul li a, .woocommerce div.product .woocommerce-tabs-layout-default ul.tabs li a, .woocommerce #reviews .comment-reply-title, .product-nav-content .product-nav-content-title a, .product-nav-content .product_nav_title, .woocommerce table.shop_table th, .woocommerce-cart .cart-collaterals .cart_totals table th, form.woocommerce-checkout #payment ul.payment_methods li, form.woocommerce-checkout #payment ul.payment_methods li div.payment_box, article.hentry .entry-title a, article.hentry .entry-footer .share ul li a:hover, ul.page-numbers li > span, ul.page-numbers li > a, .comments-area h3.comments-title, .author-info .author-description h2, .author-info .author-description h5, .woocommerce ul.cart_list li a, .woocommerce ul.product_list_widget li a, .widget-woocommerce-currency-rates ul.woocs_currency_rates li strong, .woocommerce-currency-switcher-form a.dd-selected:not([href]):not([tabindex]), .woocommerce .widget_shopping_cart .total strong, .woocommerce.widget_shopping_cart .total strong, .kite-images-actions .kite-btn:hover, .woocommerce div.product .woocommerce-tabs-layout-left ul.tabs li a, .blog .timeline li.entry-date span, .blog .timeline li.entry-date-bottom a,  table.compare-list th, table.compare-list tr.image th, table.compare-list tr.image td, table.compare-list tr.title th, table.compare-list tr.title td, table.compare-list tr.price th, table.compare-list tr.price td, .product-deal-content .product-deal-title a, .pgscore_product_showcase_wrapper .right-info .product_type-title a, .woocommerce.single-product div.product .cart .group_table tr.product-type-variable > td.label a, .woocommerce .summary .share-wrapper .share-label, .sidebar .widget_yith_wc_category_accordion .ywcca_category_accordion_widget > li.opened > a,  .woocommerce.single-product div.product form.cart .variations td label, .woocommerce .woocommerce-error a, .woocommerce .woocommerce-info a, .woocommerce table.shop_table_responsive tr td::before, .woocommerce-page table.shop_table_responsive tr td::before, .woocommerce .cart-empty, .woocommerce table.wishlist_table tbody td:before, .testimonial.testimonial-style-3 p, .search_form-keywords-title, .woocommerce .products .product-hover-button-style-border .open-quick-view, .pgscore_opening_hours_wrapper .pgs-opening-hours ul li, .widget_pgs_bestseller_widget .item-detail h4 a, .widget_pgs_related_widget .item-detail h4 a, .widget_pgs_featured_products_widget .item-detail h4 a, .testimonial.testimonial-style-1 p, .testimonial.testimonial-style-2 p, .woocommerce-cart .cart-collaterals .cart_totals table td, .search_form-autocomplete .ui-menu-item a, .footer-mobile-device .footer-mobile-device-actions > div a, .dokan-store-sidebar .dokan-widget-area .widget .widget-title, .dokan-category-menu #cat-drop-stack > ul li a, #asl-storelocator.asl-p-cont.asl-bg-0 .search_filter > p:first-child, #asl-storelocator.asl-p-cont.asl-bg-0 .btn-default, #asl-storelocator.asl-p-cont.asl-bg-0 .panel-inner .item .addr-sec .p-title, #asl-storelocator.asl-p-cont.asl-bg-0 #style_0.infoWindow a.action, .post-navigation .nav-links .nav-previous a, .post-navigation .nav-links .nav-next a,  .widget_calendar .calendar_wrap table th, .single-post article.hentry .entry-footer .share .share-button, article.hentry .entry-footer .share .share-button, .widget_recent_entries .recent-post .recent-post-info a, .newsletter-wrapper.newsletter-style-1.newsletter-design-4 .button-area .btn.submit, .newsletter-wrapper.newsletter-style-1.newsletter-design-4 .button-area:before,  .pgscore_product_showcase_wrapper .owl-carousel .owl-nav > div i, .widget .owl-carousel .owl-nav > div i, .single-product-carousel .product-name a, .woocommerce.single-product div.product .cart .group_table tr.purchasable .woocommerce-grouped-product-list-item__label label a, .woocommerce.single-product .product-summary-actions .yith-wcwl-add-button .add_to_wishlist, .woocommerce.single-product .product-summary-actions .yith-wcwl-wishlistexistsbrowse > a, .woocommerce.single-product .product-summary-actions .compare, .header-search-wrap .search_form-inner.search-bg-white input.form-control, .portfolio-content-area.portfolio-style-2 .project-info .entry-title, .portfolio-content-area.portfolio-style-3 .project-info .entry-title, .portfolio-content-area.portfolio-style-4 .project-info .entry-title, .woocommerce-checkout #order_review_heading, .woocommerce-checkout .woocommerce table.shop_table thead th, .woocommerce .woocommerce-checkout-review-order-table .cart-subtotal th, .woocommerce .woocommerce-checkout-review-order-table .order-total th, .woocommerce .woocommerce-checkout-review-order-table .cart-subtotal td, .woocommerce-checkout .woocommerce .woocommerce-info, .woocommerce-cart .woocommerce .cart-collaterals .cart_totals table .cart-subtotal td, .woocommerce.single-product .ciyashop-sticky-btn .ciyashop-sticky-btn-cart .compare:before, .woocommerce.single-product .ciyashop-sticky-btn .ciyashop-sticky-btn-cart .add_to_wishlist:before, .woocommerce.single-product .ciyashop-sticky-btn .yith-wcwl-wishlistaddedbrowse a:before, .woocommerce.single-product .ciyashop-sticky-btn .yith-wcwl-wishlistexistsbrowse a:before, .woocommerce-account .woocommerce-MyAccount-navigation > ul li.is-active a, .woocommerce-account .woocommerce-MyAccount-navigation > ul li a:hover, .woocommerce-account .woocommerce-MyAccount-content p > a, .myaccount-grid-navigation .grid-navigation a span, .woocommerce-MyAccount-content table.shop_table a, .woocommerce .woocommerce-MyAccount-content table.shop_table .button:hover, .woocommerce-account .woocommerce-MyAccount-content .woocommerce-EditAccountForm legend, .latest-post-style-6 .latest-post-item .post-date,    .ciyashop-popup-quick-view .product_meta > span label, .woocommerce.single-product .product_meta > span label, .ciyashop-popup-quick-view .product_meta > .yith-wcbr-brands, .woocommerce.single-product .product_meta > .yith-wcbr-brands, .widget_price_filter .price_slider_wrapper .price_slider_amount .button, .sidebar .pgs_shop_filters .pgs-shop-filters-wrapper .shop-filter .button, .shop-off_canvas_sidebar .sidebar .sidebar-widget-heading .close-sidebar-widget, #after_add_to_cart_message-popup .cart_message-inner a, .progress-bar .progress_bar_type_value, .progress-title, .testimonial p, .row-background-dark .pgscore_pricing_wrapper .pgscore-pricing-style-2 .pricing-title h2, .row-background-dark .pgscore_pricing_wrapper .pgscore-pricing-style-3 .pricing-title h2, .row-background-dark .pgscore_pricing_wrapper .pgscore-pricing-style-1 .pricing-title h2, table.compare-list tbody th, .ciyashop-popup-quick-view .product_title, .sidebar .pgs-opening-hours ul li, .wpb-js-composer .content-wrapper .vc_tta-accordion.vc_tta-style-classic .vc_tta-panel .vc_tta-panel-title>a, .wpb-js-composer .vc_tta.vc_tta-style-classic.vc_tta-tabs .vc_tta-tab>a, .wpb-js-composer .content-wrapper .vc_general.vc_tta-style-modern .vc_tta-panel .vc_tta-panel-title>a, .wpb-js-composer .entry-content .vc_tta.vc_general.vc_tta-style-modern .vc_tta-tab>a, .wpb-js-composer .entry-content .vc_tta-accordion.vc_tta-style-flat .vc_tta-panel .vc_tta-panel-title>a, .wpb-js-composer .entry-content .vc_tta.vc_general.vc_tta-style-flat .vc_tta-tab>a, .wpb-js-composer .entry-content .vc_tta.vc_tta-style-outline .vc_tta-panel .vc_tta-panel-title>a, .wpb-js-composer .entry-content .vc_tta.vc_general.vc_tta-style-outline .vc_tta-tab>a, .inner-intro.woocommerce_inner-intro h1, .inner-intro.woocommerce_inner-intro ul.page-breadcrumb li, .inner-intro.woocommerce_inner-intro ul.page-breadcrumb li a, .inner-intro.woocommerce_inner-intro ul.page-breadcrumb li>span, .woocommerce.single-product .summary .woo-product-countdown li span, .latest-post-item .blog-title, .woo-tools-action.woo-tools-cart .widget_shopping_cart, .woocommerce.single-product div.product form.cart .variations td .reset_variations, .ciyashop-popup-quick-view div.product form.cart .variations td.label, .ciyashop-popup-quick-view form.cart .variations td .reset_variations, .loop-header-active-filters .col .ciyashop-clear-filters-wrapp a, .woocommerce.single-product .content-wrapper #ciyashop_breadcrumbs li.current-item .item-element, .woocommerce.single-product .product-summary-actions .cs-wcwl-add-button .add_to_wishlist, .woocommerce.widget_shopping_cart .cart_list li .variation dt, .woocommerce #reviews #comments h2, .woocommerce #reviews .comment-reply-title, .woocommerce table.shop_table td .variation dt, .woocommerce table.shop_table td .variation dd, #cs-comparelist .cs-compare-list-wrapper .cs-compare-list-header .cs-compare-list-title, #cs-comparelist .cs-woocompare-popup-header .close-model, form.woocommerce-checkout #payment ul.payment_methods li.woocommerce-notice { color: ' . $secondary_color . '; }

		/* Border Color */
		.addclass, .testimonial-style-4 .owl-next, .testimonial-style-4 .owl-prev, .testimonial-style-6 .owl-next, .testimonial-style-6 .owl-prev, .wpb-js-composer .content-wrapper .vc_tta-accordion.vc_tta-style-classic .vc_tta-controls-icon::after, .wpb-js-composer .content-wrapper .vc_tta-accordion.vc_tta-style-classic .vc_tta-controls-icon::before, .wpb-js-composer .vc_tta-accordion.vc_tta-style-classic .vc_tta-controls-icon.vc_tta-controls-icon-plus::after, .wpb-js-composer .vc_tta-accordion.vc_tta-style-classic .vc_tta-controls-icon.vc_tta-controls-icon-plus::before, .wpb-js-composer .vc_tta.vc_general.vc_tta-style-modern .vc_tta-controls-icon::after, .wpb-js-composer .vc_tta.vc_general.vc_tta-style-modern .vc_tta-controls-icon::before, .wpb-js-composer .entry-content .vc_tta-accordion.vc_tta-style-flat .vc_active .vc_tta-panel-heading .vc_tta-controls-icon::after, .wpb-js-composer .entry-content .vc_tta-accordion.vc_tta-style-flat .vc_active .vc_tta-panel-heading .vc_tta-controls-icon::before, .wpb-js-composer .entry-content .vc_tta-accordion.vc_tta-style-flat .vc_tta-controls-icon::after, .wpb-js-composer .entry-content .vc_tta-accordion.vc_tta-style-flat .vc_tta-controls-icon::before, .wpb-js-composer .content-wrapper .vc_tta.vc_tta-style-outline .vc_tta-panel .vc_tta-panel-heading, .wpb-js-composer .entry-content .vc_tta-accordion.vc_tta-style-outline .vc_tta-controls-icon::after, .wpb-js-composer .entry-content .vc_tta-accordion.vc_tta-style-outline .vc_tta-controls-icon::before, .wpb-js-composer .entry-content .vc_tta.vc_general.vc_tta-style-outline .vc_tta-tab>a, .testimonial-style-2 .testimonials-carousel-nav>div { border-color: ' . $secondary_color . '; }

		/* Border Top Color */
		.header-search-wrap .search_form-inner.search-bg-white .search_form-category-wrap .select2-container--default .select2-selection--single .select2-selection__arrow bm { border-top-color: ' . $secondary_color . '; }

		/* Border Bottom Color */
		.header-search-wrap .search_form-inner.search-bg-white .search_form-category-wrap .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b { border-bottom-color: ' . $secondary_color . '; }

		/* Border left Color */
		.addclass { border-left-color: ' . $secondary_color . '; }


		/* Border Right Color */
		.addclass { border-right-color: ' . $secondary_color . '; }

		';

	if ( empty( $secondary_color ) ) {
		$secondary_color_customize = '';
	}

	/*
	 * Tertiary Color Option
	 */
	$tertiary_color_customize = '
	/* Text Color */
	body, input[type=text], input[type=email], input[type=search], input[type=password], textarea, .gform_wrapper.gf_browser_chrome select, .search-results .page-header .page-title span, #bbpress-forums .status-closed, #bbpress-forums .status-closed a, #bbpress-forums fieldset.bbp-form textarea, #bbpress-forums fieldset.bbp-form select, #bbpress-forums fieldset.bbp-form input, span.bbp-admin-links a, span.bbp-admin-links, #buddypress div#item-header div#item-meta, #buddypress #item-body .item-list-tabs#subnav ul li a, #buddypress .dir-search input[type=search], #buddypress .dir-search input[type=text], #buddypress .groups-members-search input[type=search], #buddypress .groups-members-search input[type=text], #buddypress .standard-form input[type=color], #buddypress .standard-form input[type=date], #buddypress .standard-form input[type=datetime-local], #buddypress .standard-form input[type=datetime], #buddypress .standard-form input[type=email], #buddypress .standard-form input[type=month], #buddypress .standard-form input[type=number], #buddypress .standard-form input[type=password], #buddypress .standard-form input[type=range], #buddypress .standard-form input[type=search], #buddypress .standard-form input[type=tel], #buddypress .standard-form input[type=text], #buddypress .standard-form input[type=time], #buddypress .standard-form input[type=url], #buddypress .standard-form input[type=week], #buddypress .standard-form select, #buddypress .standard-form textarea, #buddypress .field-visibility-settings, #buddypress .field-visibility-settings-notoggle, #buddypress .field-visibility-settings-toggle, #buddypress #item-body #activity-filter-select #activity-filter-by, #buddypress form#whats-new-form textarea, #buddypress a.activity-time-since, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset], #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, #buddypress .activity-list .activity-content .activity-header, #buddypress .activity-list .activity-content .comment-header, #buddypress .dir-search input[type=search], #buddypress .dir-search input[type=text], #buddypress .groups-members-search input[type=search], #buddypress .groups-members-search input[type=text], #buddypress .standard-form input[type=color], #buddypress .standard-form input[type=date], #buddypress .standard-form input[type=datetime-local], #buddypress .standard-form input[type=datetime], #buddypress .standard-form input[type=email], #buddypress .standard-form input[type=month], #buddypress .standard-form input[type=number], #buddypress .standard-form input[type=password], #buddypress .standard-form input[type=range], #buddypress .standard-form input[type=search], #buddypress .standard-form input[type=tel], #buddypress .standard-form input[type=text], #buddypress .standard-form input[type=time], #buddypress .standard-form input[type=url], #buddypress .standard-form input[type=week], #buddypress .standard-form select, #buddypress .standard-form textarea, #buddypress div.activity-comments div.acomment-meta, #buddypress div.item-list-tabs ul li.last select, #buddypress #item-body .profile .bp-widget .profile-fields td, .drag-drop .drag-drop-inside p, #buddypress div.pagination, select, #buddypress table.forum tr.alt td, #buddypress table.messages-notices tr.alt td, #buddypress table.notifications tr.alt td, #buddypress table.notifications-settings tr.alt td, #buddypress table.profile-fields tr.alt td, #buddypress table.profile-settings tr.alt td, #buddypress table.wp-profile-fields tr.alt td, #buddypress .standard-form .checkbox label, #buddypress .standard-form .radio label, .kite-steps-wrapper .step-content .step-description, .category-box-link ul li a i, .latest-post-item .latest-post-meta ul li, .latest-post-item .latest-post-meta ul li a, .latest-post-wrapper.latest-post-style-1 .latest-post-entry-footer > a, .woocommerce ul.products li.product .price del .amount, .woocommerce ul.products li.product .price, .products-listing-intro-wrapper .products-listing-nav > div, .products-listing-header .products-listing-nav > div, .product-deals-content-wrapper .product-deals-nav > div, .category-box-link ul li a, .latest-post-social-share ul li a, .mtpl-arrows .mtpl-arrow > div, .quantity-button, .quantity input, .pgscore_v_menu-main .menu > li > a, .testimonial, .team.shadow .team-description span, .cookies-buttons a.cookies-more-btn, .widget select, .select2-container--default .select2-selection--single .select2-selection__placeholder, .select2-container--default .select2-selection--single .select2-selection__rendered, .loop-header-tools, .product-nav-btn .product-nav-arrow, .woocommerce table.shop_table .product-name a, .related-posts .related-post-info h5 a, ol.commentlist .comment .comments-info span a, ol.commentlist .comment .comments-info span, .author-info .author-description p, .widget.widget_recent_comments ul li a, .widget_pgs_featured_products_widget .item-detail del .amount, .widget_pgs_bestseller_widget .item-detail del .amount, .widget ul li a, .widget_archive ul li:before, .widget_meta ul li:before, table caption, .widget_rss ul li, .widget_search .search-button, .widget_tag_cloud .tagcloud a.tag-cloud-link, .widget_product_tag_cloud .tagcloud a, .widget_pgs_contact_widget ul li, table.compare-list .remove td a, .woocommerce.single-product div.product .woocommerce-product-rating .woocommerce-review-link, .woocommerce #reviews #comments ol.commentlist li .meta, .pgscore_product_showcase_wrapper .right-info span.price del, .widget_recently_viewed_products del .amount, .widget_products del .amount, .widget_top_rated_products del .amount, .search_form-keywords-list li a, .widget_pgs_bestseller_widget del .amount, .widget_products del .amount, .widget_pgs_related_widget del .amount, .widget_products del .amount, ul.pgscore_list li a,  .pgs-qrcode-wrapper.pgs-qrcode-style-popup .pgs-qrcode-popup-link .pgs-qrcode-desc, .product-size-guide .open-product-size-guide, .addr-sec p, body .dokan-single-store .dokan-store-tabs ul li a, .woocommerce-account .woocommerce-MyAccount-navigation > ul li a, .latest-post-style-4 .latest-post-entry-footer > a, .latest-post-style-6 .latest-post-entry-footer > a, .latest-post-style-7 .latest-post-entry-footer > a, .latest-post-style-5 .latest-post-entry-footer > a, .latest-post-style-7 .latest-post-item .latest-post-meta ul li, .woocommerce.single-product .product_meta > span, .ciyashop-popup-quick-view .product_meta > span, .ciyashop-popup-quick-view .product_meta > .yith-wcbr-brands span, .woocommerce.single-product .product_meta > .yith-wcbr-brands span, .woocommerce-cart .woocommerce .cart-collaterals .cart_totals table td, .woocommerce-widget-layered-nav .pgs-woocommerce-widget-layered-nav-list > .wc-layered-nav-term .woocommerce_attribute_item-title, .woocommerce-widget-layered-nav .pgs-woocommerce-widget-layered-nav-list .wc-layered-nav-term > .count, .pgscore-hotspot-dark-bg .hotspot-content-text, .pgscore_pricing_wrapper .pgscore-pricing-style-1, .pgscore_pricing_wrapper .pgscore-pricing-style-3, .widget_archive ul li:before, .widget_categories ul li:before, .widget_meta ul li:before, .widget_pages ul li:before, .yith-woocompare-widget ul.products-list li .remove:before, .wpb-js-composer .entry-content .vc_tta.vc_general.vc_tta-style-flat .vc_tta-panel-body, .woocommerce.single-product .summary .woo-product-countdown li p, .loop-header-tools .ciyashop-show-shop-sidebar .ciyashop-show-shop-btn, .woocommerce-categories-wrapper .carousel-wrapper .woo-category-name, .woocommerce-categories-wrapper .carousel-wrapper .woo-category-products-count .woo-cat-count, .testimonial .client-info .author-name+span:before, .loop-header-tools .ciyashop-products-per-page .per-page-title, .loop-header-tools .ciyashop-products-per-page .per-page-variation { color: ' . $tertiary_color . '; }

	/* Border Color */
	.addclass { border-color: ' . $tertiary_color . '; }

	/* Border Top Color */
	.select2-container--default .select2-selection--single .select2-selection__arrow b { border-top-color: ' . $tertiary_color . '; }

	/* Border Bottom Color */
	.select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b { border-bottom-color: ' . $tertiary_color . '; }

	 ';

	if ( empty( $tertiary_color ) ) {
		$tertiary_color_customize = '';
	}

	$header_customize = '';

	if ( ! empty( $header_schema ) && is_array( $header_schema ) ) {

		foreach ( $header_schema as $key => $value ) {

			if ( 'menu_color_schema' === (string) $key ) {

				foreach ( $value as $menu_position => $menu_color ) {
					foreach ( $menu_color as $menu_color_key => $menu_color_value ) {
						foreach ( $menu_color_value as $menu_key => $menu_value ) {

							$menu_colors_color        = '';
							$menu_link_color          = '';
							$menu_link_hover_color    = '';
							$menu_link_hover_bg_color = '';

							if ( isset( $menu_value['menu_colors_color'] ) && ! empty( $menu_value['menu_colors_color'] ) ) {
								$menu_colors_color = 'color: ' . $menu_value['menu_colors_color'] . ';';
							}

							if ( isset( $menu_value['menu_colors_link_color'] ) && ! empty( $menu_value['menu_colors_link_color'] ) ) {
								$menu_link_color = 'color: ' . $menu_value['menu_colors_link_color'] . ';';
							}

							if ( isset( $menu_value['menu_colors_hover_color'] ) && ! empty( $menu_value['menu_colors_hover_color'] ) ) {
								$menu_link_hover_color    = 'color: ' . $menu_value['menu_colors_hover_color'] . ';';
								$menu_link_hover_bg_color = 'background-color: ' . $menu_value['menu_colors_hover_color'] . ';';
							}

							if ( ! empty( $menu_colors_color ) ) {
								$header_customize .= '
								.header-' . $menu_position . '-' . $menu_color_key . ' .category-nav-title { ' . $menu_colors_color . ' }';
							}

							if ( ! empty( $menu_link_color ) ) {
								$header_customize .= '
								.header-' . $menu_position . '-' . $menu_color_key . ' .element-item-' . $menu_key . ' .category-nav-wrapper .categories-menu > li > a,
								.header-' . $menu_position . '-' . $menu_color_key . ' .element-item-' . $menu_key . ' .category-nav-wrapper .categories-menu .sub-menu > li a,
								.header-' . $menu_position . '-' . $menu_color_key . ' .element-item-' . $menu_key . ' .header-nav-wrapper .ciyashop-secondary-menu > li > a { ' . $menu_link_color . ' }';
							}

							if ( ! empty( $menu_link_hover_color ) ) {
								$header_customize .= '
								.header-' . $menu_position . '-' . $menu_color_key . ' .element-item-' . $menu_key . ' .header-nav-wrapper .ciyashop-secondary-menu > li:hover > a,
								.header-' . $menu_position . '-' . $menu_color_key . ' .element-item-' . $menu_key . ' .header-nav-wrapper .ciyashop-secondary-menu > li > a:hover,
								.header-' . $menu_position . '-' . $menu_color_key . ' .element-item-' . $menu_key . ' .category-nav-wrapper .categories-menu li:hover > a,
								.header-' . $menu_position . '-' . $menu_color_key . ' .element-item-' . $menu_key . ' .category-nav-wrapper .categories-menu li a:hover,
								.header-' . $menu_position . '-' . $menu_color_key . ' .element-item-' . $menu_key . ' .category-nav-wrapper .categories-menu .sub-menu > li a:hover { ' . $menu_link_hover_color . ' }';
							}
						}
					}
				}
			} elseif ( 'primary_menu_color_schema' === (string) $key ) {

				foreach ( $value as $menu_position => $menu_color ) {
					foreach ( $menu_color as $menu_color_key => $menu_color_value ) {
						foreach ( $menu_color_value as $menu_key => $menu_value ) {
							$menu_colors_color        = '';
							$menu_link_color          = '';
							$menu_link_hover_color    = '';
							$menu_link_hover_bg_color = '';

							if ( isset( $menu_value['menu_colors_color'] ) && ! empty( $menu_value['menu_colors_color'] ) ) {
								$menu_colors_color = 'color: ' . $menu_value['menu_colors_color'] . ';';
							}

							if ( isset( $menu_value['menu_colors_link_color'] ) && ! empty( $menu_value['menu_colors_link_color'] ) ) {
								$menu_link_color = 'color: ' . $menu_value['menu_colors_link_color'] . ';';
							}

							if ( isset( $menu_value['menu_colors_hover_color'] ) && ! empty( $menu_value['menu_colors_hover_color'] ) ) {
								$menu_link_hover_color    = 'color: ' . $menu_value['menu_colors_hover_color'] . ';';
								$menu_link_hover_bg_color = 'background-color: ' . $menu_value['menu_colors_hover_color'] . ';';
							}

							if ( ! empty( $menu_link_color ) ) {
								$header_customize .= '
								.header-' . $menu_position . '-' . $menu_color_key . ' .element-item-' . $menu_key . ' .header-nav-wrapper .primary-menu > li > a { ' . $menu_link_color . ' }';
							}

							if ( ! empty( $menu_link_hover_color ) ) {
								$header_customize .= '
								.header-' . $menu_position . '-' . $menu_color_key . ' .element-item-' . $menu_key . ' .header-nav-wrapper .primary-menu > li:hover > a,
								.header-' . $menu_position . '-' . $menu_color_key . ' .element-item-' . $menu_key . ' .header-nav-wrapper .primary-menu > li.current-menu-item > a,
								.header-' . $menu_position . '-' . $menu_color_key . ' .element-item-' . $menu_key . ' .header-nav-wrapper .primary-menu > li.current-menu-ancestor > a,
								.header-' . $menu_position . '-' . $menu_color_key . ' .element-item-' . $menu_key . ' .header-nav-wrapper .primary-menu > li > a:hover { ' . $menu_link_hover_color . ' }';
							}
						}
					}
				}
			} elseif ( 'mobile_menu_schema' === (string) $key ) {

				foreach ( $value as $menu_position => $menu_color ) {
					foreach ( $menu_color as $menu_color_key => $menu_color_value ) {
						foreach ( $menu_color_value as $menu_key => $menu_value ) {
							$menu_colors_color        = '';
							$menu_link_color          = '';
							$menu_link_hover_color    = '';
							$menu_link_hover_bg_color = '';
							$menu_bg_color            = '';

							if ( isset( $menu_value['menu_colors_color'] ) && ! empty( $menu_value['menu_colors_color'] ) ) {
								$menu_colors_color = 'color: ' . $menu_value['menu_colors_color'] . ';';
							}

							if ( isset( $menu_value['menu_colors_link_color'] ) && ! empty( $menu_value['menu_colors_link_color'] ) ) {
								$menu_link_color = 'color: ' . $menu_value['menu_colors_link_color'] . ';';
							}

							if ( isset( $menu_value['menu_colors_hover_color'] ) && ! empty( $menu_value['menu_colors_hover_color'] ) ) {
								$menu_link_hover_color    = 'color: ' . $menu_value['menu_colors_hover_color'] . ';';
								$menu_link_hover_bg_color = 'background-color: ' . $menu_value['menu_colors_hover_color'] . ';';
							}

							if ( isset( $menu_value['menu_bg_color'] ) && ! empty( $menu_value['menu_bg_color'] ) ) {
								$menu_bg_color = 'background-color: ' . $menu_value['menu_bg_color'] . ';';
							}

							if ( ! empty( $menu_link_color ) ) {
								$header_customize .= '
								.header-' . $menu_position . '-' . $menu_color_key . ' .element-item-' . $menu_key . ' #site-navigation-sticky-mobile .slicknav_nav .primary-menu-mobile li a { ' . $menu_link_color . ' }';
							}

							if ( ! empty( $menu_link_hover_color ) ) {
								$header_customize .= '
								.header-' . $menu_position . '-' . $menu_color_key . ' .element-item-' . $menu_key . ' #site-navigation-sticky-mobile .slicknav_nav .primary-menu-mobile li.current-menu-item > a,
								.header-' . $menu_position . '-' . $menu_color_key . ' .element-item-' . $menu_key . ' #site-navigation-sticky-mobile .slicknav_nav .primary-menu-mobile li.current-menu-item > a > a,
								.header-' . $menu_position . '-' . $menu_color_key . ' .element-item-' . $menu_key . ' #site-navigation-sticky-mobile .slicknav_nav .primary-menu-mobile li.current-menu-ancestor > a,
								.header-' . $menu_position . '-' . $menu_color_key . ' .element-item-' . $menu_key . ' #site-navigation-sticky-mobile .slicknav_nav .primary-menu-mobile li.current-menu-ancestor > a > a { ' . $menu_link_hover_color . ' }';
							}

							if ( ! empty( $menu_bg_color ) ) {
								$header_customize .= '
								.header-' . $menu_position . '-' . $menu_color_key . ' .element-item-' . $menu_key . ' #site-navigation-sticky-mobile .slicknav_nav { ' . $menu_bg_color . ' }';
							}
						}
					}
				}
			} else {

				$color                          = '';
				$link_color                     = '';
				$link_hover_color               = '';
				$link_bg_color                  = '';
				$link_hover_bg_color            = '';
				$link_border_top_color          = '';
				$link_border_bottom_color       = '';
				$link_border_top_hover_color    = '';
				$link_border_bottom_hover_color = '';
				$background_image               = '';
				$background_color               = '';
				$background_repeat              = '';
				$background_size                = '';
				$background_attachment          = '';
				$background_position            = '';
				$border_width                   = '';
				$border_style                   = '';
				$border_color                   = '';
				$desktop_height                 = '';
				$mobile_height                  = '';

				if ( isset( $value['row_height_desktop'] ) && ! empty( $value['row_height_desktop'] ) ) {
					$desktop_height = 'height: ' . $value['row_height_desktop'] . 'px;';
				}

				if ( isset( $value['row_height_mobile'] ) && ! empty( $value['row_height_mobile'] ) ) {
					$mobile_height = 'height: ' . $value['row_height_mobile'] . 'px;';
				}

				if ( isset( $value['header_color_color'] ) && ! empty( $value['header_color_color'] ) ) {
					$color = 'color: ' . $value['header_color_color'] . ';';
				}
				if ( isset( $value['header_color_link_color'] ) && ! empty( $value['header_color_link_color'] ) ) {
					$link_color               = 'color: ' . $value['header_color_link_color'] . ';';
					$link_bg_color            = 'background-color: ' . $value['header_color_link_color'] . ';';
					$link_border_top_color    = 'border-top-color: ' . $value['header_color_link_color'] . ';';
					$link_border_bottom_color = 'border-bottom-color: ' . $value['header_color_link_color'] . ';';
				}
				if ( isset( $value['header_color_hover_color'] ) && ! empty( $value['header_color_hover_color'] ) ) {
					$link_hover_color               = 'color: ' . $value['header_color_hover_color'] . ';';
					$link_hover_bg_color            = 'background-color: ' . $value['header_color_hover_color'] . ';';
					$link_border_top_hover_color    = 'border-top-color: ' . $value['header_color_hover_color'] . ';';
					$link_border_bottom_hover_color = 'border-bottom-color: ' . $value['header_color_hover_color'] . ';';
				}
				if ( isset( $value['bg_settings_bg_src'] ) && ! empty( $value['bg_settings_bg_src'] ) ) {
					$background_image = 'background-image: url("' . $value['bg_settings_bg_src'] . '");';
				}
				if ( isset( $value['bg_settings_bg_color'] ) && ! empty( $value['bg_settings_bg_color'] ) ) {
					$background_color = 'background-color: ' . $value['bg_settings_bg_color'] . ';';
				}
				if ( isset( $value['bg_settings_bg_repeat'] ) && ! empty( $value['bg_settings_bg_repeat'] ) ) {
					$background_repeat = 'background-repeat: ' . $value['bg_settings_bg_repeat'] . ';';
				}
				if ( isset( $value['bg_settings_bg_size'] ) && ! empty( $value['bg_settings_bg_size'] ) ) {
					$background_size = 'background-size: ' . $value['bg_settings_bg_size'] . ';';
				}
				if ( isset( $value['bg_settings_bg_attachment'] ) && ! empty( $value['bg_settings_bg_attachment'] ) ) {
					$background_attachment = 'background-attachment: ' . $value['bg_settings_bg_attachment'] . ';';
				}
				if ( isset( $value['bg_settings_bg_position'] ) && ! empty( $value['bg_settings_bg_position'] ) ) {
					$background_position = 'background-position: ' . $value['bg_settings_bg_position'] . ';';
				}
				if ( isset( $value['border_bottom_width'] ) && ! empty( $value['border_bottom_width'] ) ) {
					$border_width = 'border-bottom-width: ' . $value['border_bottom_width'] . 'px;';
					if ( isset( $value['border_bottom_style'] ) && ! empty( $value['border_bottom_style'] ) ) {
						$border_style = 'border-bottom-style: ' . $value['border_bottom_style'] . ';';
					}
				}

				if ( isset( $value['border_bottom_color'] ) && ! empty( $value['border_bottom_color'] ) ) {
					$border_color = 'border-bottom-color: ' . $value['border_bottom_color'] . ';';
				}

				$header_customize .= ' .header-' . $key . ' { ' . $color . $background_image . $background_color . $background_repeat . $background_size . $background_attachment . $background_position . ' }';

				if ( isset( $value['border_bottom_border_width'] ) && 'container' === (string) $value['border_bottom_border_width'] ) {
					$header_customize .= ' .header-' . $key . ' .header-item-wrapper { ' . $border_width . $border_style . $border_color . ' }';
				} else {
					$header_customize .= ' .header-' . $key . ' { ' . $border_width . $border_style . $border_color . ' }';
				}

				if ( ! empty( $desktop_height ) ) {
					$header_customize .= '
						.header-' . $key . '-desktop,
						.header-' . $key . '-desktop .divider-full-height { ' . $desktop_height . ' }';
				}

				if ( ! empty( $mobile_height ) ) {
					$header_customize .= '
						.header-' . $key . '-mobile,
						.header-' . $key . '-mobile .divider-full-height { ' . $mobile_height . ' }';
				}

				if ( ! empty( $link_color ) ) {
					$header_customize .= '
						.header-style-custom .header-' . $key . ' a,
						.header-' . $key . ' .woocommerce-currency-switcher-form .select2-container--default .select2-selection--single .select2-selection__rendered,
						.header-' . $key . ' .woocommerce-currency-switcher-form .select2-container--default .select2-selection--single .select2-selection__arrow b,
						.header-' . $key . ' .search-button-wrap .search-button,
						.header-' . $key . ' .header-social_profiles li a,
						.header-' . $key . ' .header-element-item .cart_subtotal .woo-cart-subtotal,
						.header-style-custom .header-' . $key . ' .categories-menu .sub-menu > li a,
						.header-' . $key . ' .header-nav-wrapper .ciyashop-secondary-menu > li > a,
						.header-' . $key . ' .header-nav-wrapper .primary-menu > li > a { ' . $link_color . ' }';
				}
				if ( ! empty( $color ) ) {
					$header_customize .= '
						.header-' . $key . ' .class { ' . $color . ' }';
				}
				if ( ! empty( $link_hover_color ) ) {
					$header_customize .= '
						.header-style-custom .header-' . $key . ' a:hover,
						.header-' . $key . ' .woocommerce-currency-switcher-form .select2-container--default .select2-selection--single:hover .select2-selection__rendered,
						.header-' . $key . ' .search-button-wrap .search-button:hover,
						.header-' . $key . ' .header-social_profiles li a:hover,
						.header-' . $key . ' .cart_both .woo-cart-subtotal,
						.header-style-custom .header-' . $key . ' .categories-menu .sub-menu > li a:hover,
						.header-' . $key . ' .header-nav-wrapper .ciyashop-secondary-menu > li:hover > a,
						.header-' . $key . ' .header-nav-wrapper .ciyashop-secondary-menu > li > a:hover,
						.header-' . $key . ' .header-nav-wrapper .primary-menu > li:hover > a,
						.header-' . $key . ' .header-nav-wrapper .primary-menu > li.current-menu-item > a,
						.header-' . $key . ' .header-nav-wrapper .primary-menu > li.current-menu-ancestor > a,
						.header-' . $key . ' .header-nav-wrapper .primary-menu > li > a:hover,
						#site-navigation .current_page_item > a { ' . $link_hover_color . ' }';
				}

				if ( ! empty( $link_bg_color ) ) {
					$header_customize .= '
						.header-' . $key . ' #site-navigation-sticky-mobile .slicknav_menu .slicknav_icon-bar { ' . $link_bg_color . ' }';
				}

				if ( ! empty( $link_hover_bg_color ) ) {
					$header_customize .= '
						.header-' . $key . ' .header-element-item .woo-tools-cart .cart-link .count,
						.header-' . $key . ' .header-element-item .woo-tools-compare .ciyashop-compare-count,
						.header-' . $key . ' .header-element-item .woo-tools-wishlist .ciyashop-wishlist-count { ' . $link_hover_bg_color . ' }';
				}

				if ( ! empty( $link_border_top_color ) ) {
					$header_customize .= '
						.header-' . $key . ' .woocommerce-currency-switcher-form .select2-container--default .select2-selection--single .select2-selection__arrow b { ' . $link_border_top_color . ' }';
				}
				if ( ! empty( $link_border_top_hover_color ) ) {
					$header_customize .= '
						.header-' . $key . ' .woocommerce-currency-switcher-form .select2-container--default .select2-selection--single:hover .select2-selection__arrow b { ' . $link_border_top_hover_color . ' }';
				}
				if ( ! empty( $link_border_bottom_color ) ) {
					$header_customize .= '
						.header-' . $key . ' .woocommerce-currency-switcher-form .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b { ' . $link_border_bottom_color . ' }';
				}
				if ( ! empty( $link_border_bottom_hover_color ) ) {
					$header_customize .= '
						.header-' . $key . ' .woocommerce-currency-switcher-form .select2-container--default.select2-container--open .select2-selection--single:hover .select2-selection__arrow b { ' . $link_border_bottom_hover_color . ' }';

				}
			}
		}
	}
	return $primary_color_customize . $secondary_color_customize . $tertiary_color_customize . $header_customize;
}
/**
 * Custom header schema
 *
 * @param string $header_id .
 */
function ciyashop_get_custom_header_schema( $header_id = '' ) {
	global $ciyashop_options, $header_elements, $wpdb;

	$header_layout_data      = '';
	$header_builder_elements = '';

	if ( ! $header_id ) {
		$header_id = isset( $ciyashop_options['custom_headers'] ) ? $ciyashop_options['custom_headers'] : '';
		if ( ! $header_id ) {
			return;
		}
	}

	$table_name = $wpdb->prefix . 'cs_header_builder';

	if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) ) === $table_name ) {

		$header_layout_data = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT * FROM ' . $wpdb->prefix . 'cs_header_builder
				WHERE id = %d
				',
				$header_id
			)
		);

		if ( $header_layout_data ) {
			$header_builder_elements = unserialize( $header_layout_data[0]->value );
		}
	}

	if ( empty( $header_builder_elements ) ) {
		return;
	}

	$topbar_config        = array();
	$main_config          = array();
	$menu_color_schema    = array();
	$primary_color_schema = array();
	$mobile_menu_schema   = array();
	$bottom_config        = array();

	$topbar_configuration = $header_builder_elements['topbar']['configuration'];
	$main_configuration   = $header_builder_elements['main']['configuration'];
	$bottom_configuration = $header_builder_elements['bottom']['configuration'];
	$all_header_layouts   = array( 'topbar', 'main', 'bottom' );
	$all_header_position  = array( 'left', 'center', 'right' );

	foreach ( $all_header_layouts as $header_layouts ) {
		foreach ( $all_header_position as $header_position ) {
			if ( isset( $header_builder_elements[ $header_layouts ][ 'desktop_' . $header_layouts . '_' . $header_position ] ) ) {
				$count = 0;
				foreach ( $header_builder_elements[ $header_layouts ][ 'desktop_' . $header_layouts . '_' . $header_position ] as $inner_elements ) {
					$count++;
					if ( isset( $inner_elements['menu'] ) ) {
						foreach ( $inner_elements['menu'] as $menu_elements ) {
							if ( isset( $menu_elements['name'] ) && 'menu_colors_color' === (string) $menu_elements['name'] ) {
								$menu_color_schema[ $header_layouts ][ 'desktop_' . $header_layouts . '_' . $header_position ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
							}

							if ( isset( $menu_elements['name'] ) && 'menu_colors_link_color' === (string) $menu_elements['name'] ) {
								$menu_color_schema[ $header_layouts ][ 'desktop_' . $header_layouts . '_' . $header_position ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
							}

							if ( isset( $menu_elements['name'] ) && 'menu_colors_hover_color' === (string) $menu_elements['name'] ) {
								$menu_color_schema[ $header_layouts ][ 'desktop_' . $header_layouts . '_' . $header_position ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
							}
						}
					}

					if ( isset( $inner_elements['primary_menu'] ) ) {
						foreach ( $inner_elements['primary_menu'] as $menu_elements ) {

							if ( isset( $menu_elements['name'] ) && 'menu_colors_color' === (string) $menu_elements['name'] ) {
								$primary_color_schema[ $header_layouts ][ 'desktop_' . $header_layouts . '_' . $header_position ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
							}

							if ( isset( $menu_elements['name'] ) && 'menu_colors_link_color' === (string) $menu_elements['name'] ) {
								$primary_color_schema[ $header_layouts ][ 'desktop_' . $header_layouts . '_' . $header_position ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
							}

							if ( isset( $menu_elements['name'] ) && 'menu_colors_hover_color' === (string) $menu_elements['name'] ) {
								$primary_color_schema[ $header_layouts ][ 'desktop_' . $header_layouts . '_' . $header_position ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
							}
						}
					}
				}
			}
		}
	}

	foreach ( $all_header_layouts as $header_layouts ) {
		if ( 'main' === (string) $header_layouts ) {
			foreach ( $all_header_position as $header_position ) {
				if ( isset( $header_builder_elements[ $header_layouts ][ 'mobile_' . $header_layouts . '_' . $header_position ] ) ) {
					$count = 0;
					foreach ( $header_builder_elements[ $header_layouts ][ 'mobile_' . $header_layouts . '_' . $header_position ] as $inner_elements ) {
						$count++;

						if ( isset( $inner_elements['menu'] ) ) {
							foreach ( $inner_elements['menu'] as $menu_elements ) {
								if ( isset( $menu_elements['name'] ) && 'menu_colors_color' === (string) $menu_elements['name'] ) {
									$menu_color_schema[ $header_layouts ][ 'mobile_' . $header_layouts . '_' . $header_position ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
								}

								if ( isset( $menu_elements['name'] ) && 'menu_colors_link_color' === (string) $menu_elements['name'] ) {
									$menu_color_schema[ $header_layouts ][ 'mobile_' . $header_layouts . '_' . $header_position ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
								}

								if ( isset( $menu_elements['name'] ) && 'menu_colors_hover_color' === (string) $menu_elements['name'] ) {
									$menu_color_schema[ $header_layouts ][ 'mobile_' . $header_layouts . '_' . $header_position ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
								}
							}
						}

						if ( isset( $inner_elements['primary_menu'] ) ) {
							foreach ( $inner_elements['primary_menu'] as $menu_elements ) {
								if ( isset( $menu_elements['name'] ) && 'menu_colors_color' === (string) $menu_elements['name'] ) {
									$primary_color_schema[ $header_layouts ][ 'mobile_' . $header_layouts . '_' . $header_position ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
								}

								if ( isset( $menu_elements['name'] ) && 'menu_colors_link_color' === (string) $menu_elements['name'] ) {
									$primary_color_schema[ $header_layouts ][ 'mobile_' . $header_layouts . '_' . $header_position ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
								}

								if ( isset( $menu_elements['name'] ) && 'menu_colors_hover_color' === (string) $menu_elements['name'] ) {
									$primary_color_schema[ $header_layouts ][ 'mobile_' . $header_layouts . '_' . $header_position ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
								}
							}
						}

						if ( isset( $inner_elements['mobile_menu'] ) ) {
							foreach ( $inner_elements['mobile_menu'] as $menu_elements ) {
								if ( isset( $menu_elements['name'] ) && 'menu_colors_color' === (string) $menu_elements['name'] ) {
									$mobile_menu_schema[ $header_layouts ][ 'mobile_' . $header_layouts . '_' . $header_position ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
								}

								if ( isset( $menu_elements['name'] ) && 'menu_colors_link_color' === (string) $menu_elements['name'] ) {
									$mobile_menu_schema[ $header_layouts ][ 'mobile_' . $header_layouts . '_' . $header_position ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
								}

								if ( isset( $menu_elements['name'] ) && 'menu_colors_hover_color' === (string) $menu_elements['name'] ) {
									$mobile_menu_schema[ $header_layouts ][ 'mobile_' . $header_layouts . '_' . $header_position ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
								}

								if ( isset( $menu_elements['name'] ) && 'menu_bg_color' === (string) $menu_elements['name'] ) {
									$mobile_menu_schema[ $header_layouts ][ 'mobile_' . $header_layouts . '_' . $header_position ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
								}
							}
						}
					}
				}
			}
		} else {
			if ( isset( $header_builder_elements[ $header_layouts ][ 'mobile_' . $header_layouts ] ) ) {

				$count = 0;
				foreach ( $header_builder_elements[ $header_layouts ][ 'mobile_' . $header_layouts ] as $mobile_inner_elements ) {
					$count++;

					if ( isset( $mobile_inner_elements['menu'] ) && $mobile_inner_elements['menu'] ) {
						foreach ( $mobile_inner_elements['menu'] as $menu_elements ) {
							if ( isset( $menu_elements['name'] ) && 'menu_colors_color' === (string) $menu_elements['name'] ) {
								$menu_color_schema[ $header_layouts ][ 'mobile_' . $header_layouts ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
							}

							if ( isset( $menu_elements['name'] ) && 'menu_colors_link_color' === (string) $menu_elements['name'] ) {
								$menu_color_schema[ $header_layouts ][ 'mobile_' . $header_layouts ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
							}

							if ( isset( $menu_elements['name'] ) && 'menu_colors_hover_color' === (string) $menu_elements['name'] ) {
								$menu_color_schema[ $header_layouts ][ 'mobile_' . $header_layouts ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
							}
						}
					}

					if ( isset( $mobile_inner_elements['primary_menu'] ) && $mobile_inner_elements['primary_menu'] ) {
						foreach ( $mobile_inner_elements['primary_menu'] as $menu_elements ) {
							if ( isset( $menu_elements['name'] ) && 'menu_colors_color' === (string) $menu_elements['name'] ) {
								$primary_color_schema[ $header_layouts ][ 'mobile_' . $header_layouts ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
							}

							if ( isset( $menu_elements['name'] ) && 'menu_colors_link_color' === (string) $menu_elements['name'] ) {
								$primary_color_schema[ $header_layouts ][ 'mobile_' . $header_layouts ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
							}

							if ( isset( $menu_elements['name'] ) && 'menu_colors_hover_color' === (string) $menu_elements['name'] ) {
								$primary_color_schema[ $header_layouts ][ 'mobile_' . $header_layouts ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
							}
						}
					}

					if ( isset( $mobile_inner_elements['mobile_menu'] ) && $mobile_inner_elements['mobile_menu'] ) {
						foreach ( $mobile_inner_elements['mobile_menu'] as $menu_elements ) {
							if ( isset( $menu_elements['name'] ) && 'menu_colors_color' === (string) $menu_elements['name'] ) {
								$mobile_menu_schema[ $header_layouts ][ 'mobile_' . $header_layouts ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
							}

							if ( isset( $menu_elements['name'] ) && 'menu_colors_link_color' === (string) $menu_elements['name'] ) {
								$mobile_menu_schema[ $header_layouts ][ 'mobile_' . $header_layouts ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
							}

							if ( isset( $menu_elements['name'] ) && 'menu_colors_hover_color' === (string) $menu_elements['name'] ) {
								$mobile_menu_schema[ $header_layouts ][ 'mobile_' . $header_layouts ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
							}

							if ( isset( $menu_elements['name'] ) && 'menu_bg_color' === (string) $menu_elements['name'] ) {
								$mobile_menu_schema[ $header_layouts ][ 'mobile_' . $header_layouts ][ $count ][ $menu_elements['name'] ] = $menu_elements['value'];
							}
						}
					}
				}
			}
		}
	}

	$default_config = array(
		'row_layout'                => 'row_flex',
		'row_height_desktop'        => 40,
		'row_height_mobile'         => 40,
		'desktop_hide'              => false,
		'desktop_sticky'            => false,
		'mobile_hide'               => false,
		'mobile_sticky'             => false,
		'header_color_color'        => '',
		'header_color_link_color'   => '',
		'header_color_hover_color'  => '',
		'bg_settings_bg_image'      => '',
		'bg_settings_bg_src'        => '',
		'bg_settings_bg_color'      => '',
		'bg_settings_bg_repeat'     => 'inherit',
		'bg_settings_bg_size'       => 'inherit',
		'bg_settings_bg_attachment' => 'inherit',
		'bg_settings_bg_position'   => 'inherit',
		'border_bottom_width'       => 0,
		'border_bottom_style'       => 'solid',
		'border_bottom_style'       => 'full_width',
		'border_bottom_color'       => '',
		'element_id'                => '',
		'element_class'             => '',
	);

	if ( ! empty( $topbar_configuration ) ) {
		foreach ( $topbar_configuration as $value ) {
			$topbar_config[ $value['name'] ] = $value['value'];
		}
	}

	if ( ! empty( $main_configuration ) ) {
		foreach ( $main_configuration as $value ) {
			$main_config[ $value['name'] ] = $value['value'];
		}
	}

	if ( ! empty( $main_configuration ) ) {
		foreach ( $bottom_configuration as $value ) {
			$bottom_config[ $value['name'] ] = $value['value'];
		}
	}

	$topbar_config = array_merge( $default_config, $topbar_config );
	$main_config   = array_merge( $default_config, $main_config );
	$bottom_config = array_merge( $default_config, $bottom_config );

	$header_config = array(
		'topbar'                    => $topbar_config,
		'main'                      => $main_config,
		'bottom'                    => $bottom_config,
		'menu_color_schema'         => $menu_color_schema,
		'primary_menu_color_schema' => $primary_color_schema,
		'mobile_menu_schema'        => $mobile_menu_schema,
	);

	return $header_config;
}
