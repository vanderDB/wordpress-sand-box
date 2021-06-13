<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * The template for displaying the archive header
 *
 * @package Ciyashop
 */

global $ciyashop_options;
$archive_header = ( isset( $ciyashop_options['archive_header'] ) ) ? $ciyashop_options['archive_header'] : array(
	'author'   => true,
	'category' => true,
	'tag'      => true,
);

$archive_header_author   = $archive_header['author'];
$archive_header_category = $archive_header['category'];
$archive_header_tag      = $archive_header['tag'];

if ( is_author() && ! empty( $archive_header_author ) && get_the_author_meta( 'description' ) ) {
	?>
	<div class="archive-header">
		<div class="row">
			<div class="col-sm-12">
				<div class="author-info">
					<div class="author-avatar">
						<?php
						/**
						 * Filters the avatar thumbnail size.
						 *
						 * @param int $avatar_size Size of avatar. Default 68.
						 *
						 * @visible true
						 */
						$author_bio_avatar_size = apply_filters( 'ciyashop_bio_avatar_size', 68 );
						echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
						?>
					</div><!-- .author-avatar -->
					<div class="author-description">
						<h2>
							<?php
							/* translators: $s: abothor name */
							printf( esc_html__( 'About %s', 'ciyashop' ), get_the_author() );
							?>
						</h2>
						<p><?php the_author_meta( 'description' ); ?></p>
					</div><!-- .author-description	-->
				</div><!-- .author-info -->
			</div>
		</div>
	</div>
	<?php
} elseif ( is_category() && ! empty( $archive_header_category ) && category_description() ) {
	?>
	<div class="archive-header">
		<div class="row">
			<div class="col-sm-12">
				<div class="archive-description">
					<?php echo category_description(); ?>
				</div>
			</div>
		</div>
	</div>
	<?php
} elseif ( is_tag() && ! empty( $archive_header_tag ) && tag_description() ) {
	?>
	<div class="archive-header">
		<div class="row">
			<div class="col-sm-12">
				<div class="archive-description">
					<?php echo tag_description(); ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
