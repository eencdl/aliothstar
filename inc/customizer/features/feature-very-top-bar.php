<?php
/**
 * Customizer functionality for the Very Top Bar section
 *
 * @package WordPress
 * @subpackage Shop Isle
 */

/**
 * Hook controls for Very Top Bar section to Customizer.
 */
function shop_isle_very_top_bar_customize_register( $wp_customize ) {

	require_once( SHOP_ISLE_PHP_INCLUDE . '/customizer/class/class-shop-isle-display-text.php' );
	require_once( SHOP_ISLE_PHP_INCLUDE . '/customizer/customizer-radio-image/class/class-shop-isle-customize-control-radio-image.php' );

	/*  Header */
	$wp_customize->add_panel(
		'shop_isle_header_options', array(
			'priority' => 35,
			'title'    => esc_html__( 'Header Options', 'shop-isle' ),
		)
	);

	$shop_isle_header_section = $wp_customize->get_section( 'shop_isle_header_section' );
	if ( ! empty( $shop_isle_header_section ) ) {
		$wp_customize->get_section( 'shop_isle_header_section' )->panel = 'shop_isle_header_options';
	}

	/**
	 * Very Top Bar section.
	 */
	$wp_customize->add_section(
		'shop_isle_top_bar', array(
			'title'    => esc_html__( 'Very Top Bar', 'shop-isle' ),
			'panel'    => 'shop_isle_header_options',
			'priority' => 10,
		)
	);
	$wp_customize->add_setting(
		'shop_isle_top_bar_hide', array(
			'sanitize_callback' => 'shop_isle_sanitize_checkbox',
			'default'           => true,
		)
	);
	$wp_customize->add_control(
		'shop_isle_top_bar_hide', array(
			'type'     => 'checkbox',
			'label'    => esc_html__( 'Disable section', 'shop-isle' ),
			'section'  => 'shop_isle_top_bar',
			'priority' => 1,
		)
	);

	if ( class_exists( 'Shop_Isle_Display_Text' ) ) {
		$wp_customize->add_setting(
			'shop_isle_link_to_top_menu', array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new Shop_Isle_Display_Text(
				$wp_customize, 'shop_isle_link_to_top_menu', array(
					'priority'     => 25,
					'section'      => 'shop_isle_top_bar',
					'button_text'  => esc_html__( 'Very Top Bar', 'shop-isle' ) . ' ' . esc_html__( 'Menu', 'shop-isle' ),
					'button_class' => 'shop-isle-link-to-top-menu',
					'icon_class'   => 'fa-bars',
				)
			)
		);
	}

	$selective_refresh = isset( $wp_customize->selective_refresh ) ? true : false;
	/* Control for menu selective refresh */
	$wp_customize->add_setting(
		'shop_isle_top_menu_hidden', array(
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => $selective_refresh,
		)
	);
	$wp_customize->add_control(
		'shop_isle_top_menu_hidden', array(
			'priority' => 25,
			'type'     => 'hidden',
			'section'  => 'menu_locations',
		)
	);

	if ( class_exists( 'Shop_Isle_Customize_Control_Radio_Image' ) ) {
		$wp_customize->add_setting(
			'shop_isle_top_bar_alignment', array(
				'default'           => 'right',
				'sanitize_callback' => 'shop_isle_sanitize_alignment_options',
			)
		);
		$wp_customize->add_control(
			new Shop_Isle_Customize_Control_Radio_Image(
				$wp_customize, 'shop_isle_top_bar_alignment', array(
					'label'    => esc_html__( 'Layout', 'shop-isle' ),
					'priority' => 25,
					'section'  => 'shop_isle_top_bar',
					'choices'  => array(
						'left'  => array(
							'url'   => trailingslashit( get_template_directory_uri() ) . 'inc/customizer/customizer-radio-image/img/very-top-bar-layout-1.png',
							'label' => esc_html__( 'Left Sidebar', 'shop-isle' ),
						),
						'right' => array(
							'url'   => trailingslashit( get_template_directory_uri() ) . 'inc/customizer/customizer-radio-image/img/very-top-bar-layout-2.png',
							'label' => esc_html__( 'Right Sidebar', 'shop-isle' ),
						),
					),
				)
			)
		);
	}

	$top_bar_sidebar = $wp_customize->get_section( 'sidebar-widgets-sidebar-top-bar' );
	if ( ! empty( $top_bar_sidebar ) ) {
		$top_bar_sidebar->panel = 'shop_isle_header_options';
		$controls_to_move       = array(
			'shop_isle_top_bar_hide',
			'shop_isle_link_to_top_menu',
			'shop_isle_top_bar_alignment',
		);
		foreach ( $controls_to_move as $control ) {
			$shop_isle_control = $wp_customize->get_control( $control );
			if ( ! empty( $shop_isle_control ) ) {
				$shop_isle_control->section  = 'sidebar-widgets-sidebar-top-bar';
				$shop_isle_control->priority = -2;
			}
		}
	}

	$wp_customize->selective_refresh->add_partial(
		'shop_isle_top_menu_hidden', array(
			'selector'        => '.top-bar-nav',
			'settings'        => 'shop_isle_top_menu_hidden',
			'render_callback' => 'shop_isle_top_bar_callback',
		)
	);

}

add_action( 'customize_register', 'shop_isle_very_top_bar_customize_register', 30 );

/**
 * Sanitize alignment control.

 * @param string $value Control output.
 * @return string
 */
function shop_isle_sanitize_alignment_options( $value ) {
	$value        = sanitize_text_field( $value );
	$valid_values = array(
		'left',
		'center',
		'right',
	);
	if ( ! in_array( $value, $valid_values ) ) {
		wp_die( 'Invalid value, go back and try again.' );
	}
	return $value;
}

/**
 * Callback function for top bar alignment.
 *
 * @since 1.1.40
 */
function shop_isle_top_bar_callback() {
	shop_isle_the_very_top_bar( true );
}

/**
 * Register the Very Top Bar menu
 */
function shop_isle_setup_very_top_bar() {

	register_nav_menus(
		array(
			'top-bar-menu' => esc_html__( 'Very Top Bar', 'shop-isle' ) . ' ' . esc_html__( 'Menu', 'shop-isle' ),
		)
	);
}

add_action( 'after_setup_theme', 'shop_isle_setup_very_top_bar', 20 );

/**
 * Register the Very Top Bar widget area
 */
function shop_isle_widgets_init_very_top_bar() {

	register_sidebar(
		array(
			'name'          => __( 'Very Top Bar', 'shop-isle' ),
			'id'            => 'sidebar-top-bar',
			'description'   => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}

add_action( 'widgets_init', 'shop_isle_widgets_init_very_top_bar', 20 );
