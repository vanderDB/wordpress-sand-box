<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package CiyaShop
 */

get_header();
global $ciyashop_options;
?>
<div class="row align-items-center">
	<?php
	$content_class = 'col-sm-12 content-no-image';
	if ( isset( $ciyashop_options['fourofour_page_content_source'] ) && 'default' === $ciyashop_options['fourofour_page_content_source'] && isset( $ciyashop_options['fourofour_page_content_image'] ) && ! empty( $ciyashop_options['fourofour_page_content_image']['url'] ) ) {
		$content_class = 'col-lg-7 text-center text-lg-left';
	} elseif ( ( isset( $ciyashop_options['fourofour_page_content_source'] ) && 'page' === $ciyashop_options['fourofour_page_content_source'] ) && ( isset( $ciyashop_options['fourofour_page_content_page'] ) && '' !== $ciyashop_options['fourofour_page_content_page'] ) && get_post( $ciyashop_options['fourofour_page_content_page'] ) ) {
		$content_class = 'col-sm-12';
	}

	// Content Style.
	$content_css   = '';
	$content_style = array();

	if ( isset( $ciyashop_options['fourofour_page_content_source'] ) && 'page' !== $ciyashop_options['fourofour_page_content_source'] ) {
		// Padding.
		if ( isset( $ciyashop_options['fourofour_page_content_padding'] ) && is_array( $ciyashop_options['fourofour_page_content_padding'] ) && ! empty( $ciyashop_options['fourofour_page_content_padding'] ) ) {
			if ( isset( $ciyashop_options['fourofour_page_content_padding']['padding-top'] ) && ! empty( $ciyashop_options['fourofour_page_content_padding']['padding-top'] ) ) {
				$content_style['padding-top'] = 'padding-top:' . $ciyashop_options['fourofour_page_content_padding']['padding-top'];
			}
			if ( isset( $ciyashop_options['fourofour_page_content_padding']['padding-right'] ) && ! empty( $ciyashop_options['fourofour_page_content_padding']['padding-right'] ) ) {
				$content_style['padding-right'] = 'padding-right:' . $ciyashop_options['fourofour_page_content_padding']['padding-right'];
			}
			if ( isset( $ciyashop_options['fourofour_page_content_padding']['padding-bottom'] ) && ! empty( $ciyashop_options['fourofour_page_content_padding']['padding-bottom'] ) ) {
				$content_style['padding-bottom'] = 'padding-bottom:' . $ciyashop_options['fourofour_page_content_padding']['padding-bottom'];
			}
			if ( isset( $ciyashop_options['fourofour_page_content_padding']['padding-left'] ) && ! empty( $ciyashop_options['fourofour_page_content_padding']['padding-left'] ) ) {
				$content_style['padding-left'] = 'padding-left:' . $ciyashop_options['fourofour_page_content_padding']['padding-left'];
			}
		}

		// Margin.
		if ( isset( $ciyashop_options['fourofour_page_content_margin'] ) && is_array( $ciyashop_options['fourofour_page_content_margin'] ) && ! empty( $ciyashop_options['fourofour_page_content_margin'] ) ) {
			if ( isset( $ciyashop_options['fourofour_page_content_margin']['margin-top'] ) && ! empty( $ciyashop_options['fourofour_page_content_margin']['margin-top'] ) ) {
				$content_style['margin-top'] = 'margin-top:' . $ciyashop_options['fourofour_page_content_margin']['margin-top'];
			}
			if ( isset( $ciyashop_options['fourofour_page_content_margin']['margin-right'] ) && ! empty( $ciyashop_options['fourofour_page_content_margin']['margin-right'] ) ) {
				$content_style['margin-right'] = 'margin-right:' . $ciyashop_options['fourofour_page_content_margin']['margin-right'];
			}
			if ( isset( $ciyashop_options['fourofour_page_content_margin']['margin-bottom'] ) && ! empty( $ciyashop_options['fourofour_page_content_margin']['margin-bottom'] ) ) {
				$content_style['margin-bottom'] = 'margin-bottom:' . $ciyashop_options['fourofour_page_content_margin']['margin-bottom'];
			}
			if ( isset( $ciyashop_options['fourofour_page_content_margin']['margin-left'] ) && ! empty( $ciyashop_options['fourofour_page_content_margin']['margin-left'] ) ) {
				$content_style['margin-left'] = 'margin-left:' . $ciyashop_options['fourofour_page_content_margin']['margin-left'];
			}
		}
	}

	if ( is_array( $content_style ) && ! empty( $content_style ) ) {
		$content_css = implode( ';', array_filter( array_unique( $content_style ) ) );
	}

	$page_content_type = 'default';
	$page_content_post = '';
	$article_id        = 0;
	$article_class     = 'post error404 no-results not-found';

	if ( isset( $ciyashop_options['fourofour_page_content_source'] ) && 'page' === $ciyashop_options['fourofour_page_content_source'] && isset( $ciyashop_options['fourofour_page_content_page'] ) && ! empty( $ciyashop_options['fourofour_page_content_page'] ) ) {
		global $post;
		$fourofour_page_id = $ciyashop_options['fourofour_page_content_page'];
		$fourofour_page    = get_post( (int) $fourofour_page_id );

		if ( $fourofour_page ) {
			$page_content_type = 'page';
			$page_content_post = $fourofour_page;
			$article_id        = $fourofour_page_id;
			$article_class     = '';
		}
	}
	?>
	<div class="<?php echo esc_attr( $content_class ); ?>">
		<div id="primary" class="content-area">
			<main id="main" class="site-main">
				<article id="post-<?php echo esc_attr( $article_id ); ?>" <?php post_class( $article_class ); ?>>
					<div class="entry-content" style="<?php echo esc_attr( $content_css ); ?>">
						<?php
						if ( 'page' === $page_content_type ) {
							$wp_query_data = null;
							$wp_query_data = new WP_Query();
							$wp_query_data->query( 'page_id=' . (int) $fourofour_page_id );

							$wp_query_data->the_post();
							$template = get_page_template();
							the_content();
							rewind_posts();

						} else {
							$page_content_title       = esc_html__( '404', 'ciyashop' );
							$page_content_subtitle    = esc_html__( "Oops ! Sorry We Can't Find That Page.", 'ciyashop' );
							$page_content_description = sprintf(
								wp_kses(
									/* translators: $s: Link to Home */
									__( "Can't find what you looking for? Take a moment and do a search below or start from our <a class='error-search-box-description-link' href='%s'>Home Page</a>", 'ciyashop' ),
									array(
										'a' => array(
											'class' => array(),
											'href'  => array(),
										),
									)
								),
								esc_url( home_url( '/' ) )
							);

							if ( isset( $ciyashop_options['fourofour_page_content_title'] ) && ! empty( $ciyashop_options['fourofour_page_content_title'] ) ) {
								$page_content_title = $ciyashop_options['fourofour_page_content_title'];
							}
							if ( isset( $ciyashop_options['fourofour_page_content_subtitle'] ) && ! empty( $ciyashop_options['fourofour_page_content_subtitle'] ) ) {
								$page_content_subtitle = $ciyashop_options['fourofour_page_content_subtitle'];
							}
							if ( isset( $ciyashop_options['fourofour_page_content_description'] ) && ! empty( $ciyashop_options['fourofour_page_content_description'] ) ) {
								$page_content_description = $ciyashop_options['fourofour_page_content_description'];
							}

							?>
							<div class="error-block clearfix">
								<h1 class="error-block-title"><?php echo esc_html( $page_content_title ); ?></h1>
								<p class="error-block-subtitle"><?php echo esc_html( $page_content_subtitle ); ?></p>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="error-search-box">
										<p class="error-search-box-description">
										<?php
										echo wp_kses(
											$page_content_description,
											array(
												'a'      => array(
													'href' => array(),
													'title' => array(),
													'class' => array(),
												),
												'br'     => array(),
												'em'     => array(),
												'strong' => array(),
											)
										);
										?>
										</p>
										<div class="fourofour-searchform">
											<?php get_search_form(); ?>
										</div>
									</div>
								</div>
							</div>
							<?php
						}
						?>
					</div><!-- .page-content -->
				</article><!-- .error-404 -->

			</main><!-- #main -->
		</div><!-- #primary -->
	</div>
	<?php
	if ( isset( $ciyashop_options['fourofour_page_content_source'] ) && 'default' === $ciyashop_options['fourofour_page_content_source'] && isset( $ciyashop_options['fourofour_page_content_image'] ) && ! empty( $ciyashop_options['fourofour_page_content_image']['url'] ) ) {
			$page_content_image = $ciyashop_options['fourofour_page_content_image']['url'];
		?>
			<div class="col-sm-5 d-none d-lg-block d-xl-block">
				<div class="content-404-image">
					<?php
					if ( isset( $ciyashop_options['enable_lazyload'] ) && $ciyashop_options['enable_lazyload'] ) {
						echo '<img class="img-fluid ciyashop-lazy-load" src="' . esc_url( LOADER_IMAGE ) . '" data-src="' . esc_url( $page_content_image ) . '" alt="' . esc_attr__( '404 Image', 'ciyashop' ) . '">';
					} else {
						echo '<img class="img-fluid" src="' . esc_url( $page_content_image ) . '" alt="' . esc_attr__( '404 Image', 'ciyashop' ) . '">';
					}
					?>
				</div>
			</div>
			<?php
	}
	?>
</div>
<?php
get_footer();
