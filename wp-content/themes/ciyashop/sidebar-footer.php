<?php
/**
 * The sidebar containing the footer widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CiyaShop
 */

global $ciyashop_options;

$footer_bottom_content   = '';
$footer_builder_elements = ciyashop_get_custom_footer_data();

if ( $footer_builder_elements ) {
	if ( isset( $footer_builder_elements['columns_settings'] ) && $footer_builder_elements['columns_settings'] ) {
		$column_class = '';
		?>
		<div class="footer-widgets-wrapper">
			<div class="footer">
				<div class="container">
					<div class="footer-widgets">
						<div class="row">
						<?php
						foreach ( $footer_builder_elements['columns_settings'] as $columns_setting ) {

							$footer_columns                  = '';
							$footer_columns_tablet_landscape = '';
							$footer_columns_tablet           = '';

							if ( isset( $columns_setting['footer_columns'] ) && $columns_setting['footer_columns'] ) {
								$footer_columns = $columns_setting['footer_columns'];
							}

							if ( isset( $columns_setting['footer_columns_tablet_landscape'] ) && $columns_setting['footer_columns_tablet_landscape'] ) {
								$footer_columns_tablet_landscape = $columns_setting['footer_columns_tablet_landscape'];
							}

							if ( isset( $columns_setting['footer_columns_tablet'] ) && $columns_setting['footer_columns_tablet'] ) {
								$footer_columns_tablet = $columns_setting['footer_columns_tablet'];
							}

							$column_class = 'col-xl-' . $footer_columns . ' col-lg-' . $footer_columns_tablet_landscape . ' col-md-' . $footer_columns_tablet . ' col-sm-12 col-xs-12';

							if ( isset( $columns_setting['columns_alignment'] ) && $columns_setting['columns_alignment'] ) {
								$column_class .= ' footer-align-' . $columns_setting['columns_alignment'];
							}

							if ( isset( $columns_setting['hide_for_desktop'] ) && $columns_setting['hide_for_desktop'] ) {
								$column_class .= ' hide-for-desktop';
							}

							if ( isset( $columns_setting['hide_for_mobile'] ) && $columns_setting['hide_for_mobile'] ) {
								$column_class .= ' hide-for-mobile';
							}

							if ( isset( $columns_setting['hide_for_tablet'] ) && $columns_setting['hide_for_tablet'] ) {
								$column_class .= ' hide-for-tablet';
							}
							?>
							<div class="<?php echo esc_attr( $column_class ); ?>">
								<?php
								if ( isset( $columns_setting['footer_sidebar'] ) && $columns_setting['footer_sidebar'] ) {
									dynamic_sidebar( $columns_setting['footer_sidebar'] );
								}
								?>
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
		if ( ( isset( $footer_builder_elements['common_settings']['footer_bottom'] ) && 'show' === $footer_builder_elements['common_settings']['footer_bottom'] ) && ( isset( $footer_builder_elements['common_settings']['footer_bottom_content'] ) && $footer_builder_elements['common_settings']['footer_bottom_content'] ) ) {
			$footer_bottom_content = $footer_builder_elements['common_settings']['footer_bottom_content'];
		}
	}
} else {

	$footer_rows                     = 1;
	$footer_cols                     = 1;
	$classes                         = array( 'col-lg-12 col-md-12 col-sm-12' );
	$footer_widget_columns_alignment = array();

	$footer_widget_columns = ( isset( $ciyashop_options['footer_widget_columns'] ) && ! empty( $ciyashop_options['footer_widget_columns'] ) ) ? $ciyashop_options['footer_widget_columns'] : 'four-columns';

	if ( isset( $ciyashop_options['footer_one_alignment'] ) && ! empty( $ciyashop_options['footer_one_alignment'] ) ) {
		$footer_widget_columns_alignment[] = 'footer-align-' . $ciyashop_options['footer_one_alignment'];
	}

	if ( isset( $ciyashop_options['footer_two_alignment'] ) && ! empty( $ciyashop_options['footer_two_alignment'] ) ) {
		$footer_widget_columns_alignment[] = 'footer-align-' . $ciyashop_options['footer_two_alignment'];
	}

	if ( isset( $ciyashop_options['footer_three_alignment'] ) && ! empty( $ciyashop_options['footer_three_alignment'] ) ) {
		$footer_widget_columns_alignment[] = 'footer-align-' . $ciyashop_options['footer_three_alignment'];
	}

	if ( isset( $ciyashop_options['footer_four_alignment'] ) && ! empty( $ciyashop_options['footer_four_alignment'] ) ) {
		$footer_widget_columns_alignment[] = 'footer-align-' . $ciyashop_options['footer_four_alignment'];
	}

	if ( isset( $ciyashop_options['footer_five_alignment'] ) && ! empty( $ciyashop_options['footer_five_alignment'] ) ) {
		$footer_widget_columns_alignment[] = 'footer-align-' . $ciyashop_options['footer_five_alignment'];
	}

	if ( isset( $ciyashop_options['footer_six_alignment'] ) && ! empty( $ciyashop_options['footer_six_alignment'] ) ) {
		$footer_widget_columns_alignment[] = 'footer-align-' . $ciyashop_options['footer_six_alignment'];
	}

	if ( isset( $ciyashop_options['footer_seven_alignment'] ) && ! empty( $ciyashop_options['footer_seven_alignment'] ) ) {
		$footer_widget_columns_alignment[] = 'footer-align-' . $ciyashop_options['footer_seven_alignment'];
	}

	if ( empty( $footer_widget_columns_alignment ) ) {
		$footer_widget_columns_alignment[] = 'footer-align-left';
	}

	/**
	 * Filters footer widget columns.
	 *
	 * @param string $widget_column Name of widget column option.
	 *
	 * @visible true
	 */
	$footer_widget_columns = apply_filters( 'ciyashop_footer_widget_columns', $footer_widget_columns );

	/**
	 * Filters footer widget columns alignment.
	 *
	 * @param array $columns_alignment Array of columns alignment.
	 *
	 * @visible true
	 */
	$footer_widget_columns_alignment = apply_filters( 'ciyashop_footer_widget_columns_alignment', $footer_widget_columns_alignment );

	if ( isset( $ciyashop_options['footer_widget_columns'] ) ) {
		switch ( $ciyashop_options['footer_widget_columns'] ) {
			case 'two-columns':
				$footer_cols = 2;
				$classes     = array( 'col-lg-6 col-md-6' );
				break;
			case 'three-columns':
				$footer_cols = 3;
				$classes     = array( 'col-lg-4 col-md-4' );
				break;
			case 'four-columns':
				$footer_cols = 4;
				$classes     = array( 'col-lg-3 col-md-6' );
				break;
			case '8-4-columns':
				$footer_cols = 2;
				$classes     = array( 'col-lg-8 col-md-6', 'col-lg-4 col-md-6' );
				break;
			case '4-8-columns':
				$footer_cols = 2;
				$classes     = array( 'col-lg-4 col-md-6', 'col-lg-8 col-md-6' );
				break;
			case '6-3-3-columns':
				$footer_cols = 3;
				$classes     = array( 'col-lg-6 col-md-4', 'col-lg-3 col-md-4', 'col-lg-3 col-md-4' );
				break;
			case '3-3-6-columns':
				$footer_cols = 3;
				$classes     = array( 'col-lg-3 col-md-4', 'col-lg-3 col-md-4', 'col-lg-6 col-md-4' );
				break;
			case '8-2-2-columns':
				$footer_cols = 3;
				$classes     = array( 'col-xl-8 col-lg-6 col-md-4', 'col-xl-2 col-lg-3 col-md-4', 'col-xl-2 col-lg-3 col-md-4' );
				break;
			case '2-2-8-columns':
				$footer_cols = 3;
				$classes     = array( 'col-xl-2 col-lg-3 col-md-4', 'col-xl-2 col-lg-3 col-md-4', 'col-xl-8 col-lg-6 col-md-4' );
				break;
			case '6-2-2-2-columns':
				$footer_cols = 4;
				$classes     = array( 'col-xl-6 col-lg-3 col-md-6 col-sm-12', 'col-xl-2 col-lg-3 col-md-6 col-sm-12', 'col-xl-2 col-lg-3 col-md-6 col-sm-12', 'col-xl-2 col-lg-3 col-md-6 col-sm-12' );
				break;
			case '2-2-2-6-columns':
				$footer_cols = 4;
				$classes     = array( 'col-xl-2 col-lg-3 col-md-6 col-sm-12', 'col-xl-2 col-lg-3 col-md-6 col-sm-12', 'col-xl-2 col-lg-3 col-md-6 col-sm-12', 'col-xl-6 col-lg-3 col-md-6 col-sm-12' );
				break;
			case '3-3-2-2-2-columns':
				$footer_cols = 5;
				$classes     = array( 'col-xl-3 col-lg-3 col-md-6 col-sm-12', 'col-xl-3 col-lg-3 col-md-6 col-sm-12', 'col-xl-2 col-lg-2 col-md-6 col-sm-12', 'col-xl-2 col-lg-2 col-md-6 col-sm-12', 'col-xl-2 col-lg-2 col-md-6 col-sm-12' );
				break;
			case '2-2-2-3-3-columns':
				$footer_cols = 5;
				$classes     = array( 'col-xl-2 col-lg-2 col-md-6 col-sm-12', 'col-xl-2 col-lg-2 col-md-6 col-sm-12', 'col-xl-2 col-lg-2 col-md-6 col-sm-12', 'col-xl-3 col-lg-3 col-md-6 col-sm-12', 'col-xl-3 col-lg-3 col-md-6 col-sm-12' );
				break;
			case '3-2-2-2-3-columns':
				$footer_cols = 5;
				$classes     = array( 'col-xl-3 col-lg-3 col-md-6 col-sm-12', 'col-xl-2 col-lg-2 col-md-6 col-sm-12', 'col-xl-2 col-lg-2 col-md-6 col-sm-12', 'col-xl-2 col-lg-2 col-md-6 col-sm-12', 'col-xl-3 col-lg-3 col-md-6 col-sm-12' );
				break;
			case '6-6-3-3-3-3-columns':
				$footer_rows        = 2;
				$footer_cols        = 6;
				$footer_first_cols  = 2;
				$footer_second_cols = 4;
				$classes            = array( 'col-xl-6 col-lg-6 col-md-6', 'col-xl-6 col-lg-6 col-md-6', 'col-xl-3 col-lg-3 col-md-6', 'col-xl-3 col-lg-3 col-md-6', 'col-xl-3 col-lg-3 col-md-6', 'col-xl-3 col-lg-3 col-md-6' );
				break;
			case '6-6-2-2-2-2-4-columns':
				$footer_rows        = 2;
				$footer_cols        = 7;
				$footer_first_cols  = 2;
				$footer_second_cols = 5;
				$classes            = array( 'col-xl-6 col-lg-6 col-md-6', 'col-xl-6 col-lg-6 col-md-6', 'col-xl-2 col-lg-2 col-md-6', 'col-xl-2 col-lg-2 col-md-6', 'col-xl-2 col-lg-2 col-md-6', 'col-xl-2 col-lg-2 col-md-6', 'col-xl-4 col-lg-4 col-md-6' );
				break;
			case '12-2-2-2-2-4-columns':
				$footer_rows        = 2;
				$footer_cols        = 6;
				$footer_first_cols  = 1;
				$footer_second_cols = 5;
				$classes            = array( 'col-xl-12 col-lg-12 col-md-12 col-sm-12', 'col-xl-2 col-lg-2 col-md-6 col-sm-12', 'col-xl-2 col-lg-2 col-md-6 col-sm-12', 'col-xl-2 col-lg-2 col-md-6 col-sm-12', 'col-xl-2 col-lg-2 col-md-6 col-sm-12', 'col-xl-4 col-lg-4 col-md-6 col-sm-12' );
				break;
			case '12-3-3-3-3-columns':
				$footer_rows        = 2;
				$footer_cols        = 5;
				$footer_first_cols  = 1;
				$footer_second_cols = 4;
				$classes            = array( 'col-xl-12 col-lg-12 col-md-12 col-sm-12', 'col-xl-3 col-lg-3 col-md-6 col-sm-12', 'col-xl-3 col-lg-3 col-md-6 col-sm-12', 'col-xl-3 col-lg-3 col-md-6 col-sm-12', 'col-xl-3 col-lg-3 col-md-6 col-sm-12', 'col-xl-3 col-lg-3 col-md-6 col-sm-12' );
				break;
			case '2-2-2-2-2-2-columns':
				$footer_cols = 6;
				$classes     = array( 'col-md-2 col-sm-4', 'col-md-2 col-sm-4', 'col-md-2 col-sm-4', 'col-md-2 col-sm-4', 'col-md-2 col-sm-4', 'col-md-2 col-sm-4' );
				break;
		}
	}
	$sidebar_content = 0;
	for ( $col = 1; $col <= $footer_cols; $col++ ) {
		if ( is_active_sidebar( 'sidebar-footer-' . $col ) ) {
			$sidebar_content++;
		}
	}

	if ( $sidebar_content > 0 ) {
		if ( $footer_rows > 1 ) {
			$footer_col = $footer_first_cols;
		} else {
			$footer_col = $footer_cols;
		}
		?>
		<div class="footer-widgets-wrapper">
			<div class="footer"><!-- .footer -->
				<div class="container"><!-- .container -->
					<div class="footer-widgets">
						<div class="row">
							<?php
							for ( $col = 1; $col <= $footer_col; $col++ ) {
								if ( is_active_sidebar( 'sidebar-footer-' . $col ) ) {
									if ( ! isset( $classes[ $col - 1 ] ) ) {
										$classes[ $col - 1 ] = $classes[0];
									}
									?>
									<div class="<?php echo esc_attr( $classes[ $col - 1 ] . ' ' . $footer_widget_columns_alignment[ $col - 1 ] ); ?>">
										<?php dynamic_sidebar( 'sidebar-footer-' . $col ); ?>
									</div>
									<?php
								}
							}
							?>
						</div>
						<?php
						if ( $footer_rows > 1 ) {
							?>
							<div class="row">
								<?php
								for ( $col = $footer_first_cols + 1; $col <= $footer_cols; $col++ ) {
									if ( is_active_sidebar( 'sidebar-footer-' . $col ) ) {
										?>
										<div class="<?php echo esc_attr( $classes[ $col - 1 ] . ' ' . $footer_widget_columns_alignment[ $col - 1 ] ); ?>">
											<?php dynamic_sidebar( 'sidebar-footer-' . $col ); ?>
										</div>
										<?php
									}
								}
								?>
							</div>
							<?php
						}
						?>
					</div>
				</div><!-- .container -->
			</div><!-- .footer -->
		</div>
		<?php
		if ( ( isset( $ciyashop_options['footer_bottom'] ) && 'show' === $ciyashop_options['footer_bottom'] ) && ( isset( $ciyashop_options['footer_bottom_content'] ) && ! empty( $ciyashop_options['footer_bottom_content'] ) ) ) {
			$footer_bottom_content = $ciyashop_options['footer_bottom_content'];
		}
	}
}

if ( $footer_bottom_content ) {
	?>
	<div class="footer-bottom-wrapper">
		<div class="container"><!-- .container -->
			<div class="row">
				<div class="col-12">
					<div class="footer-bottom">
						<?php echo do_shortcode( $footer_bottom_content ); ?>
					</div><!-- .footer-bottom -->
				</div>
			</div>
		</div>
	</div><!-- .footer-bottom-wrapper -->
	<?php
}
