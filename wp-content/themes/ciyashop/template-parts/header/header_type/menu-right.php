<?php
/**
 * Header Type Menu Right.
 *
 * @package CiyaShop
 */

/**
 * **************************************************
 *
 * Topbar
 *
 * **************************************************
 */
if ( 'enable' === ciyashop_topbar_enable() ) {
	get_template_part( 'template-parts/header/topbar' );
}

/**
 * **************************************************
 *
 * Header Main
 *
 * **************************************************
 */

/**
 * Fires before main header.
 *
 * @visible true
 */
do_action( 'ciyashop_before_header_main' );

$main_classes = ciyashop_header_main_classes( 'header-main' );
?>

<div class="<?php echo esc_attr( $main_classes ); ?>">
	<div class="header-main-wrapper">
		<div class="<?php echo esc_attr( ciyashop_header_main_container_classes() ); ?>">
			<div class="row">
				<div class="col-lg-12">

					<?php
					/**
					 * Fires before main header content.
					 *
					 * @visible true
					 */
					do_action( 'ciyashop_before_header_main_content' );
					?>

					<div class="row align-items-center">
						<div class="col-lg-3 col-md-3 col-sm-3">
							<?php get_template_part( 'template-parts/header/header-elements/site_title' ); ?>
						</div>
						<div class="col text-right">
							<?php ciyashop_primary_menu(); ?>
						</div>
						<div class="header-nav-right-wrapper">
							<div class="header-nav-right">
								<?php ciyashop_wootools(); ?>
								<?php
								$show_search = ciyashop_show_search();
								if ( $show_search ) {
									get_template_part( 'template-parts/header/header-elements/search' );
								}
								?>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

<?php
/**
 * Fires after main header.
 *
 * @visible true
 */
do_action( 'ciyashop_after_header_main' );


/**
 * **************************************************
 *
 * Header Mobile
 *
 * **************************************************
 */
get_template_part( 'template-parts/header/header_mobile' );


/**
 * **************************************************
 *
 * Header Sticky
 *
 * **************************************************
 */
get_template_part( 'template-parts/header/header_sticky' );
