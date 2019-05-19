<?php
/**
 * This file is used for providing compatibility with WPML and Polylang plugins.
 *
 * @package ShopIsle
 * @since 2.2.35
 */

/**
 * Filter to translate strings
 *
 * @param string $original_value String that is not translated.
 * @param string $domain The string’s registered domain.
 * @since 2.2.35
 */
function shop_isle_translate_single_string( $original_value, $domain ) {
	if ( is_customize_preview() ) {
		$wpml_translation = $original_value;
	} else {
		$wpml_translation = apply_filters( 'wpml_translate_single_string', $original_value, $domain, $original_value );
		if ( $wpml_translation === $original_value && function_exists( 'pll__' ) ) {
			return pll__( $original_value );
		}
	}
	return $wpml_translation;
}
add_filter( 'shop_isle_translate_single_string', 'shop_isle_translate_single_string', 10, 2 );

/**
 * Helper to register pll string.
 *
 * @param String    $theme_mod Theme mod name.
 * @param bool/json $default Default value.
 * @param String    $name Name for polylang backend.
 */
function shop_isle_pll_string_register_helper( $theme_mod, $default = false, $name ) {
	if ( ! function_exists( 'pll_register_string' ) ) {
		return;
	}

	$repeater_content = get_theme_mod( $theme_mod, $default );
	$repeater_content = json_decode( $repeater_content );
	if ( ! empty( $repeater_content ) ) {
		foreach ( $repeater_content as $repeater_item ) {
			foreach ( $repeater_item as $field_name => $field_value ) {
				if ( $field_value !== 'undefined' ) {
					if ( $field_name === 'social_repeater' ) {
						$social_repeater_value = json_decode( $field_value );
						if ( ! empty( $social_repeater_value ) ) {
							foreach ( $social_repeater_value as $social ) {
								foreach ( $social as $key => $value ) {
									if ( $key === 'link' ) {
										pll_register_string( 'Social link', $value, $name );
									}
									if ( $key === 'icon' ) {
										pll_register_string( 'Social icon', $value, $name );
									}
								}
							}
						}
					} else {
						if ( $field_name !== 'id' ) {
							$f_n = ucfirst( $field_name );
							pll_register_string( $f_n, $field_value, $name );
						}
					}
				}
			}
		}
	}
}

/**
 * The following functions are required in order to make sections that are made with repeater translatable.
 */

/**
 * Register strings from slider section in polylang.
 *
 * @since 2.2.35
 */
function shop_isle_register_slider_strings() {
	$default = json_encode(
		array(
			array(
				'image_url' => get_template_directory_uri() . '/assets/images/slide1.jpg',
				'link'      => '#',
				'text'      => __( 'Shop Isle', 'shop-isle' ),
				'subtext'   => __( 'WooCommerce Theme', 'shop-isle' ),
				'label'     => __( 'Read more', 'shop-isle' ),
			),
			array(
				'image_url' => get_template_directory_uri() . '/assets/images/slide2.jpg',
				'link'      => '#',
				'text'      => __( 'Shop Isle', 'shop-isle' ),
				'subtext'   => __( 'WooCommerce Theme', 'shop-isle' ),
				'label'     => __( 'Read more', 'shop-isle' ),
			),
			array(
				'image_url' => get_template_directory_uri() . '/assets/images/slide3.jpg',
				'link'      => '#',
				'text'      => __( 'Shop Isle', 'shop-isle' ),
				'subtext'   => __( 'WooCommerce Theme', 'shop-isle' ),
				'label'     => __( 'Read more', 'shop-isle' ),
			),
		)
	);
	shop_isle_pll_string_register_helper( 'shop_isle_slider', $default, 'Slider section' );
}


/**
 * Register strings from banners section in polylang.
 *
 * @since 2.2.35
 */
function shop_isle_register_banners_strings() {
	$default = json_encode(
		array(
			array(
				'image_url' => get_template_directory_uri() . '/assets/images/banner1.jpg',
				'link'      => '#',
			),
			array(
				'image_url' => get_template_directory_uri() . '/assets/images/banner2.jpg',
				'link'      => '#',
			),
			array(
				'image_url' => get_template_directory_uri() . '/assets/images/banner3.jpg',
				'link'      => '#',
			),
		)
	);
	shop_isle_pll_string_register_helper( 'shop_isle_banners', $default, 'Banners section' );
}

/**
 * Register strings from services section in polylang.
 *
 * @since 2.2.35
 */
function shop_isle_register_services_strings() {

	$default = json_encode(
		array(
			array(
				'icon_value' => 'icon_gift',
				'text'       => esc_html__( 'Social icons', 'shop-isle' ),
				'subtext'    => esc_html__( 'Ideas and concepts', 'shop-isle' ),
				'link'       => esc_url( '#' ),
			),
			array(
				'icon_value' => 'icon_pin',
				'text'       => esc_html__( 'WooCommerce', 'shop-isle' ),
				'subtext'    => esc_html__( 'Top Rated Products', 'shop-isle' ),
				'link'       => esc_url( '#' ),
			),
			array(
				'icon_value' => 'icon_star',
				'text'       => esc_html__( 'Highly customizable', 'shop-isle' ),
				'subtext'    => esc_html__( 'Easy to use', 'shop-isle' ),
				'link'       => esc_url( '#' ),
			),
		)
	);
	shop_isle_pll_string_register_helper( 'shop_isle_service_box', $default, 'Features section' );
}

/**
 * Register strings from shortcode section in polylang.
 *
 * @since 2.2.35
 */
function shop_isle_register_shortcode_strings() {
	shop_isle_pll_string_register_helper( 'shop_isle_shortcodes_settings', false, 'Shortcodes section' );
}

/**
 * Register strings from socials section in polylang.
 *
 * @since 2.2.35
 */
function shop_isle_register_socials_strings() {
	shop_isle_pll_string_register_helper( 'shop_isle_socials', false, 'Footer socials' );
}

/**
 * Register strings from team section in polylang.
 *
 * @since 2.2.35
 */
function shop_isle_register_team_strings() {
	$default = json_encode(
		array(
			array(
				'image_url'   => get_template_directory_uri() . '/assets/images/team1.jpg',
				'text'        => 'Eva Bean',
				'subtext'     => 'Developer',
				'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit lacus, a iaculis diam.',
			),
			array(
				'image_url'   => get_template_directory_uri() . '/assets/images/team2.jpg',
				'text'        => 'Maria Woods',
				'subtext'     => 'Designer',
				'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit lacus, a iaculis diam.',
			),
			array(
				'image_url'   => get_template_directory_uri() . '/assets/images/team3.jpg',
				'text'        => 'Booby Stone',
				'subtext'     => 'Director',
				'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit lacus, a iaculis diam.',
			),
			array(
				'image_url'   => get_template_directory_uri() . '/assets/images/team4.jpg',
				'text'        => 'Anna Neaga',
				'subtext'     => 'Art Director',
				'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit lacus, a iaculis diam.',
			),
		)
	);
	shop_isle_pll_string_register_helper( 'shop_isle_team_members', $default, 'Team section' );
}

/**
 * Register strings from advantages section in polylang.
 *
 * @since 2.2.35
 */
function shop_isle_register_advantages_strings() {
	$default = json_encode(
		array(
			array(
				'icon_value' => 'icon_lightbulb',
				'text'       => __( 'Ideas and concepts', 'shop-isle' ),
				'subtext'    => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'shop-isle' ),
			),
			array(
				'icon_value' => 'icon_tools',
				'text'       => __( 'Designs & interfaces', 'shop-isle' ),
				'subtext'    => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'shop-isle' ),
			),
			array(
				'icon_value' => 'icon_cogs',
				'text'       => __( 'Highly customizable', 'shop-isle' ),
				'subtext'    => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'shop-isle' ),
			),
			array(
				'icon_value' => 'icon_like',
				'text'       => __( 'Easy to use', 'shop-isle' ),
				'subtext'    => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'shop-isle' ),
			),
		)
	);
	shop_isle_pll_string_register_helper( 'shop_isle_advantages', $default, 'Advantages section' );
}

if ( function_exists( 'pll_register_string' ) ) {
	add_action( 'after_setup_theme', 'shop_isle_register_slider_strings', 11 );
	add_action( 'after_setup_theme', 'shop_isle_register_banners_strings', 11 );
	add_action( 'after_setup_theme', 'shop_isle_register_services_strings', 11 );
	add_action( 'after_setup_theme', 'shop_isle_register_shortcode_strings', 11 );
	add_action( 'after_setup_theme', 'shop_isle_register_socials_strings', 11 );
	add_action( 'after_setup_theme', 'shop_isle_register_team_strings', 11 );
	add_action( 'after_setup_theme', 'shop_isle_register_advantages_strings', 11 );
}
