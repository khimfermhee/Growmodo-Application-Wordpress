<?php
/**
 * Field groups for Secure Custom Fields (free, by WordPress.org) or ACF Pro.
 *
 * Registered in PHP (acf_add_local_field_group) so fields are version
 * controlled and appear automatically after activating the plugin – no
 * JSON import step. Repeaters, galleries and the options page require SCF
 * or ACF Pro; templates fall back to the design copy when fields are empty.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Compact field definition builder.
 *
 * @param string $group Group prefix for unique keys.
 * @param string $type  ACF field type.
 * @param string $name  Field name (meta key).
 * @param string $label Admin label.
 * @param array  $extra Extra field settings.
 * @return array
 */
function estatein_f( $group, $type, $name, $label, $extra = array() ) {
	return array_merge(
		array(
			'key'   => 'field_est_' . $group . '_' . $name,
			'name'  => $name,
			'label' => $label,
			'type'  => $type,
		),
		$extra
	);
}

/**
 * Tab field builder.
 *
 * @param string $group Group prefix.
 * @param string $label Tab label.
 * @return array
 */
function estatein_tab( $group, $label ) {
	return array(
		'key'       => 'field_est_' . $group . '_tab_' . sanitize_title( $label ),
		'label'     => $label,
		'type'      => 'tab',
		'placement' => 'top',
	);
}

/**
 * Title + text pair for a section heading.
 *
 * @param string $group  Group prefix.
 * @param string $prefix Field name prefix, e.g. "featured".
 * @param string $label  Human label.
 * @param bool   $button Include a button label field.
 * @return array
 */
function estatein_heading_fields( $group, $prefix, $label, $button = false ) {
	$fields = array(
		estatein_f( $group, 'text', $prefix . '_title', $label . ' – ' . __( 'Title', 'estatein' ), array( 'placeholder' => estatein_default( $group . '.' . $prefix . '_title' ) ) ),
		estatein_f( $group, 'textarea', $prefix . '_text', $label . ' – ' . __( 'Intro text', 'estatein' ), array( 'rows' => 3, 'new_lines' => '', 'placeholder' => estatein_default( $group . '.' . $prefix . '_text' ) ) ),
	);
	if ( $button ) {
		$fields[] = estatein_f( $group, 'text', $prefix . '_button', $label . ' – ' . __( 'Button label', 'estatein' ), array( 'placeholder' => estatein_default( $group . '.' . $prefix . '_button' ) ) );
	}
	return $fields;
}

/**
 * Location rule helper for page templates.
 *
 * @param string $template Template file.
 * @return array
 */
function estatein_template_location( $template ) {
	return array(
		array(
			array(
				'param'    => 'page_template',
				'operator' => '==',
				'value'    => $template,
			),
		),
	);
}

/**
 * Location rule helper for post types.
 *
 * @param string $post_type Post type.
 * @return array
 */
function estatein_post_type_location( $post_type ) {
	return array(
		array(
			array(
				'param'    => 'post_type',
				'operator' => '==',
				'value'    => $post_type,
			),
		),
	);
}

/**
 * Register the Theme Settings options page.
 */
function estatein_register_options_page() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}
	acf_add_options_page(
		array(
			'page_title' => __( 'Theme Settings', 'estatein' ),
			'menu_title' => __( 'Theme Settings', 'estatein' ),
			'menu_slug'  => 'estatein-settings',
			'capability' => 'edit_theme_options',
			'icon_url'   => 'dashicons-admin-customizer',
			'position'   => 59,
			'redirect'   => false,
		)
	);
}
add_action( 'acf/init', 'estatein_register_options_page' );

/**
 * Register all field groups.
 */
function estatein_register_field_groups() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$icon_select = array(
		'choices'       => estatein_icon_choices(),
		'default_value' => 'star',
		'ui'            => 1,
	);
	$rows_hint   = __( 'Leave empty to show the default design content.', 'estatein' );

	/* -----------------------------------------------------------------
	 * Theme Settings (options page).
	 * --------------------------------------------------------------- */
	$o = 'options';
	acf_add_local_field_group(
		array(
			'key'      => 'group_est_options',
			'title'    => __( 'Theme Settings', 'estatein' ),
			'fields'   => array(
				estatein_tab( $o, __( 'Header', 'estatein' ) ),
				estatein_f( $o, 'true_false', 'announcement_enabled', __( 'Show announcement bar', 'estatein' ), array( 'ui' => 1, 'default_value' => 1 ) ),
				estatein_f( $o, 'text', 'announcement_text', __( 'Announcement text', 'estatein' ), array( 'placeholder' => estatein_default( 'options.announcement_text' ) ) ),
				estatein_f( $o, 'text', 'announcement_link', __( 'Announcement link label', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_f( $o, 'text', 'announcement_url', __( 'Announcement link URL', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_f( $o, 'text', 'header_button_label', __( 'Header button label', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_f( $o, 'text', 'header_button_url', __( 'Header button URL', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),

				estatein_tab( $o, __( 'Contact & Social', 'estatein' ) ),
				estatein_f( $o, 'email', 'contact_email', __( 'Email', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_f( $o, 'text', 'contact_phone', __( 'Phone', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_f( $o, 'text', 'contact_hq_label', __( 'Headquarters label', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_f( $o, 'url', 'contact_hq_url', __( 'Headquarters map link', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_f( $o, 'url', 'social_facebook', 'Facebook', array( 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_f( $o, 'url', 'social_linkedin', 'LinkedIn', array( 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_f( $o, 'url', 'social_twitter', 'Twitter / X', array( 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_f( $o, 'url', 'social_youtube', 'YouTube', array( 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_f( $o, 'url', 'social_instagram', 'Instagram', array( 'wrapper' => array( 'width' => 50 ) ) ),

				estatein_tab( $o, __( 'Stats & Quick Links', 'estatein' ) ),
				estatein_f(
					$o,
					'repeater',
					'stats',
					__( 'Statistics (Home + About)', 'estatein' ),
					array(
						'instructions' => $rows_hint,
						'layout'       => 'table',
						'button_label' => __( 'Add statistic', 'estatein' ),
						'sub_fields'   => array(
							estatein_f( $o . '_stats', 'text', 'number', __( 'Number', 'estatein' ) ),
							estatein_f( $o . '_stats', 'text', 'label', __( 'Label', 'estatein' ) ),
						),
					)
				),
				estatein_f(
					$o,
					'repeater',
					'quick_links',
					__( 'Quick link cards (Home + Services)', 'estatein' ),
					array(
						'instructions' => $rows_hint,
						'layout'       => 'table',
						'max'          => 4,
						'button_label' => __( 'Add card', 'estatein' ),
						'sub_fields'   => array(
							estatein_f( $o . '_ql', 'select', 'icon', __( 'Icon', 'estatein' ), $icon_select ),
							estatein_f( $o . '_ql', 'text', 'title', __( 'Title', 'estatein' ) ),
							estatein_f( $o . '_ql', 'text', 'url', __( 'Link', 'estatein' ) ),
						),
					)
				),

				estatein_tab( $o, __( 'Call to Action', 'estatein' ) ),
				estatein_f( $o, 'text', 'cta_title', __( 'Title', 'estatein' ), array( 'placeholder' => estatein_default( 'options.cta_title' ) ) ),
				estatein_f( $o, 'textarea', 'cta_text', __( 'Text', 'estatein' ), array( 'rows' => 3, 'new_lines' => '' ) ),
				estatein_f( $o, 'text', 'cta_button_label', __( 'Button label', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_f( $o, 'text', 'cta_button_url', __( 'Button URL', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),

				estatein_tab( $o, __( 'Footer & Forms', 'estatein' ) ),
				estatein_f( $o, 'text', 'copyright', __( 'Copyright text', 'estatein' ) ),
				estatein_f( $o, 'text', 'terms_url', __( 'Terms & Conditions URL', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_f( $o, 'text', 'privacy_url', __( 'Privacy Policy URL', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_f( $o, 'email', 'form_recipient', __( 'Send form notifications to', 'estatein' ), array( 'instructions' => __( 'Defaults to the site admin email. Every submission is also saved under Inquiries.', 'estatein' ) ) ),
				estatein_f( $o, 'textarea', 'pricing_note', __( 'Property pricing note', 'estatein' ), array( 'rows' => 2, 'new_lines' => '' ) ),
			),
			'location' => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'estatein-settings',
					),
				),
			),
		)
	);

	/* -----------------------------------------------------------------
	 * Home page.
	 * --------------------------------------------------------------- */
	$g = 'home';
	acf_add_local_field_group(
		array(
			'key'            => 'group_est_home',
			'title'          => __( 'Home Page Content', 'estatein' ),
			'fields'         => array_merge(
				array(
					estatein_tab( $g, __( 'Hero', 'estatein' ) ),
					estatein_f( $g, 'text', 'hero_title', __( 'Title', 'estatein' ), array( 'placeholder' => estatein_default( 'home.hero_title' ) ) ),
					estatein_f( $g, 'textarea', 'hero_text', __( 'Text', 'estatein' ), array( 'rows' => 3, 'new_lines' => '' ) ),
					estatein_f( $g, 'text', 'hero_secondary_label', __( 'Secondary button label', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),
					estatein_f( $g, 'text', 'hero_secondary_url', __( 'Secondary button URL', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),
					estatein_f( $g, 'text', 'hero_primary_label', __( 'Primary button label', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),
					estatein_f( $g, 'text', 'hero_primary_url', __( 'Primary button URL', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),
					estatein_f( $g, 'text', 'hero_badge_text', __( 'Rotating badge text', 'estatein' ) ),
					estatein_f( $g, 'image', 'hero_image', __( 'Hero image', 'estatein' ), array( 'return_format' => 'id', 'preview_size' => 'medium', 'instructions' => __( 'Transparent PNG/WebP works best (about 1200×1060).', 'estatein' ) ) ),
					estatein_tab( $g, __( 'Sections', 'estatein' ) ),
				),
				estatein_heading_fields( $g, 'featured', __( 'Featured properties', 'estatein' ), true ),
				estatein_heading_fields( $g, 'testimonials', __( 'Testimonials', 'estatein' ), true ),
				estatein_heading_fields( $g, 'faq', __( 'FAQ', 'estatein' ), true )
			),
			'location'       => array(
				array(
					array(
						'param'    => 'page_type',
						'operator' => '==',
						'value'    => 'front_page',
					),
				),
			),
			'hide_on_screen' => array( 'the_content' ),
		)
	);

	/* -----------------------------------------------------------------
	 * About page.
	 * --------------------------------------------------------------- */
	$g = 'about';
	acf_add_local_field_group(
		array(
			'key'            => 'group_est_about',
			'title'          => __( 'About Page Content', 'estatein' ),
			'fields'         => array_merge(
				array(
					estatein_tab( $g, __( 'Our Journey', 'estatein' ) ),
					estatein_f( $g, 'text', 'journey_title', __( 'Title', 'estatein' ), array( 'placeholder' => estatein_default( 'about.journey_title' ) ) ),
					estatein_f( $g, 'textarea', 'journey_text', __( 'Text', 'estatein' ), array( 'rows' => 4, 'new_lines' => '' ) ),
					estatein_f( $g, 'image', 'journey_image', __( 'Image', 'estatein' ), array( 'return_format' => 'id', 'preview_size' => 'medium' ) ),
					estatein_tab( $g, __( 'Values', 'estatein' ) ),
				),
				estatein_heading_fields( $g, 'values', __( 'Values', 'estatein' ) ),
				array(
					estatein_f(
						$g,
						'repeater',
						'values',
						__( 'Values', 'estatein' ),
						array(
							'instructions' => $rows_hint,
							'layout'       => 'block',
							'button_label' => __( 'Add value', 'estatein' ),
							'sub_fields'   => array(
								estatein_f( $g . '_val', 'select', 'icon', __( 'Icon', 'estatein' ), $icon_select ),
								estatein_f( $g . '_val', 'text', 'title', __( 'Title', 'estatein' ) ),
								estatein_f( $g . '_val', 'textarea', 'text', __( 'Text', 'estatein' ), array( 'rows' => 2, 'new_lines' => '' ) ),
							),
						)
					),
					estatein_tab( $g, __( 'Achievements', 'estatein' ) ),
				),
				estatein_heading_fields( $g, 'achievements', __( 'Achievements', 'estatein' ) ),
				array(
					estatein_f(
						$g,
						'repeater',
						'achievements',
						__( 'Achievements', 'estatein' ),
						array(
							'instructions' => $rows_hint,
							'layout'       => 'block',
							'button_label' => __( 'Add achievement', 'estatein' ),
							'sub_fields'   => array(
								estatein_f( $g . '_ach', 'text', 'title', __( 'Title', 'estatein' ) ),
								estatein_f( $g . '_ach', 'textarea', 'text', __( 'Text', 'estatein' ), array( 'rows' => 2, 'new_lines' => '' ) ),
							),
						)
					),
					estatein_tab( $g, __( 'Process', 'estatein' ) ),
				),
				estatein_heading_fields( $g, 'process', __( 'Process', 'estatein' ) ),
				array(
					estatein_f(
						$g,
						'repeater',
						'steps',
						__( 'Steps', 'estatein' ),
						array(
							'instructions' => $rows_hint,
							'layout'       => 'block',
							'button_label' => __( 'Add step', 'estatein' ),
							'sub_fields'   => array(
								estatein_f( $g . '_steps', 'text', 'title', __( 'Title', 'estatein' ) ),
								estatein_f( $g . '_steps', 'textarea', 'text', __( 'Text', 'estatein' ), array( 'rows' => 3, 'new_lines' => '' ) ),
							),
						)
					),
					estatein_tab( $g, __( 'Team & Clients', 'estatein' ) ),
				),
				estatein_heading_fields( $g, 'team', __( 'Team', 'estatein' ) ),
				estatein_heading_fields( $g, 'clients', __( 'Clients', 'estatein' ) )
			),
			'location'       => estatein_template_location( 'page-templates/template-about.php' ),
			'hide_on_screen' => array( 'the_content' ),
		)
	);

	/* -----------------------------------------------------------------
	 * Properties page.
	 * --------------------------------------------------------------- */
	$g = 'properties';
	acf_add_local_field_group(
		array(
			'key'            => 'group_est_properties',
			'title'          => __( 'Properties Page Content', 'estatein' ),
			'fields'         => array_merge(
				estatein_heading_fields( $g, 'hero', __( 'Hero', 'estatein' ) ),
				estatein_heading_fields( $g, 'listing', __( 'Listing', 'estatein' ) ),
				estatein_heading_fields( $g, 'form', __( 'Inquiry form', 'estatein' ) )
			),
			'location'       => estatein_template_location( 'page-templates/template-properties.php' ),
			'hide_on_screen' => array( 'the_content' ),
		)
	);

	/* -----------------------------------------------------------------
	 * Services page.
	 * --------------------------------------------------------------- */
	$g = 'services';
	acf_add_local_field_group(
		array(
			'key'            => 'group_est_services',
			'title'          => __( 'Services Page Content', 'estatein' ),
			'fields'         => array_merge(
				estatein_heading_fields( $g, 'hero', __( 'Hero', 'estatein' ) ),
				array(
					array(
						'key'     => 'field_est_services_note',
						'label'   => __( 'Service sections', 'estatein' ),
						'name'    => '',
						'type'    => 'message',
						'message' => __( 'The service sections below the hero are managed under Services → Service Sections (intro text, layout and call to action) and Services → All Services (cards).', 'estatein' ),
					),
				)
			),
			'location'       => estatein_template_location( 'page-templates/template-services.php' ),
			'hide_on_screen' => array( 'the_content' ),
		)
	);

	/* -----------------------------------------------------------------
	 * Contact page.
	 * --------------------------------------------------------------- */
	$g = 'contact';
	acf_add_local_field_group(
		array(
			'key'            => 'group_est_contact',
			'title'          => __( 'Contact Page Content', 'estatein' ),
			'fields'         => array_merge(
				estatein_heading_fields( $g, 'hero', __( 'Hero', 'estatein' ) ),
				estatein_heading_fields( $g, 'form', __( 'Form', 'estatein' ) ),
				estatein_heading_fields( $g, 'offices', __( 'Offices', 'estatein' ) ),
				estatein_heading_fields( $g, 'gallery', __( 'Gallery', 'estatein' ) ),
				array(
					estatein_f( $g, 'gallery', 'gallery', __( 'Gallery images (6)', 'estatein' ), array( 'return_format' => 'id', 'max' => 6, 'preview_size' => 'thumbnail' ) ),
				)
			),
			'location'       => estatein_template_location( 'page-templates/template-contact.php' ),
			'hide_on_screen' => array( 'the_content' ),
		)
	);

	/* -----------------------------------------------------------------
	 * Property.
	 * --------------------------------------------------------------- */
	$g = 'property';
	acf_add_local_field_group(
		array(
			'key'      => 'group_est_property',
			'title'    => __( 'Property Details', 'estatein' ),
			'position' => 'acf_after_title',
			'fields'   => array(
				estatein_tab( $g, __( 'Overview', 'estatein' ) ),
				estatein_f( $g, 'number', 'price', __( 'Price (USD)', 'estatein' ), array( 'required' => 1, 'min' => 0, 'prepend' => '$', 'wrapper' => array( 'width' => 33 ) ) ),
				estatein_f( $g, 'text', 'location_label', __( 'Location label', 'estatein' ), array( 'placeholder' => 'Malibu, California', 'wrapper' => array( 'width' => 33 ) ) ),
				estatein_f( $g, 'true_false', 'featured', __( 'Featured on home page', 'estatein' ), array( 'ui' => 1, 'wrapper' => array( 'width' => 33 ) ) ),
				estatein_f( $g, 'text', 'tagline', __( 'Category tagline', 'estatein' ), array( 'placeholder' => 'Coastal Escapes - Where Waves Beckon' ) ),
				estatein_f( $g, 'number', 'bedrooms', __( 'Bedrooms', 'estatein' ), array( 'min' => 0, 'wrapper' => array( 'width' => 25 ) ) ),
				estatein_f( $g, 'number', 'bathrooms', __( 'Bathrooms', 'estatein' ), array( 'min' => 0, 'step' => 0.5, 'wrapper' => array( 'width' => 25 ) ) ),
				estatein_f( $g, 'number', 'area', __( 'Area (sq ft)', 'estatein' ), array( 'min' => 0, 'wrapper' => array( 'width' => 25 ) ) ),
				estatein_f( $g, 'number', 'build_year', __( 'Build year', 'estatein' ), array( 'min' => 1800, 'max' => 2100, 'wrapper' => array( 'width' => 25 ) ) ),
				estatein_tab( $g, __( 'Gallery', 'estatein' ) ),
				estatein_f( $g, 'gallery', 'gallery', __( 'Gallery', 'estatein' ), array( 'return_format' => 'id', 'preview_size' => 'thumbnail', 'instructions' => __( 'The featured image is used when the gallery is empty.', 'estatein' ) ) ),
				estatein_tab( $g, __( 'Features', 'estatein' ) ),
				estatein_f(
					$g,
					'repeater',
					'features',
					__( 'Key features and amenities', 'estatein' ),
					array(
						'layout'       => 'table',
						'button_label' => __( 'Add feature', 'estatein' ),
						'sub_fields'   => array(
							estatein_f( $g . '_features', 'text', 'text', __( 'Feature', 'estatein' ) ),
						),
					)
				),
				estatein_tab( $g, __( 'Pricing', 'estatein' ) ),
				estatein_f(
					$g,
					'repeater',
					'pricing_groups',
					__( 'Pricing breakdown', 'estatein' ),
					array(
						'instructions' => __( 'E.g. Additional Fees, Monthly Costs, Total Initial Costs. Leave empty to hide the section.', 'estatein' ),
						'layout'       => 'block',
						'button_label' => __( 'Add pricing group', 'estatein' ),
						'sub_fields'   => array(
							estatein_f( $g . '_pg', 'text', 'title', __( 'Group title', 'estatein' ) ),
							estatein_f(
								$g . '_pg',
								'repeater',
								'rows',
								__( 'Rows', 'estatein' ),
								array(
									'layout'       => 'table',
									'button_label' => __( 'Add row', 'estatein' ),
									'sub_fields'   => array(
										estatein_f( $g . '_pg_rows', 'text', 'label', __( 'Label', 'estatein' ) ),
										estatein_f( $g . '_pg_rows', 'text', 'amount', __( 'Amount', 'estatein' ) ),
										estatein_f( $g . '_pg_rows', 'text', 'note', __( 'Note', 'estatein' ) ),
										estatein_f( $g . '_pg_rows', 'true_false', 'full', __( 'Full width', 'estatein' ), array( 'ui' => 1 ) ),
									),
								)
							),
						),
					)
				),
			),
			'location' => estatein_post_type_location( 'property' ),
		)
	);

	/* -----------------------------------------------------------------
	 * Small content types.
	 * --------------------------------------------------------------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_est_testimonial',
			'title'    => __( 'Testimonial Details', 'estatein' ),
			'position' => 'acf_after_title',
			'fields'   => array(
				estatein_f( 'testimonial', 'text', 'client_name', __( 'Client name', 'estatein' ), array( 'required' => 1, 'wrapper' => array( 'width' => 40 ) ) ),
				estatein_f( 'testimonial', 'text', 'client_location', __( 'Client location', 'estatein' ), array( 'placeholder' => 'USA, California', 'wrapper' => array( 'width' => 40 ) ) ),
				estatein_f( 'testimonial', 'number', 'rating', __( 'Rating', 'estatein' ), array( 'min' => 1, 'max' => 5, 'default_value' => 5, 'wrapper' => array( 'width' => 20 ) ) ),
				array(
					'key'     => 'field_est_testimonial_hint',
					'label'   => '',
					'name'    => '',
					'type'    => 'message',
					'message' => __( 'Title = headline (e.g. "Exceptional Service!"), content = the quote, featured image = client photo.', 'estatein' ),
				),
			),
			'location' => estatein_post_type_location( 'testimonial' ),
		)
	);

	acf_add_local_field_group(
		array(
			'key'      => 'group_est_faq',
			'title'    => __( 'FAQ', 'estatein' ),
			'position' => 'acf_after_title',
			'fields'   => array(
				array(
					'key'     => 'field_est_faq_hint',
					'label'   => '',
					'name'    => '',
					'type'    => 'message',
					'message' => __( 'Title = question, excerpt = short summary on cards, content = full answer on the FAQ page.', 'estatein' ),
				),
			),
			'location' => estatein_post_type_location( 'faq' ),
		)
	);

	acf_add_local_field_group(
		array(
			'key'      => 'group_est_team',
			'title'    => __( 'Team Member Details', 'estatein' ),
			'position' => 'acf_after_title',
			'fields'   => array(
				estatein_f( 'team', 'text', 'role', __( 'Role', 'estatein' ), array( 'required' => 1 ) ),
				estatein_f( 'team', 'url', 'twitter', __( 'Twitter / X profile', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_f( 'team', 'email', 'email', __( 'Email ("Say Hello")', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),
			),
			'location' => estatein_post_type_location( 'team_member' ),
		)
	);

	acf_add_local_field_group(
		array(
			'key'      => 'group_est_client',
			'title'    => __( 'Client Details', 'estatein' ),
			'position' => 'acf_after_title',
			'fields'   => array(
				estatein_f( 'client', 'text', 'since', __( 'Client since (year)', 'estatein' ), array( 'wrapper' => array( 'width' => 25 ) ) ),
				estatein_f( 'client', 'url', 'website', __( 'Website', 'estatein' ), array( 'wrapper' => array( 'width' => 75 ) ) ),
				estatein_f( 'client', 'text', 'domain', __( 'Domain', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_f( 'client', 'text', 'category', __( 'Category', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_f( 'client', 'textarea', 'quote', __( 'What they said', 'estatein' ), array( 'rows' => 3, 'new_lines' => '' ) ),
			),
			'location' => estatein_post_type_location( 'client' ),
		)
	);

	acf_add_local_field_group(
		array(
			'key'      => 'group_est_service',
			'title'    => __( 'Service Card', 'estatein' ),
			'position' => 'acf_after_title',
			'fields'   => array(
				estatein_f( 'service', 'select', 'icon', __( 'Icon', 'estatein' ), $icon_select ),
				array(
					'key'     => 'field_est_service_hint',
					'label'   => '',
					'name'    => '',
					'type'    => 'message',
					'message' => __( 'The excerpt is used as the card text. Assign a Service Section and use "Order" to sort.', 'estatein' ),
				),
			),
			'location' => estatein_post_type_location( 'service' ),
		)
	);

	acf_add_local_field_group(
		array(
			'key'      => 'group_est_service_section',
			'title'    => __( 'Section Settings', 'estatein' ),
			'fields'   => array(
				estatein_f( 'svc_section', 'textarea', 'intro', __( 'Intro text', 'estatein' ), array( 'rows' => 3, 'new_lines' => '' ) ),
				estatein_f(
					'svc_section',
					'select',
					'layout',
					__( 'Layout', 'estatein' ),
					array(
						'choices'       => array(
							'grid'  => __( 'Cards grid with CTA card', 'estatein' ),
							'split' => __( 'Intro + CTA left, 2×2 cards right', 'estatein' ),
						),
						'default_value' => 'grid',
					)
				),
				estatein_f( 'svc_section', 'number', 'order', __( 'Display order', 'estatein' ), array( 'default_value' => 0 ) ),
				estatein_f( 'svc_section', 'text', 'cta_title', __( 'CTA title', 'estatein' ) ),
				estatein_f( 'svc_section', 'textarea', 'cta_text', __( 'CTA text', 'estatein' ), array( 'rows' => 2, 'new_lines' => '' ) ),
				estatein_f( 'svc_section', 'text', 'cta_label', __( 'CTA button label', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_f( 'svc_section', 'text', 'cta_url', __( 'CTA button URL', 'estatein' ), array( 'wrapper' => array( 'width' => 50 ) ) ),
			),
			'location' => array(
				array(
					array(
						'param'    => 'taxonomy',
						'operator' => '==',
						'value'    => 'service_category',
					),
				),
			),
		)
	);

	acf_add_local_field_group(
		array(
			'key'      => 'group_est_office',
			'title'    => __( 'Office Details', 'estatein' ),
			'position' => 'acf_after_title',
			'fields'   => array(
				estatein_f( 'office', 'text', 'label', __( 'Label', 'estatein' ), array( 'placeholder' => 'Main Headquarters', 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_f(
					'office',
					'select',
					'office_type',
					__( 'Type', 'estatein' ),
					array(
						'choices'       => array(
							'regional'      => __( 'Regional', 'estatein' ),
							'international' => __( 'International', 'estatein' ),
						),
						'default_value' => 'regional',
						'wrapper'       => array( 'width' => 50 ),
					)
				),
				estatein_f( 'office', 'email', 'email', __( 'Email', 'estatein' ), array( 'wrapper' => array( 'width' => 33 ) ) ),
				estatein_f( 'office', 'text', 'phone', __( 'Phone', 'estatein' ), array( 'wrapper' => array( 'width' => 33 ) ) ),
				estatein_f( 'office', 'text', 'city', __( 'City', 'estatein' ), array( 'wrapper' => array( 'width' => 33 ) ) ),
				estatein_f( 'office', 'url', 'map', __( 'Map / directions link', 'estatein' ) ),
				array(
					'key'     => 'field_est_office_hint',
					'label'   => '',
					'name'    => '',
					'type'    => 'message',
					'message' => __( 'Title = full address, content = description.', 'estatein' ),
				),
			),
			'location' => estatein_post_type_location( 'office' ),
		)
	);
}
add_action( 'acf/init', 'estatein_register_field_groups' );
