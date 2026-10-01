<?php
/**
 * Basic SEO: meta description, Open Graph / Twitter cards and JSON-LD.
 *
 * Steps aside automatically when Yoast SEO, Rank Math or SEOPress is active.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is a dedicated SEO plugin handling meta tags?
 *
 * @return bool
 */
function estatein_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'SEOPRESS_VERSION' );
}

/**
 * Best description for the current view.
 *
 * @return string
 */
function estatein_meta_description() {
	$text = '';

	if ( is_front_page() ) {
		$text = estatein_page_field( 'home', 'hero_text' );
	} elseif ( is_singular() ) {
		$post = get_queried_object();
		if ( has_excerpt( $post ) ) {
			$text = get_the_excerpt( $post );
		} else {
			$template = get_page_template_slug( $post );
			$map      = array(
				'page-templates/template-about.php'      => array( 'about', 'journey_text' ),
				'page-templates/template-properties.php' => array( 'properties', 'hero_text' ),
				'page-templates/template-services.php'   => array( 'services', 'hero_text' ),
				'page-templates/template-contact.php'    => array( 'contact', 'hero_text' ),
			);
			if ( isset( $map[ $template ] ) ) {
				$text = estatein_page_field( $map[ $template ][0], $map[ $template ][1] );
			} else {
				$text = wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
			}
		}
	} elseif ( is_post_type_archive( 'testimonial' ) ) {
		$text = estatein_default( 'home.testimonials_text' );
	} elseif ( is_post_type_archive( 'faq' ) ) {
		$text = estatein_default( 'home.faq_text' );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$text = term_description();
	}

	if ( ! $text ) {
		$text = get_bloginfo( 'description' );
	}

	return wp_html_excerpt( wp_strip_all_tags( (string) $text ), 158, '…' );
}

/**
 * Print meta description and social tags.
 */
function estatein_social_meta() {
	if ( estatein_seo_plugin_active() || is_404() ) {
		return;
	}

	$description = estatein_meta_description();
	$title       = wp_get_document_title();
	$url         = is_singular() ? get_permalink() : home_url( add_query_arg( array() ) );
	$image       = '';

	if ( is_singular() && has_post_thumbnail() ) {
		$image = get_the_post_thumbnail_url( null, 'large' );
	}
	if ( ! $image ) {
		$image = ESTATEIN_URI . '/assets/images/og-default.jpg';
	}

	$tags = array(
		'description'         => $description,
		'og:type'             => is_singular( array( 'post', 'property' ) ) ? 'article' : 'website',
		'og:site_name'        => get_bloginfo( 'name' ),
		'og:title'            => $title,
		'og:description'      => $description,
		'og:url'              => $url,
		'og:image'            => $image,
		'twitter:card'        => 'summary_large_image',
		'twitter:title'       => $title,
		'twitter:description' => $description,
		'twitter:image'       => $image,
	);

	foreach ( $tags as $key => $content ) {
		if ( '' === (string) $content ) {
			continue;
		}
		$attr = 0 === strpos( $key, 'og:' ) ? 'property' : 'name';
		printf( '<meta %1$s="%2$s" content="%3$s">' . "\n", esc_attr( $attr ), esc_attr( $key ), esc_attr( $content ) );
	}
}
add_action( 'wp_head', 'estatein_social_meta', 5 );

/**
 * Structured data (JSON-LD).
 */
function estatein_json_ld() {
	if ( estatein_seo_plugin_active() ) {
		return;
	}

	$graph = array();

	if ( is_front_page() ) {
		$graph[] = array(
			'@type'     => 'RealEstateAgent',
			'name'      => get_bloginfo( 'name' ),
			'url'       => home_url( '/' ),
			'email'     => estatein_option( 'contact_email' ),
			'telephone' => estatein_option( 'contact_phone' ),
			'logo'      => ESTATEIN_URI . '/assets/images/logo.svg',
			'sameAs'    => array_values( wp_list_pluck( estatein_socials(), 'url' ) ),
		);
	}

	if ( is_singular( 'property' ) ) {
		$id      = get_queried_object_id();
		$data    = estatein_property_data( $id );
		$graph[] = array(
			'@type'         => 'RealEstateListing',
			'name'          => $data['title'],
			'description'   => $data['excerpt'],
			'url'           => $data['url'],
			'image'         => get_the_post_thumbnail_url( $id, 'large' ),
			'datePosted'    => get_the_date( 'c', $id ),
			'offers'        => array(
				'@type'         => 'Offer',
				'price'         => is_numeric( $data['price'] ) ? (float) $data['price'] : null,
				'priceCurrency' => 'USD',
			),
			'contentLocation' => array(
				'@type' => 'Place',
				'name'  => $data['location'],
			),
		);
	}

	if ( is_post_type_archive( 'faq' ) ) {
		$entities = array();
		foreach ( estatein_get_faqs( 50 ) as $faq ) {
			$entities[] = array(
				'@type'          => 'Question',
				'name'           => $faq['question'],
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => wp_strip_all_tags( $faq['answer'] ),
				),
			);
		}
		$graph[] = array(
			'@type'      => 'FAQPage',
			'mainEntity' => $entities,
		);
	}

	if ( ! $graph ) {
		return;
	}

	$json = array(
		'@context' => 'https://schema.org',
		'@graph'   => $graph,
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'estatein_json_ld', 20 );

/**
 * Use a cleaner title separator.
 *
 * @return string
 */
function estatein_title_separator() {
	return '|';
}
add_filter( 'document_title_separator', 'estatein_title_separator' );
