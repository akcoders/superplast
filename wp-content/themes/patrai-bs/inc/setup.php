<?php
/**
 * Theme setup, assets and shared helpers.
 *
 * @package Patrai_BS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function patrai_bs_setup() {
	load_theme_textdomain( 'patrai-bs', PATRAI_BS_DIR . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array( 'height' => 90, 'width' => 220, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	register_nav_menus( array( 'primary' => __( 'Primary Menu', 'patrai-bs' ) ) );
	add_image_size( 'patrai-card', 720, 500, true );
	add_image_size( 'patrai-wide', 1440, 720, true );
}
add_action( 'after_setup_theme', 'patrai_bs_setup' );

/**
 * Repair clean permalinks once after the Git/database deployment.
 *
 * The source site used date permalinks and the target temporarily fell back to
 * index.php URLs. A hard flush preserves any hosting rules while rebuilding
 * WordPress' rewrite block for the public page and product URLs used by the
 * theme.
 */
function patrai_bs_repair_deployment_rewrites() {
	$repair_version = '1.0.1';
	if ( $repair_version === get_option( 'patrai_bs_rewrite_repair_version' ) ) {
		return;
	}

	global $wp_rewrite;
	if ( '/%postname%/' !== $wp_rewrite->permalink_structure ) {
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
	}

	flush_rewrite_rules( true );
	update_option( 'patrai_bs_rewrite_repair_version', $repair_version, false );
}
add_action( 'init', 'patrai_bs_repair_deployment_rewrites', PHP_INT_MAX );

/**
 * Purge the pre-migration LiteSpeed page/object cache once on the live theme.
 */
function patrai_bs_purge_deployment_cache() {
	$purge_version = '1.1.1';
	if ( $purge_version === get_option( 'patrai_bs_cache_purge_version' ) ) {
		return;
	}

	do_action( 'litespeed_purge_all', 'PATRAI BS deployment' );
	do_action( 'litespeed_purge_all_object' );
	wp_cache_flush();

	if ( ! headers_sent() ) {
		header( 'X-LiteSpeed-Purge: public,*', false );
		header( 'Cache-Control: no-cache, no-store, must-revalidate, max-age=0' );
	}

	update_option( 'patrai_bs_cache_purge_version', $purge_version, false );
}
add_action( 'template_redirect', 'patrai_bs_purge_deployment_cache', 0 );

function patrai_bs_assets() {
	wp_enqueue_style( 'patrai-bs-bootstrap', PATRAI_BS_URI . '/assets/css/bootstrap.min.css', array(), '5.3.8' );
	wp_enqueue_style( 'patrai-bs-theme', PATRAI_BS_URI . '/assets/css/theme.css', array( 'patrai-bs-bootstrap' ), PATRAI_BS_VERSION );
	wp_enqueue_script( 'patrai-bs-bootstrap', PATRAI_BS_URI . '/assets/js/bootstrap.bundle.min.js', array(), '5.3.8', true );
	wp_enqueue_script( 'patrai-bs-theme', PATRAI_BS_URI . '/assets/js/theme.js', array( 'patrai-bs-bootstrap' ), PATRAI_BS_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'patrai_bs_assets' );

function patrai_bs_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		return array();
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'patrai_bs_resource_hints', 10, 2 );

// Keep the public head small and remove legacy WordPress discovery payloads.
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'rest_output_link_wp_head' );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

function patrai_bs_defaults() {
	return array(
		'company_name'       => 'Super Plast Company',
		'tagline'            => 'Water & Environment Solutions',
		'since'              => 'Since 1988',
		'phone'              => '+91 9967083379',
		'phone_secondary'    => '+91 9167718318',
		'email'              => 'sales@superplast.in',
		'email_secondary'    => 'superpst@rediffmail.com',
		'whatsapp'           => '+91 9769142609',
		'address'            => 'Gala No. 1 to 7, Haridwar Industrial Estate, Building No. 2-D, K.T. Industries Park, Village Bhilalpada (Gauraipada), Vasai (E), Thane - 401208, Maharashtra, India.',
		'facebook'           => 'https://www.facebook.com/Super-Plast-109876134100788/',
		'twitter'            => 'https://twitter.com/Superplast2',
		'linkedin'           => 'https://www.linkedin.com/in/superplast/',
		'brochure_url'       => PATRAI_BS_URI . '/assets/documents/super-plast-company-brochure.pdf',
		'primary_color'      => '#2f80ed',
		'primary_dark'       => '#174d78',
		'soft_color'         => '#e8f3ff',
		'hero_kicker'        => 'Polymer Engineering Since 1988',
		'hero_title'         => 'Purpose-built products for water, cooling & construction',
		'hero_text'          => 'Durable polymer-engineered components backed by application knowledge and responsive support.',
		'about_title'        => 'Engineering dependable polymer solutions',
		'about_text'         => 'Super Plast Company manufactures PVC profiles and engineered polymer products for industrial, commercial and building applications. Our focus is practical design, consistent quality and long-term customer relationships.',
		'contact_recipient'  => 'sales@superplast.in',
	);
}

function patrai_bs_option( $key, $fallback = '' ) {
	$options  = wp_parse_args( (array) get_option( 'patrai_bs_options', array() ), patrai_bs_defaults() );
	$value    = isset( $options[ $key ] ) ? $options[ $key ] : $fallback;
	return '' !== $value ? $value : $fallback;
}

function patrai_bs_phone_href( $phone ) {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', (string) $phone );
}

function patrai_bs_whatsapp_url() {
	$number = preg_replace( '/\D+/', '', patrai_bs_option( 'whatsapp' ) );
	return 'https://wa.me/' . $number . '?text=' . rawurlencode( 'Hello Super Plast, I would like to discuss a requirement.' );
}

function patrai_bs_maps_url() {
	return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( patrai_bs_option( 'address' ) );
}

/**
 * Register a migrated Super Plast image when the database attachment row did
 * not travel with the Git-managed uploads directory.
 *
 * The original image files are already present below
 * wp-content/uploads/superplast-company. Registering them in place avoids a
 * second copy and restores normal WordPress featured-image/gallery editing.
 *
 * @param string $source_path Path relative to the superplast-company folder.
 * @return int Attachment ID, or zero when the file cannot be registered.
 */
function patrai_bs_register_legacy_attachment( $source_path ) {
	$source_path = ltrim( wp_normalize_path( (string) $source_path ), '/' );
	if ( '' === $source_path || false !== strpos( $source_path, '../' ) ) {
		return 0;
	}

	$uploads      = wp_upload_dir();
	$relative     = 'superplast-company/' . $source_path;
	$uploads_base = trailingslashit( wp_normalize_path( $uploads['basedir'] ) );
	$file         = wp_normalize_path( $uploads_base . $relative );

	if ( 0 !== strpos( $file, $uploads_base ) || ! is_file( $file ) || ! is_readable( $file ) ) {
		return 0;
	}

	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_wp_attached_file',
			'meta_value'     => $relative,
		)
	);
	if ( $existing ) {
		update_post_meta( (int) $existing[0], '_spm_source_path', $source_path );
		return (int) $existing[0];
	}

	$filetype = wp_check_filetype( wp_basename( $file ) );
	if ( empty( $filetype['type'] ) || 0 !== strpos( $filetype['type'], 'image/' ) ) {
		return 0;
	}

	$title         = pathinfo( wp_basename( $file ), PATHINFO_FILENAME );
	$attachment_id = wp_insert_attachment(
		array(
			'guid'           => trailingslashit( $uploads['baseurl'] ) . $relative,
			'post_mime_type' => $filetype['type'],
			'post_title'     => sanitize_text_field( str_replace( array( '-', '_' ), ' ', $title ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		),
		$file
	);
	if ( ! $attachment_id || is_wp_error( $attachment_id ) ) {
		return 0;
	}

	update_post_meta( $attachment_id, '_spm_source_path', $source_path );
	update_post_meta( $attachment_id, '_wp_attached_file', $relative );

	$image_size = wp_getimagesize( $file );
	if ( $image_size ) {
		wp_update_attachment_metadata(
			$attachment_id,
			array(
				'width'      => (int) $image_size[0],
				'height'     => (int) $image_size[1],
				'file'       => $relative,
				'filesize'   => (int) filesize( $file ),
				'sizes'      => array(),
				'image_meta' => array(),
			)
		);
	}

	return (int) $attachment_id;
}

function patrai_bs_asset_attachment_id( $source_path ) {
	static $cache = array();
	if ( isset( $cache[ $source_path ] ) ) {
		return $cache[ $source_path ];
	}
	$posts = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_spm_source_path',
			'meta_value'     => $source_path,
		)
	);
	$cache[ $source_path ] = $posts ? (int) $posts[0] : patrai_bs_register_legacy_attachment( $source_path );
	return $cache[ $source_path ];
}

function patrai_bs_asset_image( $source_path, $size = 'full', $attributes = array(), $fallback = '' ) {
	$id = patrai_bs_asset_attachment_id( $source_path );
	if ( $id ) {
		return wp_get_attachment_image( $id, $size, false, $attributes );
	}
	if ( $fallback ) {
		$attributes['src'] = PATRAI_BS_URI . '/assets/img/' . ltrim( $fallback, '/' );
		$attributes['alt'] = isset( $attributes['alt'] ) ? $attributes['alt'] : '';
		$parts = array();
		foreach ( $attributes as $key => $value ) {
			$parts[] = sprintf( '%s="%s"', esc_attr( $key ), esc_attr( $value ) );
		}
		return '<img ' . implode( ' ', $parts ) . '>';
	}
	return '';
}

function patrai_bs_logo() {
	if ( has_custom_logo() ) {
		return get_custom_logo();
	}
	$image = patrai_bs_asset_image(
		'img/logo/logo.png',
		'full',
		array( 'class' => 'brand-logo', 'alt' => patrai_bs_option( 'company_name' ), 'width' => '150', 'height' => '75', 'decoding' => 'async' ),
		'logo.png'
	);
	return '<a class="custom-logo-link" href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . $image . '</a>';
}

function patrai_bs_menu_fallback() {
	$items = array(
		'Home'              => home_url( '/' ),
		'About Us'          => home_url( '/about-us/' ),
		'Our Journey'       => home_url( '/our-journey/' ),
		'Our Products'      => home_url( '/our-products/' ),
		'Case Studies'      => home_url( '/case-studies/' ),
		'Contact Us'        => home_url( '/contact-us/' ),
		'Download Brochure' => patrai_bs_option( 'brochure_url' ),
	);
	echo '<ul class="navbar-nav ms-auto align-items-xl-center">';
	foreach ( $items as $label => $url ) {
		$target = 'Download Brochure' === $label ? ' target="_blank" rel="noopener"' : '';
		$is_products = 'Our Products' === $label;
		$class       = $is_products ? 'menu-item menu-item-has-children patrai-products-menu' : 'menu-item';
		echo '<li class="' . esc_attr( $class ) . '"><a' . ( $is_products ? ' class="product-menu-link" aria-haspopup="true" aria-controls="patrai-product-mega-menu" aria-expanded="false"' : '' ) . ' href="' . esc_url( $url ) . '"' . $target . '>' . esc_html( $label ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		if ( $is_products && function_exists( 'patrai_bs_product_mega_menu' ) ) {
			echo patrai_bs_product_mega_menu(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo '</li>';
	}
	echo '</ul>';
}

function patrai_bs_icon( $name, $class = '' ) {
	$icons = array(
		'phone'     => '<path d="M6.6 10.8c1.5 3 3.8 5.3 6.8 6.8l2.3-2.3c.3-.3.7-.4 1.1-.3 1.2.4 2.4.6 3.7.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.8 21 3 13.2 3 3.5c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.7.1.4 0 .8-.3 1.1l-2.2 2.5z"/>',
		'mail'      => '<path d="M3 5h18v14H3V5zm9 7 7-5H5l7 5zm0 2.4L5 9.4V17h14V9.4l-7 5z"/>',
		'location'  => '<path d="M12 2a7 7 0 0 0-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/>',
		'arrow'     => '<path d="M5 11h10.6l-4.3-4.3L12.7 5l7 7-7 7-1.4-1.7 4.3-4.3H5v-2z"/>',
		'chevron'   => '<path d="m7.4 8.6 4.6 4.6 4.6-4.6L18 10l-6 6-6-6 1.4-1.4z"/>',
		'up'        => '<path d="m5 14 7-7 7 7-1.7 1.7-4.1-4.2V21h-2.4v-9.5l-4.1 4.2L5 14z"/>',
		'check'     => '<path d="m9.2 16.2-4.4-4.4L3.4 13.2 9.2 19 21 7.2l-1.4-1.4z"/>',
		'whatsapp'  => '<path d="M20.5 3.5A11.8 11.8 0 0 0 1.9 17.8L.3 23.7l6-1.6A11.7 11.7 0 0 0 12 23.5h.1A11.8 11.8 0 0 0 20.5 3.5zM12 21.5a9.7 9.7 0 0 1-5-1.4l-.4-.2-3.5.9.9-3.4-.2-.4A9.8 9.8 0 1 1 12 21.5zm5.4-7.3c-.3-.2-1.7-.9-2-1-.3-.1-.5-.2-.7.2-.2.3-.8 1-1 1.2-.2.2-.4.2-.7.1-1.8-.9-3-1.7-4.2-3.8-.3-.5.3-.5.9-1.6.1-.2 0-.4 0-.6l-.9-2.1c-.2-.5-.5-.5-.7-.5h-.6c-.2 0-.6.1-.9.4-.3.3-1.2 1.2-1.2 3 0 1.7 1.3 3.4 1.5 3.7.2.2 2.5 3.8 6 5.3 2.2 1 3.1 1 4.2.8 1.3-.2 1.7-1.3 1.9-2.2.2-.8.2-1.6.1-1.8-.2-.3-.5-.4-.8-.5z"/>',
	);
	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}
	return '<svg class="patrai-icon ' . esc_attr( $class ) . '" viewBox="0 0 24 24" aria-hidden="true" focusable="false">' . $icons[ $name ] . '</svg>';
}

function patrai_bs_feature_icon( $name ) {
	$icons = array(
		'technology'  => '<path d="M8 54V28l16-8v9l15-9v9l17-8v33H8Z"/><path d="M14 54V35h11v19M31 37h6m6 0h6m-18 8h6m6 0h6M13 22V10h8v8M11 10h12"/>',
		'application' => '<circle cx="32" cy="32" r="9"/><path d="M32 8v8m0 32v8M8 32h8m32 0h8M15 15l6 6m22 22 6 6m0-34-6 6M21 43l-6 6"/><path d="M29 32l3 3 6-7"/>',
		'engineers'   => '<path d="M24 29a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm22-2a7 7 0 1 0 0-14 7 7 0 0 0 0 14ZM8 54v-8c0-8 7-14 16-14s16 6 16 14v8H8Zm32-20c8 0 14 5 14 12v8H43"/><path d="M18 11V8h12v3M41 13v-3h10v3"/>',
		'support'     => '<path d="M13 35v-5a19 19 0 0 1 38 0v5"/><path d="M13 32H8v13h9V32h-4Zm38 0h5v13h-9V32h4ZM47 46c-2 6-7 9-14 9h-5"/><rect x="25" y="51" width="8" height="6" rx="3"/>',
	);
	$path = isset( $icons[ $name ] ) ? $icons[ $name ] : $icons['technology'];
	return '<svg class="feature-line-icon" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $path . '</svg>';
}

function patrai_bs_excerpt( $length = 26 ) {
	$text = has_excerpt() ? get_the_excerpt() : wp_strip_all_tags( get_the_content() );
	return wp_trim_words( $text, $length );
}

function patrai_bs_meta_lines( $meta_key, $post_id = 0 ) {
	$post_id = $post_id ?: get_the_ID();
	$value   = (string) get_post_meta( $post_id, $meta_key, true );
	$lines   = preg_split( '/\r\n|\r|\n/', $value );
	return array_values( array_filter( array_map( 'trim', $lines ) ) );
}

function patrai_bs_contact_submit() {
	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/contact-us/' );
	if ( ! isset( $_POST['patrai_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['patrai_contact_nonce'] ) ), 'patrai_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'security', $redirect ) );
		exit;
	}
	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : 'Website enquiry';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
	if ( ! $name || ! is_email( $email ) || ! $message ) {
		wp_safe_redirect( add_query_arg( 'contact', 'invalid', $redirect ) );
		exit;
	}
	$body = "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\n\n{$message}";
	$sent = wp_mail( patrai_bs_option( 'contact_recipient', get_option( 'admin_email' ) ), '[Super Plast Website] ' . $subject, $body, array( 'Reply-To: ' . $name . ' <' . $email . '>' ) );
	wp_safe_redirect( add_query_arg( 'contact', $sent ? 'success' : 'failed', $redirect ) );
	exit;
}
add_action( 'admin_post_nopriv_patrai_contact', 'patrai_bs_contact_submit' );
add_action( 'admin_post_patrai_contact', 'patrai_bs_contact_submit' );

function patrai_bs_css_variables() {
	$primary = sanitize_hex_color( patrai_bs_option( 'primary_color' ) ) ?: '#2f80ed';
	$dark    = sanitize_hex_color( patrai_bs_option( 'primary_dark' ) ) ?: '#174d78';
	$soft    = sanitize_hex_color( patrai_bs_option( 'soft_color' ) ) ?: '#e8f3ff';
	echo '<style id="patrai-bs-colors">:root{--patrai-primary:' . esc_html( $primary ) . ';--patrai-dark:' . esc_html( $dark ) . ';--patrai-soft:' . esc_html( $soft ) . ';}</style>';
}
add_action( 'wp_head', 'patrai_bs_css_variables', 3 );
