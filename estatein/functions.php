<?php
/**
 * Estatein theme bootstrap.
 *
 * Every feature lives in its own file inside /inc so this file stays a
 * readable table of contents.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

define( 'ESTATEIN_VERSION', '1.0.0' );
define( 'ESTATEIN_DIR', get_template_directory() );
define( 'ESTATEIN_URI', get_template_directory_uri() );

$estatein_includes = array(
	'inc/defaults.php',       // Design copy used as fallback content and by the demo importer.
	'inc/helpers.php',        // Field getters, image + markup helpers.
	'inc/icons.php',          // Inline SVG icon set.
	'inc/setup.php',          // Theme supports, menus, image sizes.
	'inc/enqueue.php',        // Styles, scripts, fonts, performance tweaks.
	'inc/post-types.php',     // Custom post types + taxonomies.
	'inc/acf-fields.php',     // Secure Custom Fields / ACF field groups + options page.
	'inc/queries.php',        // Data mappers + property search query.
	'inc/forms.php',          // Inquiry + newsletter form handling.
	'inc/seo.php',            // Meta description, Open Graph, JSON-LD.
	'inc/admin.php',          // Admin columns, notices, editor tweaks.
	'inc/demo-import.php',    // Appearance → Estatein Setup (one-click demo content).
);

foreach ( $estatein_includes as $estatein_file ) {
	require_once ESTATEIN_DIR . '/' . $estatein_file;
}
unset( $estatein_includes, $estatein_file );
