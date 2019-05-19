<?php
/**
 * The front-page.php
 *
 * @package ShopIsle
 */

$shop_isle_blog_case             = 0;
$shop_isle_front_page_case       = 0;
$shop_isle_default_template_case = 0;

$shop_isle_wporg_flag = get_option( 'shop_isle_wporg_flag' );

if ( ! empty( $shop_isle_wporg_flag ) && ( 'true' === $shop_isle_wporg_flag ) ) {

	if ( 'posts' == get_option( 'show_on_front' ) ) {
		$shop_isle_blog_case = 1;
	} else {
		$shop_isle_keep_old_fp_template = get_theme_mod( 'shop_isle_keep_old_fp_template' );
		if ( ! $shop_isle_keep_old_fp_template ) {
			$shop_isle_front_page_case = 1;
		} else {
			$shop_isle_default_template_case = 1;
		}
	}
} else {
	if ( 'posts' == get_option( 'show_on_front' ) ) {
		$shop_isle_front_page_case = 1;
	} else {
		$shop_isle_default_template_case = 1;
	}
}



if ( $shop_isle_front_page_case ) {

	/**
	 * The front page used for static page.
	 *
	 * @package WordPress
	 * @subpackage Shop Isle
	 */
	get_header();

	$sections_order = get_theme_mod(
		'sections_order', json_encode(
			apply_filters(
				'shop_isle_sections', array(
					'shop_isle_banners_section'         => 15,
					'shop_isle_products_section'        => 20,
					'shop_isle_video_section'           => 25,
					'shop_isle_products_slider_section' => 55,
				)
			)
		)
	);

	$sections_order_decoded = json_decode( $sections_order, true );
	$result                 = array();
	if ( ! empty( $sections_order_decoded ) ) {
		foreach ( $sections_order_decoded as $key => $priority ) {
			array_push( $result, $key );
		}
	}

	$big_title = get_template_directory() . '/inc/sections/shop_isle_big_title_section.php';

	load_template( apply_filters( 'shop-isle-subheader', $big_title ) );

	/* Wrapper start */
	$shop_isle_bg = get_theme_mod( 'background_color' );
	if ( isset( $shop_isle_bg ) && $shop_isle_bg != '' ) {
		echo '<div class="main front-page-main" style="background-color: #' . esc_attr( $shop_isle_bg ) . '">';
	} else {
		echo '<div class="main front-page-main" style="background-color: #FFF">';
	}

	if ( ! empty( $result ) ) {
		foreach ( $result as $section ) {
			get_template_part( 'inc/sections/' . $section );
		}
	}
	get_footer();

} elseif ( $shop_isle_default_template_case ) {
	include( get_page_template() );

}// End if().
