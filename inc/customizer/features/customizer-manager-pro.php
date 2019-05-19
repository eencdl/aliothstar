<?php
/**
 * Manage pro options.
 *
 * @package WordPress
 * @subpackage Shop Isle Lite
 */

/**
 * Unhook lite messages and upsells from Customizer.
 */
function shop_isle_manager_customize_register( $wp_customize ) {

	$wp_customize->remove_section( 'shopisle-upsell-section' );
	$wp_customize->remove_section( 'shopisle_pro_features_section' );

	$wp_customize->remove_control( 'shop_isle_site_info_hide' );
	$wp_customize->remove_control( 'shopisle_upsell_colors' );

	$upsell_section = $wp_customize->get_section( 'shopisle-upsell' );
	if ( ! empty( $upsell_section ) ) {
		$upsell_section->label_url    = esc_url( 'http://docs.themeisle.com/article/318-shopisle-pro-documentation' );
		$upsell_section->upsell_title = esc_html__( 'ShopIsle Pro', 'shop-isle' );
	}

	$footer_copyright = $wp_customize->get_setting( 'shop_isle_copyright' );
	$footer_socials   = $wp_customize->get_setting( 'shop_isle_socials' );

	if ( ! empty( $footer_copyright ) ) :
		$footer_copyright->default = __( '&copy; Themeisle, All rights reserved', 'shop-isle' );
	endif;

	if ( ! empty( $footer_socials ) ) :
		$footer_socials->default = json_encode(
			array(
				array(
					'icon_value' => 'social_facebook',
					'link'       => '#',
				),
				array(
					'icon_value' => 'social_twitter',
					'link'       => '#',
				),
				array(
					'icon_value' => 'social_dribbble',
					'link'       => '#',
				),
				array(
					'icon_value' => 'social_skype',
					'link'       => '#',
				),
			)
		);
	endif;

}
add_action( 'customize_register', 'shop_isle_manager_customize_register', 90 );
add_filter( 'shop_isle_site_info', '__return_empty_string' );

/* Add defaults to footer for pro version */
add_filter( 'shop_isle_footer_copyright_filter', 'shop_isle_footer_copyright_filter_callback' );

/**
 * Footer Copyright Default Value Filter
 *
 * @return string
 */
function shop_isle_footer_copyright_filter_callback() {
	return get_theme_mod( 'shop_isle_copyright', __( '&copy; Themeisle, All rights reserved', 'shop-isle' ) );
}

add_filter( 'shop_isle_footer_socials_filter', 'shop_isle_footer_socials_filter_callback' );

/**
 * Footer Socials Default Value Filter
 *
 * @return string
 */
function shop_isle_footer_socials_filter_callback() {
	return get_theme_mod(
		'shop_isle_site_info_hide', json_encode(
			array(
				array(
					'icon_value' => 'social_facebook',
					'link'       => '#',
				),
				array(
					'icon_value' => 'social_twitter',
					'link'       => '#',
				),
				array(
					'icon_value' => 'social_dribbble',
					'link'       => '#',
				),
				array(
					'icon_value' => 'social_skype',
					'link'       => '#',
				),
			)
		)
	);
}

/**
 * Load script for sections order
 */
require_once( get_template_directory() . '/inc/customizer/customizer-sections-order/customizer-sections-order.php' );

/**
 * Sections order array filter
 */
function shop_isle_pro_sections() {
	return array(
		'shop_isle_banners_section'         => 15,
		'shop_isle_products_section'        => 20,
		'shop_isle_video_section'           => 25,
		'shop_isle_services_section'        => 30,
		'shop_isle_ribbon_section'          => 35,
		'shop_isle_map_section'             => 40,
		'shop_isle_fp_categories_section'   => 45,
		'shop_isle_shortcodes_section'      => 50,
		'shop_isle_products_slider_section' => 55,
	);
}
add_filter( 'shop_isle_sections', 'shop_isle_pro_sections' );

/*
 * Import customizer options from Lite version
 */
add_action( 'after_switch_theme', 'shop_isle_get_lite_options' );

/**
 * Import lite options.
 */
function shop_isle_get_lite_options() {

	/* import shop isle options */
	$shop_isle_mods = get_option( 'theme_mods_shop-isle' );

	if ( ! empty( $shop_isle_mods ) ) {

		$new_slider = new stdClass();

		foreach ( $shop_isle_mods as $shop_isle_mod_k => $shop_isle_mod_v ) {

			/* migrate Big title section to Slider section */
			if ( ( $shop_isle_mod_k == 'shop_isle_big_title_image' ) || ( $shop_isle_mod_k == 'shop_isle_big_title_title' ) || ( $shop_isle_mod_k == 'shop_isle_big_title_subtitle' ) || ( $shop_isle_mod_k == 'shop_isle_big_title_button_label' ) || ( $shop_isle_mod_k == 'shop_isle_big_title_button_link' ) ) {

				if ( $shop_isle_mod_k == 'shop_isle_big_title_image' ) {
					if ( ! empty( $shop_isle_mod_v ) ) {
						$new_slider->image_url = $shop_isle_mod_v;
					} else {
						$new_slider->image_url = get_template_directory_uri() . '/assets/images/slide1.jpg';
					}
				}

				if ( $shop_isle_mod_k == 'shop_isle_big_title_title' ) {
					if ( ! empty( $shop_isle_mod_v ) ) {
						$new_slider->text = $shop_isle_mod_v;
					} else {
						$new_slider->text = __( 'Shop Isle', 'shop-isle' );
					}
				}

				if ( $shop_isle_mod_k == 'shop_isle_big_title_subtitle' ) {
					if ( ! empty( $shop_isle_mod_v ) ) {
						$new_slider->subtext = $shop_isle_mod_v;
					} else {
						$new_slider->subtext = __( 'WooCommerce Theme', 'shop-isle' );
					}
				}

				if ( $shop_isle_mod_k == 'shop_isle_big_title_button_label' ) {
					if ( ! empty( $shop_isle_mod_v ) ) {
						$new_slider->label = $shop_isle_mod_v;
					} else {
						$new_slider->label = __( 'Read more', 'shop-isle' );
					}
				}

				if ( $shop_isle_mod_k == 'shop_isle_big_title_button_link' ) {
					if ( ! empty( $shop_isle_mod_v ) ) {
						$new_slider->link = $shop_isle_mod_v;
					} else {
						$new_slider->link = '#';
					}
				}

				if ( ! empty( $new_slider ) ) {
					$new_slider_encode = json_encode( array( $new_slider ) );
					set_theme_mod( 'shop_isle_slider', $new_slider_encode );
				}
			} else {

				set_theme_mod( $shop_isle_mod_k, $shop_isle_mod_v );
			}// End if().
		}// End foreach().
	}// End if().
}

/**
 * Extend the cart with the cart widget.
 */
function shop_isle_cart_icon_pro() {
	echo '<div class="header-shopping-cart">';
	the_widget( 'WC_Widget_Cart' );
	echo '</div>';
}

add_filter( 'shop_isle_cart_icon', 'shop_isle_cart_icon_pro' );

/**
 * Remove frontpage filter if user not from wporg.
 */
function shop_isle_frontpage_remove_filter() {
	$wporg_flag = get_option( 'shop_isle_wporg_flag' );
	if ( ! isset( $wporg_flag ) || ( (bool) $wporg_flag === false ) ) {
		remove_filter( 'frontpage_template', 'shop_isle_filter_front_page_template' );
	}
}
add_action( 'init', 'shop_isle_frontpage_remove_filter' );
