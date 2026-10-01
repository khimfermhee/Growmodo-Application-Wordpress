<?php
/**
 * Property search bar + filter dropdowns (GET form – shareable URLs, works without JS).
 *
 * @package Estatein
 *
 * @var array $args { request: array }
 */

defined( 'ABSPATH' ) || exit;

$estatein_request = $args['request'];
$estatein_filters = estatein_property_filters();
?>
<section class="property-search" id="property-search" aria-label="<?php esc_attr_e( 'Search properties', 'estatein' ); ?>">
	<div class="container">
		<form class="property-search__form" role="search" method="get" action="<?php echo esc_url( get_permalink() ); ?>#listing">
			<div class="property-search__bar">
				<label class="screen-reader-text" for="property-q"><?php esc_html_e( 'Search for a property', 'estatein' ); ?></label>
				<input class="property-search__input" id="property-q" type="search" name="q" value="<?php echo esc_attr( $estatein_request['q'] ); ?>" placeholder="<?php esc_attr_e( 'Search For A Property', 'estatein' ); ?>">
				<button class="btn btn--primary property-search__submit" type="submit">
					<?php echo estatein_icon( 'search', array( 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php esc_html_e( 'Find Property', 'estatein' ); ?>
				</button>
			</div>

			<div class="property-search__filters">
				<?php foreach ( $estatein_filters as $estatein_key => $estatein_filter ) : ?>
					<div class="filter">
						<label class="screen-reader-text" for="filter-<?php echo esc_attr( $estatein_key ); ?>"><?php echo esc_html( $estatein_filter['label'] ); ?></label>
						<span class="filter__icon" aria-hidden="true"><?php echo estatein_icon( $estatein_filter['icon'], array( 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<select class="filter__select" id="filter-<?php echo esc_attr( $estatein_key ); ?>" name="<?php echo esc_attr( $estatein_key ); ?>">
							<option value=""><?php echo esc_html( $estatein_filter['label'] ); ?></option>
							<?php foreach ( $estatein_filter['choices'] as $estatein_value => $estatein_label ) : ?>
								<option value="<?php echo esc_attr( $estatein_value ); ?>" <?php selected( $estatein_request[ $estatein_key ], $estatein_value ); ?>><?php echo esc_html( $estatein_label ); ?></option>
							<?php endforeach; ?>
						</select>
						<span class="filter__chevron" aria-hidden="true"><?php echo estatein_icon( 'chevron-down', array( 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		</form>
	</div>
</section>
