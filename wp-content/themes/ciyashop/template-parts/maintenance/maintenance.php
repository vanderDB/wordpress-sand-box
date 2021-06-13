<?php
/**
 * Default page template to display all pages
 *
 * @package CiyaShop
 * @author Potenza Global Solutions
 */

get_template_part( 'template-parts/maintenance/header' );
?>

<?php
global $ciyashop_options;
$maintenance_mode = $ciyashop_options['maintenance_mode'];
if ( empty( $maintenance_mode ) ) {
	$maintenance_mode = 'maintenance';
}

if ( 'comingsoon' === $maintenance_mode ) {
	$comingsoon_title    = $ciyashop_options['comingsoon_title'];
	$comingsoon_subtitle = $ciyashop_options['comingsoon_subtitle'];

	$mntc_cs_title   = ( ! empty( $comingsoon_title ) ) ? $comingsoon_title : esc_html__( 'Coming Soon', 'ciyashop' );
	$mntc_cs_sutitle = ( ! empty( $comingsoon_subtitle ) ) ? $comingsoon_subtitle : esc_html__( 'We are currently working on a website and won\'t take long. Don\'t forget to check out our Social updates.', 'ciyashop' );
} else {
	$maintenance_title    = $ciyashop_options['maintenance_title'];
	$maintenance_subtitle = $ciyashop_options['maintenance_subtitle'];

	$mntc_cs_title   = ( ! empty( $maintenance_title ) ) ? $maintenance_title : esc_html__( 'Site is Under Maintenance', 'ciyashop' );
	$mntc_cs_sutitle = ( ! empty( $maintenance_subtitle ) ) ? $maintenance_subtitle : esc_html__( 'This Site is Currently Under Maintenance. We will back shortly', 'ciyashop' );
}
?>
<div class="mntc-cs-main white-bg">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<div class="mntc-cs-item mntc-cs-content text-center">
					<i class="fa <?php echo esc_attr( ( 'comingsoon' === $maintenance_mode ) ? 'fa-clock-o' : 'fa-cog' ); ?> fa-spin fa-3x fa-fw margin-bottom"></i>
					<h1 class="text-blue"><?php echo esc_html( $mntc_cs_title ); ?></h1>
					<p><?php echo esc_html( $mntc_cs_sutitle ); ?></p>
				</div>
				<?php
				if ( 'comingsoon' === $maintenance_mode ) {
					$comingsoon_date = $ciyashop_options['comingsoon_date'];
					if ( empty( $comingsoon_date ) ) {
						$comingsoon_date = gmdate( 'm/d/Y', strtotime( '+1 months' ) );
					}
					$comingsoon_date = date_create_from_format( 'm/d/Y', $comingsoon_date );
					$comingsoon_date = $comingsoon_date->format( 'Y/m/d' );

					$counter_data = array(
						'days'    => esc_html__( 'Days', 'ciyashop' ),
						'hours'   => esc_html__( 'Hours', 'ciyashop' ),
						'minutes' => esc_html__( 'Minutes', 'ciyashop' ),
						'seconds' => esc_html__( 'Seconds', 'ciyashop' ),
					);

					$counter_data = wp_json_encode( $counter_data );
					?>
					<div class="mntc-cs-item mntc-cs-content coming-soon-countdown">
						<ul class="commingsoon_countdown" data-countdown_date="<?php echo esc_attr( $comingsoon_date ); ?>" data-counter_data="<?php echo esc_attr( $counter_data ); ?>"></ul>
					</div>
					<?php
				}

				$social_icons = ciyashop_social_profiles();

				if ( isset( $ciyashop_options['comming_soon_social_icons'] ) && 1 === (int) $ciyashop_options['comming_soon_social_icons'] && ! empty( $social_icons ) ) {
					?>

					<div class="social-profiles">
						<ul>
							<?php
							foreach ( $social_icons as $key => $value ) {
								?>
								<li class="social-<?php echo esc_attr( strtolower( $value['title'] ) ); ?>">
									<a href="<?php echo esc_url( $value['link'] ); ?>" target="_blank">
										<?php
										echo wp_kses(
											$value['icon'],
											array(
												'i' => array(
													'class' => array(),
												),
											)
										);
										?>
									</a>
								</li>
								<?php
							}
							?>
						</ul>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
<?php
// Newsletter.
get_template_part( 'template-parts/maintenance/newsletter' );

get_template_part( 'template-parts/maintenance/footer' );
