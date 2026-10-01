<?php
/**
 * Default page template (legal pages and any page built in the editor).
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	estatein_part( 'components/page-hero', array( 'title' => get_the_title() ) );
	?>
	<div class="section">
		<div class="container container--narrow">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
				<div class="entry__content">
					<?php
					the_content();
					wp_link_pages();
					?>
				</div>
			</article>
			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</div>
	</div>
	<?php
endwhile;

get_footer();
