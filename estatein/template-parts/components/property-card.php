<?php
/**
 * Property card (home "Featured Properties" and the Properties listing).
 *
 * @package Estatein
 *
 * @var array $args {
 *     @type array  $property Property data (estatein_property_data()).
 *     @type string $variant  "features" (beds/baths/type pills) or "tagline" (category pill).
 *     @type string $heading  Heading tag, default h3.
 * }
 */

defined( 'ABSPATH' ) || exit;

$estatein_p       = $args['property'];
$estatein_variant = isset( $args['variant'] ) ? $args['variant'] : 'features';
$estatein_heading = isset( $args['heading'] ) ? $args['heading'] : 'h3';
$estatein_title_id = 'property-' . sanitize_title( $estatein_p['title'] ) . '-' . wp_unique_id();
?>
<article class="card property-card" aria-labelledby="<?php echo esc_attr( $estatein_title_id ); ?>">
	<div class="property-card__media">
		<?php
		echo estatein_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$estatein_p['image'],
			'estatein-card',
			array(
				'alt'   => $estatein_p['title'],
				'sizes' => '(min-width: 1200px) 400px, (min-width: 768px) 45vw, 90vw',
			)
		);
		?>
	</div>

	<?php if ( 'tagline' === $estatein_variant && $estatein_p['tagline'] ) : ?>
		<p class="pill property-card__tagline"><?php echo esc_html( $estatein_p['tagline'] ); ?></p>
	<?php endif; ?>

	<div class="property-card__body">
		<<?php echo esc_html( $estatein_heading ); ?> class="property-card__title" id="<?php echo esc_attr( $estatein_title_id ); ?>"><?php echo esc_html( $estatein_p['title'] ); ?></<?php echo esc_html( $estatein_heading ); ?>>
		<p class="property-card__excerpt">
			<?php echo esc_html( wp_trim_words( $estatein_p['excerpt'], 12, '' ) ); ?>...
			<a class="property-card__more" href="<?php echo esc_url( $estatein_p['url'] ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: property name */ __( 'Read more about %s', 'estatein' ), $estatein_p['title'] ) ); ?>"><?php esc_html_e( 'Read More', 'estatein' ); ?></a>
		</p>
	</div>

	<?php if ( 'features' === $estatein_variant ) : ?>
		<ul class="property-card__features" aria-label="<?php esc_attr_e( 'Key facts', 'estatein' ); ?>">
			<?php if ( '' !== (string) $estatein_p['bedrooms'] ) : ?>
				<li class="pill"><?php echo estatein_icon( 'bed', array( 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( sprintf( /* translators: %s: number of bedrooms */ __( '%s-Bedroom', 'estatein' ), $estatein_p['bedrooms'] ) ); ?></li>
			<?php endif; ?>
			<?php if ( '' !== (string) $estatein_p['bathrooms'] ) : ?>
				<li class="pill"><?php echo estatein_icon( 'bath', array( 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( sprintf( /* translators: %s: number of bathrooms */ __( '%s-Bathroom', 'estatein' ), $estatein_p['bathrooms'] ) ); ?></li>
			<?php endif; ?>
			<?php if ( $estatein_p['type'] ) : ?>
				<li class="pill"><?php echo estatein_icon( 'building', array( 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $estatein_p['type'] ); ?></li>
			<?php endif; ?>
		</ul>
	<?php endif; ?>

	<div class="property-card__footer">
		<p class="price">
			<span class="price__label"><?php esc_html_e( 'Price', 'estatein' ); ?></span>
			<span class="price__value"><?php echo esc_html( estatein_price( $estatein_p['price'] ) ); ?></span>
		</p>
		<a class="btn btn--primary property-card__cta" href="<?php echo esc_url( $estatein_p['url'] ); ?>">
			<?php esc_html_e( 'View Property Details', 'estatein' ); ?>
			<span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %s: property name */ __( 'for %s', 'estatein' ), $estatein_p['title'] ) ); ?></span>
		</a>
	</div>
</article>
