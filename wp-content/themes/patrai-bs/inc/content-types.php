<?php
/**
 * Manageable content types and their lightweight meta fields.
 *
 * @package Patrai_BS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function patrai_bs_register_content_types() {
	$common = array(
		'public'       => true,
		'show_in_rest' => true,
		'menu_position'=> 20,
		'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
	);

	register_post_type(
		'patrai_product',
		array_merge(
			$common,
			array(
				'labels' => array(
					'name'          => __( 'Products', 'patrai-bs' ),
					'singular_name' => __( 'Product', 'patrai-bs' ),
					'add_new_item'  => __( 'Add New Product', 'patrai-bs' ),
					'edit_item'     => __( 'Edit Product', 'patrai-bs' ),
				),
				'menu_icon'   => 'dashicons-products',
				'has_archive' => 'our-products',
				'rewrite'     => array( 'slug' => 'product', 'with_front' => false ),
			)
		)
	);

	register_post_type(
		'patrai_case',
		array_merge(
			$common,
			array(
				'labels' => array(
					'name'          => __( 'Case Studies', 'patrai-bs' ),
					'singular_name' => __( 'Case Study', 'patrai-bs' ),
					'add_new_item'  => __( 'Add New Case Study', 'patrai-bs' ),
					'edit_item'     => __( 'Edit Case Study', 'patrai-bs' ),
				),
				'menu_icon'   => 'dashicons-analytics',
				'has_archive' => 'case-studies',
				'rewrite'     => array( 'slug' => 'case-study', 'with_front' => false ),
			)
		)
	);

	register_post_type(
		'patrai_milestone',
		array(
			'labels' => array(
				'name'          => __( 'Journey', 'patrai-bs' ),
				'singular_name' => __( 'Milestone', 'patrai-bs' ),
				'add_new_item'  => __( 'Add Milestone', 'patrai-bs' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-backup',
			'supports'     => array( 'title', 'editor', 'page-attributes' ),
		)
	);

	register_post_type(
		'patrai_slide',
		array(
			'labels' => array(
				'name'          => __( 'Home Slides', 'patrai-bs' ),
				'singular_name' => __( 'Home Slide', 'patrai-bs' ),
				'add_new_item'  => __( 'Add Home Slide', 'patrai-bs' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-images-alt2',
			'supports'     => array( 'title', 'excerpt', 'thumbnail', 'page-attributes' ),
		)
	);

	register_post_type(
		'patrai_feature',
		array(
			'labels' => array(
				'name'          => __( 'Why Choose Us', 'patrai-bs' ),
				'singular_name' => __( 'Feature Card', 'patrai-bs' ),
				'add_new_item'  => __( 'Add Feature Card', 'patrai-bs' ),
				'edit_item'     => __( 'Edit Feature Card', 'patrai-bs' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-grid-view',
			'menu_position'=> 24,
			'supports'     => array( 'title', 'excerpt', 'thumbnail', 'page-attributes' ),
		)
	);

	register_taxonomy(
		'patrai_product_category',
		'patrai_product',
		array(
			'labels'            => array( 'name' => __( 'Product Verticals', 'patrai-bs' ), 'singular_name' => __( 'Product Vertical', 'patrai-bs' ) ),
			'public'            => true,
			'show_in_rest'      => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'product-category', 'with_front' => false ),
		)
	);

	register_taxonomy(
		'patrai_case_industry',
		'patrai_case',
		array(
			'labels'            => array( 'name' => __( 'Industries', 'patrai-bs' ), 'singular_name' => __( 'Industry', 'patrai-bs' ) ),
			'public'            => true,
			'show_in_rest'      => true,
			'hierarchical'      => false,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'industry', 'with_front' => false ),
		)
	);
}
add_action( 'init', 'patrai_bs_register_content_types', 5 );

function patrai_bs_meta_boxes() {
	add_meta_box( 'patrai-slide-details', __( 'Slide Action', 'patrai-bs' ), 'patrai_bs_slide_meta_box', 'patrai_slide', 'normal', 'high' );
	add_meta_box( 'patrai-milestone-year', __( 'Timeline Label', 'patrai-bs' ), 'patrai_bs_milestone_meta_box', 'patrai_milestone', 'side', 'high' );
	add_meta_box( 'patrai-product-details', __( 'Product Highlights', 'patrai-bs' ), 'patrai_bs_product_details_meta_box', 'patrai_product', 'normal', 'high' );
	add_meta_box( 'patrai-product-technical', __( 'Technical Details & Specifications', 'patrai-bs' ), 'patrai_bs_product_technical_meta_box', 'patrai_product', 'normal', 'default' );
	add_meta_box( 'patrai-product-gallery', __( 'Product Gallery', 'patrai-bs' ), 'patrai_bs_product_gallery_meta_box', 'patrai_product', 'normal', 'default' );
	add_meta_box( 'patrai-feature-settings', __( 'Card Settings', 'patrai-bs' ), 'patrai_bs_feature_meta_box', 'patrai_feature', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'patrai_bs_meta_boxes' );

function patrai_bs_slide_meta_box( $post ) {
	wp_nonce_field( 'patrai_bs_meta', 'patrai_bs_meta_nonce' );
	$kicker = get_post_meta( $post->ID, '_patrai_slide_kicker', true );
	$label  = get_post_meta( $post->ID, '_patrai_slide_button_label', true );
	$url    = get_post_meta( $post->ID, '_patrai_slide_button_url', true );
	?>
	<p><label for="patrai_slide_kicker"><strong><?php esc_html_e( 'Small heading', 'patrai-bs' ); ?></strong></label><br><input class="widefat" id="patrai_slide_kicker" name="patrai_slide_kicker" value="<?php echo esc_attr( $kicker ); ?>"></p>
	<p><label for="patrai_slide_button_label"><strong><?php esc_html_e( 'Button label', 'patrai-bs' ); ?></strong></label><br><input class="widefat" id="patrai_slide_button_label" name="patrai_slide_button_label" value="<?php echo esc_attr( $label ); ?>"></p>
	<p><label for="patrai_slide_button_url"><strong><?php esc_html_e( 'Button URL', 'patrai-bs' ); ?></strong></label><br><input class="widefat" type="url" id="patrai_slide_button_url" name="patrai_slide_button_url" value="<?php echo esc_attr( $url ); ?>"></p>
	<p><?php esc_html_e( 'Use the Featured Image panel for the full-width slide image. Recommended: 1920 × 900 px or larger.', 'patrai-bs' ); ?></p>
	<?php
}

function patrai_bs_milestone_meta_box( $post ) {
	wp_nonce_field( 'patrai_bs_meta', 'patrai_bs_meta_nonce' );
	$year = get_post_meta( $post->ID, '_patrai_milestone_year', true );
	?>
	<label class="screen-reader-text" for="patrai_milestone_year"><?php esc_html_e( 'Year or period', 'patrai-bs' ); ?></label>
	<input class="widefat" id="patrai_milestone_year" name="patrai_milestone_year" value="<?php echo esc_attr( $year ); ?>" placeholder="1988 / Today">
	<?php
}

function patrai_bs_product_details_meta_box( $post ) {
	wp_nonce_field( 'patrai_bs_product_meta', 'patrai_bs_product_meta_nonce' );
	$tagline      = get_post_meta( $post->ID, '_patrai_product_tagline', true );
	$features     = get_post_meta( $post->ID, '_patrai_product_features', true );
	$applications = get_post_meta( $post->ID, '_patrai_product_applications', true );
	?>
	<p><label for="patrai_product_tagline"><strong><?php esc_html_e( 'Short product tagline', 'patrai-bs' ); ?></strong></label><br><input class="widefat" id="patrai_product_tagline" name="patrai_product_tagline" value="<?php echo esc_attr( $tagline ); ?>" placeholder="One clear line used in the product hero"></p>
	<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:20px">
		<p><label for="patrai_product_features"><strong><?php esc_html_e( 'Key features / advantages', 'patrai-bs' ); ?></strong></label><br><textarea class="widefat" rows="9" id="patrai_product_features" name="patrai_product_features" placeholder="Enter one item per line"><?php echo esc_textarea( $features ); ?></textarea><small><?php esc_html_e( 'One item per line. These appear as clean highlight cards.', 'patrai-bs' ); ?></small></p>
		<p><label for="patrai_product_applications"><strong><?php esc_html_e( 'Applications', 'patrai-bs' ); ?></strong></label><br><textarea class="widefat" rows="9" id="patrai_product_applications" name="patrai_product_applications" placeholder="Enter one application per line"><?php echo esc_textarea( $applications ); ?></textarea><small><?php esc_html_e( 'One application per line. Leave empty when not applicable.', 'patrai-bs' ); ?></small></p>
	</div>
	<p><em><?php esc_html_e( 'Use the main WordPress editor above for the full product overview and detailed narrative.', 'patrai-bs' ); ?></em></p>
	<?php
}

function patrai_bs_feature_meta_box( $post ) {
	wp_nonce_field( 'patrai_bs_meta', 'patrai_bs_meta_nonce' );
	$icon = get_post_meta( $post->ID, '_patrai_feature_icon', true ) ?: 'technology';
	$url  = get_post_meta( $post->ID, '_patrai_feature_url', true );
	$icons = array(
		'technology'  => __( 'Technology / Factory', 'patrai-bs' ),
		'application' => __( 'Application / Process', 'patrai-bs' ),
		'engineers'   => __( 'Engineers / Team', 'patrai-bs' ),
		'support'     => __( 'Customer Support', 'patrai-bs' ),
	);
	?>
	<p><label for="patrai_feature_icon"><strong><?php esc_html_e( 'Line icon', 'patrai-bs' ); ?></strong></label><br><select id="patrai_feature_icon" name="patrai_feature_icon"><?php foreach ( $icons as $value => $label ) : ?><option value="<?php echo esc_attr( $value ); ?>" <?php selected( $icon, $value ); ?>><?php echo esc_html( $label ); ?></option><?php endforeach; ?></select></p>
	<p><label for="patrai_feature_url"><strong><?php esc_html_e( 'Card link', 'patrai-bs' ); ?></strong></label><br><input class="widefat" type="url" id="patrai_feature_url" name="patrai_feature_url" value="<?php echo esc_attr( $url ); ?>" placeholder="<?php echo esc_attr( home_url( '/about-us/' ) ); ?>"></p>
	<p><?php esc_html_e( 'Use Excerpt for the hover description and Featured Image for the hover background. Use the Order field to change card position.', 'patrai-bs' ); ?></p>
	<?php
}

function patrai_bs_product_technical_meta_box( $post ) {
	$technical = get_post_meta( $post->ID, '_patrai_product_specs_html', true );
	wp_editor(
		$technical,
		'patrai_product_specs_html_editor',
		array(
			'textarea_name' => 'patrai_product_specs_html',
			'textarea_rows' => 16,
			'media_buttons' => false,
			'tinymce'       => true,
			'quicktags'     => true,
		)
	);
	echo '<p><em>' . esc_html__( 'Add headings, tables and notes here. This section only appears when content exists.', 'patrai-bs' ) . '</em></p>';
}

function patrai_bs_product_gallery_meta_box( $post ) {
	$ids = get_post_meta( $post->ID, '_patrai_product_gallery_ids', true );
	$ids = is_array( $ids ) ? array_map( 'absint', $ids ) : array_filter( array_map( 'absint', explode( ',', (string) $ids ) ) );
	?>
	<div class="patrai-gallery-field">
		<input type="hidden" id="patrai_product_gallery_ids" name="patrai_product_gallery_ids" value="<?php echo esc_attr( implode( ',', $ids ) ); ?>">
		<div class="patrai-gallery-preview" data-empty-text="<?php esc_attr_e( 'No gallery images selected.', 'patrai-bs' ); ?>">
			<?php foreach ( $ids as $id ) : ?>
				<span data-id="<?php echo esc_attr( $id ); ?>"><?php echo wp_get_attachment_image( $id, 'thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<?php endforeach; ?>
		</div>
		<p><button type="button" class="button button-primary" id="patrai-select-gallery"><?php esc_html_e( 'Select / Replace Gallery', 'patrai-bs' ); ?></button> <button type="button" class="button" id="patrai-clear-gallery"><?php esc_html_e( 'Clear Gallery', 'patrai-bs' ); ?></button></p>
		<p><em><?php esc_html_e( 'Select multiple images from Media Library. Drag them in the selection window to control order.', 'patrai-bs' ); ?></em></p>
	</div>
	<?php
}

function patrai_bs_product_admin_assets( $hook ) {
	$screen = get_current_screen();
	if ( ! $screen || 'patrai_product' !== $screen->post_type || ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'patrai-product-admin', PATRAI_BS_URI . '/assets/admin/product-admin.js', array( 'jquery' ), PATRAI_BS_VERSION, true );
	wp_enqueue_style( 'patrai-product-admin', PATRAI_BS_URI . '/assets/admin/product-admin.css', array(), PATRAI_BS_VERSION );
}
add_action( 'admin_enqueue_scripts', 'patrai_bs_product_admin_assets' );

function patrai_bs_save_meta( $post_id ) {
	if ( ! isset( $_POST['patrai_bs_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['patrai_bs_meta_nonce'] ) ), 'patrai_bs_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$fields = array(
		'patrai_slide_kicker'       => array( '_patrai_slide_kicker', 'sanitize_text_field' ),
		'patrai_slide_button_label' => array( '_patrai_slide_button_label', 'sanitize_text_field' ),
		'patrai_slide_button_url'   => array( '_patrai_slide_button_url', 'esc_url_raw' ),
		'patrai_milestone_year'     => array( '_patrai_milestone_year', 'sanitize_text_field' ),
		'patrai_feature_icon'        => array( '_patrai_feature_icon', 'sanitize_key' ),
		'patrai_feature_url'         => array( '_patrai_feature_url', 'esc_url_raw' ),
	);
	foreach ( $fields as $input => $config ) {
		if ( isset( $_POST[ $input ] ) ) {
			update_post_meta( $post_id, $config[0], call_user_func( $config[1], wp_unslash( $_POST[ $input ] ) ) );
		}
	}
}
add_action( 'save_post_patrai_slide', 'patrai_bs_save_meta' );
add_action( 'save_post_patrai_milestone', 'patrai_bs_save_meta' );
add_action( 'save_post_patrai_feature', 'patrai_bs_save_meta' );

function patrai_bs_save_product_meta( $post_id ) {
	if ( ! isset( $_POST['patrai_bs_product_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['patrai_bs_product_meta_nonce'] ) ), 'patrai_bs_product_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$text_fields = array(
		'patrai_product_tagline'      => '_patrai_product_tagline',
		'patrai_product_features'     => '_patrai_product_features',
		'patrai_product_applications' => '_patrai_product_applications',
	);
	foreach ( $text_fields as $input => $meta_key ) {
		if ( isset( $_POST[ $input ] ) ) {
			update_post_meta( $post_id, $meta_key, sanitize_textarea_field( wp_unslash( $_POST[ $input ] ) ) );
		}
	}
	if ( isset( $_POST['patrai_product_specs_html'] ) ) {
		update_post_meta( $post_id, '_patrai_product_specs_html', wp_kses_post( wp_unslash( $_POST['patrai_product_specs_html'] ) ) );
	}
	if ( isset( $_POST['patrai_product_gallery_ids'] ) ) {
		$ids = array_values( array_filter( array_map( 'absint', explode( ',', sanitize_text_field( wp_unslash( $_POST['patrai_product_gallery_ids'] ) ) ) ) ) );
		update_post_meta( $post_id, '_patrai_product_gallery_ids', $ids );
	}
}
add_action( 'save_post_patrai_product', 'patrai_bs_save_product_meta' );

function patrai_bs_ensure_product_category( $post_id ) {
	if ( wp_is_post_revision( $post_id ) || 'patrai_product' !== get_post_type( $post_id ) ) {
		return;
	}
	$terms = wp_get_object_terms( $post_id, 'patrai_product_category', array( 'fields' => 'ids' ) );
	if ( ! is_wp_error( $terms ) && ! $terms ) {
		wp_set_object_terms( $post_id, 'General Products', 'patrai_product_category' );
	}
}
add_action( 'save_post_patrai_product', 'patrai_bs_ensure_product_category', 20 );
