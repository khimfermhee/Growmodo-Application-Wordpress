<?php
/**
 * Inline SVG icon set.
 *
 * Inline SVG avoids an icon-font request, inherits currentColor and is
 * hidden from assistive tech (labels live on the parent control).
 * Stroke icons are based on the open-source Lucide set (ISC licence).
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Icon paths. "fill" icons are solid, everything else is a 2px stroke icon.
 *
 * @return array
 */
function estatein_icon_paths() {
	return array(
		'arrow-up-right' => '<path d="M7 17 17 7"/><path d="M7 7h10v10"/>',
		'arrow-left'     => '<path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>',
		'arrow-right'    => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
		'x'              => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
		'menu'           => '<path d="M4 7h16"/><path d="M4 12h16"/><path d="M4 17h16"/>',
		'chevron-down'   => '<path d="m6 9 6 6 6-6"/>',
		'search'         => '<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>',
		'check'          => '<path d="M20 6 9 17l-5-5"/>',
		'map-pin'        => '<path d="M20 10c0 5-5.5 10.2-7.4 11.8a1 1 0 0 1-1.2 0C9.5 20.2 4 15 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/>',
		'phone'          => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/>',
		'mail'           => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-9 5.7a2 2 0 0 1-2 0L2 7"/>',
		'mail-plus'      => '<path d="M22 13V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8"/><path d="m22 7-9 5.7a2 2 0 0 1-2 0L2 7"/><path d="M19 16v6"/><path d="M16 19h6"/>',
		'send'           => '<path d="M14.5 21.7a.5.5 0 0 0 .9 0l6.5-19a.5.5 0 0 0-.6-.6l-19 6.5a.5.5 0 0 0 0 .9l7.9 3.2a2 2 0 0 1 1.1 1.1z"/><path d="m21.9 2.1-10.9 10.9"/>',
		'zap'            => '<path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/>',
		'bed'            => '<path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/>',
		'bath'           => '<path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.7 3 4 3.7 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/><path d="M10 5 8 7"/><path d="M2 12h20"/><path d="M7 19v2"/><path d="M17 19v2"/>',
		'building'       => '<rect width="16" height="20" x="4" y="2" rx="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01M16 6h.01M12 6h.01M12 10h.01M12 14h.01M16 10h.01M16 14h.01M8 10h.01M8 14h.01"/>',
		'building-2'     => '<path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4M10 10h4M10 14h4M10 18h4"/>',
		'store'          => '<path d="m2 7 4.4-4.4A2 2 0 0 1 7.8 2h8.4a2 2 0 0 1 1.4.6L22 7"/><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/><path d="M2 7h20"/><path d="M22 7v3a2 2 0 0 1-2 2 2.7 2.7 0 0 1-2-.8 2.7 2.7 0 0 1-4 0 2.7 2.7 0 0 1-4 0 2.7 2.7 0 0 1-4 0A2.7 2.7 0 0 1 4 12a2 2 0 0 1-2-2V7"/>',
		'banknote'       => '<rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>',
		'sun'            => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2m-7.07-17.07 1.41 1.41m11.32 11.32 1.41 1.41M2 12h2m16 0h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>',
		'star'           => '<path d="M11.5 2.3a.5.5 0 0 1 .9 0l2.3 4.7a2 2 0 0 0 1.6 1.1l5.2.8a.5.5 0 0 1 .3.9l-3.8 3.6a2 2 0 0 0-.6 1.9l.9 5.1a.5.5 0 0 1-.8.6l-4.6-2.4a2 2 0 0 0-2 0l-4.6 2.4a.5.5 0 0 1-.8-.6l.9-5.1a2 2 0 0 0-.6-1.9L1.7 9.8a.5.5 0 0 1 .3-.9l5.2-.8A2 2 0 0 0 8.8 7z"/>',
		'graduation-cap' => '<path d="M21.4 10.9a1 1 0 0 0 0-1.8l-8.6-3.9a2 2 0 0 0-1.7 0L2.6 9.1a1 1 0 0 0 0 1.8l8.6 3.9a2 2 0 0 0 1.7 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/>',
		'users'          => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.9"/><path d="M16 3.1a4 4 0 0 1 0 7.8"/>',
		'bar-chart'      => '<path d="M12 20V10"/><path d="M18 20V4"/><path d="M6 20v-4"/>',
		'pie-chart'      => '<path d="M21.2 15.9A10 10 0 1 1 8 2.8"/><path d="M22 12A10 10 0 0 0 12 2v10z"/>',
		'database'       => '<ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14a9 3 0 0 0 18 0V5"/><path d="M3 12a9 3 0 0 0 18 0"/>',
		'megaphone'      => '<path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>',
		'layout-grid'    => '<rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>',
		'wrench'         => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.8-3.8a6 6 0 0 1-7.9 7.9l-6.9 6.9a2.1 2.1 0 0 1-3-3l6.9-6.9a6 6 0 0 1 7.9-7.9z"/>',
		'sparkles'       => '<path d="M9.9 15.5A2 2 0 0 0 8.5 14l-6.1-1.6a.5.5 0 0 1 0-1L8.5 10A2 2 0 0 0 9.9 8.5l1.6-6.1a.5.5 0 0 1 1 0L14 8.5a2 2 0 0 0 1.5 1.4l6.1 1.6a.5.5 0 0 1 0 1L15.5 14a2 2 0 0 0-1.5 1.5l-1.6 6.1a.5.5 0 0 1-1 0z"/><path d="M20 3v4M22 5h-4"/>',
		'lightbulb'      => '<path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/>',
		'flame'          => '<path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.4-.5-2-1-3-1.1-2.1-.2-4.1 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.2.4-2.3 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>',
		'calendar'       => '<rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
		'box'            => '<path d="M21 8a2 2 0 0 0-1-1.7l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.7l7 4a2 2 0 0 0 2 0l7-4a2 2 0 0 0 1-1.7Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>',
		'area'           => '<path d="M8 3H5a2 2 0 0 0-2 2v3"/><path d="M21 8V5a2 2 0 0 0-2-2h-3"/><path d="M3 16v3a2 2 0 0 0 2 2h3"/><path d="M16 21h3a2 2 0 0 0 2-2v-3"/><path d="m7 17 10-10"/>',
		'home'           => '<path d="M3 10.2 12 3l9 7.2V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/>',
		'external'       => '<path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',
		'instagram'      => '<rect width="20" height="20" x="2" y="2" rx="5"/><path d="M16 11.4A4 4 0 1 1 12.6 8 4 4 0 0 1 16 11.4z"/><path d="M17.5 6.5h.01"/>',
		/* Solid icons. */
		'star-solid'     => 'fill:<path d="M12 1.8l3.1 6.3 6.9 1-5 4.9 1.2 6.9L12 17.6l-6.2 3.3L7 14l-5-4.9 6.9-1z"/>',
		'pin-solid'      => 'fill:<path d="M12 2a8 8 0 0 0-8 8c0 5.4 6.6 11.2 7.3 11.8a1 1 0 0 0 1.4 0C13.4 21.2 20 15.4 20 10a8 8 0 0 0-8-8Zm0 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>',
		'zap-solid'      => 'fill:<path d="M13.5 2 3 14.5h8L10 22l11-12.5h-8z"/>',
		'send-solid'     => 'fill:<path d="M21.7 2.3a1 1 0 0 0-1-.2l-18 6.3a1 1 0 0 0-.1 1.9l7.5 3.4 3.4 7.5a1 1 0 0 0 1.9-.1l6.3-18a1 1 0 0 0 0-.8ZM10.7 12l-4.6-2 10.8-3.8z"/>',
		'facebook'       => 'fill:<path d="M14 13.5h2.5l1-4H14v-2c0-1 0-2 2-2h1.5V2.1C17.2 2.1 16 2 14.6 2 11.9 2 10 3.7 10 6.7v2.8H7v4h3V22h4z"/>',
		'linkedin'       => 'fill:<path d="M20.4 20.5h-3.6v-5.6c0-1.3 0-3-1.8-3s-2.1 1.4-2.1 2.9v5.7H9.3V9h3.4v1.6c.5-.9 1.6-1.9 3.4-1.9 3.6 0 4.3 2.4 4.3 5.5zM5.3 7.4a2.1 2.1 0 1 1 0-4.1 2.1 2.1 0 0 1 0 4.1ZM7.1 20.5H3.6V9h3.5z"/>',
		'twitter'        => 'fill:<path d="M23.6 5.1c-.9.4-1.8.6-2.8.8 1-.6 1.8-1.6 2.1-2.7-.9.6-2 1-3.1 1.2a4.9 4.9 0 0 0-8.4 4.5A14 14 0 0 1 1.7 3.7a4.9 4.9 0 0 0 1.5 6.6c-.8 0-1.6-.3-2.2-.6v.1c0 2.4 1.7 4.4 4 4.8-.7.2-1.5.2-2.2.1a4.9 4.9 0 0 0 4.6 3.4A9.9 9.9 0 0 1 0 20.1 14 14 0 0 0 7.5 22.3c9.1 0 14-7.5 14-14v-.6c1-.7 1.8-1.6 2.5-2.5Z"/>',
		'youtube'        => 'fill:<path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 0 0 .5 6.2 31.6 31.6 0 0 0 0 12a31.6 31.6 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 0 0 2.1-2.1A31.6 31.6 0 0 0 24 12a31.6 31.6 0 0 0-.5-5.8ZM9.6 15.6V8.4l6.2 3.6z"/>',
	);
}

/**
 * Return an inline SVG icon.
 *
 * @param string $name  Icon key from estatein_icon_paths().
 * @param array  $args  { class, size, label } – pass a label for meaningful icons.
 * @return string
 */
function estatein_icon( $name, $args = array() ) {
	$paths = estatein_icon_paths();
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	$args  = wp_parse_args(
		$args,
		array(
			'class' => '',
			'size'  => 24,
			'label' => '',
		)
	);
	$path  = $paths[ $name ];
	$solid = 0 === strpos( $path, 'fill:' );
	$path  = $solid ? substr( $path, 5 ) : $path;
	$paint = $solid
		? 'fill="currentColor"'
		: 'fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"';
	$a11y  = $args['label']
		? 'role="img" aria-label="' . esc_attr( $args['label'] ) . '"'
		: 'aria-hidden="true" focusable="false"';

	return sprintf(
		'<svg class="icon icon--%1$s %2$s" width="%3$d" height="%3$d" viewBox="0 0 24 24" %4$s %5$s>%6$s</svg>',
		esc_attr( $name ),
		esc_attr( $args['class'] ),
		(int) $args['size'],
		$paint,
		$a11y,
		$path
	);
}

/**
 * Icon choices for SCF/ACF select fields.
 *
 * @return array
 */
function estatein_icon_choices() {
	$labels = array();
	foreach ( array_keys( estatein_icon_paths() ) as $key ) {
		if ( in_array( $key, array( 'x', 'menu', 'arrow-left', 'arrow-right', 'chevron-down' ), true ) ) {
			continue;
		}
		$labels[ $key ] = ucwords( str_replace( '-', ' ', $key ) );
	}
	return $labels;
}
