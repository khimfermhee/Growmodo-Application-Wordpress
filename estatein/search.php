<?php
/**
 * Search results.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();

estatein_part(
	'components/page-hero',
	array(
		/* translators: %s: search query */
		'title' => sprintf( __( 'Results for “%s”', 'estatein' ), get_search_query() ),
		'text'  => sprintf(
			/* translators: %s: number of results */
			_n( '%s result found.', '%s results found.', (int) $GLOBALS['wp_query']->found_posts, 'estatein' ),
			number_format_i18n( (int) $GLOBALS['wp_query']->found_posts )
		),
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
