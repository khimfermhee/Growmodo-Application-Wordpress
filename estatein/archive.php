<?php
/**
 * Category, tag, date and author archives.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();

estatein_part(
	'components/page-hero',
	array(
		'title' => wp_strip_all_tags( get_the_archive_title() ),
		'text'  => wp_strip_all_tags( get_the_archive_description() ),
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
