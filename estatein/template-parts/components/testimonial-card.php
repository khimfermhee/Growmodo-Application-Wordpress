<?php
/**
 * Testimonial card.
 *
 * @package Estatein
 *
 * @var array $args { item: { title, quote, name, location, rating, image } }
 */

defined( 'ABSPATH' ) || exit;

$estatein_t      = $args['item'];
$estatein_rating = max( 1, min( 5, (int) $estatein_t['rating'] ) );
?>
<figure class="card testimonial-card">
	<p class="stars" role="img" aria-label="<?php echo esc_attr( sprintf( /* translators: %d: rating */ __( 'Rated %d out of 5', 'estatein' ), $estatein_rating ) ); ?>">
		<?php for ( $estatein_s = 0; $estatein_s < $estatein_rating; $estatein_s++ ) : ?>
			<span class="stars__star"><?php echo estatein_icon( 'star-solid', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		<?php endfor; ?>
	</p>
	<blockquote class="testimonial-card__quote">
		<p class="testimonial-card__title"><?php echo esc_html( $estatein_t['title'] ); ?></p>
		<p><?php echo esc_html( $estatein_t['quote'] ); ?></p>
	</blockquote>
	<figcaption class="person">
		<?php if ( $estatein_t['image'] ) : ?>
			<?php echo estatein_image( $estatein_t['image'], 'estatein-avatar', array( 'class' => 'person__avatar', 'alt' => '', 'width' => 50, 'height' => 50 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php else : ?>
			<span class="person__avatar person__avatar--initials" aria-hidden="true"><?php echo esc_html( mb_substr( $estatein_t['name'], 0, 1 ) ); ?></span>
		<?php endif; ?>
		<span class="person__meta">
			<cite class="person__name"><?php echo esc_html( $estatein_t['name'] ); ?></cite>
			<span class="person__location"><?php echo esc_html( $estatein_t['location'] ); ?></span>
		</span>
	</figcaption>
</figure>
