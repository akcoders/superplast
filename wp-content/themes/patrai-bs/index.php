<?php
/**
 * Index fallback.
 *
 * @package Patrai_BS
 */
get_header();
?>
<main id="main-content" class="section-space"><div class="container"><div class="row g-4">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?><div class="col-md-6"><article class="content-panel"><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><p><?php echo esc_html( patrai_bs_excerpt() ); ?></p></article></div><?php endwhile; else : ?><div class="col-12"><h1>Nothing found</h1></div><?php endif; ?>
</div></div></main>
<?php get_footer(); ?>
