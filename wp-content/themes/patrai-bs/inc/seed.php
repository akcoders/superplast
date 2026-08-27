<?php
/**
 * One-time starter content for the Super Plast installation.
 *
 * @package Patrai_BS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function patrai_bs_schedule_seed() {
	update_option( 'patrai_bs_seed_pending', 1, false );
}
add_action( 'after_switch_theme', 'patrai_bs_schedule_seed' );

function patrai_bs_upsert_page( $title, $slug, $content ) {
	$page = get_page_by_path( $slug, OBJECT, 'page' );
	$data = array(
		'post_title'   => $title,
		'post_name'    => $slug,
		'post_content' => $content,
		'post_status'  => 'publish',
		'post_type'    => 'page',
	);
	if ( $page ) {
		$data['ID'] = $page->ID;
		$id         = wp_update_post( wp_slash( $data ) );
	} else {
		$id = wp_insert_post( wp_slash( $data ) );
	}
	if ( $id && ! is_wp_error( $id ) ) {
		update_post_meta( $id, '_patrai_bs_managed', 1 );
	}
	return (int) $id;
}

function patrai_bs_seed_post( $post_type, $key, $data, $image_path = '' ) {
	$found = get_posts(
		array(
			'post_type'      => $post_type,
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'meta_key'       => '_patrai_bs_seed_key',
			'meta_value'     => $key,
		)
	);
	if ( $found ) {
		return (int) $found[0]->ID;
	}
	$data['post_type']   = $post_type;
	$data['post_status'] = 'publish';
	$id = wp_insert_post( wp_slash( $data ) );
	if ( ! $id || is_wp_error( $id ) ) {
		return 0;
	}
	update_post_meta( $id, '_patrai_bs_seed_key', $key );
	if ( $image_path ) {
		$image_id = patrai_bs_asset_attachment_id( $image_path );
		if ( $image_id ) {
			set_post_thumbnail( $id, $image_id );
		}
	}
	return (int) $id;
}

function patrai_bs_product_copy( $lead, $points = array() ) {
	$html  = '<p class="lead">' . esc_html( $lead ) . '</p>';
	if ( $points ) {
		$html .= '<h2>Product focus</h2><ul class="patrai-check-list">';
		foreach ( $points as $point ) {
			$html .= '<li>' . esc_html( $point ) . '</li>';
		}
		$html .= '</ul>';
	}
	$html .= '<div class="patrai-note"><strong>Need technical guidance?</strong><p>Share your application, operating conditions and dimensional requirements with our team for a suitable product discussion.</p></div>';
	return $html;
}

function patrai_bs_seed_content() {
	if ( ! get_option( 'patrai_bs_seed_pending' ) || get_option( 'patrai_bs_seed_version' ) === PATRAI_BS_VERSION ) {
		return;
	}

	if ( ! get_option( 'patrai_bs_options' ) ) {
		add_option( 'patrai_bs_options', patrai_bs_defaults() );
	}

	$pages = array();
	$pages['home'] = patrai_bs_upsert_page(
		'Home',
		'home',
		'<p>Super Plast Company creates polymer-engineered products for cooling towers, water and wastewater systems, building applications and custom PVC profiles.</p>'
	);
	$pages['about'] = patrai_bs_upsert_page(
		'About Us',
		'about-us',
		'<p class="lead">Super Plast Company is a partnership firm with its registered office in Andheri (East), Mumbai and manufacturing space in Vasai (East), District Thane.</p><p>We have manufactured PVC pipes, casing and capping, PVC profiles and application-focused polymer products since 1988. This experience supports a wide range of industrial, commercial and consumer applications.</p><p>Our work is guided by durability, a strong strength-to-weight ratio, good finish, responsible service and long-term customer relationships. Sections are manufactured from virgin polyvinyl chloride, supporting insulation resistance and dielectric strength for relevant applications.</p>'
	);
	$pages['journey'] = patrai_bs_upsert_page(
		'Our Journey',
		'our-journey',
		'<p class="lead">From PVC manufacturing roots to application-led polymer engineering, our journey has been shaped by continuous learning, manufacturing discipline and customer requirements.</p>'
	);
	$pages['products'] = patrai_bs_upsert_page(
		'Our Products',
		'our-products',
		'<p class="lead">Explore Super Plast solutions for cooling towers, water and wastewater treatment, building applications and PVC profile extrusion.</p>'
	);
	$pages['cases'] = patrai_bs_upsert_page(
		'Case Studies',
		'case-studies',
		'<p class="lead">See how our product knowledge can be applied to common selection, access, separation and profile-development requirements.</p>'
	);
	$pages['contact'] = patrai_bs_upsert_page(
		'Contact Us',
		'contact-us',
		'<p>Tell us about your application, material preference, required dimensions and quantities. Our team will respond with the next practical step.</p>'
	);

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $pages['home'] );
	update_option( 'page_for_posts', 0 );

	$categories = array( 'Cooling Tower Components', 'Water & Wastewater Technology', 'Building Products & PVC Profiles' );
	foreach ( $categories as $category ) {
		if ( ! term_exists( $category, 'patrai_product_category' ) ) {
			wp_insert_term( $category, 'patrai_product_category' );
		}
	}

	$products = array(
		array( 'cooling-tower-components', 'Cooling Tower Components', 'Cooling Tower Components', 'Polymer-engineered fills, drift eliminators, splash bars, nozzles and accessories for crossflow and counterflow cooling towers.', 'img/background/cooling_tower.jpg', array( 'Thermal-transfer media for varied water quality', 'Drift control and water distribution components', 'Application support for industrial, power and HVAC use' ) ),
		array( 'film-fills', 'Film Fills', 'Cooling Tower Components', 'Efficient heat-transfer media designed to improve water distribution as it descends through a cooling tower fill pack.', 'img/products/CoolingTowerComponents/cooling-tower-page/All-Film-Fills.jpg', array( 'Crossflow and counterflow configurations', 'Multiple flute geometries for application needs', 'Virgin PVC construction options' ) ),
		array( 'splash-fills', 'Splash Fills', 'Cooling Tower Components', 'Open splash-media designs that break water into droplets while offering practical fouling resistance for demanding water conditions.', 'img/products/CoolingTowerComponents/cooling-tower-page/Splash-fills.jpg', array( 'Open-flow geometries', 'Modular support arrangements', 'Options for water with suspended solids' ) ),
		array( 'drift-eliminators', 'Drift Eliminators', 'Cooling Tower Components', 'Profiled eliminator modules that change airflow direction and help reduce the escape of entrained water droplets.', 'img/products/CoolingTowerComponents/cooling-tower-page/All-drift-eliminators.jpg', array( 'Cellular and blade-style options', 'Low-pressure-drop focused geometry', 'Modular fabrication for installation' ) ),
		array( 'nozzles', 'Nozzles', 'Cooling Tower Components', 'Water distribution nozzles selected for spray pattern, flow requirement and cooling tower configuration.', 'img/products/CoolingTowerComponents/cooling-tower-page/Nozzles.jpg', array( 'Industrial water distribution', 'Multiple connection and flow options', 'Polymer construction for corrosion resistance' ) ),
		array( 'other-accessories', 'Cooling Tower Accessories', 'Cooling Tower Components', 'Support components including V-bars, clips and nylon cables for fill and eliminator installation arrangements.', 'img/products/CoolingTowerComponents/cooling-tower-page/Cooling-Tower-Accessories.jpg', array( 'Installation-focused components', 'Polymer and nylon options', 'Selection support for existing assemblies' ) ),
		array( 'biological-media', 'Biological Media', 'Water & Wastewater Technology', 'Structured and random media options that provide surface area for attached-growth biological treatment processes.', 'img/products/WaterTechnology/BiologicalMedia/biomedia.jpg', array( 'Fixed and floating media options', 'Application-led surface-area selection', 'Lightweight polymer construction' ) ),
		array( 'floating-media', 'Floating Media', 'Water & Wastewater Technology', 'Free-moving carrier media for biological processes where protected surface area and mixing are important.', 'img/products/WaterTechnology/BiologicalMedia/productRange/floating-media.jpg', array( 'Multiple carrier geometries', 'High protected surface-area options', 'Suitable for moving-bed process discussions' ) ),
		array( 'settling-media', 'Settling Media', 'Water & Wastewater Technology', 'Inclined tube and plate media configurations that support compact gravity-separation and clarification systems.', 'img/products/WaterTechnology/SettlingMedia/Settling-Tank01.jpg', array( 'Tube settler module options', 'Efficient settling-area use', 'Modular installation planning' ) ),
		array( 'trickling-filter-media', 'Trickling Filter Media', 'Water & Wastewater Technology', 'Structured media for attached-growth treatment where air and wastewater need open, repeatable flow paths.', 'img/products/WaterTechnology/trickling-filter.jpg', array( 'Open-flow structure', 'Modular media blocks', 'Application and loading review' ) ),
		array( 'saff-media', 'SAFF Media', 'Water & Wastewater Technology', 'Submerged aerobic fixed-film media for biological treatment systems requiring stable attached-growth surface.', 'img/products/WaterTechnology/saff.jpg', array( 'Fixed-film process use', 'Lightweight modular blocks', 'Geometry selection based on process needs' ) ),
		array( 'anaerobic-digester-media', 'Anaerobic Digester Media', 'Water & Wastewater Technology', 'Polymer media solutions for anaerobic attached-growth processes and related wastewater-treatment applications.', 'img/products/WaterTechnology/Anaerobic-Digester.jpg', array( 'Surface for biomass attachment', 'Corrosion-resistant material', 'Application-specific configuration' ) ),
		array( 'fab-reactor-media', 'FAB Reactor Media', 'Water & Wastewater Technology', 'Fluidized aerobic bioreactor media intended to provide active protected surface area within biological treatment tanks.', 'img/products/WaterTechnology/fab-reactor.jpg', array( 'Moving carrier formats', 'Process-based media quantity review', 'Lightweight polymer construction' ) ),
		array( 'building-products', 'Building Products', 'Building Products & PVC Profiles', 'uPVC, WPC and FRP solutions for building applications where durability, low maintenance and corrosion resistance matter.', 'img/background/buildingTechnology.jpg', array( 'Alternatives to corrosion-prone materials', 'Low-maintenance polymer products', 'Custom profile development capability' ) ),
		array( 'louvers-fins', 'uPVC Louvers & Fins', 'Building Products & PVC Profiles', 'Extruded louver and fin profiles for screening, ventilation and building-detail applications.', 'img/products/building/louvers.jpg', array( 'Multiple profile shapes', 'Clean, consistent extruded finish', 'Application-specific lengths and formats' ) ),
		array( 'frp-gratings', 'FRP Gratings', 'Building Products & PVC Profiles', 'Corrosion-resistant grating panels for industrial access, drainage, platforms and building-service areas.', 'img/products/building/frp-grating-vertical.jpg', array( 'Lightweight panels', 'Corrosion-resistant construction', 'Industrial access and drainage applications' ) ),
		array( 'pvc-profiles', 'PVC Sleeving, Soft & Rigid Profiles', 'Building Products & PVC Profiles', 'Custom-extruded flexible and rigid PVC profiles developed around dimensional, fit and application requirements.', 'img/home/pvc_sleeving_and_soft_rigid.jpg', array( 'Soft and rigid PVC options', 'Application-led profile development', 'Consistent dimensional and finish focus' ) ),
	);

	foreach ( $products as $index => $product ) {
		$id = patrai_bs_seed_post(
			'patrai_product',
			'product-' . $product[0],
			array(
				'post_title'   => $product[1],
				'post_name'    => $product[0],
				'post_excerpt' => $product[3],
				'post_content' => patrai_bs_product_copy( $product[3], $product[5] ),
				'menu_order'   => $index,
			),
			$product[4]
		);
		if ( $id ) {
			wp_set_object_terms( $id, $product[2], 'patrai_product_category' );
		}
	}

	$slides = array(
		array( 'cooling', 'Cooling Tower Components', 'Polymer Engineering Since 1988', 'Fills, eliminators, nozzles and application-focused components.', 'img/slider/slider02.jpg', home_url( '/product/cooling-tower-components/' ) ),
		array( 'water', 'Water & Wastewater Technology', 'Biological & Separation Media', 'Structured solutions for treatment, clarification and attached-growth processes.', 'img/slider/waste-water-banner.jpg', home_url( '/product/biological-media/' ) ),
		array( 'building', 'Building Product Solutions', 'Clean. Durable. Low Maintenance.', 'uPVC and FRP products for practical construction and industrial applications.', 'img/slider/slider(building).jpg', home_url( '/product/building-products/' ) ),
		array( 'profiles', 'Custom PVC Profiles', 'From Requirement to Repeatable Profile', 'Flexible and rigid extrusions developed around fit, finish and function.', 'img/slider/slider4-PVC_banner.jpg', home_url( '/product/pvc-profiles/' ) ),
	);
	foreach ( $slides as $index => $slide ) {
		$id = patrai_bs_seed_post( 'patrai_slide', 'slide-' . $slide[0], array( 'post_title' => $slide[1], 'post_excerpt' => $slide[3], 'menu_order' => $index ), $slide[4] );
		if ( $id ) {
			update_post_meta( $id, '_patrai_slide_kicker', $slide[2] );
			update_post_meta( $id, '_patrai_slide_button_label', 'Explore Solutions' );
			update_post_meta( $id, '_patrai_slide_button_url', $slide[5] );
		}
	}

	$milestones = array(
		array( '1988', 'The Beginning', 'Super Plast began manufacturing PVC pipe, casing and capping, and PVC profiles—building the production knowledge that still guides our work.' ),
		array( '1990s', 'Building Capability', 'Manufacturing practice expanded around consistent extrusion, material understanding and practical customer requirements.' ),
		array( '2000s', 'Application Expansion', 'The product focus grew into application-led polymer components for cooling, water-treatment and industrial use.' ),
		array( '2010s', 'Broader Product Families', 'Cooling tower components, biological media and building products developed into defined solution families.' ),
		array( 'Today', 'Engineering the Next Requirement', 'We continue to combine polymer knowledge, manufacturing discipline and responsive support for new and existing applications.' ),
	);
	foreach ( $milestones as $index => $milestone ) {
		$id = patrai_bs_seed_post( 'patrai_milestone', 'milestone-' . sanitize_title( $milestone[0] ), array( 'post_title' => $milestone[1], 'post_content' => '<p>' . esc_html( $milestone[2] ) . '</p>', 'menu_order' => $index ) );
		if ( $id ) {
			update_post_meta( $id, '_patrai_milestone_year', $milestone[0] );
		}
	}

	$cases = array(
		array( 'cooling-fill-selection', 'Cooling Tower Fill Selection', 'Cooling Systems', 'Matching fill geometry to water quality, tower configuration and maintenance priorities.', 'img/products/CoolingTowerComponents/large-slider-cooling-tower/IMG_6598.jpg', 'A selection review starts with tower type, water quality, thermal duty and fouling risk. The result is a practical shortlist of film or splash media geometries, supported by suitable eliminator and distribution components.' ),
		array( 'biological-media-application', 'Biological Media Application', 'Water Treatment', 'Reviewing carrier type, protected area and process conditions for attached-growth treatment.', 'img/products/WaterTechnology/homeSlider/water03.jpg', 'Process goals, tank configuration, loading and mixing conditions guide the media discussion. This application-led approach helps identify an appropriate structured or floating media format.' ),
		array( 'frp-access-drainage', 'FRP Access & Drainage', 'Building & Industry', 'Using corrosion-resistant grating for service areas, drainage covers and access platforms.', 'img/products/building/frp-grating/Frp-slider-img/FRP.jpg', 'Where metal corrosion and maintenance are concerns, FRP grating can provide a lightweight modular alternative. Panel layout, span, load and surface requirements are considered before selection.' ),
		array( 'custom-pvc-profile', 'Custom PVC Profile Development', 'Profile Extrusion', 'Turning a fit-and-function requirement into a repeatable flexible or rigid profile.', 'img/products/polymer_sheets/PVC1.jpeg', 'The development conversation covers the mating part, dimensions, hardness or rigidity, finish, environment and quantity. Samples and repeatable production criteria can then be discussed.' ),
	);
	foreach ( $cases as $index => $case ) {
		$id = patrai_bs_seed_post( 'patrai_case', 'case-' . $case[0], array( 'post_title' => $case[1], 'post_name' => $case[0], 'post_excerpt' => $case[3], 'post_content' => '<p class="lead">' . esc_html( $case[3] ) . '</p><h2>Application approach</h2><p>' . esc_html( $case[5] ) . '</p><p><em>This application highlight explains a typical approach and does not identify a specific customer project.</em></p>', 'menu_order' => $index ), $case[4] );
		if ( $id ) {
			wp_set_object_terms( $id, $case[2], 'patrai_case_industry' );
		}
	}

	$menu_name = 'PATRAI BS Primary';
	$menu_obj  = wp_get_nav_menu_object( $menu_name );
	$menu_id   = $menu_obj ? (int) $menu_obj->term_id : (int) wp_create_nav_menu( $menu_name );
	$existing  = wp_get_nav_menu_items( $menu_id );
	if ( ! $existing ) {
		$menu_pages = array(
			array( 'Home', $pages['home'] ),
			array( 'About Us', $pages['about'] ),
			array( 'Our Journey', $pages['journey'] ),
			array( 'Our Products', $pages['products'] ),
			array( 'Case Studies', $pages['cases'] ),
			array( 'Contact Us', $pages['contact'] ),
		);
		foreach ( $menu_pages as $item ) {
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => $item[0], 'menu-item-object-id' => $item[1], 'menu-item-object' => 'page', 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
		}
		wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => 'Download Brochure', 'menu-item-url' => patrai_bs_option( 'brochure_url' ), 'menu-item-type' => 'custom', 'menu-item-status' => 'publish', 'menu-item-target' => '_blank' ) );
	}
	$locations            = get_theme_mod( 'nav_menu_locations', array() );
	$locations['primary'] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );

	patrai_bs_import_legacy_product_catalog( false );

	update_option( 'patrai_bs_seed_version', PATRAI_BS_VERSION, false );
	delete_option( 'patrai_bs_seed_pending' );
	flush_rewrite_rules();
}
add_action( 'init', 'patrai_bs_seed_content', 50 );

function patrai_bs_seed_feature_cards() {
	if ( '1.0.0' === get_option( 'patrai_bs_feature_seed_version' ) ) {
		return;
	}
	$cards = array(
		array(
			'key'     => 'advanced-polymer-technology',
			'title'   => 'Advanced Polymer Technology',
			'excerpt' => 'Material understanding and purposeful geometries come together in products developed for real operating conditions.',
			'icon'    => 'technology',
			'url'     => home_url( '/our-products/' ),
			'image'   => 'img/products/CoolingTowerComponents/large-slider-cooling-tower/shot-36.jpg',
		),
		array(
			'key'     => 'application-led-engineering',
			'title'   => 'Application-led Engineering',
			'excerpt' => 'We begin with the process, environment and dimensions before discussing a suitable product direction.',
			'icon'    => 'application',
			'url'     => home_url( '/case-studies/' ),
			'image'   => 'img/products/WaterTechnology/homeSlider/water02.jpg',
		),
		array(
			'key'     => 'manufacturing-experience',
			'title'   => 'Manufacturing Experience',
			'excerpt' => 'Manufacturing roots since 1988 support a practical focus on consistency, durability, finish and function.',
			'icon'    => 'engineers',
			'url'     => home_url( '/our-journey/' ),
			'image'   => 'img/products/building/louvers_fins/b3.jpeg',
		),
		array(
			'key'     => 'responsive-customer-support',
			'title'   => 'Responsive Customer Support',
			'excerpt' => 'Clear communication keeps product selection, technical discussion and commercial enquiries moving forward.',
			'icon'    => 'support',
			'url'     => home_url( '/contact-us/' ),
			'image'   => 'img/home/buildingProducts.jpg',
		),
	);
	foreach ( $cards as $index => $card ) {
		$id = patrai_bs_seed_post(
			'patrai_feature',
			'feature-' . $card['key'],
			array(
				'post_title'   => $card['title'],
				'post_name'    => $card['key'],
				'post_excerpt' => $card['excerpt'],
				'menu_order'   => $index,
			),
			$card['image']
		);
		if ( $id ) {
			update_post_meta( $id, '_patrai_feature_icon', $card['icon'] );
			update_post_meta( $id, '_patrai_feature_url', $card['url'] );
		}
	}
	update_option( 'patrai_bs_feature_seed_version', '1.0.0', false );
}
add_action( 'init', 'patrai_bs_seed_feature_cards', 60 );
