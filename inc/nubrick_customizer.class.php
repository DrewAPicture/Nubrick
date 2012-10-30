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

	/**
	 * Initialize
	 *
	 * @since Nubrick 1.0
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'modify_settings' ) );
	}
	
	/**
	 * Add & Rearrange Customizer Settings, Controls and Sections
	 *
	 * @param WP_Customize_Manager $wp_customize
	 * @since Nubrick 1.0
	 */
	public static function modify_settings( $wp_customize ) {

		/**
		 * Temporarily remove built-in controls/sections
		 * 
		 * Removes 'header_textcolor' and 'background_color' controls
		 * Removes 'colors' section
		 */
		$wp_customize->remove_control( 'header_textcolor' );
		$wp_customize->remove_control( 'background_color' );
		$wp_customize->remove_section( 'colors' );


		/**
		 * Add Custom Nubrick Sections/Re-add 'colors'
		 *
		 * Adds:
		 * 		1. 'Site Settings' section below 'Site Title & Tagline'
		 * 		2. 'Header Settings' section below 'Site Settings'
		 * Re-adds:
		 * 		1. 'colors' section renamed to 'Other Colors' below 'Header Image'
		 */
		$wp_customize->add_section( 'nubrick_site_settings', array(
			'title' => __( 'Site Settings', 'nubrick' ),
			'priority' => 40
		) );
		$wp_customize->add_section( 'nubrick_header_colors', array(
			'title' => __( 'Header Colors', 'nubrick' ),
			'priority' => 45
		) );
		$wp_customize->add_section( 'colors', array(
			'title' => __( 'Other Colors', 'nubrick' ),
			'priority' => 65
		) );		


		/**
		 * Add Custom Nubrick Settings
		 *
		 * Adds:
		 * 		1. 'Site Width' setting 			default: 860
		 * 		2. 'Gradient direction' setting 	default: 'top'
		 * 		3. 'First header color' setting 	default: '#69aee7'
		 * 		4. 'Second header color' setting 	default: '#4180b6'
		 * 		5. 'Links color' setting		 	default: '#0066CC'
		 * 		6. 'Links hover color' setting		default: '#114477'
		 */
		$wp_customize->add_setting( 'nubrick_site_width', array( 'default' => 860 ) );
		$wp_customize->add_setting( 'nubrick_gradient_direction', array( 'default' => 'top' ) );
		$wp_customize->add_setting( 'nubrick_first_header_color', array( 'default' => '#69aee7' ) );
		$wp_customize->add_setting( 'nubrick_second_header_color', array( 'default' => '#4180b6' ) );
		$wp_customize->add_setting( 'nubrick_global_link_color', array( 'default' => '#0066CC' ) );
		$wp_customize->add_setting( 'nubrick_global_link_hover', array( 'default' => '#114477' ) );



		/**
		 * Add Custom Nubrick Controls/Re-add removed controls
		 *
		 * Adds:
		 * 		1. 'Site Width (in pixels)' control 	to 'Site Settings'
		 * 		2. 'Header Gradient Direction' control 	to 'Header Colors'
		 * 		3. 'First Header Color' control 		to 'Header Colors'
		 * 		4. 'Second Header Color' control 		to 'Header Colors'
		 * Re-adds:
		 * 		1. 'Header Text Color' control 			to 'Header Colors'
		 * 		2. 'Background Color' control 			to 'Other Colors'
		 * Adds:
		 * 		1. 'Link Color' control 				to 'Other Colors'
		 * 		2. 'Link Hover Color' control 			to 'Other Colors'
		 */

		// Site Settings
		$wp_customize->add_control( 'nubrick_site_width', array( 
			'label' => __( 'Site Width (in pixels)', 'nubrick' ),
			'section' => 'nubrick_site_settings'
		) );
		
		// Header Colors
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
				) 
		) );
		$wp_customize->add_control( 
			new WP_Customize_Color_Control( $wp_customize, 'nubrick_first_header_color', array(
				'label' => __( 'First Header Color', 'nubrick' ),
				'section' => 'nubrick_header_colors',
				'settings' => 'nubrick_first_header_color',
			) ) 
		);
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

		// Other Colors
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'background_color', array(
				'label'   => __( 'Background Color', 'nubrick' ),
				'section' => 'colors',
				'priority' => 1
			) )
		);		
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'nubrick_global_link_color', array(
				'label' => __( 'Link Color', 'nubrick' ),
				'section' => 'colors'
			) )
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'nubrick_global_link_hover', array(
				'label' => __( 'Link Hover Color', 'nubrick' ),
				'section' => 'colors'
			) ) 
		);
	}

} // Nubrick_Customizer
