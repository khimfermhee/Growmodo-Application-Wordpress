<?php
/**
 * Site logo: custom logo from the Customizer, or the Estatein mark + name.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

if ( has_custom_logo() ) {
	the_custom_logo();
	return;
}
?>
<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) . ' – ' . __( 'Home', 'estatein' ) ); ?>">
	<svg class="logo__mark" width="34" height="34" viewBox="0 0 34 34" aria-hidden="true" focusable="false"><path d="M0 0a17 17 0 0 1 17 17V0a17 17 0 0 1 17 17A17 17 0 0 1 0 17Z" fill="#703BF7"/></svg>
	<span class="logo__text"><?php bloginfo( 'name' ); ?></span>
</a>
