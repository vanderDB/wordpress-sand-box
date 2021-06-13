<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package CiyaShop
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	if ( have_comments() ) {
		?>
		<h3 class="h2 comments comments-title">
			<?php
			$comments_number = get_comments_number();
			if ( '1' === $comments_number ) {
				/* translators: %s: post title */
				printf( esc_html__( 'One Reply to &ldquo;%s&rdquo;', 'ciyashop' ), esc_html( get_the_title() ) );
			} else {
				printf(
					/* translators: 1: number of comments, 2: post title */
					esc_html( _nx( '%1$s Reply to &ldquo;%2$s&rdquo;', '%1$s Replies to &ldquo;%2$s&rdquo;', $comments_number, 'comments title', 'ciyashop' ) ),
					esc_html( number_format_i18n( $comments_number ) ),
					esc_html( get_the_title() )
				);
			}
			?>
		</h3>

		<?php ciyashop_comment_nav(); ?>

		<ol class="commentlist">
			<?php
			wp_list_comments(
				array(
					'callback' => 'ciyashop_comments',
					'style'    => 'ol',
				)
			);
			?>
		</ol>

		<?php ciyashop_comment_nav(); ?>
		<?php
	}

	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) {
		?>
		<p class='comment_close'><?php echo esc_html__( 'Comments are closed', 'ciyashop' ); ?></p>
		<?php
	}

	if ( comments_open() ) {
		?>
		<section class="respond-form">
			<?php
			$req      = get_option( 'require_name_email' );
			$aria_req = ( $req ? " aria-required='true'" : '' );

			$commenter = wp_get_current_commenter();
			$consent   = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';

			$comments_args = array(
				'class_form'          => 'comment-form contact-form',
				'title_reply_before'  => '<h3 id="reply-title" class="comment-reply-title text-blue">',
				'comment_notes_after' => '', // remove "Text or HTML to be displayed after the set of comment fields".
				'class_submit'        => 'submit button pull-left',
				/**
				 * Filters the default comment form fields.
				 *
				 * @since 3.0.0
				 *
				 * @param array $fields The default comment fields.
				 * @visible false
				 * @ignore
				 */
				'fields'              => apply_filters(
					'comment_form_default_fields',
					array(
						'author'  => '<div class="section-field comment-form-author">' .
							'<i class="fa fa-user">&nbsp;</i>' .
							'<input id="author" class="placeholder" placeholder="' . esc_attr__( 'Name', 'ciyashop' ) . '*" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />' .
							'</div>',
						'email'   => '<div class="section-field comment-form-email">' .
							'<i class="fa fa-envelope-o">&nbsp;</i>' .
							'<input id="email" class="placeholder" placeholder="' . esc_attr__( 'Email', 'ciyashop' ) . '*" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />' .
							'</div>',
						'url'     => '<div class="section-field comment-form-url">' .
							'<i class="fa fa-envelope-o">&nbsp;</i>' .
							'<input id="url" name="url" placeholder="' . esc_attr__( 'Website', 'ciyashop' ) . '" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /> ' .
							'</div>',
						'cookies' => '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />' .
							'<label for="wp-comment-cookies-consent">' . esc_html__( 'Save my name, email, and website in this browser for the next time I comment.', 'ciyashop' ) . '</label></p>',
					)
				),
				'comment_field'       => '<div class="section-field textarea comment-form-comment">' .
					'<i class="fa fa-pencil">&nbsp;</i>' .
					'<textarea id="comment" class="input-message placeholder" name="comment" placeholder="' . esc_attr__( 'Comment', 'ciyashop' ) . '" cols="45" rows="8" aria-required="true"></textarea>' .
					'</div>',
			);
			comment_form( $comments_args );
			// If registration required and not logged in.
			?>
		</section>
		<?php
	} // if you delete this the sky will fall on your head.
	?>
</div>
