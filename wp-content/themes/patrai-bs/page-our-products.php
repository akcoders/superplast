<?php
/**
 * Products listing page.
 *
 * @package Patrai_BS
 */
get_header();
get_template_part( 'template-parts/page', 'hero', array( 'title' => 'Our Products', 'text' => 'Explore engineered polymer products by sector and application.', 'image' => 'img/background/Water_Wastewater_Technology.jpg' ) );
?>
<main id="main-content">
	<section class="section-space"><div class="container">
		<?php $page = get_page_by_path( 'our-products' ); if ( $page ) : ?><div class="listing-intro entry-content"><?php echo apply_filters( 'the_content', $page->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div><?php endif; ?>
		<?php
		$terms = array();
		foreach ( patrai_bs_product_verticals() as $vertical ) {
			$term = get_term_by( 'slug', $vertical['slug'], 'patrai_product_category' );
			if ( $term && ! is_wp_error( $term ) && $term->count ) {
				$terms[] = $term;
			}
		}
		?>
		<?php if ( $terms ) : ?><nav class="product-family-nav" aria-label="Product families"><?php foreach ( $terms as $index => $term ) : ?><a href="#<?php echo esc_attr( $term->slug ); ?>"><span><?php echo esc_html( 'V' . ( $index + 1 ) ); ?></span><?php echo esc_html( $term->name ); ?></a><?php endforeach; ?></nav><?php endif; ?>
		<?php foreach ( $terms as $term ) : ?>
			<section class="product-group" id="<?php echo esc_attr( $term->slug ); ?>"><div class="group-heading"><div><span class="eyebrow text-primary">Product family</span><h2><?php echo esc_html( $term->name ); ?></h2></div><span class="group-count"><?php echo esc_html( $term->count ); ?> solutions</span></div>
			<div class="row g-4">
			<?php $query = new WP_Query( array( 'post_type' => 'patrai_product', 'posts_per_page' => -1, 'orderby' => array( 'menu_order' => 'ASC', 'title' => 'ASC' ), 'tax_query' => array( array( 'taxonomy' => 'patrai_product_category', 'field' => 'term_id', 'terms' => $term->term_id ) ), 'no_found_rows' => true ) ); ?>
			<?php while ( $query->have_posts() ) : $query->the_post(); ?><div class="col-md-6 col-xl-4"><?php get_template_part( 'template-parts/product', 'card' ); ?></div><?php endwhile; wp_reset_postdata(); ?>
			</div></section>
		<?php endforeach; ?>
	</div></section>
	<?php get_template_part( 'template-parts/cta' ); ?>
</main>
<?php get_footer(); ?>
