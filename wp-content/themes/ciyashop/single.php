<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package CiyaShop
 */

get_header();
global $ciyashop_blog_layout, $ciyashop_options;

$ciyashop_blog_layout  = isset( $ciyashop_options['blog_layout'] ) ? $ciyashop_options['blog_layout'] : 'classic';
$ciyashop_blog_sidebar = ( isset( $ciyashop_options['blog_sidebar'] ) && $ciyashop_options['blog_sidebar'] ) ? $ciyashop_options['blog_sidebar'] : 'right_sidebar';

if ( ( 'left_sidebar' === $ciyashop_blog_sidebar || 'right_sidebar' === $ciyashop_blog_sidebar ) && is_active_sidebar( 'sidebar-1' ) && 'static_block' !== get_post_type() ) {

	if ( 'left_sidebar' === $ciyashop_blog_sidebar ) {
		$content_class = 'col-sm-12 col-md-12 col-lg-8 col-xl-9 order-xl-2 order-lg-2';
	} else {
		$content_class = 'col-sm-12 col-md-12 col-lg-8 col-xl-9';
	}
} else {
	$content_class = 'col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12';
}
?> 
<div class="row">
	<div class="<?php echo esc_attr( $content_class ); ?>">
		<div id="primary" class="content-area">
			<main id="main" class="site-main">
				<?php
				while ( have_posts() ) :
					the_post();

					$part_1 = "template-parts/blog/$ciyashop_blog_layout/content";
					$part_2 = get_post_format();

					if ( ( $part_2 && locate_template( "$part_1-$part_2.php" ) ) || ( locate_template( "$part_1.php" ) ) ) {
						get_template_part( $part_1, $part_2 );
					} else {
						get_template_part( 'template-parts/blog/classic/content', get_post_format() );
					}

					$post_nav = ( isset( $ciyashop_options['post_nav'] ) ) ? $ciyashop_options['post_nav'] : true;

					if ( $post_nav && 'static_block' !== get_post_type() ) {
						the_post_navigation(
							array(
								'prev_text' => '&laquo; %title',
								'next_text' => '%title &raquo;',
							)
						);
					}

					ciyashop_authorbox();

					ciyashop_related_posts();

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

				endwhile; // End of the loop.
				?>
			</main><!-- #main -->
		</div><!-- #primary -->
	</div>		
	<?php
	if ( ( 'left_sidebar' === $ciyashop_blog_sidebar || 'right_sidebar' === $ciyashop_blog_sidebar ) && is_active_sidebar( 'sidebar-1' ) ) {
		get_sidebar();
	}
	?>
</div>
<?php get_footer(); ?>
