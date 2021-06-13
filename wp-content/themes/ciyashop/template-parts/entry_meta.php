<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Template part for displaying page content meta.
 *
 * @package CiyaShop
 */

global $ciyashop_options;

if ( is_single() ) {
	$blog_metas = ( isset( $ciyashop_options['single_metas'] ) ) ? $ciyashop_options['single_metas'] : array(
		'author'     => '1',
		'categories' => '1',
		'tags'       => '1',
		'comments'   => '1',
	);
} else {
	$blog_metas = ( isset( $ciyashop_options['blog_metas'] ) ) ? $ciyashop_options['blog_metas'] : array(
		'author'     => '1',
		'categories' => '1',
		'tags'       => '1',
		'comments'   => '1',
	);
}
if ( empty( $blog_metas ) ) {
	return;
}

?>
<div class="entry-meta">
	<ul>
		<?php
		foreach ( $blog_metas as $blog_meta_k => $blog_meta_v ) {
			if ( 'author' === $blog_meta_k && ! empty( $blog_meta_v ) ) {
				echo sprintf(
					'<li><span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author"><i class="fa fa-user"></i> %3$s</a></span></li>',
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					/* translators: $s: author link */
					esc_attr( sprintf( esc_html__( 'View all posts by %s', 'ciyashop' ), get_the_author() ) ),
					get_the_author()
				);
			}
			if ( 'comments' === $blog_meta_k && ! empty( $blog_meta_v ) ) {
				?>
				<li>
				<?php
				comments_popup_link(
					'<i class="fa fa-comments-o"></i> <span class="leave-comment">' . esc_html__( '0', 'ciyashop' ) . '</span>', // Zero Comment.
					'<i class="fa fa-comments-o"></i> <span class="leave-comment">' . esc_html__( '1', 'ciyashop' ) . '</span>', // One Comment.
					'<i class="fa fa-comments-o"></i> <span class="leave-comment">' . esc_html__( '%', 'ciyashop' ) . '</span>', // More Comments.
					'',
					/* translators: $s: get title */
					'<i class="fa fa-comments-o"></i> <span class="leave-comment">' . sprintf( __( 'Comments Off<span class="screen-reader-text"> on %s</span>', 'ciyashop' ), get_the_title() ) . '</span>'
				);
				?>
				</li>
				<?php
			}

			$category_list_args = array(
				'sep'   => ', ',
				'after' => '',
			);
			$category_list      = get_the_category_list( trim( $category_list_args['sep'] ) . ' ' );
			if ( 'categories' === $blog_meta_k && ! empty( $blog_meta_v ) && ! empty( $category_list ) ) {
				?>
				<li>
					<span class="entry-meta-categories">
						<i class="fa fa-folder-open"></i>&nbsp;
						<?php
						echo wp_kses(
							get_the_category_list( trim( $category_list_args['sep'] ) . ' ' ),
							array(
								'a' => array(
									'href'  => array(),
									'title' => array(),
									'class' => array(),
								),
							)
						);
						?>
					</span>
				</li>
				<?php
			}

			if ( 'tags' === $blog_meta_k && ! empty( $blog_meta_v ) && ! empty( get_the_tag_list() ) ) {
				?>
				<li>
					<span class="entry-meta-tags">
						<?php
						echo wp_kses(
							get_the_tag_list( '<i class="fa fa-tags"></i>', ', ' ),
							array(
								'a' => array(
									'href'  => array(),
									'title' => array(),
									'class' => array(),
								),
								'i' => array(
									'class' => array(),
								),
							)
						);
						?>
					</span>
				</li>
				<?php
			}
		}
		edit_post_link( '<i class="fa fa-pencil"></i> ' . esc_html__( 'Edit', 'ciyashop' ), '<li>', '</li>' );
		?>
	</ul>
</div>
