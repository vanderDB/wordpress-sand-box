<?php
/**
 * Field helper
 *
 * @package CiyaShop
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Menu render fields
 *
 * @param string $item_id .
 * @param string $menu_depth .
 * @param string $parent_menu_id .
 */
function menu_render_fields( $item_id, $menu_depth = '', $parent_menu_id = '' ) {
	$output = '';
	$saved  = '';

	$field_args = pgs_menu_fields();

	foreach ( $field_args as $args ) {
		if ( 1 === (int) $menu_depth && ! empty( $parent_menu_id ) && 'menu_widget_area' === (string) $args['id'] ) {
			$parent_saved = get_post_meta( $parent_menu_id, 'pgs_menu-item-menu_design', true );

			if ( 'mega-menu' === (string) $parent_saved ) {

				$saved   = get_post_meta( $item_id, 'pgs_menu-item-' . $args['id'], true );
				$output .= get_menu_fields_html( $item_id, $args, $saved );
			}
		} else {
			$saved   = get_post_meta( $item_id, 'pgs_menu-item-' . $args['id'], true );
			$output .= get_menu_fields_html( $item_id, $args, $saved );
		}
	}

	return $output;
}

/**
 * Menu fields html
 *
 * @param string $item_id .
 * @param string $args .
 * @param string $saved .
 */
function get_menu_fields_html( $item_id, $args, $saved ) {

	$output = '';

	if ( ! $args ) {
		return;
	}

	$placeholder          = '';
	$field_type           = $args['type'];
	$field_wrapper_class  = 'pgs_menu_field-' . $field_type;
	$field_wrapper_class .= ' pgs_menu_field';

	if ( isset( $args['class'] ) && isset( $args['class'] ) ) {
		$field_wrapper_class .= ' ' . $args['class'];
	}

	$pgs_menu_depth = isset( $args['depth'] ) ? $args['depth'] : null;

	ob_start();

	?>
	<div class="<?php echo esc_attr( $field_wrapper_class ); ?>" data-menu-depth="<?php echo esc_attr( wp_json_encode( $pgs_menu_depth ) ); ?>">
	<?php

	switch ( $field_type ) {

		// Textfield.
		case 'textfield':
			if ( isset( $args['placeholder'] ) && ! empty( $args['placeholder'] ) ) {
				$placeholder = $args['placeholder'];
			}

			?>
			<label><?php echo esc_html( $args['title'] ); ?></label>
			<div class="pgs_menu_input-field">
				<input type="<?php echo esc_attr( $field_type ); ?>" class="widefat" placeholder="<?php echo esc_attr( $placeholder ); ?>" name="<?php echo esc_attr( $args['id'] ); ?>" value="<?php echo esc_attr( $saved ); ?>"/>
				<?php
				if ( isset( $args['desc'] ) && ! empty( $args['desc'] ) ) {
					?>
					<span class="description">
					<?php 
					echo wp_kses(
						$args['desc'],
						array(
							'a'      => array(
								'href'   => true,
								'target' => true,
							),
							'strong' => true,
						)
					);
					?>
					</span>
					<?php
				}
				?>
			</div>
			<?php

			break;

		// Dropdown Field.
		case 'dropdown':
			?>
			<label>
				<?php echo esc_html( $args['title'] ); ?>
			</label>
			<div class="pgs_menu_input-field">
				<select name="<?php echo esc_attr( $args['id'] ); ?>" class="widefat">
					<?php
					if ( isset( $args['first_empty'] ) && $args['first_empty'] ) {
						?>
						<option value="" <?php echo selected( '', $saved, false ); ?>><?php echo esc_html( $args['empty_label'] ); ?></option>
						<?php
					}
					foreach ( $args['options'] as $key => $value ) {
						?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php echo selected( $key, $saved, false ); ?>><?php echo esc_html( $value ); ?></option>
						<?php
					}
					?>
				</select>
				<?php
				if ( isset( $args['desc'] ) && ! empty( $args['desc'] ) ) {
					?>
					<span class="description">
					<?php 
					echo wp_kses(
						$args['desc'],
						array(
							'a'      => array(
								'href'   => true,
								'target' => true,
							),
							'strong' => true,
						)
					);
					?>
					</span>
					<?php
				}
				?>
			</div>
			<?php

			break;

		// Checkbox Field.
		case 'checkbox':
			?>
			<label>
				<?php 
				if ( isset( $args['title'] ) ) {
					echo esc_html( $args['title'] );
				}
				?>
			</label>
			<div class="pgs_menu_input-field">
				<label for="menu-item-<?php echo esc_attr( $args['id'] ); ?>">
					<input type="checkbox" id="menu-item-<?php echo esc_attr( $args['id'] ); ?>" name="<?php echo esc_attr( $args['id'] ); ?>" value="<?php echo esc_attr( $args['value'] ); ?>" <?php echo checked( $saved, 1, false ); ?> />
					<?php echo esc_html( $args['label'] ); ?>
				</label>
			</div>
			<?php
			if ( isset( $args['desc'] ) && ! empty( $args['desc'] ) ) {
				?>
				<span class="description">
				<?php 
				echo wp_kses(
					$args['desc'],
					array(
						'a'      => array(
							'href'   => true,
							'target' => true,
						),
						'strong' => true,
					)
				);
				?>
				</span>
				<?php
			}

			break;

		// Iconpicker Field.

		case 'iconpicker':
			?>
			<label for="menu-item-<?php echo esc_attr( $args['id'] ); ?>">
				<?php echo esc_html( $args['title'] ); ?>
			</label>
			<div class="pgs_menu_input-field">
				<input type="text" class="pgs_menu-iconpicker" name="<?php echo esc_attr( $args['id'] ); ?>" value="<?php echo esc_attr( $saved ); ?>"/>
			</div>
			<?php
			if ( isset( $args['desc'] ) && ! empty( $args['desc'] ) ) {
				?>
				<span class="description">
				<?php 
				echo wp_kses(
					$args['desc'],
					array(
						'a'      => array(
							'href'   => true,
							'target' => true,
						),
						'strong' => true,
					)
				);
				?>
				</span>
				<?php
			}

			break;

		case 'number':
			?>
			<label for="menu-item-<?php echo esc_attr( $args['id'] ); ?>">
				<?php echo esc_html( $args['title'] ); ?>
			</label>
			<div class="pgs_menu_input-field">
				<input type="number" name="<?php echo esc_attr( $args['id'] ); ?>" value="<?php echo esc_attr( $saved ); ?>" />
				<?php 
				if ( isset( $args['label'] ) ) {
					echo esc_html( $args['label'] ); 
				}
				?>
			</div>
			<?php
			if ( isset( $args['desc'] ) && ! empty( $args['desc'] ) ) {
				?>
				<span class="description">
				<?php 
				echo wp_kses(
					$args['desc'],
					array(
						'a'      => array(
							'href'   => true,
							'target' => true,
						),
						'strong' => true,
					)
				);
				?>
				</span>
				<?php
			}

			break;

		case 'image':
			?>
		<label for="menu-item-<?php echo esc_attr( $args['id'] ); ?>">
			<?php echo esc_html( $args['title'] ); ?>
		</label>
		<div class="pgs_menu_input-field pgs_menu_input-field-image">
			<div class="nmi-current-image-wrapper">
				<?php
				$upload_url = admin_url( 'media-upload.php' );
				$query_args = array(
					'post_id'   => $item_id,
					'tab'       => 'gallery',
					'TB_iframe' => '1',
					'width'     => '640',
					'height'    => '425',
				);
				$upload_url = esc_url( add_query_arg( $query_args, $upload_url ) );
				?>
				<input type="hidden" name="nmi_item_id" id="nmi_item_id" value="<?php echo esc_attr( $item_id ); ?>" />
				<?php

				if ( has_post_thumbnail( $item_id ) ) {
					$link_text = __( 'Change Menu Image', 'ciyashop' );
					?>
					<div class="nmi-current-image nmi-div">
						<a href="<?php echo esc_url( $upload_url ); ?>" data-id="<?php echo esc_attr( $item_id ); ?>" class="add_media"><?php echo get_the_post_thumbnail( $item_id, 'thumb' ); ?></a>
					</div>
					<?php
				} else {
					$link_text = __( 'Upload Menu Image', 'ciyashop' );
					?>
					<div class="nmi-current-image nmi-div"></div>
					<?php
				}
				?>
				<div class="nmi-upload-link nmi-div">
					<a href="<?php echo esc_url( $upload_url ); ?>" data-id="<?php echo esc_attr( $item_id ); ?>" class="add_media"><?php echo esc_html( $link_text ); ?></a>
					<?php
					if ( has_post_thumbnail( $item_id ) ) {
						$ajax_nonce = wp_create_nonce( 'set_post_thumbnail-' . $item_id );
						?>
						<a href="#" data-id="<?php echo esc_attr( $item_id ); ?>" class="nmi_remove" onclick="<?php echo esc_attr( 'NMIRemoveThumbnail(\'' . $ajax_nonce . '\',' . $item_id . ');return false;' ); ?>"><?php echo esc_html__( 'Remove Image', 'ciyashop' ); ?></a>
						<?php
					}
					?>
				</div>
			</div>
		</div>
			<?php

			echo get_menu_field_settings( $item_id ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE

			break;
	}
	?>
	</div>
	<?php
	$output .= ob_get_clean();

	return $output;
}

/**
 * Menu field settings
 *
 * @param string $post_id .
 */
function get_menu_field_settings( $post_id ) {
	global $wp_version;

	// Only works for WP 3.5+.
	if ( ! version_compare( $wp_version, '3.5', '>=' ) ) {
		return;
	}

	// Prepare general settings.
	$settings = array();

	// Prepare post specific settings.
	$post = null;
	if ( isset( $post_id ) ) {
		$post             = get_post( $post_id );
		$settings['post'] = array(
			'id'    => $post->ID,
			'nonce' => wp_create_nonce( 'update-post_' . $post->ID ),
		);

		$featured_image_id                   = get_post_meta( $post->ID, '_thumbnail_id', true );
		$settings['post']['featuredImageId'] = $featured_image_id ? $featured_image_id : -1;
		$settings['post']['featuredExisted'] = $featured_image_id ? 1 : -1;
	}

	// Filter item's settins.
	$settings = apply_filters( 'media_view_settings', $settings, $post );

	// Prepare Javascript varible name.
	$object_name = 'nmi_settings[' . $post->ID . ']';

	// Loop through each setting and prepare it for JSON.
	foreach ( (array) $settings as $key => $value ) {
		if ( ! is_scalar( $value ) ) {
			continue;
		}

		$settings[ $key ] = html_entity_decode( (string) $value, ENT_QUOTES, 'UTF-8' );
	}

	// Encode settings to JSON.
	$script = "$object_name = " . wp_json_encode( $settings ) . ';';

	// If this is first item, register variable.
	if ( ! did_action( 'nmi_setup_settings_var' ) ) {
		$script = "var nmi_settings = [];\n" . $script;
		do_action( 'nmi_setup_settings_var', $post->ID );
	}

	// Wrap everythig.
	$output  = "<script type='text/javascript'>\n"; // CDATA and type='text/javascript' is not needed for HTML 5.
	$output .= "/* <![CDATA[ */\n";
	$output .= "$script\n";
	$output .= "/* ]]> */\n";
	$output .= "</script>\n";

	return $output;
}
