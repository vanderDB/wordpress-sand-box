<?php
/**
 * Header Type Topbar with main header.
 *
 * @package CiyaShop
 */

global $ciyashop_options;

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
						<div class="col">
							<?php
							if ( $ciyashop_options['topbar_enable'] && ciyashop_get_topbar( 'left' ) ) {
								?>
								<div class="topbar-left text-left">
									<div class="topbar-link">
										<ul>
											<?php echo ciyashop_get_topbar( 'left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										</ul>
									</div>
								</div>
								<?php
							}
							?>

						</div>
						<div class="col-sm-3 col-md-3 col-lg-3 col-xl-3">
							<?php get_template_part( 'template-parts/header/header-elements/site_title' ); ?>
						</div>
						<div class="col">
							<?php
							if ( $ciyashop_options['topbar_enable'] && ciyashop_get_topbar( 'right' ) ) {
								?>
								<div class="topbar-right text-right">
									<div class="topbar-link">
										<ul>
											<?php echo ciyashop_get_topbar( 'right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										</ul>
									</div>
								</div>
								<?php
							}
							?>
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
 * Header Nav
 * **************************************************
 */
get_template_part( 'template-parts/header/header_nav' );


/**
 * **************************************************
 * Header Mobile
 * **************************************************
 */
get_template_part( 'template-parts/header/header_mobile' );


/**
 * **************************************************
 * Header Sticky
 * **************************************************
 */
get_template_part( 'template-parts/header/header_sticky' );
