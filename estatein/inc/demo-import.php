<?php
/**
 * Appearance → Estatein Setup: one-click demo content.
 *
 * Creates the pages (with templates), menus, taxonomy terms, listings,
 * testimonials, FAQs, team, clients, services, offices and blog posts from
 * inc/defaults.php and the images in /demo/images. Safe to run more than
 * once: existing items (matched by slug) are updated, never duplicated.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the setup page.
 */
function estatein_setup_menu() {
	add_theme_page(
		__( 'Estatein Setup', 'estatein' ),
		__( 'Estatein Setup', 'estatein' ),
		'edit_theme_options',
		'estatein-setup',
		'estatein_setup_page'
	);
}
add_action( 'admin_menu', 'estatein_setup_menu' );

/**
 * Render the setup page.
 */
function estatein_setup_page() {
	$fields_ready = function_exists( 'update_field' );
	$imported     = get_option( 'estatein_demo_imported' );
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display only.
	$done = isset( $_GET['imported'] );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Estatein Setup', 'estatein' ); ?></h1>

		<?php if ( $done ) : ?>
			<div class="notice notice-success"><p>
				<?php esc_html_e( 'Demo content imported. Your site now matches the design.', 'estatein' ); ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'View site →', 'estatein' ); ?></a>
			</p></div>
		<?php endif; ?>

		<div class="card" style="max-width:760px">
			<h2><?php esc_html_e( '1. Content fields plugin', 'estatein' ); ?></h2>
			<?php if ( $fields_ready ) : ?>
				<p>✅ <?php esc_html_e( 'Secure Custom Fields / ACF is active. All sections will be editable.', 'estatein' ); ?></p>
			<?php else : ?>
				<p>⚠️ <?php esc_html_e( 'Install and activate "Secure Custom Fields" (free, maintained by WordPress.org) first, so imported section content is editable in the dashboard.', 'estatein' ); ?></p>
				<p><a class="button" href="<?php echo esc_url( admin_url( 'plugin-install.php?s=secure%20custom%20fields&tab=search&type=term' ) ); ?>"><?php esc_html_e( 'Install Secure Custom Fields', 'estatein' ); ?></a></p>
			<?php endif; ?>

			<h2><?php esc_html_e( '2. Import demo content', 'estatein' ); ?></h2>
			<p><?php esc_html_e( 'Creates Home, About Us, Properties, Services, Contact Us, Blog and legal pages, the header and footer menus, 6 properties, testimonials, FAQs, team members, clients, services, offices and 3 blog posts – including images.', 'estatein' ); ?></p>
			<?php if ( $imported ) : ?>
				<p><em><?php esc_html_e( 'Already imported once. Running it again updates the demo items and keeps your other content.', 'estatein' ); ?></em></p>
			<?php endif; ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="estatein_import_demo">
				<?php wp_nonce_field( 'estatein_import_demo' ); ?>
				<?php submit_button( $imported ? __( 'Re-run import', 'estatein' ) : __( 'Import demo content', 'estatein' ), 'primary large', 'submit', false ); ?>
			</form>
		</div>
	</div>
	<?php
}

/**
 * Handle the import request.
 */
function estatein_handle_import() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to do this.', 'estatein' ) );
	}
	check_admin_referer( 'estatein_import_demo' );

	if ( function_exists( 'set_time_limit' ) ) {
		set_time_limit( 300 ); // phpcs:ignore Squiz.PHP.DiscouragedFunctions.Discouraged -- image processing on slow shared hosts.
	}

	estatein_run_demo_import();

	wp_safe_redirect( admin_url( 'themes.php?page=estatein-setup&imported=1' ) );
	exit;
}
add_action( 'admin_post_estatein_import_demo', 'estatein_handle_import' );

/**
 * Run the full import. Also callable from WP-CLI: wp eval 'estatein_run_demo_import();'
 */
function estatein_run_demo_import() {
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	$pages = estatein_import_pages();
	estatein_import_options();
	estatein_import_properties();
	estatein_import_simple_types();
	estatein_import_services();
	estatein_import_posts();
	estatein_import_menus( $pages );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $pages['home'] );
	update_option( 'page_for_posts', $pages['blog'] );

	if ( ! get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
	}
	estatein_flush_rewrites();

	update_option( 'estatein_demo_imported', time() );
}

/**
 * Save a field through SCF/ACF when available, otherwise as raw meta in the
 * same storage format so values appear once the plugin is activated.
 *
 * @param string     $name    Field name.
 * @param mixed      $value   Value.
 * @param int|string $post_id Post ID, "option" or "service_category_{id}".
 */
function estatein_save_field( $name, $value, $post_id ) {
	if ( function_exists( 'update_field' ) ) {
		update_field( $name, $value, $post_id );
		return;
	}

	$store = function ( $key, $val ) use ( $post_id ) {
		if ( 'option' === $post_id ) {
			update_option( 'options_' . $key, $val, false );
		} elseif ( is_string( $post_id ) && preg_match( '/_(\d+)$/', $post_id, $m ) ) {
			update_term_meta( (int) $m[1], $key, $val );
		} else {
			update_post_meta( (int) $post_id, $key, $val );
		}
	};

	if ( is_array( $value ) && isset( $value[0] ) && is_array( $value[0] ) ) {
		$store( $name, count( $value ) ); // Repeater: row count + name_{i}_{sub}.
		foreach ( $value as $i => $row ) {
			foreach ( $row as $sub => $sub_value ) {
				$store( $name . '_' . $i . '_' . $sub, $sub_value );
			}
		}
		return;
	}
	$store( $name, $value );
}

/**
 * Copy a demo image into the media library once and return its ID.
 *
 * @param string $file File name in /demo/images.
 * @param string $alt  Alt text.
 * @return int Attachment ID (0 on failure).
 */
function estatein_import_image( $file, $alt = '' ) {
	if ( ! $file ) {
		return 0;
	}
	$map = (array) get_option( 'estatein_demo_attachments', array() );
	if ( ! empty( $map[ $file ] ) && get_post( $map[ $file ] ) ) {
		return (int) $map[ $file ];
	}

	$path = ESTATEIN_DIR . '/demo/images/' . $file;
	if ( ! file_exists( $path ) ) {
		return 0;
	}

	$upload = wp_upload_bits( $file, null, file_get_contents( $path ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local file.
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}

	$type = wp_check_filetype( $upload['file'] );
	$id   = wp_insert_attachment(
		array(
			'post_mime_type' => $type['type'],
			'post_title'     => $alt ? $alt : ucwords( str_replace( array( '-', '.jpg' ), array( ' ', '' ), $file ) ),
			'post_status'    => 'inherit',
		),
		$upload['file']
	);
	if ( is_wp_error( $id ) || ! $id ) {
		return 0;
	}

	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
	update_post_meta( $id, '_wp_attachment_image_alt', $alt ? $alt : get_the_title( $id ) );

	$map[ $file ] = $id;
	update_option( 'estatein_demo_attachments', $map, false );

	return (int) $id;
}

/**
 * Create or update a post matched by slug.
 *
 * @param array $postarr wp_insert_post args (post_type + post_name required).
 * @return int Post ID.
 */
function estatein_upsert_post( $postarr ) {
	$existing = get_posts(
		array(
			'post_type'      => $postarr['post_type'],
			'name'           => $postarr['post_name'],
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);
	$postarr  = wp_parse_args( $postarr, array( 'post_status' => 'publish' ) );
	if ( $existing ) {
		$postarr['ID'] = $existing[0];
		return (int) wp_update_post( wp_slash( $postarr ) );
	}
	return (int) wp_insert_post( wp_slash( $postarr ) );
}

/**
 * Get or create a term.
 *
 * @param string $name     Term name.
 * @param string $taxonomy Taxonomy.
 * @param array  $args     Extra args (slug, description).
 * @return int Term ID.
 */
function estatein_upsert_term( $name, $taxonomy, $args = array() ) {
	$term = term_exists( isset( $args['slug'] ) ? $args['slug'] : $name, $taxonomy );
	if ( ! $term ) {
		$term = wp_insert_term( $name, $taxonomy, $args );
	}
	return is_wp_error( $term ) ? 0 : (int) $term['term_id'];
}

/**
 * Pages + their section fields.
 *
 * @return array Page IDs keyed by role.
 */
function estatein_import_pages() {
	$definitions = array(
		'home'       => array( 'Home', 'home', '', 'home' ),
		'about'      => array( 'About Us', 'about-us', 'page-templates/template-about.php', 'about' ),
		'properties' => array( 'Properties', 'properties', 'page-templates/template-properties.php', 'properties' ),
		'services'   => array( 'Services', 'services', 'page-templates/template-services.php', 'services' ),
		'contact'    => array( 'Contact Us', 'contact-us', 'page-templates/template-contact.php', 'contact' ),
		'blog'       => array( 'Blog', 'blog', '', '' ),
		'terms'      => array( 'Terms & Conditions', 'terms-conditions', '', '' ),
		'privacy'    => array( 'Privacy Policy', 'privacy-policy', '', '' ),
	);

	$legal = "<p>This page is a placeholder for your legal text. Replace it with terms that fit your business before going live.</p>\n<h2>Use of this website</h2>\n<p>The information on this website is provided for general information only and may change without notice.</p>\n<h2>Contact</h2>\n<p>Questions? Email info@estatein.com.</p>";

	$ids = array();
	foreach ( $definitions as $role => $def ) {
		list( $title, $slug, $template, $group ) = $def;

		$ids[ $role ] = estatein_upsert_post(
			array(
				'post_type'    => 'page',
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_content' => in_array( $role, array( 'terms', 'privacy' ), true ) ? $legal : '',
			)
		);
		update_post_meta( $ids[ $role ], '_wp_page_template', $template ? $template : 'default' );

		if ( $group ) {
			foreach ( (array) estatein_default( $group, array() ) as $name => $value ) {
				if ( 'gallery' === $name ) {
					continue;
				}
				estatein_save_field( $name, $value, $ids[ $role ] );
			}
		}
	}

	// Images for page fields.
	estatein_save_field( 'journey_image', 0, $ids['about'] ); // Theme asset is used when empty.
	$gallery = array();
	foreach ( array( 'office-workspace.jpg', 'office-team.jpg', 'office-meeting.jpg', 'office-team-2.jpg', 'office-team-3.jpg', 'office-handshake.jpg' ) as $file ) {
		$gallery[] = estatein_import_image( $file, __( 'Estatein team and offices', 'estatein' ) );
	}
	estatein_save_field( 'gallery', array_filter( $gallery ), $ids['contact'] );

	update_option( 'wp_page_for_privacy_policy', $ids['privacy'] );

	return $ids;
}

/**
 * Theme Settings defaults.
 */
function estatein_import_options() {
	foreach ( estatein_default( 'options', array() ) as $name => $value ) {
		estatein_save_field( $name, $value, 'option' );
	}
	estatein_save_field( 'announcement_enabled', 1, 'option' );
}

/**
 * Properties with taxonomies, gallery, features and pricing.
 */
function estatein_import_properties() {
	foreach ( estatein_default( 'properties_list', array() ) as $order => $p ) {
		$id = estatein_upsert_post(
			array(
				'post_type'    => 'property',
				'menu_order'   => $order,
				'post_title'   => $p['title'],
				'post_name'    => $p['slug'],
				'post_excerpt' => $p['excerpt'],
				'post_content' => '<p>' . $p['content'] . '</p>',
			)
		);
		if ( ! $id ) {
			continue;
		}

		wp_set_object_terms( $id, estatein_upsert_term( $p['type'], 'property_type' ), 'property_type' );
		wp_set_object_terms( $id, estatein_upsert_term( $p['location_term'], 'property_location' ), 'property_location' );

		$thumb = estatein_import_image( $p['image'], $p['title'] );
		if ( $thumb ) {
			set_post_thumbnail( $id, $thumb );
		}

		$gallery = array();
		foreach ( $p['gallery'] as $file ) {
			$gallery[] = estatein_import_image( $file, $p['title'] );
		}

		$fields = array(
			'price'          => $p['price'],
			'location_label' => $p['location'],
			'tagline'        => $p['tagline'],
			'featured'       => $p['featured'] ? 1 : 0,
			'bedrooms'       => $p['bedrooms'],
			'bathrooms'      => $p['bathrooms'],
			'area'           => $p['area'],
			'build_year'     => $p['build_year'],
			'gallery'        => array_values( array_filter( $gallery ) ),
			'features'       => array_map(
				function ( $text ) {
					return array( 'text' => $text );
				},
				$p['features']
			),
			'pricing_groups' => estatein_scaled_pricing( (float) $p['price'] ),
		);

		foreach ( $fields as $name => $value ) {
			estatein_save_field( $name, $value, $id );
		}

		// Plain meta copies keep the numeric search filters fast and plugin independent.
		foreach ( array( 'price', 'area', 'build_year', 'featured' ) as $key ) {
			update_post_meta( $id, $key, $fields[ $key ] );
		}
	}
}

/**
 * Pricing breakdown scaled to the listing price (design values are for $1.25M).
 *
 * @param float $price Listing price.
 * @return array
 */
function estatein_scaled_pricing( $price ) {
	$money  = function ( $value ) {
		return '$' . number_format_i18n( round( $value / 50 ) * 50 );
	};
	$tax    = $price * 0.02;
	$legal  = 3000;
	$insp   = 500;
	$ins    = max( 600, $price * 0.00096 );
	$extra  = $tax + $legal + $insp + $ins;
	$groups = estatein_default( 'pricing_groups', array() );

	$groups[0]['rows'][0]['amount'] = $money( $tax );
	$groups[0]['rows'][3]['amount'] = $money( $ins );
	$groups[1]['rows'][0]['amount'] = $money( $price * 0.001 );
	$groups[2]['rows'][0]['amount'] = $money( $price );
	$groups[2]['rows'][1]['amount'] = $money( $extra );
	$groups[2]['rows'][2]['amount'] = $money( $price * 0.2 );
	$groups[2]['rows'][3]['amount'] = $money( $price * 0.8 );
	$groups[3]['rows'][0]['amount'] = $money( $price * 0.001 );
	$groups[3]['rows'][3]['amount'] = $money( $ins / 12 );

	foreach ( $groups as &$group ) {
		foreach ( $group['rows'] as &$row ) {
			$row['full'] = empty( $row['full'] ) ? 0 : 1;
		}
	}
	return $groups;
}

/**
 * Testimonials, FAQs, team, clients and offices.
 */
function estatein_import_simple_types() {
	foreach ( estatein_default( 'testimonials', array() ) as $order => $t ) {
		$id = estatein_upsert_post(
			array(
				'post_type'    => 'testimonial',
				'post_title'   => $t['title'],
				'post_name'    => sanitize_title( $t['name'] ),
				'post_content' => $t['quote'],
				'menu_order'   => $order,
			)
		);
		estatein_save_field( 'client_name', $t['name'], $id );
		estatein_save_field( 'client_location', $t['location'], $id );
		estatein_save_field( 'rating', $t['rating'], $id );
		$img = estatein_import_image( $t['image'], $t['name'] );
		if ( $img ) {
			set_post_thumbnail( $id, $img );
		}
	}

	foreach ( estatein_default( 'faqs', array() ) as $order => $f ) {
		estatein_upsert_post(
			array(
				'post_type'    => 'faq',
				'post_title'   => $f['question'],
				'post_name'    => sanitize_title( $f['question'] ),
				'post_excerpt' => $f['summary'],
				'post_content' => '<p>' . $f['answer'] . '</p>',
				'menu_order'   => $order,
			)
		);
	}

	foreach ( estatein_default( 'team', array() ) as $order => $m ) {
		$id = estatein_upsert_post(
			array(
				'post_type'  => 'team_member',
				'post_title' => $m['name'],
				'post_name'  => sanitize_title( $m['name'] ),
				'menu_order' => $order,
			)
		);
		estatein_save_field( 'role', $m['role'], $id );
		estatein_save_field( 'twitter', 'https://twitter.com/', $id );
		estatein_save_field( 'email', 'info@estatein.com', $id );
		$img = estatein_import_image( $m['image'], $m['name'] . ', ' . $m['role'] );
		if ( $img ) {
			set_post_thumbnail( $id, $img );
		}
	}

	foreach ( estatein_default( 'clients', array() ) as $order => $c ) {
		$id = estatein_upsert_post(
			array(
				'post_type'  => 'client',
				'post_title' => $c['name'],
				'post_name'  => sanitize_title( $c['name'] ),
				'menu_order' => $order,
			)
		);
		foreach ( array( 'since', 'domain', 'category', 'quote', 'website' ) as $key ) {
			estatein_save_field( $key, $c[ $key ], $id );
		}
	}

	foreach ( estatein_default( 'offices', array() ) as $order => $o ) {
		$id = estatein_upsert_post(
			array(
				'post_type'    => 'office',
				'post_title'   => $o['address'],
				'post_name'    => sanitize_title( $o['address'] ),
				'post_content' => $o['text'],
				'menu_order'   => $order,
			)
		);
		estatein_save_field( 'label', $o['label'], $id );
		estatein_save_field( 'office_type', $o['type'], $id );
		foreach ( array( 'email', 'phone', 'city', 'map' ) as $key ) {
			estatein_save_field( $key, $o[ $key ], $id );
		}
	}
}

/**
 * Service sections (terms with settings) and service cards.
 */
function estatein_import_services() {
	foreach ( estatein_default( 'service_categories', array() ) as $order => $section ) {
		$term_id = estatein_upsert_term( $section['name'], 'service_category', array( 'slug' => $section['slug'] ) );
		if ( ! $term_id ) {
			continue;
		}
		$ref = 'service_category_' . $term_id;
		estatein_save_field( 'intro', $section['intro'], $ref );
		estatein_save_field( 'layout', $section['layout'], $ref );
		estatein_save_field( 'order', $order, $ref );
		foreach ( array( 'cta_title', 'cta_text', 'cta_label', 'cta_url' ) as $key ) {
			estatein_save_field( $key, $section[ $key ], $ref );
		}

		foreach ( $section['services'] as $i => $service ) {
			$id = estatein_upsert_post(
				array(
					'post_type'    => 'service',
					'post_title'   => $service['title'],
					'post_name'    => sanitize_title( $service['title'] ),
					'post_excerpt' => $service['text'],
					'menu_order'   => $i,
				)
			);
			wp_set_object_terms( $id, $term_id, 'service_category' );
			estatein_save_field( 'icon', $service['icon'], $id );
		}
	}
}

/**
 * Blog posts.
 */
function estatein_import_posts() {
	$category = estatein_upsert_term( 'Buying Guides', 'category' );
	foreach ( estatein_default( 'posts', array() ) as $i => $p ) {
		$id = estatein_upsert_post(
			array(
				'post_type'     => 'post',
				'post_title'    => $p['title'],
				'post_name'     => sanitize_title( $p['title'] ),
				'post_excerpt'  => $p['excerpt'],
				'post_content'  => wpautop( $p['content'] ),
				'post_category' => array( $category ),
				'post_date'     => gmdate( 'Y-m-d H:i:s', time() - ( $i + 1 ) * 5 * DAY_IN_SECONDS ),
			)
		);
		$img = estatein_import_image( $p['image'], $p['title'] );
		if ( $img ) {
			set_post_thumbnail( $id, $img );
		}
	}
}

/**
 * Header and footer menus.
 *
 * @param array $pages Page IDs keyed by role.
 */
function estatein_import_menus( $pages ) {
	$locations = get_theme_mod( 'nav_menu_locations', array() );

	$make_menu = function ( $name ) {
		$menu = wp_get_nav_menu_object( $name );
		if ( $menu ) {
			foreach ( (array) wp_get_nav_menu_items( $menu->term_id ) as $item ) {
				wp_delete_post( $item->ID, true );
			}
			return (int) $menu->term_id;
		}
		return (int) wp_create_nav_menu( $name );
	};

	$add_page = function ( $menu_id, $page_id, $parent = 0, $title = '' ) {
		return wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-object-id' => $page_id,
				'menu-item-object'    => 'page',
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-parent-id' => $parent,
				'menu-item-title'     => $title,
			)
		);
	};

	$add_link = function ( $menu_id, $title, $url, $parent = 0 ) {
		return wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => $title,
				'menu-item-url'       => $url,
				'menu-item-type'      => 'custom',
				'menu-item-status'    => 'publish',
				'menu-item-parent-id' => $parent,
			)
		);
	};

	// Header.
	$primary = $make_menu( 'Primary' );
	foreach ( array( 'home', 'about', 'properties', 'services' ) as $role ) {
		$add_page( $primary, $pages[ $role ] );
	}
	$locations['primary'] = $primary;

	// Footer: top level = column heading, children = links to page sections.
	$footer  = $make_menu( 'Footer' );
	$columns = array(
		'home'       => array(
			'Hero Section' => '#hero',
			'Features'     => '#features',
			'Properties'   => '#featured-properties',
			'Testimonials' => '#testimonials',
			'FAQ\'s'       => '#faq',
		),
		'about'      => array(
			'Our Story'    => '#journey',
			'Our Works'    => '#achievements',
			'How It Works' => '#process',
			'Our Team'     => '#team',
			'Our Clients'  => '#clients',
		),
		'properties' => array(
			'Portfolio'  => '#listing',
			'Categories' => '#property-search',
		),
		'services'   => array(
			'Valuation Mastery'   => '#unlock-property-value',
			'Strategic Marketing' => '#unlock-property-value',
			'Negotiation Wizardry' => '#unlock-property-value',
			'Closing Success'     => '#unlock-property-value',
			'Property Management' => '#effortless-property-management',
		),
		'contact'    => array(
			'Contact Form' => '#form-contact',
			'Our Offices'  => '#offices',
		),
	);
	foreach ( $columns as $role => $links ) {
		$parent = $add_page( $footer, $pages[ $role ] );
		foreach ( $links as $title => $anchor ) {
			$add_link( $footer, $title, get_permalink( $pages[ $role ] ) . $anchor, $parent );
		}
	}
	$locations['footer'] = $footer;

	set_theme_mod( 'nav_menu_locations', $locations );
}
