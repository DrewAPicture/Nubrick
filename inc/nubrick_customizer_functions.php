<?php
/**
 * Nubrick Custumizer Functions
 *
 * Adds multiple Customizer sections and rearranges default settings
 * more logically according to the adjusted hierarchy.
 *
 * @package Nubrick
 * @since Nubrick 1.0
 */

class Nubrick_Customizer {
	function __construct() {
		add_action( 'customize_register', array( $this, 'nubrick_remove_defaults' ) );
		add_action( 'customize_register', array( $this, 'nubrick_add_sections' ) );
		add_action( 'customize_register', array( $this, 'nubrick_add_settings' ) );
		add_action( 'customize_register', array( $this, 'nubrick_restore_defaults' ) );
	}

	function nubrick_remove_defaults( $wp_customize ) {
		$wp_customize->remove_control( 'header_textcolor' );
		$wp_customize->remove_control( 'background_color' );
		$wp_customize->remove_section( 'color' );
	}
	
	function nubrick_add_sections( $wp_customize ) {
		$wp_customize->add_section( 'nubrick_site_settings', array(
			'title' => __( 'Site Settings', 'nubrick' ),
			'priority' => 40
		) );
		$wp_customize->add_section( 'nubrick_header_colors', array(
			'title' => __( 'Header Settings', 'nubrick' ),
			'priority' => 45
		) );

	}
	
	function nubrick_add_settings( $wp_customize ) {
		// Site width, default is 860px
		$wp_customize->add_setting( 'nubrick_site_width', array( 'default' => 860 ) );
		$wp_customize->add_control( 'nubrick_site_width', array( 
			'label' => __( 'Site Width (in pixels)', 'nubrick' ),
			'section' => 'nubrick_site_settings'
		) );
		$wp_customize->add_setting( 'nubrick_gradient_direction', array( 'default' => 'top' ) );
		$wp_customize->add_control( 'nubrick_gradient_direction', array(
			'label' => __( 'Header Gradient Direction', 'nubrick' ),
			'section' => 'nubrick_header_colors',
			'type' => 'select',
			'priority' => 1,
			'choices' => array(
				'top' => __( 'Top', 'nubrick' ),
				'bottom' => __( 'Bottom', 'nubrick' ),
				'left' => __( 'Left', 'nubrick' ),
				'right' => __( 'Right', 'nubrick' ),
				'left top' => __( 'Top Left', 'nubrick' ),
				'right top' => __( 'Top Right', 'nubrick' ),
				'left bottom' => __( 'Bottom Left', 'nubrick' ),
				'right bottom' => __( 'Bottom Right', 'nubrick' )
			) )
		);

		$wp_customize->add_setting( 'nubrick_first_header_color', array( 'default' => '#69aee7' ) );
		$wp_customize->add_control( 
			new WP_Customize_Color_Control( $wp_customize, 'nubrick_first_header_color', array(
				'label' => __( 'First Header Color', 'nubrick' ),
				'section' => 'nubrick_header_colors',
				'settings' => 'nubrick_first_header_color',
			) ) 
		);

		/**
		 * Add 'Bottom Color' setting & control to Customizer.
		 * Default color is #4180b6
		 */
		$wp_customize->add_setting( 'nubrick_second_header_color', array( 'default' => '#4180b6' ) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'nubrick_second_header_color', array(
				'label' => __( 'Second Header Color', 'nubrick' ),
				'section' => 'nubrick_header_colors',
				'settings' => 'nubrick_second_header_color'
			) )
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'header_textcolor', array(
				'label'   => __( 'Header Text Color', 'nubrick' ),
				'section' => 'nubrick_header_colors'
			) )
		);

		/**
		 * Re-add 'Header Text Color' & 'Background Color' controls to Customizer.
		 * Default background color is '#e7e7e7'
		 */	


		// Link color
		$wp_customize->add_setting( 'nubrick_global_link_color', array( 'default' => '#0066CC' ) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'nubrick_global_link_color', array(
				'label' => __( 'Links color', 'nubrick' ),
				'section' => 'colors'
			) )
		);

		// Hover color
		$wp_customize->add_setting( 'nubrick_global_link_hover', array( 'default' => '#114477' ) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'nubrick_global_link_hover', array(
				'label' => __( 'Links hover color', 'nubrick' ),
				'section' => 'colors'
			) ) 
		);

	}
	
	function nubrick_restore_defaults( $wp_customize ) {
		$wp_customize->add_section( 'colors', array(
			'title' => 'Other Colors',
			'priority' => 65
		) );		
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'background_color', array(
				'label'   => __( 'Background Color', 'nubrick' ),
				'section' => 'colors',
				'priority' => 1
			) )
	);
		
	}
}

new Nubrick_Customizer;