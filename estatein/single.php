<?php
/**
 * Single blog post.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
		<header class="page-hero">
			<div class="container container--narrow">
				<p class="entry__meta">
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
					<?php if ( has_category() ) : ?>
						<span aria-hidden="true">·</span> <?php echo wp_kses_post( get_the_category_list( ', ' ) ); ?>
					<?php endif; ?>
				</p>
				<h1 class="page-hero__title" id="page-title"><?php the_title(); ?></h1>
			</div>
		</header>

		<div class="section">
			<div class="container layout-sidebar">
				<div class="layout-sidebar__main">
					<?php if ( has_post_thumbnail() ) : ?>
						<figure class="entry__media">
							<?php the_post_thumbnail( 'large', array( 'loading' => 'eager', 'sizes' => '(min-width: 1200px) 840px, 100vw' ) ); ?>
						</figure>
					<?php endif; ?>
					<div class="entry__content">
						<?php
						the_content();
						wp_link_pages();
						?>
					</div>
					<?php the_tags( '<p class="entry__tags">', ' ', '</p>' ); ?>
					<?php
					the_post_navigation(
						array(
							'prev_text' => '<span class="post-nav__label">' . esc_html__( 'Previous', 'estatein' ) . '</span> %title',
							'next_text' => '<span class="post-nav__label">' . esc_html__( 'Next', 'estatein' ) . '</span> %title',
						)
					);
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					?>
				</div>
				<?php get_sidebar(); ?>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
