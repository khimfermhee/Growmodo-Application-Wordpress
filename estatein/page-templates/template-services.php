<?php
/**
 * Template Name: Services
 *
 * Hero → quick links → one section per Service Section term.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();

estatein_part(
	'components/page-hero',
	array(
		'title' => estatein_page_field( 'services', 'hero_title' ),
		'text'  => estatein_page_field( 'services', 'hero_text' ),
	)
);

estatein_part( 'components/quick-links' );

foreach ( estatein_get_service_sections() as $estatein_section ) {
	estatein_part( 'services/section', array( 'section' => $estatein_section ) );
}

get_footer();
