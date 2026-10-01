<?php
/**
 * Fallback template – also the blog posts page.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();

$estatein_title = is_home() && get_option( 'page_for_posts' ) ? get_the_title( (int) get_option( 'page_for_posts' ) ) : __( 'Latest Insights', 'estatein' );

estatein_part(
	'components/page-hero',
	array(
		'title' => $estatein_title,
		'text'  => __( 'Market updates, buying guides and expert advice from the Estatein team.', 'estatein' ),
	)
);
?>
<div class="section">
	<div class="container layout-sidebar">
		<div class="layout-sidebar__main">
			<?php get_template_part( 'template-parts/blog/loop' ); ?>
		</div>
		<?php get_sidebar(); ?>
	</div>
</div>
<?php
get_footer();
