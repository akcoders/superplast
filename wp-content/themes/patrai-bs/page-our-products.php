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
		$terms = get_terms( array( 'taxonomy' => 'patrai_product_category', 'hide_empty' => true ) );
		$terms = is_wp_error( $terms ) ? array() : $terms;
		$order = array( 'Cooling Tower Components', 'Water & Wastewater Technology', 'Building Products & PVC Profiles' );
		usort( $terms, function ( $a, $b ) use ( $order ) { $a_name = html_entity_decode( $a->name, ENT_QUOTES | ENT_HTML5, 'UTF-8' ); $b_name = html_entity_decode( $b->name, ENT_QUOTES | ENT_HTML5, 'UTF-8' ); $a_key = array_search( $a_name, $order, true ); $b_key = array_search( $b_name, $order, true ); return ( false === $a_key ? 99 : $a_key ) <=> ( false === $b_key ? 99 : $b_key ); } );
		?>
		<?php if ( $terms ) : ?><nav class="product-family-nav" aria-label="Product families"><?php foreach ( $terms as $term ) : ?><a href="#<?php echo esc_attr( $term->slug ); ?>"><span><?php echo esc_html( str_pad( (string) $term->count, 2, '0', STR_PAD_LEFT ) ); ?></span><?php echo esc_html( $term->name ); ?></a><?php endforeach; ?></nav><?php endif; ?>
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
