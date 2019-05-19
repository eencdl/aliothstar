<?php
/**
 * Customizer functionality for the Ribbon Section.
 *
 * @package WordPress
 * @subpackage Shop Isle
 */

/**
 * Hook controls for Ribbon Section to Customizer.
 */
function shop_isle_ribbon_customize_register( $wp_customize ) {

	$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

	/*  Ribbon section */

	$wp_customize->add_section(
		'shop_isle_ribbon_section', array(
			'title'    => __( 'Ribbon section', 'shop-isle' ),
			'panel'    => 'shop_isle_front_page_sections',
			'priority' => apply_filters( 'shop_isle_section_priority', 35, 'shop_isle_ribbon_section' ),
		)
	);

	$wp_customize->add_setting(
		'shop_isle_ribbon_hide', array(
			'sanitize_callback' => 'shop_isle_sanitize_checkbox',
			'default'           => true,
			'transport'         => $selective_refresh,
		)
	);

	$wp_customize->add_control(
		'shop_isle_ribbon_hide', array(
			'type'     => 'checkbox',
			'label'    => __( 'Hide ribbon section?', 'shop-isle' ),
			'section'  => 'shop_isle_ribbon_section',
			'priority' => 1,
		)
	);

	/* Text */
	$wp_customize->add_setting(
		'shop_isle_ribbon_text', array(
			'default'           => __( 'Find out more', 'shop-isle' ),
			'transport'         => $selective_refresh,
			'sanitize_callback' => 'shop_isle_sanitize_text',
		)
	);
	$wp_customize->add_control(
		'shop_isle_ribbon_text', array(
			'label'    => __( 'Text', 'shop-isle' ),
			'section'  => 'shop_isle_ribbon_section',
			'priority' => 2,
		)
	);
	/* Ribbon button text */
	$wp_customize->add_setting(
		'shop_isle_ribbon_button_text', array(
			'default'           => __( 'Click to subscribe', 'shop-isle' ),
			'transport'         => $selective_refresh,
			'sanitize_callback' => 'shop_isle_sanitize_text',
		)
	);
	$wp_customize->add_control(
		'shop_isle_ribbon_button_text', array(
			'label'    => __( 'Button Text', 'shop-isle' ),
			'section'  => 'shop_isle_ribbon_section',
			'priority' => 3,
		)
	);
	/* Ribbon button link */
	$wp_customize->add_setting(
		'shop_isle_ribbon_button_link', array(
			'default'           => '#',
			'sanitize_callback' => 'esc_url',
			'transport'         => $selective_refresh,
		)
	);
	$wp_customize->add_control(
		'shop_isle_ribbon_button_link', array(
			'label'    => __( 'Button link', 'shop-isle' ),
			'section'  => 'shop_isle_ribbon_section',
			'priority' => 4,
		)
	);
	/* Ribbon background */
	$wp_customize->add_setting(
		'shop_isle_ribbon_background', array(
			'default'           => get_template_directory_uri() . '/assets/images/ribbon-bg.jpg',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_url',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize, 'shop_isle_ribbon_background', array(
				'label'    => __( 'Background image', 'shop-isle' ),
				'section'  => 'shop_isle_ribbon_section',
				'priority' => 1,
			)
		)
	);
}

add_action( 'customize_register', 'shop_isle_ribbon_customize_register' );


/**
 * Add selective refresh for ribbon section controls
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function shop_isle_ribbon_register_partials( $wp_customize ) {
	// Abort if selective refresh is not available.
	if ( ! isset( $wp_customize->selective_refresh ) ) {
		return;
	}

	$wp_customize->selective_refresh->add_partial(
		'shop_isle_ribbon_hide', array(
			'selector'            => '#ribbon',
			'render_callback'     => 'shop_isle_ribbon_section_callback',
			'container_inclusive' => true,
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'shop_isle_ribbon_text', array(
			'selector'            => '#ribbon .module-title',
			'render_callback'     => 'shop_isle_ribbon_text_section_callback',
			'container_inclusive' => false,
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'shop_isle_ribbon_button_text', array(
			'selector'            => '#ribbon .btn-ribbon-wrapper',
			'render_callback'     => 'shop_isle_display_ribbon_button',
			'container_inclusive' => true,
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'shop_isle_ribbon_button_link', array(
			'selector'            => '#ribbon .btn-ribbon-wrapper',
			'render_callback'     => 'shop_isle_display_ribbon_button',
			'container_inclusive' => true,
		)
	);
}

add_action( 'customize_register', 'shop_isle_ribbon_register_partials' );

/**
 * Callback function for ribbon section
 */
function shop_isle_ribbon_section_callback() {
	get_template_part( 'inc/sections/shop_isle_ribbon_section' );
}

/**
 * Callback function for ribbon text
 *
 * @return string - ribbon section text value
 */
function shop_isle_ribbon_text_section_callback() {
	return get_theme_mod( 'shop_isle_ribbon_text' );
}

/**
 * Display button in the Ribbon section on front page
 */
function shop_isle_display_ribbon_button() {

	$shop_isle_ribbon_button_text = get_theme_mod( 'shop_isle_ribbon_button_text', esc_html__( 'Click to subscribe', 'shop-isle' ) );
	$shop_isle_ribbon_button_link = get_theme_mod( 'shop_isle_ribbon_button_link', esc_url( '#', 'shop-isle-pro' ) );

	if ( ! empty( $shop_isle_ribbon_button_text ) && ! empty( $shop_isle_ribbon_button_link ) ) {
		echo '<div class="col-sm-12 col-md-4 btn-ribbon-wrapper">';
		echo '<a href="' . esc_url( $shop_isle_ribbon_button_link ) . '" class="btn btn-ribbon">' . $shop_isle_ribbon_button_text . '</a>';
		echo '</div>';
	} elseif ( is_customize_preview() ) {
		echo '<div class="col-sm-12 col-md-4 btn-ribbon-wrapper"></div>';
	}
}
