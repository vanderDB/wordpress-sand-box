<?php
/**
 * Admin header
 *
 * @package CiyaShop
 */

$class_prefix = $this->args['class_prefix'];
$current_tab  = $this->get_current_tab();
?>
<h2 class="hidden"></h2>
<div class="<?php echo esc_attr( $class_prefix ); ?>-welcome-header">
	<h1 class="wp-heading-inline"><?php echo esc_html( $this->args['screen_title'] ); ?></h1>
	<hr>
	<?php settings_errors(); ?>

	<div class="<?php echo esc_attr( $class_prefix ); ?>-welcome-description">
		<p>
		<?php
		echo sprintf(
			/* translators: $s: Theme Name */
			esc_html__( 'Congratulations! %1$s is now active and ready to use. %1$s is an elegant, clean, beautiful and responsive multipurpose WordPress theme.', 'ciyashop' ),
			'<strong>' . $this->theme_name . '</strong>'
		); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		?>
		</p>
		<div class="important">
			<p>
			<?php
			echo sprintf(
				/* translators: $s: Theme Name */
				esc_html__( '%1$s contains many useful features and functionalities. And, it requires some plugins to be pre-installed to enable all inbuilt features and functionalities.', 'ciyashop' ),
				'<strong>' . $this->theme_name . '</strong>'
			); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
			</p>
		</div>
	</div>

	<div class="<?php echo esc_attr( $class_prefix ); ?>-welcome-badge <?php echo esc_attr( $this->welcome_logo() ? $class_prefix . '-welcome-badge-with-logo' : $class_prefix . '-welcome-badge-without-logo' ); ?>">
		<div class="wp-badge">
		<?php
		if ( ! $this->welcome_logo() ) {
			echo esc_html( $this->theme_data['Name'] );
		}
		?>
		</div>
		<div class="<?php echo esc_attr( $class_prefix ); ?>-welcome-badge-version">
			<?php
			/* translators: $s: Theme Version */
			echo sprintf( esc_html__( 'Version %s', 'ciyashop' ), $this->theme_data['Version'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
		</div>
	</div>
</div>
