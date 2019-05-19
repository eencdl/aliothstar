<?php
/**
 * The front page template.
 *
 * Template Name: Frontpage
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

