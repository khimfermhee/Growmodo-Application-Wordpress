<?php
/**
 * Blog post card used in the loop (index, archive, search).
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card post-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="post-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php the_post_thumbnail( 'estatein-card', array( 'sizes' => '(min-width: 1200px) 400px, (min-width: 768px) 45vw, 90vw' ) ); ?>
		</a>
	<?php endif; ?>
	<div class="post-card__body">
		<p class="post-card__meta">
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			<?php if ( 'post' === get_post_type() && has_category() ) : ?>
				<span aria-hidden="true">·</span> <?php echo wp_kses_post( get_the_category_list( ', ' ) ); ?>
			<?php endif; ?>
		</p>
		<h2 class="post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<div class="post-card__excerpt"><?php the_excerpt(); ?></div>
		<a class="btn btn--secondary" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'estatein' ); ?><span class="screen-reader-text"> <?php the_title(); ?></span></a>
	</div>
</article>
