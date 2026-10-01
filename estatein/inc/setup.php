<?php
/**
 * Theme setup: supports, menus, image sizes, widget area.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme features.
 */
function estatein_setup() {
	load_theme_textdomain( 'estatein', ESTATEIN_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 40,
			'width'       => 160,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_editor_style( 'assets/css/editor.css' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu (header)', 'estatein' ),
			'footer'  => __( 'Footer Menu (top level items become column headings)', 'estatein' ),
		)
	);

	// Image sizes match the rendered size in the design at 2x on retina where it matters.
	add_image_size( 'estatein-card', 720, 520, true );      // Property / blog cards.
	add_image_size( 'estatein-gallery', 1200, 1040, true ); // Property gallery main slides.
	add_image_size( 'estatein-thumb', 250, 170, true );     // Property gallery thumbnails.
	add_image_size( 'estatein-team', 520, 440, true );      // Team members.
	add_image_size( 'estatein-avatar', 100, 100, true );    // Testimonial avatars.
	add_image_size( 'estatein-wide', 1200, 400, true );     // Contact gallery wide tiles.
}
add_action( 'after_setup_theme', 'estatein_setup' );

/**
 * Content width for embeds.
 */
function estatein_content_width() {
	$GLOBALS['content_width'] = 840; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
}
add_action( 'after_setup_theme', 'estatein_content_width', 0 );

/**
 * Blog sidebar (reusable component shown on posts, archives and search).
 */
function estatein_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Blog Sidebar', 'estatein' ),
			'id'            => 'sidebar-blog',
			'description'   => __( 'Shown next to blog posts and archives.', 'estatein' ),
			'before_widget' => '<section id="%1$s" class="widget card %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget__title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'estatein_widgets_init' );

/**
 * Human readable names for the custom image sizes in the media modal.
 *
 * @param array $sizes Sizes.
 * @return array
 */
function estatein_image_size_names( $sizes ) {
	return array_merge(
		$sizes,
		array(
			'estatein-card' => __( 'Card (720×520)', 'estatein' ),
			'estatein-wide' => __( 'Wide (1200×400)', 'estatein' ),
		)
	);
}
add_filter( 'image_size_names_choose', 'estatein_image_size_names' );

/**
 * Highlight "Properties" in the menu while viewing a single property.
 *
 * @param string[] $classes Menu item classes.
 * @param WP_Post  $item    Menu item.
 * @return string[]
 */
function estatein_menu_current_property( $classes, $item ) {
	if ( is_singular( 'property' ) && 'page' === $item->object ) {
		if ( 'page-templates/template-properties.php' === get_page_template_slug( (int) $item->object_id ) ) {
			$classes[] = 'current-menu-item';
		}
	}
	return $classes;
}
add_filter( 'nav_menu_css_class', 'estatein_menu_current_property', 10, 2 );

/**
 * Add aria-current to the active menu link (core only does this for exact matches).
 *
 * @param array   $atts Link attributes.
 * @param WP_Post $item Menu item.
 * @return array
 */
function estatein_menu_aria_current( $atts, $item ) {
	if ( in_array( 'current-menu-item', (array) $item->classes, true ) ) {
		$atts['aria-current'] = 'page';
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'estatein_menu_aria_current', 10, 2 );

/**
 * Body classes used by CSS for page specific tweaks.
 *
 * @param string[] $classes Classes.
 * @return string[]
 */
function estatein_body_classes( $classes ) {
	if ( is_singular() && has_post_thumbnail() ) {
		$classes[] = 'has-thumbnail';
	}
	return $classes;
}
add_filter( 'body_class', 'estatein_body_classes' );

/**
 * Shorter, cleaner excerpts.
 *
 * @return int
 */
function estatein_excerpt_length() {
	return 24;
}
add_filter( 'excerpt_length', 'estatein_excerpt_length' );

/**
 * Replace the [...] excerpt suffix with an ellipsis.
 *
 * @return string
 */
function estatein_excerpt_more() {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'estatein_excerpt_more' );
