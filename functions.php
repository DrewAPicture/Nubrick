<?php
/**
 * Nubrick child theme functions
 *
 * @package Twenty_Twelve
 * @subpackage Nubrick
 * @since Nubrick 1.0
 */

/**
 * Setup Nubrick Child Textdomain
 *
 * Declares the textdomain for this child theme
 * Translations can be filed in the /languages/ directory
 *
 * @uses load_child_theme_textdomain()
 * @since Nubrick 1.0
 */
function nubrick_setup() {
	load_child_theme_textdomain( 'nubrick', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'nubrick_setup' );


/**
 * Reset Custom Background default color value.
 *
 * @since Nubrick 1.0
 */
function nubrick_reset_supports() {	
	// Remove custom-background support added by Twenty Twelve
	remove_theme_support( 'custom-background' );

	// Re-add with new default color
	add_theme_support( 'custom-background', array(
		'default-color' => 'e7e7e7'
	) );
}
add_action( 'after_setup_theme', 'nubrick_reset_supports' );


/**
 * Dequeue unneeded scripts from Twenty Twelve
 *
 * @since Nubrick 1.0
 */
function nubrick_dequeue_scripts() {
	// Dequeue unneeded navigation script
	wp_dequeue_script( 'twentytwelve-navigation' );

	// Dequeue Open Sans font
	wp_dequeue_style( 'twentytwelve-fonts' );
}
add_action( 'wp_enqueue_scripts', 'nubrick_dequeue_scripts', 11 );


/**
 * Enqueue dynamic stylesheet for Header Color gradient effect.
 *
 * @since Nubrick 1.0
 */
function nubrick_enqueue_dynamic_styles() {	
	// Setup dynamic style values
	$first_color = str_replace( '#', '', get_theme_mod( 'nubrick_first_header_color' ) );
	$second_color = str_replace( '#', '', get_theme_mod( 'nubrick_second_header_color' ) );
	$direction = get_theme_mod( 'nubrick_gradient_direction' );

	// Site width
	$width = get_theme_mod( 'nubrick_site_width' );
	if ( intval( $width ) < 1 || ! is_numeric( $width ) )
		$width = '';

	// Setup empty values
	$header_img = $img = '';
	
	$header_img = get_header_image();
	
	if ( ! empty( $header_img ) )
		$img = urlencode( $header_img );
	
	// Set single post flag
	is_single() ? $single = true : $single = false;

	// Build query string from our values
	// 
	$options = sprintf( 'first=%1$s&second=%2$s&dir=%3$s&img=%4$s&single=%5$s&width=%6$s',
	 	$first_color,
		$second_color,
		$direction,
		$img,
		$single,
		$width
	);
	
	wp_register_style( 'nubrick-dynamic', get_stylesheet_directory_uri() . '/inc/dynamic-style.php?' . $options );
	wp_enqueue_style( 'nubrick-dynamic' );
}

// Priority 30 to make sure it gets printed AFTER style.css
add_action( 'wp_enqueue_scripts', 'nubrick_enqueue_dynamic_styles', 30 );


/**
 * Overwrite twentytwelve_content_nav().
 * 
 * NOTE:
 *		single.php has its own version of
 * 		this code block which we've rolled into
 * 		nubrick_content_nav_single()
 *
 * @param string $nav_id
 * @since Nubrick 1.0
 */
function twentytwelve_content_nav( $nav_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $nav_id; ?>" class="navigation" role="navigation">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'nubrick' ); ?></h3>
			<div class="nav-previous alignleft"><?php next_posts_link( __( '<span class="meta-nav">&laquo;</span> Older posts', 'nubrick' ) ); ?></div>
			<div class="nav-next alignright"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&raquo;</span>', 'nubrick' ) ); ?></div>
		</nav><!-- #<?php echo $nav_id; ?> .navigation -->
	<?php endif;
}


/**
 * Single post-version of twentytwelve_content_nav().
 *
 * @since Nubrick 1.0
 */
function nubrick_content_nav_single() {
	if ( is_single() ) : ?>
		<nav class="nav-single">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'nubrick' ); ?></h3>
			<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&laquo;', 'Previous post link', 'nubrick' ) . '</span> %title' ); ?></span>
			<span class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&raquo;', 'Next post link', 'nubrick' ) . '</span>' ); ?></span>
		</nav><!-- .nav-single -->
	<?php endif;	
}


/**
 * Overwrites twentytwelve_entry_meta()
 *
 * Prints HTML with meta information for current post: categories, tags, permalink, author and date.
 *
 * @since Nubrick 1.0
 */
function twentytwelve_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'nubrick' ) );

	$date = sprintf( '<time class="entry-date" datetime="%s" pubdate>%s</time>',
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	// Both comments & pings
	if ( comments_open() && pings_open() ) {
		$comments_pings = sprintf( __('You can <a href="#respond">%1$s</a>, or <a href="%2$s" rel="trackback">%3$s</a> from your own site.', 'nubrick' ), 
			__( 'leave a response', 'nubrick' ),
			get_trackback_url(),
			__( 'trackback', 'nubrick' )
		);

	// Pings but no comments
	} elseif ( ! comments_open() && pings_open() ) {
		$comments_pings = sprintf( __( 'Responses are currently closed, but you can <a href="%1$s" rel="trackback">%2$s</a> from your own site.', 'nubrick' ),
			get_trackback_url(),
			__( 'trackback', 'nubrick' )
		);

	// Comments but no pings
	} elseif ( comments_open() && ! pings_open() ) {
		$comments_pings = sprintf( __( 'You can skip to the end and <a href="#respond">%1$s</a>. Trackbacks are not allowed.', 'nubrick' ), 
			__( 'leave a response', 'nubrick' )
		);

	// No comments no pings
	} elseif ( ! comments_open() && ! pings_open() ) {
		$comments_pings = __( 'Both comments and trackbacks are currently closed.', 'nubrick' );
	}

	// Translators: 1 is the date, 2 is the time, 3 is categories, 4 is RSS link, 5 is response & trackbacks
	$single_meta = __( 'This entry was posted on %1$s at %2$s and is filed under %3$s. You can follow any responses to this entry through the <a href="%4$s">RSS 2.0</a> feed. %5$s', 'nubrick' );
	?>

	<p><?php __( 'Tags: ', 'nubrick' ); the_tags( __( 'Tags: ', 'nubrick' ), ', ' ); ?></p>

	<?php if ( is_single() ) : ?>

		<p class="alt">
			<small>
				<?php 
				printf( $single_meta,
					$date,
					get_the_time(),
					$categories_list,
					get_post_comments_feed_link(),
					$comments_pings
				);
				?>
			</small>
		</p><!-- /.alt -->

	<?php else: ?>

		<p>
			<?php 
			printf( __( 'Posted in %s', 'nubrick' ), $categories_list );
			edit_post_link( __( 'Edit', 'nubrick' ), ' | <span class="edit-link">', '</span>' );
			?> | <?php
			comments_popup_link( __( 'No Comments &#187;', 'nubrick' ), __( '1 Comment', 'nubrick' ), __( '% Comments', 'nubrick' ), __( 'Comments Off', 'nubrick' ) );
			?>
		</p>

	<?php endif; // is_single

}


/**
 * Add Header Color options to Customizer.
 *
 * @param global $wp_customize
 * @since Nubrick 1.0
 */
function nubrick_customizer_init( $wp_customize ) {
	
	/**
	 * Temporarily unset 'Header Text Color' & 'Background Color'
	 * controls from the Customizer, we'll add them back later.
	 */
	$wp_customize->remove_control( 'header_textcolor' );
	$wp_customize->remove_control( 'background_color' );
	
	/**
	 * Add 'Gradient Direction' select box
	 * Default is 'Top'
	 */
	$wp_customize->add_setting( 'nubrick_gradient_direction', array( 'default' => 'top' ) );
	$wp_customize->add_control( 'nubrick_gradient_direction', array(
		'label' => __( 'Header Gradient Direction', 'nubrick' ),
		'section' => 'colors',
		'type' => 'select',
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
		)
	);

	/**
	 * Add 'Top Color' setting & control to Customizer.
	 * Default color is #69aee7
	 */
	$wp_customize->add_setting( 'nubrick_first_header_color', array( 'default' => '#69aee7' ) );
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( $wp_customize, 'nubrick_first_header_color', array(
			'label' => __( 'First Header Color', 'nubrick' ),
			'section' => 'colors',
			'settings' => 'nubrick_first_header_color',
			) 
		) 
	);

	/**
	 * Add 'Bottom Color' setting & control to Customizer.
	 * Default color is #4180b6
	 */
	$wp_customize->add_setting( 'nubrick_second_header_color', array( 'default' => '#4180b6' ) );
	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, 'nubrick_second_header_color', array(
			'label' => __( 'Second Header Color', 'nubrick' ),
			'section' => 'colors',
			'settings' => 'nubrick_second_header_color'
			)
		)
	);
	
	/**
	 * Re-add 'Header Text Color' & 'Background Color' controls to Customizer.
	 * Default background color is '#e7e7e7'
	 */
	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, 'header_textcolor', array(
			'label'   => __( 'Header Text Color', 'nubrick' ),
			'section' => 'colors'
			)
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, 'background_color', array(
			'label'   => __( 'Background Color', 'nubrick' ),
			'section' => 'colors'
			)
		)
	);

	/**
	 * Add Site Width setting
	 * Default is 740px
	 */
	$wp_customize->add_setting( 'nubrick_site_width', array( 'default' => 740 ) );
	$wp_customize->add_control( 'nubrick_site_width', array( 
		'label' => __( 'Site Width (px)', 'nubrick' ),
		'section' => 'colors'
		)
	);

}
add_action( 'customize_register', 'nubrick_customizer_init' );


/**
 * Prepend the WordPress credits with our Nubricky message.
 * Using this action was overkill, I just wanted to!
 *
 * @since Nubrick 1.0
 */
function nubrick_credits() {
	printf( '%s is ', get_bloginfo( 'name' ) );
}

add_action( 'twentytwelve_credits', 'nubrick_credits' );
