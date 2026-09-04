<?php
/**
 * Product verticals and the primary-navigation mega menu.
 *
 * @package Patrai_BS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Product hierarchy taken from the supplied Website Master File.
 *
 * Product records remain editable. Assigning a product to one of these terms
 * automatically places it in the corresponding header-menu column.
 *
 * @return array
 */
function patrai_bs_product_verticals() {
	return array(
		array(
			'label'        => 'Vertical 01',
			'name'         => 'Cooling Tower Components',
			'slug'         => 'cooling-tower-components',
			'landing_slug' => 'cooling-tower-components',
			'products'     => array( 'cooling-tower-components', 'film-fills', 'splash-fills', 'drift-eliminators', 'nozzles', 'other-accessories' ),
		),
		array(
			'label'        => 'Vertical 02',
			'name'         => 'Water Treatment Technology',
			'slug'         => 'water-treatment-technology',
			'landing_slug' => 'water-technology-industry',
			'products'     => array( 'water-technology-industry', 'biological-media', 'floating-media', 'settling-media', 'trickling-filter-media', 'saff-media', 'anaerobic-digester-media', 'fab-reactor-media' ),
		),
		array(
			'label'        => 'Vertical 03',
			'name'         => 'Building Products',
			'slug'         => 'building-products',
			'landing_slug' => 'building-products',
			'products'     => array( 'building-products', 'louvers-fins', 'frp-gratings' ),
		),
		array(
			'label'        => 'Vertical 04',
			'name'         => 'PVC Sleeving & Profiles',
			'slug'         => 'pvc-sleeving-profiles',
			'landing_slug' => 'pvc-profiles',
			'products'     => array( 'pvc-profiles' ),
		),
	);
}

/**
 * Convert the earlier three broad categories into the four supplied verticals.
 */
function patrai_bs_ensure_product_verticals() {
	$version = '1.0.1';
	if ( $version === get_option( 'patrai_bs_product_vertical_version' ) ) {
		return;
	}

	foreach ( patrai_bs_product_verticals() as $vertical ) {
		$term = term_exists( $vertical['slug'], 'patrai_product_category' );
		if ( ! $term ) {
			$term = wp_insert_term(
				$vertical['name'],
				'patrai_product_category',
				array( 'slug' => $vertical['slug'] )
			);
		}
		if ( is_wp_error( $term ) ) {
			continue;
		}
		$term_id = is_array( $term ) ? (int) $term['term_id'] : (int) $term;
		foreach ( $vertical['products'] as $product_slug ) {
			$product = get_page_by_path( $product_slug, OBJECT, 'patrai_product' );
			if ( $product ) {
				wp_set_object_terms( $product->ID, array( $term_id ), 'patrai_product_category', false );
			}
		}
	}

	// Remove only the now-empty theme-generated categories superseded above.
	foreach ( array( 'water-wastewater-technology', 'building-products-pvc-profiles' ) as $legacy_slug ) {
		$legacy_term = get_term_by( 'slug', $legacy_slug, 'patrai_product_category' );
		if ( $legacy_term && ! is_wp_error( $legacy_term ) && 0 === (int) $legacy_term->count ) {
			wp_delete_term( $legacy_term->term_id, 'patrai_product_category' );
		}
	}

	update_option( 'patrai_bs_product_vertical_version', $version, false );
}
add_action( 'init', 'patrai_bs_ensure_product_verticals', 30 );

/**
 * Fetch products for a menu vertical in their editable admin order.
 *
 * @param array $vertical Vertical definition.
 * @return WP_Post[]
 */
function patrai_bs_vertical_products( $vertical ) {
	return get_posts(
		array(
			'post_type'      => 'patrai_product',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
			'tax_query'      => array(
				array(
					'taxonomy' => 'patrai_product_category',
					'field'    => 'slug',
					'terms'    => $vertical['slug'],
				),
			),
		)
	);
}

/**
 * Test whether a WordPress menu item is the Our Products page.
 *
 * @param WP_Post $item Menu item.
 */
function patrai_bs_is_products_menu_item( $item ) {
	if ( 'page' === $item->object && 'our-products' === get_post_field( 'post_name', $item->object_id ) ) {
		return true;
	}
	$path = trim( (string) wp_parse_url( $item->url, PHP_URL_PATH ), '/' );
	return 'our-products' === basename( $path );
}

/**
 * Build the dynamic four-column product mega menu.
 */
function patrai_bs_product_mega_menu() {
	static $markup = null;
	if ( null !== $markup ) {
		return $markup;
	}

	ob_start();
	?>
	<button class="mega-menu-toggle" type="button" aria-expanded="false" aria-controls="patrai-product-mega-menu">
		<span class="screen-reader-text"><?php esc_html_e( 'Toggle product menu', 'patrai-bs' ); ?></span>
		<?php echo patrai_bs_icon( 'chevron' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</button>
	<div class="product-mega-menu" id="patrai-product-mega-menu">
		<div class="mega-menu-intro">
			<div><span><?php esc_html_e( 'Product ecosystem', 'patrai-bs' ); ?></span><strong><?php esc_html_e( 'Four focused verticals. One polymer-engineering foundation.', 'patrai-bs' ); ?></strong></div>
			<a href="<?php echo esc_url( home_url( '/our-products/' ) ); ?>"><?php esc_html_e( 'View all products', 'patrai-bs' ); ?> <?php echo patrai_bs_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
		</div>
		<div class="mega-vertical-grid">
			<?php foreach ( patrai_bs_product_verticals() as $vertical ) : ?>
				<?php
				$landing  = get_page_by_path( $vertical['landing_slug'], OBJECT, 'patrai_product' );
				$products = patrai_bs_vertical_products( $vertical );
				$url      = $landing ? get_permalink( $landing ) : home_url( '/our-products/#' . $vertical['slug'] );
				?>
				<section class="mega-vertical">
					<a class="mega-vertical-heading" href="<?php echo esc_url( $url ); ?>">
						<span><?php echo esc_html( $vertical['label'] ); ?></span>
						<strong><?php echo esc_html( $vertical['name'] ); ?></strong>
					</a>
					<ul>
						<?php foreach ( $products as $product ) : ?>
							<?php if ( $landing && $product->ID === $landing->ID && count( $products ) > 1 ) { continue; } ?>
							<li><a href="<?php echo esc_url( get_permalink( $product ) ); ?>"><?php echo esc_html( get_the_title( $product ) ); ?><span aria-hidden="true">&#8599;</span></a></li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
	$markup = ob_get_clean();
	return $markup;
}

function patrai_bs_product_menu_classes( $classes, $item, $args, $depth ) {
	if ( 0 === $depth && isset( $args->theme_location ) && 'primary' === $args->theme_location && patrai_bs_is_products_menu_item( $item ) ) {
		$classes[] = 'menu-item-has-children';
		$classes[] = 'patrai-products-menu';
	}
	return $classes;
}
add_filter( 'nav_menu_css_class', 'patrai_bs_product_menu_classes', 10, 4 );

function patrai_bs_product_menu_link_attributes( $attributes, $item, $args, $depth ) {
	if ( 0 === $depth && isset( $args->theme_location ) && 'primary' === $args->theme_location && patrai_bs_is_products_menu_item( $item ) ) {
		$attributes['class']         = trim( ( isset( $attributes['class'] ) ? $attributes['class'] . ' ' : '' ) . 'product-menu-link' );
		$attributes['aria-haspopup'] = 'true';
		$attributes['aria-controls'] = 'patrai-product-mega-menu';
		$attributes['aria-expanded'] = 'false';
	}
	return $attributes;
}
add_filter( 'nav_menu_link_attributes', 'patrai_bs_product_menu_link_attributes', 10, 4 );

class Patrai_BS_Primary_Nav_Walker extends Walker_Nav_Menu {
	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
		if ( 0 === $depth && patrai_bs_is_products_menu_item( $data_object ) ) {
			$output .= patrai_bs_product_mega_menu();
		}
		$output .= "</li>\n";
	}
}
