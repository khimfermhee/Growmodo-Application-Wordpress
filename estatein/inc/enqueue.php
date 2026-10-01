<?php
/**
 * Assets and front-end performance.
 *
 * - One minified stylesheet and one deferred, dependency-free script.
 * - Self-hosted variable font (one 28 KB woff2) preloaded.
 * - Core block CSS and emoji scripts removed where they are not used.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Version string based on file modification time for cache busting.
 *
 * @param string $relative Path relative to the theme root.
 * @return string
 */
function estatein_asset_version( $relative ) {
	$file = ESTATEIN_DIR . '/' . $relative;
	return file_exists( $file ) ? (string) filemtime( $file ) : ESTATEIN_VERSION;
}

/**
 * Use the readable source files when SCRIPT_DEBUG is on, minified otherwise.
 *
 * @param string $base Path without extension, e.g. assets/css/main.
 * @param string $ext  css|js.
 * @return string
 */
function estatein_asset_path( $base, $ext ) {
	$debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
	$min   = $base . '.min.' . $ext;
	if ( ! $debug && file_exists( ESTATEIN_DIR . '/' . $min ) ) {
		return $min;
	}
	return $base . '.' . $ext;
}

/**
 * Enqueue front-end assets.
 */
function estatein_enqueue_assets() {
	$css = estatein_asset_path( 'assets/css/main', 'css' );
	$js  = estatein_asset_path( 'assets/js/main', 'js' );

	wp_enqueue_style( 'estatein', ESTATEIN_URI . '/' . $css, array(), estatein_asset_version( $css ) );

	wp_enqueue_script(
		'estatein',
		ESTATEIN_URI . '/' . $js,
		array(),
		estatein_asset_version( $js ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_localize_script(
		'estatein',
		'estateinData',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'i18n'    => array(
				'sending' => __( 'Sending…', 'estatein' ),
				'error'   => __( 'Something went wrong. Please try again.', 'estatein' ),
				'of'      => __( 'of', 'estatein' ),
				'open'    => __( 'Open menu', 'estatein' ),
				'close'   => __( 'Close menu', 'estatein' ),
			),
		)
	);

	if ( is_singular( 'post' ) && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Pages built entirely from theme templates do not render block content.
	if ( estatein_is_custom_layout() ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'classic-theme-styles' );
		wp_dequeue_style( 'global-styles' );
	}
}
add_action( 'wp_enqueue_scripts', 'estatein_enqueue_assets', 20 );

/**
 * Is the current view rendered only by theme templates (no editor content)?
 *
 * @return bool
 */
function estatein_is_custom_layout() {
	if ( is_front_page() || is_singular( 'property' ) || is_post_type_archive( array( 'testimonial', 'faq' ) ) ) {
		return true;
	}
	if ( is_page() ) {
		$template = get_page_template_slug();
		return $template && 0 === strpos( $template, 'page-templates/' );
	}
	return false;
}

/**
 * Preload the font and (on the home page) the LCP hero image.
 */
function estatein_preload_assets() {
	printf(
		'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
		esc_url( ESTATEIN_URI . '/assets/fonts/urbanist-latin-wght-normal.woff2' )
	);

	if ( is_front_page() ) {
		$hero = estatein_field( 'hero_image', null, (int) get_option( 'page_on_front' ) );
		if ( $hero ) {
			$id = is_array( $hero ) ? (int) $hero['ID'] : (int) $hero;
			$src = wp_get_attachment_image_url( $id, 'large' );
			$set = wp_get_attachment_image_srcset( $id, 'large' );
			if ( $src ) {
				printf(
					'<link rel="preload" as="image" href="%s" imagesrcset="%s" imagesizes="(min-width: 1200px) 50vw, 100vw" fetchpriority="high">' . "\n",
					esc_url( $src ),
					esc_attr( (string) $set )
				);
			}
		} else {
			printf(
				'<link rel="preload" as="image" href="%1$s" imagesrcset="%2$s 640w, %1$s 1200w" imagesizes="(min-width: 1200px) 50vw, 100vw" fetchpriority="high">' . "\n",
				esc_url( ESTATEIN_URI . '/assets/images/hero-building.webp' ),
				esc_url( ESTATEIN_URI . '/assets/images/hero-building-640.webp' )
			);
		}
	}
}
add_action( 'wp_head', 'estatein_preload_assets', 2 );

/**
 * Tiny inline script: restore the dismissed state of the announcement bar
 * before first paint (avoids a flash of the bar on every page load).
 */
function estatein_head_inline_script() {
	echo "<script>try{if(localStorage.getItem('estatein-announcement')==='dismissed'){document.documentElement.classList.add('announcement-dismissed')}}catch(e){}document.documentElement.classList.add('js')</script>\n";
}
add_action( 'wp_head', 'estatein_head_inline_script', 1 );

/**
 * Remove emoji detection script and styles (saves a request + inline JS).
 */
function estatein_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'estatein_disable_emojis' );

/**
 * Remove head clutter that has no value for this site.
 */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
