<?php
/**
 * Case studies listing.
 *
 * @package Patrai_BS
 */
get_header();
get_template_part( 'template-parts/page', 'hero', array( 'title' => 'Case Studies', 'text' => 'Representative application approaches—not anonymous claims, but a clear look at how we think.', 'image' => 'img/background/poly-sheetsBG1.jpg' ) );
?>
<main id="main-content">
	<section class="section-space"><div class="container">
		<?php $page = get_page_by_path( 'case-studies' ); if ( $page ) : ?><div class="listing-intro entry-content"><?php echo apply_filters( 'the_content', $page->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div><?php endif; ?>
		<div class="row g-4">
			<?php $cases = new WP_Query( array( 'post_type' => 'patrai_case', 'posts_per_page' => -1, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'ASC' ), 'no_found_rows' => true ) ); ?>
			<?php while ( $cases->have_posts() ) : $cases->the_post(); ?><div class="col-md-6"><?php get_template_part( 'template-parts/case', 'card' ); ?></div><?php endwhile; wp_reset_postdata(); ?>
		</div>
	</div></section>
	<?php get_template_part( 'template-parts/cta' ); ?>
</main>
<?php get_footer(); ?>
