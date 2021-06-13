<?php
/**
 * Header Type Right Topbar Main.
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

					<div class="row no-gutters">
						<div class="col-lg-3 col-md-4 col-sm-3">
							<?php get_template_part( 'template-parts/header/header-elements/site_title' ); ?>
						</div>
						<div class="col-lg-9 col-md-8 col-sm-9">
							<?php
							if ( $ciyashop_options['topbar_enable'] && ( ciyashop_get_topbar( 'left' ) || ciyashop_get_topbar( 'right' ) ) ) {
								?>
								<div class="header-main-top">
									<div class="topbar">
										<div class="topbar_wrapper">
											<div class="row">
												<div class="col-lg-6 col-sm-6">
													<?php
													if ( ciyashop_get_topbar( 'left' ) ) {
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
												<div class="col-lg-6 col-sm-6">
													<?php
													if ( ciyashop_get_topbar( 'right' ) ) {
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
								<?php
							}
							?>
							<div class="header-main-bottom">
								<div class="row align-items-center">
									<div class="col">
										<?php get_template_part( 'template-parts/header/header_nav' ); ?>
									</div>
									<?php
									ciyashop_header_nav_right_wrapper_start();
									ciyashop_wootools();
									$show_search = ciyashop_show_search();
									if ( $show_search ) {
										ciyashop_header_search_content();
									}
									ciyashop_header_nav_right_wrapper_end();
									?>
								</div>
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
