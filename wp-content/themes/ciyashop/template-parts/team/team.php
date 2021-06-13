<?php
/**
 * The template for displaying the Team
 *
 * @package Ciyashop
 */

global $ciyashop_options;
$team_page_query_base = array(
	'post_type'      => 'teams',
	'posts_per_page' => -1, // phpcs:ignore WPThemeReview.CoreFunctionality.PostsPerPage.posts_per_page_posts_per_page
);

$the_query = new WP_Query( $team_page_query_base );

$count       = 0;
$team_count  = 0;
$total_count = $the_query->post_count;

if ( $the_query->have_posts() ) :
	?>
	<div class="container">
		<?php
		while ( $the_query->have_posts() ) :
			$the_query->the_post();

			$count++;
			$team_count++;

			if ( 1 === (int) $team_count ) {
				?>
				<div class="row">
				<?php
			}
			?>
			<div class="col-lg-3 col-md-6 col-sm-6">
				<?php
				$designation = get_post_meta( get_the_ID(), 'designation', true );
				?>
				<div class="team shadow">
					<div class="team-images">
						<?php
						if ( has_post_thumbnail() ) {
							echo ciyashop_lazyload_thumbnail( get_the_ID(), 'ciyashop-team-member-thumbnail-v', array( 'img-fluid' ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
						} else {
							$member_img   = array();
							$member_img[] = get_parent_theme_file_uri( '/images/placeholder/team_members/259x482.png' );
							$member_img[] = 259;
							$member_img[] = 482;

							if ( isset( $ciyashop_options['enable_lazyload'] ) && $ciyashop_options['enable_lazyload'] ) {
								echo '<img class="img-fluid ciyashop-lazy-load" src="' . esc_url( LOADER_IMAGE ) . '" data-src="' . esc_url( $member_img[0] ) . '" width="' . esc_attr( $member_img[1] ) . '" height="' . esc_attr( $member_img[2] ) . '" alt="' . esc_attr( get_the_title() ) . '">';
							} else {
								echo '<img class="img-fluid" src="' . esc_url( $member_img[0] ) . '" width="' . esc_attr( $member_img[1] ) . '" height="' . esc_attr( $member_img[2] ) . '" alt="' . esc_attr( get_the_title() ) . '">';
							}
						}
						?>
					</div>
					<div class="team-info">
						<div class="team-description">
							<h4><?php echo esc_html( get_the_title() ); ?></h4>
							<?php
							if ( $designation ) {
								?>
								<span><?php echo esc_html( $designation ); ?></span>
								<?php
							}
							?>
						</div>
						<div class="team-social-icon pgssi-style-border pgscore-social-icons pgssi-shape-Round pgssi-effect-color-hover pgssi-size-small">
							<?php
							$social_profiles_total = get_post_meta( get_the_ID(), 'social_profiles', true );
							if ( $social_profiles_total ) {
								?>
								<ul>
									<?php
									for ( $i = 0; $i < $social_profiles_total; $i++ ) {
										$social_title = get_post_meta( get_the_ID(), 'social_profiles_' . $i . '_social_title', true );
										$social_icon  = get_post_meta( get_the_ID(), 'social_profiles_' . $i . '_social_icon', true );
										$social_url   = get_post_meta( get_the_ID(), 'social_profiles_' . $i . '_social_url', true );
										
										if ( $social_title && $social_icon && $social_url ) {
											$icon_classes   = array();
											$icon_classes[] = 'pgssi-item';
											$icon_classes[] = 'pgssi-color-' . sanitize_title( $social_title );
											$icon_classes   = ciyashop_class_builder( $icon_classes );
											?>
											<li class="<?php echo esc_attr( $icon_classes ); ?>">
												<a href="<?php echo esc_url( $social_url ); ?>" title="<?php echo esc_attr( $social_title ); ?>">
													<i class="fa <?php echo esc_attr( $social_icon ); ?>"></i>
												</a>
											</li>
											<?php
										}
									}
									?>
								</ul>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
			<?php
			if ( 4 === (int) $team_count || (int) $total_count === $count ) {
				?>
				</div>
				<?php
				$team_count = 0;
			}

		endwhile;
		wp_reset_postdata();
		?>
	</div>
	<?php
endif;
