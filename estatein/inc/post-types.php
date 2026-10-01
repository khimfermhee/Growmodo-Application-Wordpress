<?php
/**
 * Custom post types and taxonomies.
 *
 * Kept in the theme for the assessment so everything ships in one package.
 * In a long-lived client project this file would move to a small
 * site-specific plugin so content survives a theme switch.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Build a full labels array from singular/plural names.
 *
 * @param string $singular Singular name.
 * @param string $plural   Plural name.
 * @return array
 */
function estatein_cpt_labels( $singular, $plural ) {
	return array(
		'name'               => $plural,
		'singular_name'      => $singular,
		/* translators: %s: singular post type name */
		'add_new_item'       => sprintf( __( 'Add New %s', 'estatein' ), $singular ),
		/* translators: %s: singular post type name */
		'edit_item'          => sprintf( __( 'Edit %s', 'estatein' ), $singular ),
		/* translators: %s: singular post type name */
		'new_item'           => sprintf( __( 'New %s', 'estatein' ), $singular ),
		/* translators: %s: singular post type name */
		'view_item'          => sprintf( __( 'View %s', 'estatein' ), $singular ),
		/* translators: %s: plural post type name */
		'search_items'       => sprintf( __( 'Search %s', 'estatein' ), $plural ),
		/* translators: %s: plural post type name */
		'not_found'          => sprintf( __( 'No %s found', 'estatein' ), strtolower( $plural ) ),
		/* translators: %s: plural post type name */
		'all_items'          => sprintf( __( 'All %s', 'estatein' ), $plural ),
		'menu_name'          => $plural,
		/* translators: %s: plural post type name */
		'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'estatein' ), strtolower( $plural ) ),
	);
}

/**
 * Register post types.
 */
function estatein_register_post_types() {
	$common = array(
		'show_in_rest' => true,
		'show_ui'      => true,
	);

	register_post_type(
		'property',
		array_merge(
			$common,
			array(
				'labels'        => estatein_cpt_labels( __( 'Property', 'estatein' ), __( 'Properties', 'estatein' ) ),
				'public'        => true,
				'has_archive'   => false, // The "Properties" page template is the listing.
				'rewrite'       => array( 'slug' => 'property' ),
				'menu_icon'     => 'dashicons-admin-home',
				'menu_position' => 5,
				'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			)
		)
	);

	register_post_type(
		'testimonial',
		array_merge(
			$common,
			array(
				'labels'        => estatein_cpt_labels( __( 'Testimonial', 'estatein' ), __( 'Testimonials', 'estatein' ) ),
				'public'        => true,
				'has_archive'   => 'testimonials',
				'rewrite'       => array( 'slug' => 'testimonials' ),
				'publicly_queryable' => true,
				'exclude_from_search' => true,
				'menu_icon'     => 'dashicons-format-quote',
				'menu_position' => 6,
				'supports'      => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			)
		)
	);

	register_post_type(
		'faq',
		array_merge(
			$common,
			array(
				'labels'        => estatein_cpt_labels( __( 'FAQ', 'estatein' ), __( 'FAQs', 'estatein' ) ),
				'public'        => true,
				'has_archive'   => 'faqs',
				'rewrite'       => array( 'slug' => 'faqs' ),
				'exclude_from_search' => true,
				'menu_icon'     => 'dashicons-editor-help',
				'menu_position' => 7,
				'supports'      => array( 'title', 'editor', 'excerpt', 'page-attributes' ),
			)
		)
	);

	// Content-only types: managed in the dashboard, rendered inside pages.
	$internal = array(
		'public'              => false,
		'publicly_queryable'  => false,
		'exclude_from_search' => true,
		'has_archive'         => false,
		'rewrite'             => false,
	);

	register_post_type(
		'team_member',
		array_merge(
			$common,
			$internal,
			array(
				'labels'        => estatein_cpt_labels( __( 'Team Member', 'estatein' ), __( 'Team', 'estatein' ) ),
				'menu_icon'     => 'dashicons-groups',
				'menu_position' => 8,
				'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
			)
		)
	);

	register_post_type(
		'client',
		array_merge(
			$common,
			$internal,
			array(
				'labels'        => estatein_cpt_labels( __( 'Client', 'estatein' ), __( 'Clients', 'estatein' ) ),
				'menu_icon'     => 'dashicons-businessperson',
				'menu_position' => 9,
				'supports'      => array( 'title', 'page-attributes' ),
			)
		)
	);

	register_post_type(
		'service',
		array_merge(
			$common,
			$internal,
			array(
				'labels'        => estatein_cpt_labels( __( 'Service', 'estatein' ), __( 'Services', 'estatein' ) ),
				'menu_icon'     => 'dashicons-hammer',
				'menu_position' => 10,
				'supports'      => array( 'title', 'excerpt', 'page-attributes' ),
			)
		)
	);

	register_post_type(
		'office',
		array_merge(
			$common,
			$internal,
			array(
				'labels'        => estatein_cpt_labels( __( 'Office', 'estatein' ), __( 'Offices', 'estatein' ) ),
				'menu_icon'     => 'dashicons-location',
				'menu_position' => 11,
				'supports'      => array( 'title', 'editor', 'page-attributes' ),
			)
		)
	);

	// Form submissions (never public, read-only list in the dashboard).
	register_post_type(
		'est_inquiry',
		array(
			'labels'          => estatein_cpt_labels( __( 'Inquiry', 'estatein' ), __( 'Inquiries', 'estatein' ) ),
			'public'          => false,
			'show_ui'         => true,
			'show_in_rest'    => false,
			'menu_icon'       => 'dashicons-email-alt',
			'menu_position'   => 25,
			'supports'        => array( 'title' ),
			'capability_type' => 'post',
			'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'    => true,
		)
	);
}
add_action( 'init', 'estatein_register_post_types' );

/**
 * Register taxonomies.
 */
function estatein_register_taxonomies() {
	register_taxonomy(
		'property_type',
		'property',
		array(
			'labels'            => estatein_cpt_labels( __( 'Property Type', 'estatein' ), __( 'Property Types', 'estatein' ) ),
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'property-type' ),
		)
	);

	register_taxonomy(
		'property_location',
		'property',
		array(
			'labels'            => estatein_cpt_labels( __( 'Location', 'estatein' ), __( 'Locations', 'estatein' ) ),
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'location' ),
		)
	);

	register_taxonomy(
		'service_category',
		'service',
		array(
			'labels'            => estatein_cpt_labels( __( 'Service Section', 'estatein' ), __( 'Service Sections', 'estatein' ) ),
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => false,
		)
	);
}
add_action( 'init', 'estatein_register_taxonomies' );

/**
 * Flush rewrite rules once after the theme is activated.
 */
function estatein_flush_rewrites() {
	estatein_register_post_types();
	estatein_register_taxonomies();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'estatein_flush_rewrites' );
