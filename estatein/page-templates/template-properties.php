<?php
/**
 * Template Name: Properties
 *
 * Hero + search/filter bar → listing carousel (or filtered results grid)
 * → "Let's Make it Happen" inquiry form.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();

$estatein_request = estatein_property_request();
$estatein_active  = estatein_property_search_active( $estatein_request );

estatein_part(
	'components/page-hero',
	array(
		'title' => estatein_page_field( 'properties', 'hero_title' ),
		'text'  => estatein_page_field( 'properties', 'hero_text' ),
		'class' => 'page-hero--with-search',
	)
);

estatein_part( 'properties/search', array( 'request' => $estatein_request ) );

if ( $estatein_active ) {
	estatein_part( 'properties/results', array( 'request' => $estatein_request ) );
} else {
	estatein_part(
		'components/carousel-section',
		array(
			'id'        => 'listing',
			'heading'   => array(
				'title' => estatein_page_field( 'properties', 'listing_title' ),
				'text'  => estatein_page_field( 'properties', 'listing_text' ),
			),
			'items'     => estatein_get_properties( false, 12 ),
			'card'      => 'components/property-card',
			'item_key'  => 'property',
			'card_args' => array( 'variant' => 'tagline' ),
			'label'     => __( 'properties', 'estatein' ),
		)
	);
}
?>

<section class="section" id="inquiry" aria-labelledby="inquiry-title">
	<div class="container">
		<?php
		estatein_part(
			'components/section-heading',
			array(
				'title' => estatein_page_field( 'properties', 'form_title' ),
				'text'  => estatein_page_field( 'properties', 'form_text' ),
				'id'    => 'inquiry-title',
			)
		);
		estatein_part( 'components/inquiry-form', array( 'form' => 'properties' ) );
		?>
	</div>
</section>

<?php
get_footer();
