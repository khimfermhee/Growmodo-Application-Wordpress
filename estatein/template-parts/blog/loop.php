<?php
/**
 * Standard WordPress Loop with pagination and an empty state.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

if ( have_posts() ) :
	?>
	<div class="post-grid">
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/blog/card' );
		endwhile;
		?>
	</div>
	<?php
	the_posts_pagination(
		array(
			'class'     => 'pagination',
			'prev_text' => estatein_icon( 'arrow-left' ) . '<span class="screen-reader-text">' . esc_html__( 'Previous page', 'estatein' ) . '</span>',
			'next_text' => estatein_icon( 'arrow-right' ) . '<span class="screen-reader-text">' . esc_html__( 'Next page', 'estatein' ) . '</span>',
		)
	);
else :
	?>
	<div class="card empty-state">
		<h2><?php esc_html_e( 'Nothing found', 'estatein' ); ?></h2>
		<p><?php esc_html_e( 'We couldn\'t find what you were looking for. Try another search.', 'estatein' ); ?></p>
		<?php get_search_form(); ?>
	</div>
	<?php
endif;
