<?php
/**
 * Header Type Custom.
 *
 * @package CiyaShop
 */

global $ciyashop_options, $header_elements, $wpdb;

$header_id = isset( $ciyashop_options['custom_headers'] ) ? $ciyashop_options['custom_headers'] : '';

if ( ! $header_id ) {
	return;
}

$header_layout_data = false;

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
}

if ( ! $header_layout_data ) {
	return;
}

$header_builder_elements = unserialize( $header_layout_data[0]->value );
$main_classes            = ciyashop_header_main_classes( 'custom-header' );
?>
<div class="<?php echo esc_attr( $main_classes ); ?>">
	<div class="header-main-wrapper">		
		<?php
		if ( isset( $header_builder_elements['topbar'] ) && $header_builder_elements['topbar'] ) {
			$header_builder_elements_topbar = $header_builder_elements['topbar'];

			$attr = ciyashop_get_header_wrapper_classes( 'topbar', $header_builder_elements_topbar['configuration'] );
			?>
			<div <?php echo esc_attr( $attr['id'] ); ?> class="<?php echo esc_attr( $attr['class'] ); ?>">
				<div class="<?php echo esc_attr( ciyashop_header_main_container_classes() ); ?>">
					<?php
					if ( $header_builder_elements_topbar ) {
						?>
						<div class="header-topbar-desktop header-item-wrapper header-desktop">
							<?php
							if ( isset( $header_builder_elements_topbar['desktop_topbar_left'] ) && $header_builder_elements_topbar['desktop_topbar_left'] ) {
								$header_builder_desktop_topbar_left = $header_builder_elements_topbar['desktop_topbar_left'];
								?>
								<div class="header-topbar-desktop_topbar_left header-col header-col-desktop header-col-left">
									<?php
									$element_item = 0;
									foreach ( $header_builder_desktop_topbar_left as $desktop_topbar_left ) {
										$element_item++;
										$element_item_class = 'header-element-item element-item-' . $element_item;
										?>
										<div class="<?php echo esc_attr( $element_item_class ); ?>">
										<?php
										$element_key                  = key( $desktop_topbar_left );
										$header_builder_html_function = 'header_builder_html_' . $element_key;
										$header_builder_html_function( $desktop_topbar_left );
										?>
										</div>
										<?php
									}
									?>
								</div>
								<?php
							}

							if ( isset( $header_builder_elements_topbar['desktop_topbar_center'] ) && $header_builder_elements_topbar['desktop_topbar_center'] ) {
								$header_builder_desktop_topbar_center = $header_builder_elements_topbar['desktop_topbar_center'];
								?>
								<div class="header-topbar-desktop_topbar_center header-col header-col-desktop header-col-center">
									<?php
									$element_item = 0;
									foreach ( $header_builder_desktop_topbar_center as $desktop_topbar_center ) {
										$element_item++;
										$element_item_class = 'header-element-item element-item-' . $element_item;
										?>
										<div class="<?php echo esc_attr( $element_item_class ); ?>">
										<?php
										$element_key                  = key( $desktop_topbar_center );
										$header_builder_html_function = 'header_builder_html_' . $element_key;
										$header_builder_html_function( $desktop_topbar_center );
										?>
										</div>
										<?php
									}
									?>
								</div>
								<?php
							}

							if ( isset( $header_builder_elements_topbar['desktop_topbar_right'] ) && $header_builder_elements_topbar['desktop_topbar_right'] ) {
								$header_builder_desktop_topbar_right = $header_builder_elements_topbar['desktop_topbar_right'];
								?>
								<div class="header-topbar-desktop_topbar_right header-col header-col-desktop header-col-right">
									<?php
									$element_item = 0;
									foreach ( $header_builder_desktop_topbar_right as $desktop_topbar_right ) {
										$element_item++;
										$element_item_class = 'header-element-item element-item-' . $element_item;
										?>
										<div class="<?php echo esc_attr( $element_item_class ); ?>">
										<?php
										$element_key                  = key( $desktop_topbar_right );
										$header_builder_html_function = 'header_builder_html_' . $element_key;
										$header_builder_html_function( $desktop_topbar_right );
										?>
										</div>
										<?php
									}
									?>
								</div>
								<?php
							}
							?>

						</div>

						<div class="header-topbar-mobile header-item-wrapper header-mobile">
							<?php
							if ( isset( $header_builder_elements_topbar['mobile_topbar'] ) && $header_builder_elements_topbar['mobile_topbar'] ) {
								$header_builder_elements_topbar_mobile = $header_builder_elements_topbar['mobile_topbar'];
								?>
								<div class="header-topbar-mobile_topbar header-col header-col-mobile">
									<?php
									$element_item = 0;
									foreach ( $header_builder_elements_topbar_mobile as $desktop_topbar_mobile ) {
										$element_item++;
										$element_item_class = 'header-element-item element-item-' . $element_item;
										?>
										<div class="<?php echo esc_attr( $element_item_class ); ?>">
										<?php
										$element_key                  = key( $desktop_topbar_mobile );
										$header_builder_html_function = 'header_builder_html_' . $element_key;
										$header_builder_html_function( $desktop_topbar_mobile );
										?>
										</div>
										<?php
									}
									?>
								</div>
								<?php
							}
							?>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			<?php
		}
		if ( isset( $header_builder_elements['main'] ) && $header_builder_elements['main'] ) {
			$header_builder_elements_main = $header_builder_elements['main'];
			$attr                         = ciyashop_get_header_wrapper_classes( 'main', $header_builder_elements_main['configuration'] );
			?>
			<div <?php echo esc_attr( $attr['id'] ); ?> class="<?php echo esc_attr( $attr['class'] ); ?>">
				<div class="<?php echo esc_attr( ciyashop_header_main_container_classes() ); ?>">
					<?php
					if ( $header_builder_elements_main ) {
						?>
						<div class="header-main-desktop header-item-wrapper header-desktop">
							<?php
							if ( isset( $header_builder_elements_main['desktop_main_left'] ) && $header_builder_elements_main['desktop_main_left'] ) {
								$header_builder_desktop_main_left = $header_builder_elements_main['desktop_main_left'];
								?>
								<div class="header-main-desktop_main_left header-col header-col-desktop header-col-left">
									<?php
									$element_item = 0;
									foreach ( $header_builder_desktop_main_left as $desktop_main_left ) {
										$element_item++;
										$element_item_class = 'header-element-item element-item-' . $element_item;
										?>
										<div class="<?php echo esc_attr( $element_item_class ); ?>">
										<?php
										$element_key                  = key( $desktop_main_left );
										$header_builder_html_function = 'header_builder_html_' . $element_key;
										$header_builder_html_function( $desktop_main_left );
										?>
										</div>
										<?php
									}
									?>
								</div>
								<?php
							}

							if ( isset( $header_builder_elements_main['desktop_main_center'] ) && $header_builder_elements_main['desktop_main_center'] ) {
								$header_builder_desktop_main_center = $header_builder_elements_main['desktop_main_center'];
								?>
								<div class="header-main-desktop_main_center header-col header-col-desktop header-col-center">
									<?php
									$element_item = 0;
									foreach ( $header_builder_desktop_main_center as $desktop_main_center ) {
										$element_item++;
										$element_item_class = 'header-element-item element-item-' . $element_item;
										?>
										<div class="<?php echo esc_attr( $element_item_class ); ?>">
										<?php
										$element_key                  = key( $desktop_main_center );
										$header_builder_html_function = 'header_builder_html_' . $element_key;
										$header_builder_html_function( $desktop_main_center );
										?>
										</div>
										<?php
									}
									?>
								</div>
								<?php
							}

							if ( isset( $header_builder_elements_main['desktop_main_right'] ) && $header_builder_elements_main['desktop_main_right'] ) {
								$header_builder_desktop_main_right = $header_builder_elements_main['desktop_main_right'];
								?>
								<div class="header-main-desktop_main_right header-col header-col-desktop header-col-right">
									<?php
									$element_item = 0;
									foreach ( $header_builder_desktop_main_right as $desktop_main_right ) {
										$element_item++;
										$element_item_class = 'header-element-item element-item-' . $element_item;
										?>
										<div class="<?php echo esc_attr( $element_item_class ); ?>">
										<?php
										$element_key                  = key( $desktop_main_right );
										$header_builder_html_function = 'header_builder_html_' . $element_key;
										$header_builder_html_function( $desktop_main_right );
										?>
										</div>
										<?php
									}
									?>
								</div>
								<?php
							}
							?>
						</div>
						<div class="header-main-mobile header-item-wrapper header-mobile">
							<?php
							if ( isset( $header_builder_elements_main['mobile_main_left'] ) && $header_builder_elements_main['mobile_main_left'] ) {
								$header_builder_desktop_main_left = $header_builder_elements_main['mobile_main_left'];
								?>
								<div class="header-main-mobile_main_left header-col header-col-mobile header-col-left">
									<?php
									$element_item = 0;
									foreach ( $header_builder_desktop_main_left as $mobile_main_left ) {
										$element_item++;
										$element_item_class = 'header-element-item element-item-' . $element_item;
										?>
										<div class="<?php echo esc_attr( $element_item_class ); ?>">
										<?php
										$element_key                  = key( $mobile_main_left );
										$header_builder_html_function = 'header_builder_html_' . $element_key;
										$header_builder_html_function( $mobile_main_left );
										?>
										</div>
										<?php
									}
									?>
								</div>
								<?php
							}

							if ( isset( $header_builder_elements_main['mobile_main_center'] ) && $header_builder_elements_main['mobile_main_center'] ) {
								$header_builder_mobile_main_center = $header_builder_elements_main['mobile_main_center'];
								?>
								<div class="header-main-mobile_main_center header-col header-col-mobile header-col-center">
									<?php
									$element_item = 0;
									foreach ( $header_builder_mobile_main_center as $mobile_main_center ) {
										$element_item++;
										$element_item_class = 'header-element-item element-item-' . $element_item;
										?>
										<div class="<?php echo esc_attr( $element_item_class ); ?>">
										<?php
										$element_key                  = key( $mobile_main_center );
										$header_builder_html_function = 'header_builder_html_' . $element_key;
										$header_builder_html_function( $mobile_main_center );
										?>
										</div>
										<?php
									}
									?>
								</div>
								<?php
							}

							if ( isset( $header_builder_elements_main['mobile_main_right'] ) && $header_builder_elements_main['mobile_main_right'] ) {
								$header_builder_mobile_main_right = $header_builder_elements_main['mobile_main_right'];
								?>
								<div class="header-main-mobile_main_right header-col header-col-mobile header-col-right">
									<?php
									$element_item = 0;
									foreach ( $header_builder_mobile_main_right as $mobile_main_right ) {
										$element_item++;
										$element_item_class = 'header-element-item element-item-' . $element_item;
										?>
										<div class="<?php echo esc_attr( $element_item_class ); ?>">
										<?php
										$element_key                  = key( $mobile_main_right );
										$header_builder_html_function = 'header_builder_html_' . $element_key;
										$header_builder_html_function( $mobile_main_right );
										?>
										</div>
										<?php
									}
									?>
								</div>
								<?php
							}
							?>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			<?php
		}
		if ( isset( $header_builder_elements['bottom'] ) && $header_builder_elements['bottom'] ) {
			$header_builder_elements_bottom = $header_builder_elements['bottom'];
			$attr                           = ciyashop_get_header_wrapper_classes( 'bottom', $header_builder_elements_bottom['configuration'] );
			?>
			<div <?php echo esc_attr( $attr['id'] ); ?> class="<?php echo esc_attr( $attr['class'] ); ?>">
				<div class="<?php echo esc_attr( ciyashop_header_main_container_classes() ); ?>">
					<?php
					if ( $header_builder_elements_bottom ) {
						?>
						<div class="header-bottom-desktop header-item-wrapper header-desktop">
							<?php
							if ( isset( $header_builder_elements_bottom['desktop_bottom_left'] ) && $header_builder_elements_bottom['desktop_bottom_left'] ) {
								$header_builder_desktop_bottom_left = $header_builder_elements_bottom['desktop_bottom_left'];
								?>
								<div class="header-bottom-desktop_bottom_left header-col header-col-desktop header-col-left">
									<?php
									$element_item = 0;
									foreach ( $header_builder_desktop_bottom_left as $desktop_bottom_left ) {
										$element_item++;
										$element_item_class = 'header-element-item element-item-' . $element_item;
										?>
										<div class="<?php echo esc_attr( $element_item_class ); ?>">
										<?php
										$element_key                  = key( $desktop_bottom_left );
										$header_builder_html_function = 'header_builder_html_' . $element_key;
										$header_builder_html_function( $desktop_bottom_left );
										?>
										</div>
										<?php
									}
									?>
								</div>
								<?php
							}
							?>

							<?php
							if ( isset( $header_builder_elements_bottom['desktop_bottom_center'] ) && $header_builder_elements_bottom['desktop_bottom_center'] ) {
								$header_builder_desktop_bottom_center = $header_builder_elements_bottom['desktop_bottom_center'];
								?>
								<div class="header-bottom-desktop_bottom_center header-col header-col-desktop header-col-center">
									<?php
									$element_item = 0;
									foreach ( $header_builder_desktop_bottom_center as $desktop_bottom_center ) {
										$element_item++;
										$element_item_class = 'header-element-item element-item-' . $element_item;
										?>
										<div class="<?php echo esc_attr( $element_item_class ); ?>">
										<?php
										$element_key                  = key( $desktop_bottom_center );
										$header_builder_html_function = 'header_builder_html_' . $element_key;
										$header_builder_html_function( $desktop_bottom_center );
										?>
										</div>
										<?php
									}
									?>
								</div>
								<?php
							}
							?>
							<?php
							if ( isset( $header_builder_elements_bottom['desktop_bottom_right'] ) && $header_builder_elements_bottom['desktop_bottom_right'] ) {
								$header_builder_desktop_bottom_right = $header_builder_elements_bottom['desktop_bottom_right'];
								?>
								<div class="header-bottom-desktop_bottom_right header-col header-col-desktop header-col-right">
									<?php
									$element_item = 0;
									foreach ( $header_builder_desktop_bottom_right as $desktop_bottom_right ) {
										$element_item++;
										$element_item_class = 'header-element-item element-item-' . $element_item;
										?>
										<div class="<?php echo esc_attr( $element_item_class ); ?>">
										<?php
										$element_key                  = key( $desktop_bottom_right );
										$header_builder_html_function = 'header_builder_html_' . $element_key;
										$header_builder_html_function( $desktop_bottom_right );
										?>
										</div>
										<?php
									}
									?>
								</div>
								<?php
							}
							?>
						</div>

						<div class="header-bottom-mobile header-item-wrapper header-mobile">
							<?php
							if ( isset( $header_builder_elements_bottom['mobile_bottom'] ) && $header_builder_elements_bottom['mobile_bottom'] ) {
								$header_builder_mobile_bottom = $header_builder_elements_bottom['mobile_bottom'];
								?>
								<div class="header-bottom-mobile_bottom header-col header-col-mobile">
									<?php
									$element_item = 0;
									foreach ( $header_builder_mobile_bottom as $mobile_bottom ) {
										$element_item++;
										$element_item_class = 'header-element-item element-item-' . $element_item;
										?>
										<div class="<?php echo esc_attr( $element_item_class ); ?>">
										<?php
										$element_key                  = key( $mobile_bottom );
										$header_builder_html_function = 'header_builder_html_' . $element_key;
										$header_builder_html_function( $mobile_bottom );
										?>
										</div>
										<?php
									}
									?>
								</div>
								<?php
							}
							?>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>
