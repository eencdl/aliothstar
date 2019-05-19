<?php
/**
 * Customizer functionality for the Categories Section.
 *
 * @package WordPress
 * @subpackage Shop Isle
 */

/**
 * Hook controls for Categories Section to Customizer.
 */
function shop_isle_categories_customize_register( $wp_customize ) {

	$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

	require_once( trailingslashit( get_template_directory() ) . 'inc/customizer/class/class-shopisle-customize-control-multiple-select.php' );

	/* Categories section */

	$wp_customize->add_section(
		'shop_isle_fp_categories_section', array(
			'title'    => __( 'Categories section', 'shop-isle' ),
			'panel'    => 'shop_isle_front_page_sections',
			'priority' => apply_filters( 'shop_isle_section_priority', 45, 'shop_isle_fp_categories_section' ),
		)
	);

	$wp_customize->add_setting(
		'shop_isle_fp_categories_hide', array(
			'sanitize_callback' => 'shop_isle_sanitize_checkbox',
			'default'           => true,
			'transport'         => $selective_refresh,
		)
	);

	$wp_customize->add_control(
		'shop_isle_fp_categories_hide', array(
			'type'     => 'checkbox',
			'label'    => __( 'Hide categories section?', 'shop-isle' ),
			'section'  => 'shop_isle_fp_categories_section',
			'priority' => 1,
		)
	);

	/* Title */
	$wp_customize->add_setting(
		'shop_isle_fp_categories_title', array(
			'default'           => __( 'Popular categories', 'shop-isle' ),
			'transport'         => $selective_refresh,
			'sanitize_callback' => 'shop_isle_sanitize_text',
		)
	);
	$wp_customize->add_control(
		'shop_isle_fp_categories_title', array(
			'label'    => __( 'Section title', 'shop-isle' ),
			'section'  => 'shop_isle_fp_categories_section',
			'priority' => 2,
		)
	);
	/* Subtitle */
	$wp_customize->add_setting(
		'shop_isle_fp_categories_subtitle', array(
			'transport'         => $selective_refresh,
			'sanitize_callback' => 'shop_isle_sanitize_text',
		)
	);
	$wp_customize->add_control(
		'shop_isle_fp_categories_subtitle', array(
			'label'    => __( 'Section subtitle', 'shop-isle' ),
			'section'  => 'shop_isle_fp_categories_section',
			'priority' => 3,
		)
	);

	$shop_isle_product_categories       = get_terms( 'product_cat' );
	$shop_isle_product_categories_array = array(
		'none' => 'None',
	);

	if ( ! empty( $shop_isle_product_categories ) ) {

		foreach ( $shop_isle_product_categories as $term ) {

			if ( ! empty( $term ) && ! empty( $term->term_id ) && ! empty( $term->name ) ) {
				$shop_isle_product_categories_array[ $term->term_id ] = $term->name;
			}
		}
	}

	if ( ! empty( $shop_isle_product_categories_array ) ) {

		/* Category */
		$wp_customize->add_setting(
			'shop_isle_fp_categories_list', array(
				'default'           => array( 'none' ),
				'sanitize_callback' => 'shop_isle_sanitize_array',
			)
		);

		$wp_customize->add_control(
			new ShopIsle_Customize_Control_Multiple_Select(
				$wp_customize, 'shop_isle_fp_categories_list', array(
					'label'       => 'Choose categories',
					'description' => __( 'Hold down the Ctrl (windows) / Command (Mac) button to select multiple options.', 'shop-isle' ),
					'section'     => 'shop_isle_fp_categories_section',
					'type'        => 'multiple-select',
					'choices'     => $shop_isle_product_categories_array,
				)
			)
		);

	}
}

add_action( 'customize_register', 'shop_isle_categories_customize_register' );


/**
 * Add selective refresh for categories section on frontpage
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function shop_isle_fp_categories_register_partials( $wp_customize ) {
	// Abort if selective refresh is not available.
	if ( ! isset( $wp_customize->selective_refresh ) ) {
		return;
	}

	$wp_customize->selective_refresh->add_partial(
		'shop_isle_fp_categories_hide', array(
			'selector'            => '#categories',
			'render_callback'     => 'shop_isle_fp_categories_hide_callback',
			'container_inclusive' => true,
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'shop_isle_fp_categories_title', array(
			'selector'            => '#categories .home-prod-title',
			'render_callback'     => 'shop_isle_fp_categories_title_callback',
			'container_inclusive' => false,
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'shop_isle_fp_categories_subtitle', array(
			'selector'            => '#categories .home-prod-subtitle',
			'render_callback'     => 'shop_isle_fp_categories_subtitle_callback',
			'container_inclusive' => false,
		)
	);

}

add_action( 'customize_register', 'shop_isle_fp_categories_register_partials' );

/**
 * Callback function for categories section on front page
 */
function shop_isle_fp_categories_hide_callback() {
	get_template_part( 'inc/sections/shop_isle_fp_categories_section' );
}

/**
 * Callback function for categories title on front page
 *
 * @return string - categories title value
 */
function shop_isle_fp_categories_title_callback() {
	return get_theme_mod( 'shop_isle_fp_categories_title' );
}

/**
 * Callback function for categories subtitle on frontpage
 *
 * @return string - categories subtitle value
 */
function shop_isle_fp_categories_subtitle_callback() {
	return get_theme_mod( 'shop_isle_fp_categories_subtitle' );
}
