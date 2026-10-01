<?php
/**
 * Menu shown before a Primary menu is assigned (keeps the design intact on a
 * fresh install). Links point to the standard page slugs.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

$estatein_items = array(
	'/'           => __( 'Home', 'estatein' ),
	'/about-us/'  => __( 'About Us', 'estatein' ),
	'/properties/' => __( 'Properties', 'estatein' ),
	'/services/'  => __( 'Services', 'estatein' ),
);
$estatein_current = trailingslashit( wp_parse_url( home_url( add_query_arg( array() ) ), PHP_URL_PATH ) ?? '/' );
?>
<ul class="site-nav__list">
	<?php foreach ( $estatein_items as $estatein_path => $estatein_label ) : ?>
		<?php $estatein_is_current = trailingslashit( wp_parse_url( home_url( $estatein_path ), PHP_URL_PATH ) ) === $estatein_current; ?>
		<li class="menu-item<?php echo $estatein_is_current ? ' current-menu-item' : ''; ?>">
			<a href="<?php echo esc_url( home_url( $estatein_path ) ); ?>"<?php echo $estatein_is_current ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $estatein_label ); ?></a>
		</li>
	<?php endforeach; ?>
</ul>
