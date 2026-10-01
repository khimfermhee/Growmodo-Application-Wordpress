<?php
/**
 * Data layer: queries custom post types and maps posts to plain arrays that
 * the template parts consume. When a post type has no entries yet, the
 * design defaults are mapped to the same shape, so templates never need to
 * know where the data came from.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Generic CPT query helper (published, ordered by menu order then date).
 *
 * @param string $post_type Post type.
 * @param array  $args      Extra WP_Query args.
 * @return WP_Post[]
 */
function estatein_query_posts( $post_type, $args = array() ) {
	return get_posts(
		array_merge(
			array(
				'post_type'        => $post_type,
				'post_status'      => 'publish',
				'posts_per_page'   => 12,
				'orderby'          => array(
					'menu_order' => 'ASC',
					'date'       => 'DESC',
				),
				'no_found_rows'    => true,
				'suppress_filters' => false,
			),
			$args
		)
	);
}

/* ---------------------------------------------------------------------
 * Properties.
 * ------------------------------------------------------------------- */

/**
 * Map a property post to the card/detail data shape.
 *
 * @param WP_Post|int $post Property.
 * @return array
 */
function estatein_property_data( $post ) {
	$post  = get_post( $post );
	$id    = $post->ID;
	$types = get_the_terms( $id, 'property_type' );

	return array(
		'id'         => $id,
		'title'      => get_the_title( $id ),
		'url'        => get_permalink( $id ),
		'excerpt'    => has_excerpt( $id ) ? get_the_excerpt( $id ) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 16, '' ),
		'image'      => get_post_thumbnail_id( $id ),
		'tagline'    => (string) estatein_field( 'tagline', '', $id ),
		'location'   => (string) estatein_field( 'location_label', '', $id ),
		'price'      => estatein_field( 'price', '', $id ),
		'bedrooms'   => estatein_field( 'bedrooms', '', $id ),
		'bathrooms'  => estatein_field( 'bathrooms', '', $id ),
		'area'       => estatein_field( 'area', '', $id ),
		'build_year' => estatein_field( 'build_year', '', $id ),
		'type'       => ( $types && ! is_wp_error( $types ) ) ? $types[0]->name : '',
	);
}

/**
 * Default (design) properties in the same data shape.
 *
 * @return array[]
 */
function estatein_default_properties() {
	$items = array();
	foreach ( estatein_default( 'properties_list', array() ) as $p ) {
		$items[] = array(
			'id'         => 0,
			'title'      => $p['title'],
			'url'        => estatein_properties_url(),
			'excerpt'    => $p['excerpt'],
			'image'      => estatein_demo_image( $p['image'] ),
			'tagline'    => $p['tagline'],
			'location'   => $p['location'],
			'price'      => $p['price'],
			'bedrooms'   => $p['bedrooms'],
			'bathrooms'  => $p['bathrooms'],
			'area'       => $p['area'],
			'build_year' => $p['build_year'],
			'type'       => $p['type'],
		);
	}
	return $items;
}

/**
 * Properties for carousels. Falls back to the design listings.
 *
 * @param bool $featured_only Only featured properties.
 * @param int  $limit         Max items.
 * @return array[]
 */
function estatein_get_properties( $featured_only = false, $limit = 9 ) {
	$args = array( 'posts_per_page' => $limit );
	if ( $featured_only ) {
		$args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'   => 'featured',
				'value' => '1',
			),
		);
	}
	$posts = estatein_query_posts( 'property', $args );

	if ( ! $posts && $featured_only ) {
		$posts = estatein_query_posts( 'property', array( 'posts_per_page' => $limit ) );
	}
	if ( ! $posts ) {
		return array_slice( estatein_default_properties(), 0, $limit );
	}
	return array_map( 'estatein_property_data', $posts );
}

/**
 * Filter definitions for the property search bar. Keys are GET params.
 *
 * Location/type choices come from taxonomies, ranges are fixed buckets so
 * the UI stays as simple as the design (single dropdown per filter).
 *
 * @return array
 */
function estatein_property_filters() {
	$terms = function ( $taxonomy ) {
		$choices = array();
		$list    = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => true,
			)
		);
		if ( ! is_wp_error( $list ) ) {
			foreach ( $list as $term ) {
				$choices[ $term->slug ] = $term->name;
			}
		}
		return $choices;
	};

	return array(
		'location' => array(
			'label'   => __( 'Location', 'estatein' ),
			'icon'    => 'pin-solid',
			'choices' => $terms( 'property_location' ),
		),
		'type'     => array(
			'label'   => __( 'Property Type', 'estatein' ),
			'icon'    => 'building',
			'choices' => $terms( 'property_type' ),
		),
		'price'    => array(
			'label'   => __( 'Pricing Range', 'estatein' ),
			'icon'    => 'banknote',
			'choices' => array(
				'0-500000'       => __( 'Under $500,000', 'estatein' ),
				'500000-1000000' => __( '$500,000 – $1,000,000', 'estatein' ),
				'1000000-2000000' => __( '$1,000,000 – $2,000,000', 'estatein' ),
				'2000000-'       => __( '$2,000,000+', 'estatein' ),
			),
		),
		'size'     => array(
			'label'   => __( 'Property Size', 'estatein' ),
			'icon'    => 'box',
			'choices' => array(
				'0-1500'    => __( 'Under 1,500 sq ft', 'estatein' ),
				'1500-2500' => __( '1,500 – 2,500 sq ft', 'estatein' ),
				'2500-4000' => __( '2,500 – 4,000 sq ft', 'estatein' ),
				'4000-'     => __( '4,000+ sq ft', 'estatein' ),
			),
		),
		'year'     => array(
			'label'   => __( 'Build Year', 'estatein' ),
			'icon'    => 'calendar',
			'choices' => array(
				'0-1999'    => __( 'Before 2000', 'estatein' ),
				'2000-2009' => __( '2000 – 2009', 'estatein' ),
				'2010-2019' => __( '2010 – 2019', 'estatein' ),
				'2020-'     => __( '2020 and newer', 'estatein' ),
			),
		),
	);
}

/**
 * Sanitized search request from the query string.
 *
 * @return array { q, location, type, price, size, year }
 */
function estatein_property_request() {
	$request = array( 'q' => '' );
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only public search.
	if ( isset( $_GET['q'] ) ) {
		$request['q'] = sanitize_text_field( wp_unslash( $_GET['q'] ) );
	}
	foreach ( array_keys( estatein_property_filters() ) as $key ) {
		$request[ $key ] = isset( $_GET[ $key ] ) ? sanitize_text_field( wp_unslash( $_GET[ $key ] ) ) : '';
	}
	// phpcs:enable
	return $request;
}

/**
 * Is a property search/filter active?
 *
 * @param array $request Sanitized request.
 * @return bool
 */
function estatein_property_search_active( $request ) {
	return (bool) array_filter( $request, 'strlen' );
}

/**
 * Build WP_Query args for the property search.
 *
 * @param array $request Sanitized request (estatein_property_request()).
 * @return array
 */
function estatein_property_search_args( $request ) {
	$args = array(
		'post_type'      => 'property',
		'post_status'    => 'publish',
		'posts_per_page' => 12,
		'paged'          => max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) ),
	);

	if ( '' !== $request['q'] ) {
		$args['s'] = $request['q'];
	}

	$tax_query = array();
	if ( $request['location'] ) {
		$tax_query[] = array(
			'taxonomy' => 'property_location',
			'field'    => 'slug',
			'terms'    => $request['location'],
		);
	}
	if ( $request['type'] ) {
		$tax_query[] = array(
			'taxonomy' => 'property_type',
			'field'    => 'slug',
			'terms'    => $request['type'],
		);
	}
	if ( $tax_query ) {
		$args['tax_query'] = array_merge( array( 'relation' => 'AND' ), $tax_query ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
	}

	$meta_query = array();
	foreach ( array( 'price' => 'price', 'size' => 'area', 'year' => 'build_year' ) as $param => $meta_key ) {
		if ( ! $request[ $param ] || false === strpos( $request[ $param ], '-' ) ) {
			continue;
		}
		list( $min, $max ) = array_map( 'trim', explode( '-', $request[ $param ], 2 ) );
		if ( '' !== $max ) {
			$meta_query[] = array(
				'key'     => $meta_key,
				'value'   => array( (int) $min, (int) $max ),
				'compare' => 'BETWEEN',
				'type'    => 'NUMERIC',
			);
		} else {
			$meta_query[] = array(
				'key'     => $meta_key,
				'value'   => (int) $min,
				'compare' => '>=',
				'type'    => 'NUMERIC',
			);
		}
	}
	if ( $meta_query ) {
		$args['meta_query'] = array_merge( array( 'relation' => 'AND' ), $meta_query ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
	}

	return $args;
}

/**
 * Property gallery as a list of attachment IDs or URLs.
 *
 * @param int $post_id Property ID.
 * @return array
 */
function estatein_property_gallery( $post_id ) {
	$gallery = estatein_field( 'gallery', array(), $post_id );
	if ( ! is_array( $gallery ) ) {
		$gallery = array_filter( array_map( 'absint', explode( ',', (string) $gallery ) ) );
	}
	$gallery = array_map(
		function ( $item ) {
			return is_array( $item ) && isset( $item['ID'] ) ? (int) $item['ID'] : $item;
		},
		$gallery
	);
	if ( ! $gallery && has_post_thumbnail( $post_id ) ) {
		$gallery = array( get_post_thumbnail_id( $post_id ) );
	}
	return array_values( $gallery );
}

/* ---------------------------------------------------------------------
 * Testimonials, FAQs, team, clients, offices, services.
 * ------------------------------------------------------------------- */

/**
 * Testimonials (posts or design defaults).
 *
 * @param int $limit Max items (-1 for all).
 * @return array[]
 */
function estatein_get_testimonials( $limit = 9 ) {
	$posts = estatein_query_posts( 'testimonial', array( 'posts_per_page' => $limit ) );
	if ( ! $posts ) {
		return array_map(
			function ( $t ) {
				$t['image'] = estatein_demo_image( $t['image'] );
				return $t;
			},
			estatein_default( 'testimonials', array() )
		);
	}
	return array_map(
		function ( $post ) {
			return array(
				'title'    => get_the_title( $post ),
				'quote'    => wp_strip_all_tags( $post->post_content ),
				'name'     => (string) estatein_field( 'client_name', '', $post->ID ),
				'location' => (string) estatein_field( 'client_location', '', $post->ID ),
				'rating'   => (int) estatein_field( 'rating', 5, $post->ID ),
				'image'    => get_post_thumbnail_id( $post ),
			);
		},
		$posts
	);
}

/**
 * FAQs (posts or design defaults).
 *
 * @param int $limit Max items (-1 for all).
 * @return array[]
 */
function estatein_get_faqs( $limit = 9 ) {
	$posts = estatein_query_posts( 'faq', array( 'posts_per_page' => $limit ) );
	if ( ! $posts ) {
		$items = array();
		foreach ( estatein_default( 'faqs', array() ) as $faq ) {
			$items[] = array(
				'question' => $faq['question'],
				'summary'  => $faq['summary'],
				'answer'   => wpautop( esc_html( $faq['answer'] ) ),
				'slug'     => sanitize_title( $faq['question'] ),
			);
		}
		return $items;
	}
	return array_map(
		function ( $post ) {
			return array(
				'question' => get_the_title( $post ),
				'summary'  => has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 16 ),
				'answer'   => apply_filters( 'the_content', $post->post_content ), // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- core hook.
				'slug'     => $post->post_name,
			);
		},
		$posts
	);
}

/**
 * Team members (posts or design defaults).
 *
 * @return array[]
 */
function estatein_get_team() {
	$posts = estatein_query_posts( 'team_member', array( 'posts_per_page' => 12 ) );
	if ( ! $posts ) {
		return array_map(
			function ( $m ) {
				return array(
					'name'    => $m['name'],
					'role'    => $m['role'],
					'image'   => estatein_demo_image( $m['image'] ),
					'twitter' => estatein_option( 'social_twitter' ),
					'email'   => estatein_option( 'contact_email' ),
				);
			},
			estatein_default( 'team', array() )
		);
	}
	return array_map(
		function ( $post ) {
			return array(
				'name'    => get_the_title( $post ),
				'role'    => (string) estatein_field( 'role', '', $post->ID ),
				'image'   => get_post_thumbnail_id( $post ),
				'twitter' => (string) estatein_field( 'twitter', '', $post->ID ),
				'email'   => (string) estatein_field( 'email', estatein_option( 'contact_email' ), $post->ID ),
			);
		},
		$posts
	);
}

/**
 * Clients (posts or design defaults).
 *
 * @return array[]
 */
function estatein_get_clients() {
	$posts = estatein_query_posts( 'client', array( 'posts_per_page' => 20 ) );
	if ( ! $posts ) {
		return estatein_default( 'clients', array() );
	}
	return array_map(
		function ( $post ) {
			return array(
				'name'     => get_the_title( $post ),
				'since'    => (string) estatein_field( 'since', '', $post->ID ),
				'domain'   => (string) estatein_field( 'domain', '', $post->ID ),
				'category' => (string) estatein_field( 'category', '', $post->ID ),
				'quote'    => (string) estatein_field( 'quote', '', $post->ID ),
				'website'  => (string) estatein_field( 'website', '', $post->ID ),
			);
		},
		$posts
	);
}

/**
 * Offices (posts or design defaults).
 *
 * @return array[]
 */
function estatein_get_offices() {
	$posts = estatein_query_posts( 'office', array( 'posts_per_page' => 20 ) );
	if ( ! $posts ) {
		return estatein_default( 'offices', array() );
	}
	return array_map(
		function ( $post ) {
			return array(
				'label'   => (string) estatein_field( 'label', '', $post->ID ),
				'type'    => (string) estatein_field( 'office_type', 'regional', $post->ID ),
				'address' => get_the_title( $post ),
				'text'    => wp_strip_all_tags( $post->post_content ),
				'email'   => (string) estatein_field( 'email', '', $post->ID ),
				'phone'   => (string) estatein_field( 'phone', '', $post->ID ),
				'city'    => (string) estatein_field( 'city', '', $post->ID ),
				'map'     => (string) estatein_field( 'map', '', $post->ID ),
			);
		},
		$posts
	);
}

/**
 * Service sections with their service cards (terms + posts or defaults).
 *
 * @return array[]
 */
function estatein_get_service_sections() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'service_category',
			'hide_empty' => true,
		)
	);

	if ( is_wp_error( $terms ) || ! $terms ) {
		return estatein_default( 'service_categories', array() );
	}

	$sections = array();
	foreach ( $terms as $term ) {
		$ref      = 'service_category_' . $term->term_id;
		$services = estatein_query_posts(
			'service',
			array(
				'posts_per_page' => 12,
				'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => 'service_category',
						'terms'    => $term->term_id,
					),
				),
			)
		);

		$sections[] = array(
			'name'      => $term->name,
			'slug'      => $term->slug,
			'intro'     => (string) estatein_field( 'intro', $term->description, $ref ),
			'layout'    => (string) estatein_field( 'layout', 'grid', $ref ),
			'order'     => (int) estatein_field( 'order', 0, $ref ),
			'cta_title' => (string) estatein_field( 'cta_title', '', $ref ),
			'cta_text'  => (string) estatein_field( 'cta_text', '', $ref ),
			'cta_label' => (string) estatein_field( 'cta_label', '', $ref ),
			'cta_url'   => (string) estatein_field( 'cta_url', '', $ref ),
			'services'  => array_map(
				function ( $post ) {
					return array(
						'title' => get_the_title( $post ),
						'icon'  => (string) estatein_field( 'icon', 'star', $post->ID ),
						'text'  => has_excerpt( $post ) ? get_the_excerpt( $post ) : '',
					);
				},
				$services
			),
		);
	}

	usort(
		$sections,
		function ( $a, $b ) {
			return $a['order'] <=> $b['order'];
		}
	);

	return $sections;
}

/**
 * Testimonial/FAQ single pages have no design – send visitors to the archive.
 */
function estatein_redirect_thin_singles() {
	if ( is_singular( array( 'testimonial', 'faq' ) ) ) {
		$post_type = get_post_type();
		$anchor    = 'faq' === $post_type ? '#' . get_post_field( 'post_name' ) : '';
		wp_safe_redirect( get_post_type_archive_link( $post_type ) . $anchor, 301 );
		exit;
	}
}
add_action( 'template_redirect', 'estatein_redirect_thin_singles' );

/**
 * Show all testimonials / FAQs on their archive pages.
 *
 * @param WP_Query $query Main query.
 */
function estatein_archive_queries( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( $query->is_post_type_archive( array( 'testimonial', 'faq' ) ) ) {
		$query->set( 'posts_per_page', 50 );
		$query->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'DESC' ) );
	}
}
add_action( 'pre_get_posts', 'estatein_archive_queries' );
