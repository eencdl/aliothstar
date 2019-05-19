<?php
/**
 * Color Palette Controls
 *
 * Customizer functionality for the Color Palette.
 *
 * @package WordPress
 * @subpackage Shop Isle
 */

/**
 * Hook controls for Colors section to Customizer.
 */
function shop_isle_colors_customize_register( $wp_customize ) {

	/* Colors */

	require_once( SHOP_ISLE_PHP_INCLUDE . '/customizer/class/class-shop-isle-pro-palette.php' );
	require_once( SHOP_ISLE_PHP_INCLUDE . '/customizer/customizer-alpha-color-picker/class-shop-isle-customize-alpha-color-control.php' );
	require_once( SHOP_ISLE_PHP_INCLUDE . '/customizer/customizer-range-value-control/class-customizer-range-value-control.php' );

	$wp_customize->add_setting(
		'shop_isle_palette_picker', array(
			'sanitize_callback' => 'shop_isle_sanitize_text',
		)
	);
	$wp_customize->add_control(
		new Shop_Isle_Pro_Palette(
			$wp_customize, 'shop_isle_palette_picker', array(
				'label'                         => esc_html__( 'Change the color scheme', 'shop-isle' ),
				'section'                       => 'colors',
				'priority'                      => 1,
				'metro_customizr_image_control' => true,
				'metro_customizr_icon_control'  => true,
				'metro_customizr_text_control'  => false,
				'metro_customizr_link_control'  => true,
			)
		)
	);

	$wp_customize->add_setting(
		'shop_isle_navbar_background', array(
			'sanitize_callback' => 'shop_isle_sanitize_colors',
		)
	);
	$wp_customize->add_control(
		new Shop_Isle_Customize_Alpha_Color_Control(
			$wp_customize, 'shop_isle_navbar_background', array(
				'label'        => esc_html__( 'Navbar Background', 'shop-isle' ),
				'section'      => 'colors',
				'show_opacity' => true,
				'palette'      => false,
				'priority'     => 10,
			)
		)
	);

	$wp_customize->add_setting(
		'shop_isle_menu_items_color', array(
			'sanitize_callback' => 'shop_isle_sanitize_colors',
		)
	);
	$wp_customize->add_control(
		new Shop_Isle_Customize_Alpha_Color_Control(
			$wp_customize, 'shop_isle_menu_items_color', array(
				'label'        => esc_html__( 'Menu Items', 'shop-isle' ),
				'section'      => 'colors',
				'show_opacity' => true,
				'palette'      => false,
				'priority'     => 15,
			)
		)
	);

	$wp_customize->add_setting(
		'shop_isle_menu_items_hover_color', array(
			'sanitize_callback' => 'shop_isle_sanitize_colors',
		)
	);
	$wp_customize->add_control(
		new Shop_Isle_Customize_Alpha_Color_Control(
			$wp_customize, 'shop_isle_menu_items_hover_color', array(
				'label'        => esc_html__( 'Menu Items on Hover', 'shop-isle' ),
				'section'      => 'colors',
				'show_opacity' => true,
				'palette'      => false,
				'priority'     => 20,
			)
		)
	);

	$wp_customize->add_setting(
		'shop_isle_footer_background', array(
			'sanitize_callback' => 'shop_isle_sanitize_colors',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, 'shop_isle_footer_background', array(
				'label'    => esc_html__( 'Footer Background', 'shop-isle' ),
				'section'  => 'colors',
				'priority' => 25,
			)
		)
	);

	$wp_customize->add_setting(
		'shop_isle_video_section_padding', array(
			'sanitize_callback' => 'esc_attr',
			'default'           => '130',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		new Shop_Isle_Customizer_Range_Value_Control(
			$wp_customize, 'shop_isle_video_section_padding', array(
				'type'        => 'range-value',
				'section'     => 'shop_isle_video_section',
				'label'       => __( 'Padding', 'shop-isle' ),
				'priority'    => 5,
				'input_attrs' => array(
					'min'    => 130,
					'max'    => 400,
					'step'   => 5,
					'suffix' => 'px',
				),
			)
		)
	);
}

add_action( 'customize_register', 'shop_isle_colors_customize_register' );

/**
 * Function to sanitize alpha color.
 *
 * @param string $input Hex or RGBA color.
 *
 * @return string|void
 */
function shop_isle_sanitize_colors( $input ) {
	// Is this an rgba color or a hex?
	$mode = ( false === strpos( $input, 'rgba' ) ) ? 'hex' : 'rgba';
	if ( 'rgba' === $mode ) {
		return shop_isle_sanitize_rgba( $input );
	} else {
		return sanitize_hex_color( $input );
	}
}

/**
 * Sanitize rgba color.
 *
 * @param string $value Color in rgba format.
 *
 * @return string
 */
function shop_isle_sanitize_rgba( $value ) {
	$red   = 'rgba(0,0,0,0)';
	$green = 'rgba(0,0,0,0)';
	$blue  = 'rgba(0,0,0,0)';
	$alpha = 'rgba(0,0,0,0)';
	if ( empty( $value ) || is_array( $value ) ) {
		return '';
	}
	// By now we know the string is formatted as an rgba color so we need to further sanitize it.
	$value = str_replace( ' ', '', $value );
	sscanf( $value, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );

	return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
}
