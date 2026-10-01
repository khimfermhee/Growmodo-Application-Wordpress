<?php
/**
 * Filtered property results (WP_Query loop + pagination).
 *
 * @package Estatein
 *
 * @var array $args { request: array }
 */

defined( 'ABSPATH' ) || exit;

$estatein_query = new WP_Query( estatein_property_search_args( $args['request'] ) );
$estatein_total = (int) $estatein_query->found_posts;
?>
<section class="section" id="listing" aria-labelledby="listing-title">
	<div class="container">
		<div class="results-head">
			<div>
				<?php estatein_sparkle(); ?>
				<h2 class="section-heading__title" id="listing-title"><?php esc_html_e( 'Search Results', 'estatein' ); ?></h2>
				<p class="section-heading__text" role="status">
					<?php
					/* translators: %s: number of properties */
					echo esc_html( sprintf( _n( '%s property matches your search.', '%s properties match your search.', $estatein_total, 'estatein' ), number_format_i18n( $estatein_total ) ) );
					?>
				</p>
			</div>
			<a class="btn btn--secondary" href="<?php echo esc_url( get_permalink() ); ?>#listing"><?php esc_html_e( 'Clear filters', 'estatein' ); ?></a>
		</div>

		<?php if ( $estatein_query->have_posts() ) : ?>
			<ul class="grid grid--3">
				<?php
				while ( $estatein_query->have_posts() ) :
					$estatein_query->the_post();
					?>
					<li><?php estatein_part( 'components/property-card', array( 'property' => estatein_property_data( get_post() ), 'variant' => 'tagline' ) ); ?></li>
				<?php endwhile; ?>
			</ul>
			<?php
			$estatein_links = paginate_links(
				array(
					'total'     => $estatein_query->max_num_pages,
					'current'   => max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) ),
					'type'      => 'list',
					'prev_text' => estatein_icon( 'arrow-left' ) . '<span class="screen-reader-text">' . esc_html__( 'Previous page', 'estatein' ) . '</span>',
					'next_text' => estatein_icon( 'arrow-right' ) . '<span class="screen-reader-text">' . esc_html__( 'Next page', 'estatein' ) . '</span>',
				)
			);
			if ( $estatein_links ) {
				echo '<nav class="pagination" aria-label="' . esc_attr__( 'Results pages', 'estatein' ) . '">' . $estatein_links . '</nav>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		<?php else : ?>
			<div class="card empty-state">
				<h3><?php esc_html_e( 'No properties found', 'estatein' ); ?></h3>
				<p><?php esc_html_e( 'Try removing a filter or searching for a different keyword. Or tell us what you are looking for below and we will find it for you.', 'estatein' ); ?></p>
				<a class="btn btn--primary" href="#inquiry"><?php esc_html_e( 'Tell us what you need', 'estatein' ); ?></a>
			</div>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	</div>
</section>
