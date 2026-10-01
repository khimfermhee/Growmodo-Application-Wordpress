<?php
/**
 * Template helpers: field access with design fallbacks, images, links and
 * small markup builders shared by the template parts.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is a field value "empty" for fallback purposes? 0 and "0" are valid values.
 *
 * @param mixed $value Value to test.
 * @return bool
 */
function estatein_is_blank( $value ) {
	return null === $value || false === $value || '' === $value || array() === $value;
}

/**
 * Read a custom field with a fallback.
 *
 * Works with Secure Custom Fields / ACF (get_field) and degrades to plain
 * post meta when the plugin is not active, so templates never fatal.
 *
 * @param string          $name     Field name.
 * @param mixed           $fallback Value used when the field is empty.
 * @param int|string|null $post_id  Post ID, "option" or null for the current post.
 * @return mixed
 */
function estatein_field( $name, $fallback = null, $post_id = null ) {
	$value = null;

	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $name, $post_id );
	} elseif ( 'option' === $post_id ) {
		$value = get_option( 'options_' . $name, null );
	} elseif ( is_string( $post_id ) && ! is_numeric( $post_id ) && preg_match( '/_(\d+)$/', $post_id, $m ) ) {
		$value = get_term_meta( (int) $m[1], $name, true ); // e.g. "service_category_12".
	} else {
		$id    = $post_id ? $post_id : get_the_ID();
		$value = $id ? get_post_meta( $id, $name, true ) : null;
	}

	return estatein_is_blank( $value ) ? $fallback : $value;
}

/**
 * Read a Theme Settings value, falling back to the design default.
 *
 * @param string $name Option field name.
 * @return mixed
 */
function estatein_option( $name ) {
	return estatein_field( $name, estatein_default( 'options.' . $name ), 'option' );
}

/**
 * Read a page field (current page) with its design default.
 *
 * @param string $group Defaults group (home, about, properties, services, contact).
 * @param string $name  Field name (also the defaults key).
 * @return mixed
 */
function estatein_page_field( $group, $name ) {
	$post_id = is_front_page() ? (int) get_option( 'page_on_front' ) : get_queried_object_id();
	return estatein_field( $name, estatein_default( $group . '.' . $name ), $post_id ? $post_id : null );
}

/**
 * Turn a relative path from the defaults ("/properties/") into a full URL.
 * Absolute URLs, anchors, mailto: and tel: links are returned unchanged.
 *
 * @param string $url Raw URL.
 * @return string
 */
function estatein_url( $url ) {
	if ( ! is_string( $url ) || '' === $url ) {
		return '';
	}
	if ( 0 === strpos( $url, '/' ) && 0 !== strpos( $url, '//' ) ) {
		return home_url( $url );
	}
	return $url;
}

/**
 * Normalise an ACF link field (array) or a plain string into [url, title, target].
 *
 * @param array|string $link     Link value.
 * @param string       $fallback_label Label used for plain strings.
 * @return array
 */
function estatein_link( $link, $fallback_label = '' ) {
	if ( is_array( $link ) ) {
		return array(
			'url'    => estatein_url( isset( $link['url'] ) ? $link['url'] : '' ),
			'title'  => ! empty( $link['title'] ) ? $link['title'] : $fallback_label,
			'target' => ! empty( $link['target'] ) ? $link['target'] : '',
		);
	}
	return array(
		'url'    => estatein_url( (string) $link ),
		'title'  => $fallback_label,
		'target' => '',
	);
}

/**
 * Print a button / link styled with the design system.
 *
 * @param string $label   Visible label.
 * @param string $url     Destination.
 * @param string $variant primary|secondary.
 * @param array  $args    Extra args: class, icon, attrs (array), block (bool).
 */
function estatein_button( $label, $url, $variant = 'secondary', $args = array() ) {
	if ( '' === trim( (string) $label ) ) {
		return;
	}
	$args    = wp_parse_args(
		$args,
		array(
			'class'  => '',
			'icon'   => '',
			'attrs'  => array(),
			'target' => '',
		)
	);
	$classes = trim( 'btn btn--' . $variant . ' ' . $args['class'] );
	$attrs   = '';
	foreach ( $args['attrs'] as $key => $val ) {
		$attrs .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $val ) );
	}
	if ( '_blank' === $args['target'] ) {
		$attrs .= ' target="_blank" rel="noopener noreferrer"';
	}
	printf(
		'<a class="%1$s" href="%2$s"%3$s>%4$s%5$s</a>',
		esc_attr( $classes ),
		esc_url( estatein_url( $url ) ),
		$attrs, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
		$args['icon'] ? estatein_icon( $args['icon'] ) : '', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG.
		esc_html( $label )
	);
}

/**
 * Render an image from an attachment ID, an ACF image array or a URL.
 *
 * Attachment images get srcset/sizes and native lazy loading from core.
 *
 * @param int|array|string $image Attachment ID, ACF image array or URL.
 * @param string           $size  Registered image size.
 * @param array            $attr  Extra attributes (alt, class, loading, sizes...).
 * @return string HTML.
 */
function estatein_image( $image, $size = 'large', $attr = array() ) {
	if ( is_array( $image ) && isset( $image['ID'] ) ) {
		$image = (int) $image['ID'];
	}

	if ( is_numeric( $image ) && (int) $image > 0 ) {
		return wp_get_attachment_image( (int) $image, $size, false, $attr );
	}

	if ( is_string( $image ) && '' !== $image ) {
		$attr = wp_parse_args(
			$attr,
			array(
				'alt'      => '',
				'loading'  => 'lazy',
				'decoding' => 'async',
			)
		);
		$html = '<img src="' . esc_url( $image ) . '"';
		foreach ( $attr as $key => $val ) {
			if ( false === $val || null === $val ) {
				continue;
			}
			$html .= ' ' . esc_attr( $key ) . '="' . esc_attr( $val ) . '"';
		}
		return $html . '>';
	}

	return '';
}

/**
 * URL of a demo image shipped with the theme (used as fallback content).
 *
 * @param string $file File name inside /demo/images.
 * @return string
 */
function estatein_demo_image( $file ) {
	return $file ? ESTATEIN_URI . '/demo/images/' . ltrim( $file, '/' ) : '';
}

/**
 * Format a numeric price as $1,250,000. Non numeric values are returned as-is.
 *
 * @param mixed $price Price.
 * @return string
 */
function estatein_price( $price ) {
	if ( is_numeric( $price ) ) {
		return '$' . number_format_i18n( (float) $price );
	}
	return (string) $price;
}

/**
 * Zero-padded number used by step labels and carousel counters (01, 02...).
 *
 * @param int $number Number.
 * @return string
 */
function estatein_pad( $number ) {
	return str_pad( (string) absint( $number ), 2, '0', STR_PAD_LEFT );
}

/**
 * Load a template part and pass data to it (thin wrapper for readability).
 *
 * @param string $slug Template part slug relative to template-parts/.
 * @param array  $args Data available as $args inside the part.
 */
function estatein_part( $slug, $args = array() ) {
	get_template_part( 'template-parts/' . $slug, null, $args );
}

/**
 * The decorative three-star mark shown above every section heading.
 */
function estatein_sparkle() {
	echo '<span class="sparkle" aria-hidden="true"><svg viewBox="0 0 69 30" width="69" height="30" focusable="false"><path d="M15 0c.9 7.7 6.3 13.1 14 14-7.7.9-13.1 6.3-14 14-.9-7.7-6.3-13.1-14-14 7.7-.9 13.1-6.3 14-14Z" fill="currentColor" opacity=".9"/><path d="M40 8.5c.5 3.6 2.9 6 6.5 6.5-3.6.5-6 2.9-6.5 6.5-.5-3.6-2.9-6-6.5-6.5 3.6-.5 6-2.9 6.5-6.5Z" fill="currentColor" opacity=".6"/><path d="M59 12.5c.3 1.3 1.2 2.2 2.5 2.5-1.3.3-2.2 1.2-2.5 2.5-.3-1.3-1.2-2.2-2.5-2.5 1.3-.3 2.2-1.2 2.5-2.5Z" fill="currentColor" opacity=".4"/></svg></span>';
}

/**
 * Social profile links from Theme Settings, in display order.
 *
 * @return array[] { key, label, url }
 */
function estatein_socials() {
	$networks = array(
		'facebook'  => __( 'Facebook', 'estatein' ),
		'linkedin'  => __( 'LinkedIn', 'estatein' ),
		'twitter'   => __( 'Twitter', 'estatein' ),
		'youtube'   => __( 'YouTube', 'estatein' ),
		'instagram' => __( 'Instagram', 'estatein' ),
	);
	$links    = array();
	foreach ( $networks as $key => $label ) {
		$url = estatein_option( 'social_' . $key );
		if ( $url ) {
			$links[] = array(
				'key'   => $key,
				'label' => $label,
				'url'   => $url,
			);
		}
	}
	return $links;
}

/**
 * Find the permalink of the first page using a given page template.
 *
 * @param string $template Template file, e.g. page-templates/template-properties.php.
 * @param string $fallback Path used when no page exists yet.
 * @return string
 */
function estatein_template_url( $template, $fallback = '/' ) {
	static $cache = array();
	if ( ! isset( $cache[ $template ] ) ) {
		$pages              = get_posts(
			array(
				'post_type'      => 'page',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => $template, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			)
		);
		$cache[ $template ] = $pages ? get_permalink( $pages[0] ) : home_url( $fallback );
	}
	return $cache[ $template ];
}

/**
 * Properties listing URL (page using the Properties template).
 *
 * @return string
 */
function estatein_properties_url() {
	return estatein_template_url( 'page-templates/template-properties.php', '/properties/' );
}

/**
 * Footer link columns.
 *
 * Built from the "Footer" menu (top-level items = headings, children = links).
 * Before a menu is assigned, the design's columns are shown.
 *
 * @return array[] { title, url, links: [ { title, url } ] }
 */
function estatein_footer_columns() {
	$locations = get_nav_menu_locations();

	if ( ! empty( $locations['footer'] ) ) {
		$items   = (array) wp_get_nav_menu_items( $locations['footer'] );
		$columns = array();
		foreach ( $items as $item ) {
			if ( ! $item->menu_item_parent ) {
				$columns[ $item->ID ] = array(
					'title' => $item->title,
					'url'   => $item->url,
					'links' => array(),
				);
			}
		}
		foreach ( $items as $item ) {
			if ( $item->menu_item_parent && isset( $columns[ $item->menu_item_parent ] ) ) {
				$columns[ $item->menu_item_parent ]['links'][] = array(
					'title' => $item->title,
					'url'   => $item->url,
				);
			}
		}
		if ( $columns ) {
			return array_values( $columns );
		}
	}

	$link = function ( $title, $path ) {
		return array(
			'title' => $title,
			'url'   => home_url( $path ),
		);
	};

	return array(
		array(
			'title' => __( 'Home', 'estatein' ),
			'url'   => home_url( '/' ),
			'links' => array( $link( __( 'Hero Section', 'estatein' ), '/#hero' ), $link( __( 'Features', 'estatein' ), '/#features' ), $link( __( 'Properties', 'estatein' ), '/#featured-properties' ), $link( __( 'Testimonials', 'estatein' ), '/#testimonials' ), $link( __( 'FAQ\'s', 'estatein' ), '/#faq' ) ),
		),
		array(
			'title' => __( 'About Us', 'estatein' ),
			'url'   => home_url( '/about-us/' ),
			'links' => array( $link( __( 'Our Story', 'estatein' ), '/about-us/#journey' ), $link( __( 'Our Works', 'estatein' ), '/about-us/#achievements' ), $link( __( 'How It Works', 'estatein' ), '/about-us/#process' ), $link( __( 'Our Team', 'estatein' ), '/about-us/#team' ), $link( __( 'Our Clients', 'estatein' ), '/about-us/#clients' ) ),
		),
		array(
			'title' => __( 'Properties', 'estatein' ),
			'url'   => home_url( '/properties/' ),
			'links' => array( $link( __( 'Portfolio', 'estatein' ), '/properties/#listing' ), $link( __( 'Categories', 'estatein' ), '/properties/#property-search' ) ),
		),
		array(
			'title' => __( 'Services', 'estatein' ),
			'url'   => home_url( '/services/' ),
			'links' => array( $link( __( 'Valuation Mastery', 'estatein' ), '/services/#unlock-property-value' ), $link( __( 'Strategic Marketing', 'estatein' ), '/services/#unlock-property-value' ), $link( __( 'Negotiation Wizardry', 'estatein' ), '/services/#unlock-property-value' ), $link( __( 'Closing Success', 'estatein' ), '/services/#unlock-property-value' ), $link( __( 'Property Management', 'estatein' ), '/services/#effortless-property-management' ) ),
		),
		array(
			'title' => __( 'Contact Us', 'estatein' ),
			'url'   => home_url( '/contact-us/' ),
			'links' => array( $link( __( 'Contact Form', 'estatein' ), '/contact-us/#form-contact' ), $link( __( 'Our Offices', 'estatein' ), '/contact-us/#offices' ) ),
		),
	);
}

/**
 * Purple icon inside the gradient ring used across cards.
 *
 * @param string $icon Icon key.
 * @param string $size sm|md.
 * @return string
 */
function estatein_icon_badge( $icon, $size = 'md' ) {
	return '<span class="icon-badge icon-badge--' . esc_attr( $size ) . '" aria-hidden="true"><span class="icon-badge__inner">' . estatein_icon( $icon ? $icon : 'star' ) . '</span></span>';
}

/**
 * Normalise repeater rows so templates never break on unexpected data
 * (e.g. a field saved before the fields plugin was active returns a row
 * count instead of rows). Invalid input falls back to $default.
 *
 * @param mixed    $rows    Repeater value.
 * @param string[] $keys    Sub field names every row must have.
 * @param array    $default Rows used when $rows is unusable.
 * @return array[]
 */
function estatein_rows( $rows, $keys, $default = array() ) {
	$clean = array();
	if ( is_array( $rows ) ) {
		foreach ( $rows as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			foreach ( $keys as $key ) {
				$row[ $key ] = isset( $row[ $key ] ) && is_scalar( $row[ $key ] ) ? (string) $row[ $key ] : ( isset( $row[ $key ] ) && is_array( $row[ $key ] ) ? $row[ $key ] : '' );
			}
			$clean[] = $row;
		}
	}
	if ( ! $clean && $default !== $rows ) {
		return $default ? estatein_rows( $default, $keys ) : array();
	}
	return $clean;
}
