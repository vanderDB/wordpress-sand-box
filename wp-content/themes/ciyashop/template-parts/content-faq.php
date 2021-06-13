<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package CiyaShop
 */

?>

<?php
global $ciyashop_options, $ciyashop_faq_layout;

/**
 * Fires before faq page content initiated.
 *
 * @visible false
 * @ignore
 */
do_action( 'faq_init' );

// FAQ Layout (Settings From Theme Options).
$ciyashop_faq_layout = 'layout_1';
$faq_layout_option   = isset( $ciyashop_options['faq_layout'] ) ? $ciyashop_options['faq_layout'] : '';
if ( ! empty( $faq_layout_option ) ) {
	$ciyashop_faq_layout = $faq_layout_option;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	if ( ! is_front_page() && ! ciyashop_show_header() ) {
		?>
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header><!-- .entry-header -->
		<?php
	}
	?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ciyashop' ),
				'after'  => '</div>',
			)
		);
		?>

		<div class="faqs-wrapper faq-layout-<?php echo esc_attr( $ciyashop_faq_layout ); ?>">
			<?php get_template_part( 'template-parts/faq/' . $ciyashop_faq_layout ); ?>
		</div>

	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post */
					esc_html__( 'Edit %s', 'ciyashop' ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-## -->
