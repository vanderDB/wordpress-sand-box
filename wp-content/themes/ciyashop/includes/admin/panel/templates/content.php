<?php
/**
 * Admin Content
 *
 * @package CiyaShop
 */

$class_prefix = $this->args['class_prefix'];
$current_tab  = $this->get_current_tab();
$tabs         = $this->sections; // phpcs:ignore WordPress.WP.GlobalVariablesOverride

$tab_content_classes = array(
	$class_prefix . '-admin-tab_content',
	$class_prefix . '-admin-tab_content_' . $current_tab,
);

$tab_content_classes = implode( ' ', array_filter( array_unique( $tab_content_classes ) ) );
?>
<div class="wrap <?php echo esc_attr( $class_prefix ); ?>-welcome">

	<?php $this->get_template( 'header.php' ); ?>

	<div class="<?php echo esc_attr( $class_prefix ); ?>-welcome-content">
		<?php
		if ( count( $tabs ) > 1 ) {
			?>
			<div class="<?php echo esc_attr( $class_prefix ); ?>-admin-tabs">
				<?php $this->get_template( 'tabs.php' ); ?>
			</div>
			<?php
		}
		?>
		<div class="<?php echo esc_attr( $tab_content_classes ); ?>">
			<?php
			settings_fields( $this->theme_id . '_welcome' );
			do_settings_sections( $this->theme_id . '_welcome' );
			?>
			<div class="<?php echo esc_attr( $class_prefix ); ?>-admin-tab_content_inner">
				<div class="<?php echo esc_attr( $current_tab . '-content' ); ?>">
					<?php $this->get_template( "$current_tab.php" ); ?>
				</div>
			</div>
		</div>
	</div>
</div>
