<?php
/**
 * Not found page.
 *
 * @package Patrai_BS
 */
get_header();
?>
<main id="main-content" class="not-found"><div class="container"><span class="error-code">404</span><h1>That page isn’t here.</h1><p>The address may have changed. Continue with our products or return to the homepage.</p><div class="d-flex justify-content-center flex-wrap gap-3"><a class="btn btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">Back to Home</a><a class="btn btn-outline-primary" href="<?php echo esc_url( home_url( '/our-products/' ) ); ?>">Explore Products</a></div></div></main>
<?php get_footer(); ?>
