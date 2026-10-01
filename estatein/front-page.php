<?php
/**
 * Home page.
 *
 * Sections: hero → quick links → featured properties → testimonials → FAQ.
 * Copy is edited under Pages → Home (Home Page Content), listings under
 * Properties (tick "Featured"), testimonials and FAQs in their own menus.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();

estatein_part( 'home/hero' );
estatein_part( 'components/quick-links' );

estatein_part(
	'components/carousel-section',
	array(
		'id'       => 'featured-properties',
		'heading'  => array(
			'title'        => estatein_page_field( 'home', 'featured_title' ),
			'text'         => estatein_page_field( 'home', 'featured_text' ),
			'button_label' => estatein_page_field( 'home', 'featured_button' ),
			'button_url'   => estatein_properties_url(),
		),
		'items'    => estatein_get_properties( true ),
		'card'     => 'components/property-card',
		'item_key' => 'property',
		'label'    => __( 'featured properties', 'estatein' ),
	)
);

estatein_part(
	'components/carousel-section',
	array(
		'id'      => 'testimonials',
		'heading' => array(
			'title'        => estatein_page_field( 'home', 'testimonials_title' ),
			'text'         => estatein_page_field( 'home', 'testimonials_text' ),
			'button_label' => estatein_page_field( 'home', 'testimonials_button' ),
			'button_url'   => get_post_type_archive_link( 'testimonial' ),
		),
		'items'   => estatein_get_testimonials(),
		'card'    => 'components/testimonial-card',
		'label'   => __( 'testimonials', 'estatein' ),
	)
);

estatein_part(
	'components/carousel-section',
	array(
		'id'      => 'faq',
		'heading' => array(
			'title'        => estatein_page_field( 'home', 'faq_title' ),
			'text'         => estatein_page_field( 'home', 'faq_text' ),
			'button_label' => estatein_page_field( 'home', 'faq_button' ),
			'button_url'   => get_post_type_archive_link( 'faq' ),
		),
		'items'   => estatein_get_faqs(),
		'card'    => 'components/faq-card',
		'label'   => __( 'frequently asked questions', 'estatein' ),
	)
);

get_footer();
