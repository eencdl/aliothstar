<?php
/**
 * Customizer sections ordem main file
 *
 * @package ShopIsle
 */

/**
 * Function to enqueue sections order main script.
 */
function shop_isle_sections_customizer_script() {
	wp_enqueue_script( 'customizer-sections-order-script', get_template_directory_uri() . '/inc/customizer/customizer-sections-order/js/customizer-sections-order.js', array( 'jquery', 'jquery-ui-sortable' ), '1.0.0', true );
	$control_settings = array(
		'sections_container' => '#accordion-panel-shop_isle_front_page_sections > ul, #sub-accordion-panel-shop_isle_front_page_sections',
		'blocked_items'      => '#accordion-section-shop_isle_slider_section',
		'saved_data_input'   => '#customize-control-sections_order input',
	);
	wp_localize_script( 'customizer-sections-order-script', 'control_settings', $control_settings );
	wp_enqueue_style( 'customizer-sections-order-style', get_template_directory_uri() . '/inc/customizer/customizer-sections-order/css/customizer-sections-order-style.css', array( 'dashicons' ) );
}
add_action( 'customize_controls_enqueue_scripts', 'shop_isle_sections_customizer_script' );


/**
 * Register input for sections order.
 *
 * @param object $wp_customize Customizer object.
 */
function shop_isle_section_control_register( $wp_customize ) {

	$wp_customize->add_setting(
		'sections_order', array(
			'sanitize_callback' => 'shop_isle_sanitize_sections_order',
			'default'           => json_encode(
				apply_filters(
					'shop_isle_sections', array(
						'shop_isle_banners_section'  => 15,
						'shop_isle_products_section' => 20,
						'shop_isle_video_section'    => 25,
						'shop_isle_products_slider_section' => 55,
					)
				)
			),
		)
	);

	$wp_customize->add_control(
		'sections_order', array(
			'section'  => 'shop_isle_general_section',
			'type'     => 'hidden',
			'priority' => 800,
		)
	);

}
add_action( 'customize_register', 'shop_isle_section_control_register' );


/**
 * Function for returning section priority
 *
 * @param int    $value Default priority.
 * @param string $key Section id.
 *
 * @return int
 */
function shop_isle_get_section_priority( $value, $key = '' ) {
	$orders = get_theme_mod( 'sections_order' );
	if ( ! empty( $orders ) ) {
		$json = json_decode( $orders );
		if ( isset( $json->$key ) ) {
			return $json->$key;
		}
	}
	return $value;
}
add_filter( 'shop_isle_section_priority', 'shop_isle_get_section_priority', 10, 2 );


/**
 * Function to sanitize sections order control
 *
 * @param string $input Sections order in json format.
 */
function shop_isle_sanitize_sections_order( $input ) {

	$json = json_decode( $input, true );
	foreach ( $json as $section => $priority ) {
		if ( ! is_string( $section ) || ! is_int( $priority ) ) {
			return false;
		}
	}
	$filter_empty = array_filter( $json, 'shop_isle_not_empty' );
	return json_encode( $filter_empty );
}

/**
 * Function to filter json empty elements.
 *
 * @param int $val Element of json decoded.
 *
 * @return bool
 */
function shop_isle_not_empty( $val ) {
	return ! empty( $val );
}
