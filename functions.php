<?php
/**
 * Nubrick child theme functions
 *
 * The functions defined in this file generally serve one
 * of two purposes: 1) Provide functionality unique to the child theme
 * 2) Override pluggable parent theme functions.
 *
 * @package Nubrick
 * @since Nubrick 1.0
 */

/**
 * Nubrick Child Theme Setup
 *
 * Declares the textdomain for this child theme
 * Translations can be filed in the /languages/ directory
 *
 * Also overrides some custom-background and custom-header defaults 
 * defined by Twenty Twelve. The suggested width of header images
 * changes dynamically based on the value of the 'nubrick_site_width' setting.
 *
 * @uses load_child_theme_textdomain() to set child textdomain
 * @uses add_theme_support() to modify custom-background and custom-header defaults
 * @uses unregister_nav_menu() to unregister 'primary' menu
 * @uses register_nav_menu() to register 'footer' menu
 * @uses add_theme_support()
 * @since Nubrick 1.0
 */
function nubrick_theme_setup() {
	// Setup Nubrick child textdomain
	load_child_theme_textdomain( 'nubrick', get_stylesheet_directory() . '/languages' );
	
	// Override default background color setting
	add_theme_support( 'custom-background', array(
		'default-color' => 'e7e7e7'
	) );
	
	// Override some custom header default settings
	add_theme_support( 'custom-header', array(
		'default-text-color' => 'fff',
		'height' => 185,
		'width' => get_theme_mod( 'nubrick_site_width' ),		
	) );
	
	// Register 'footer' nav menu for Nubrick
	register_nav_menu( 'footer', __( 'Primary Menu: Footer', 'nubrick' ) );

	// Unregister 'primary' nav menu from Twenty Twelve
	unregister_nav_menu( 'primary' );
}
add_action( 'after_setup_theme', 'nubrick_theme_setup', 11 );


/**
 * Nubrick Customizer Controls
 */
require_once( get_stylesheet_directory() . '/inc/nubrick_customizer.class.php' );

if ( class_exists( 'Nubrick_Customizer' ) )
	new Nubrick_Customizer;


/**
 * Dequeue unneeded Twenty Twelve scripts/styles.
 *
 * In the spirit of the 'Default' theme Nubrick is based on, a header navigation
 * menu is unnecessary as was the Web font inlcuded with Twenty Twelve, 'Open Sans',
 * Both are dequeued from printing using this function.
 *
 * @uses wp_dequeue_script() to dequeue Twenty Twelve's navigation script
 * @uses wp_dequeue_style() to dequeue Twenty Twelve's web font, Open Sans
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
 * Enqueue Nubrick dynamic stylesheet.
 *
 * Nubrick uses a dynamic stylesheet in the form of a php file, which allows information
 * to be passed dynamically (and change dynamically) without printing messy CSS declarations
 * directly in the source code. It also allows live-preview of generated styles to work in
 * the Theme Customizer without postMessage. Values passed to the stylesheet include the header 
 * gradient settings, link colors, the source to a custom header image and a dynamic site width.
 *
 * @uses get_them_mod() to pull settings for header colors, link colors and site width
 * @uses wp_register_style() to register the dynamic stylesheet with query string variables
 * @uses wp_enqueue_style() to enqueue the dynamic stylesheet
 * @since Nubrick 1.0
 */
function nubrick_enqueue_dynamic_styles() {	
	/**
	 * Setup dynamic header style values
	 *  1. First color
	 *  2. Second color
	 *  3. Gradient direction
	 */
	$first_color = str_replace( '#', '', get_theme_mod( 'nubrick_first_header_color' ) );
	$second_color = str_replace( '#', '', get_theme_mod( 'nubrick_second_header_color' ) );
	$direction = get_theme_mod( 'nubrick_gradient_direction' );

	/**
	 * Check for and assign header image source
	 */
	$header_img = $img = '';
	$header_img = get_header_image();
	
	if ( ! empty( $header_img ) )
		$img = urlencode( $header_img );
	
	/**
	 * Check for and assign global link colors
	 */
	$links = str_replace( '#', '', get_theme_mod( 'nubrick_global_link_color' ) );
	$hover = str_replace( '#', '', get_theme_mod( 'nubrick_global_hover_color' ) );

	/**
	 * Get dynamic site width
	 */
	$width = get_theme_mod( 'nubrick_site_width' );
	if ( intval( $width ) < 1 || ! is_numeric( $width ) )
		$width = '';

	/**
	 * Build our query string to pass to dynamic-style.php
	 *  1: First header color
	 *  2: Second header color
	 *  3: Header gradient direction
	 *  4: Global link color
	 *  5: Global link:hover color
	 *  6: Header image src
	 *  7: Site width
	 */
	$options = sprintf( 'first=%1$s&second=%2$s&dir=%3$s&links=%4$s&hover=%5$s&img=%6$s&width=%7$s',
	 	$first_color, $second_color, $direction, $links, $hover, $img, $width
	);
	
	/**
	 * Register and enqueue dynamic stylesheet
	 */
	wp_register_style( 'nubrick-dynamic', get_stylesheet_directory_uri() . '/css/dynamic-style.php?' . $options );
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
 * Prints HTML with meta information for current post: categories,
 * tags, permalink, author and date.
 *
 * @uses get_the_category_list() to generate a comma-separated list of categories
 * @uses comments_open() to check if comments are open for the given post
 * @uses pings_open() to check if pings/trackbacks are open for the given post
 * @uses comments_popup_link() to generate the nooped Comment count text
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
			<small><?php printf( $single_meta, $date, get_the_time(), $categories_list, get_post_comments_feed_link(), $comments_pings ); ?></small>
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
 * Prepend the WordPress credits with our Nubricky message.
 *
 * Using this action was overkill, just seemed like the thing to do.
 *
 * @uses get_bloginfo() to output the site name in the footer
 * @since Nubrick 1.0
 */
function nubrick_credits() {
	printf( '%s is ', get_bloginfo( 'name' ) );
}
add_action( 'twentytwelve_credits', 'nubrick_credits' );
