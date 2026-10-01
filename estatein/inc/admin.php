<?php
/**
 * Dashboard improvements: helpful list columns, inquiry viewer and setup
 * notices, so the client can manage content without guessing.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Property list columns: thumbnail, price, featured.
 *
 * @param array $columns Columns.
 * @return array
 */
function estatein_property_columns( $columns ) {
	$new = array();
	foreach ( $columns as $key => $label ) {
		if ( 'title' === $key ) {
			$new['estatein_thumb'] = '<span class="screen-reader-text">' . esc_html__( 'Image', 'estatein' ) . '</span>';
		}
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['estatein_price']    = __( 'Price', 'estatein' );
			$new['estatein_featured'] = __( 'Featured', 'estatein' );
		}
	}
	return $new;
}
add_filter( 'manage_property_posts_columns', 'estatein_property_columns' );

/**
 * Property list column values.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function estatein_property_column_values( $column, $post_id ) {
	switch ( $column ) {
		case 'estatein_thumb':
			echo get_the_post_thumbnail( $post_id, array( 60, 44 ), array( 'style' => 'border-radius:4px;object-fit:cover;width:60px;height:44px' ) );
			break;
		case 'estatein_price':
			echo esc_html( estatein_price( estatein_field( 'price', '—', $post_id ) ) );
			break;
		case 'estatein_featured':
			echo estatein_field( 'featured', false, $post_id ) ? '<span class="dashicons dashicons-star-filled" style="color:#703bf7" aria-label="' . esc_attr__( 'Featured', 'estatein' ) . '"></span>' : '—';
			break;
	}
}
add_action( 'manage_property_posts_custom_column', 'estatein_property_column_values', 10, 2 );

/**
 * Inquiry list columns.
 *
 * @param array $columns Columns.
 * @return array
 */
function estatein_inquiry_columns( $columns ) {
	return array(
		'cb'             => $columns['cb'],
		'title'          => __( 'Submission', 'estatein' ),
		'estatein_email' => __( 'Email', 'estatein' ),
		'estatein_form'  => __( 'Form', 'estatein' ),
		'date'           => __( 'Received', 'estatein' ),
	);
}
add_filter( 'manage_est_inquiry_posts_columns', 'estatein_inquiry_columns' );

/**
 * Inquiry column values.
 *
 * @param string $column  Column.
 * @param int    $post_id Post ID.
 */
function estatein_inquiry_column_values( $column, $post_id ) {
	$fields = (array) get_post_meta( $post_id, '_estatein_fields', true );
	if ( 'estatein_email' === $column && ! empty( $fields['email'] ) ) {
		printf( '<a href="mailto:%1$s">%1$s</a>', esc_html( $fields['email'] ) );
	}
	if ( 'estatein_form' === $column ) {
		$schemas = estatein_form_schemas();
		$form    = get_post_meta( $post_id, '_estatein_form', true );
		echo esc_html( isset( $schemas[ $form ] ) ? $schemas[ $form ]['label'] : $form );
	}
}
add_action( 'manage_est_inquiry_posts_custom_column', 'estatein_inquiry_column_values', 10, 2 );

/**
 * Read-only meta box with the submitted values.
 */
function estatein_inquiry_meta_box() {
	add_meta_box(
		'estatein-inquiry',
		__( 'Submitted details', 'estatein' ),
		function ( $post ) {
			$schemas = estatein_form_schemas();
			$form    = get_post_meta( $post->ID, '_estatein_form', true );
			$fields  = (array) get_post_meta( $post->ID, '_estatein_fields', true );
			$page    = get_post_meta( $post->ID, '_estatein_page', true );
			echo '<table class="widefat striped"><tbody>';
			foreach ( $fields as $key => $value ) {
				if ( '' === $value ) {
					continue;
				}
				$def   = $schemas[ $form ]['fields'][ $key ] ?? array( 'label' => $key );
				$value = isset( $def['choices'][ $value ] ) ? $def['choices'][ $value ] : $value;
				printf( '<tr><th style="width:200px">%s</th><td>%s</td></tr>', esc_html( $def['label'] ), nl2br( esc_html( $value ) ) );
			}
			if ( $page ) {
				printf( '<tr><th>%s</th><td><a href="%2$s">%2$s</a></td></tr>', esc_html__( 'Sent from', 'estatein' ), esc_url( $page ) );
			}
			echo '</tbody></table>';
		},
		'est_inquiry',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'estatein_inquiry_meta_box' );

/**
 * Setup notices: recommend Secure Custom Fields and point to the importer.
 */
function estatein_admin_notices() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( $screen && 'appearance_page_estatein-setup' === $screen->id ) {
		return;
	}

	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		printf(
			'<div class="notice notice-warning"><p>%1$s <a href="%2$s">%3$s</a></p></div>',
			esc_html__( 'Estatein: install and activate "Secure Custom Fields" (free, by WordPress.org) or ACF Pro to edit page sections. The site still shows the default design content without it.', 'estatein' ),
			esc_url( admin_url( 'plugin-install.php?s=secure%20custom%20fields&tab=search&type=term' ) ),
			esc_html__( 'Install Secure Custom Fields', 'estatein' )
		);
	}

	if ( ! get_option( 'estatein_demo_imported' ) ) {
		printf(
			'<div class="notice notice-info"><p>%1$s <a class="button button-primary" href="%2$s">%3$s</a></p></div>',
			esc_html__( 'Estatein: import the demo pages, menus and listings to match the design in one click.', 'estatein' ),
			esc_url( admin_url( 'themes.php?page=estatein-setup' ) ),
			esc_html__( 'Open Estatein Setup', 'estatein' )
		);
	}
}
add_action( 'admin_notices', 'estatein_admin_notices' );

/**
 * Brand the login screen with the Estatein colours (small touch for the client).
 */
function estatein_login_style() {
	echo '<style>body.login{background:#141414}.login h1 a{background:none!important;width:auto;height:auto;text-indent:0;font:600 28px/1 Urbanist,system-ui,sans-serif;color:#fff}.login form{background:#1a1a1a;border:1px solid #262626;border-radius:12px}.login label,.login #nav a,.login #backtoblog a{color:#999!important}.login input[type=text],.login input[type=password]{background:#141414;border-color:#262626;color:#fff}.wp-core-ui .button-primary{background:#703bf7;border-color:#703bf7}</style>';
}
add_action( 'login_enqueue_scripts', 'estatein_login_style' );

/**
 * Login logo links to the site and shows its name.
 *
 * @return string
 */
function estatein_login_header_url() {
	return home_url( '/' );
}
add_filter( 'login_headerurl', 'estatein_login_header_url' );

/**
 * Login logo text.
 *
 * @return string
 */
function estatein_login_header_text() {
	return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'estatein_login_header_text' );
