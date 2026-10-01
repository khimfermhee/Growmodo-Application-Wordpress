<?php
/**
 * Comments.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="comments card">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments__title">
			<?php
			/* translators: %s: number of comments */
			printf( esc_html( _n( '%s Comment', '%s Comments', get_comments_number(), 'estatein' ) ), esc_html( number_format_i18n( get_comments_number() ) ) );
			?>
		</h2>
		<ol class="comments__list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'class_submit' => 'btn btn--primary',
			'title_reply'  => __( 'Leave a comment', 'estatein' ),
		)
	);
	?>
</section>
