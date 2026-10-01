<?php
/**
 * Blog sidebar – widgets, or helpful defaults when no widgets are set.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;
?>
<aside class="sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'estatein' ); ?>">
	<?php if ( is_active_sidebar( 'sidebar-blog' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-blog' ); ?>
	<?php else : ?>
		<section class="widget card">
			<h2 class="widget__title"><?php esc_html_e( 'Search', 'estatein' ); ?></h2>
			<?php get_search_form(); ?>
		</section>
		<section class="widget card">
			<h2 class="widget__title"><?php esc_html_e( 'Categories', 'estatein' ); ?></h2>
			<ul><?php wp_list_categories( array( 'title_li' => '', 'show_count' => true ) ); ?></ul>
		</section>
		<section class="widget card widget--cta">
			<h2 class="widget__title"><?php esc_html_e( 'Looking for a property?', 'estatein' ); ?></h2>
			<p><?php esc_html_e( 'Browse our handpicked listings or talk to an agent today.', 'estatein' ); ?></p>
			<?php estatein_button( __( 'Browse Properties', 'estatein' ), estatein_properties_url(), 'primary', array( 'class' => 'btn--block' ) ); ?>
		</section>
	<?php endif; ?>
</aside>
