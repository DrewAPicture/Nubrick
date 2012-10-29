<?php
header( 'Content-type: text/css');

/**
 * Provides dynamic styling for:
 * 	1. Gradient effects in the Header.
 * 	2. The single post view
 *  3. Dynamic site width
 *
 * @package Nubrick
 * @since Nubrick 1.0
 */

	/**
	 * Setup Header Colors:
	 *  1. Get and set header color values.
	 *  2. Set fallback of transparent if necessary.
	 *  3. Build reusable linear gradient string.
	 */
	$first = isset( $_GET['first'] ) ? '#' . $_GET['first'] : '';
	$second = isset( $_GET['second'] ) ? '#' . $_GET['second'] : '';
	$dir = isset( $_GET['dir'] ) ? $_GET['dir'] : '';

	if ( ! $first )
		$color = $second;
	elseif ( ! $second )
		$color = $first;
	else
		$color = 'transparent';

	$gradient = sprintf( '%1$s, %2$s, %3$s', $dir, $first, $second );

	/**
	 * Setup Custom Header Image:
	 *  1. Handle a custom header image if set.
	 *  2. Build background declaration with header image value.
	 */
	$header_image = isset( $_GET['img'] ) ? urldecode( $_GET['img'] ) : '';

	if ( $header_image )
		$header_image = 'background: url(' . $header_image . ') no-repeat;' . "\n";

	/**
	 * Setup global link colors
	 */
	$links = isset( $_GET['links'] ) ? '#' . $_GET['links'] : '';
	$hover = isset( $_GET['hover'] ) ? '#' . $_GET['hover'] : '';
	
	/**
	 * Setup Site Width
	 *  1. Check if width parameter is set, if not, set default of 740.
	 *  2. Calculate the rem width per the theme's formula in style.css.
	 */
	$width = ! empty( $_GET['width'] ) ? $_GET['width'] : 860;
	$rem_width = $width / 14;

	/**
	 * Check for global link colors
	 *  1. Get global link color
	 *  2. Get global link:hover color
	 */

?>
/* Dynamic Site Width */

@media screen and (min-width: 600px) {
	.site {
		max-width: <?php echo $width; ?>px;
		max-width: <?php echo $rem_width; ?>rem;
	}
}

/* Header Gradient */
.site-header {
	background-color: <?php echo $color; ?>;
	background: -webkit-gradient( linear, 0% 0%, 0% 100%, from(<?php echo $first; ?>), to(<?php echo $second; ?>) );
	background: -webkit-linear-gradient( <?php echo $gradient; ?> );
	background: -moz-linear-gradient( <?php echo $gradient; ?> );
	background: -ms-linear-gradient( <?php echo $gradient; ?> );
	background: -o-linear-gradient( <?php echo $gradient; ?> );
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=<?php echo $first; ?>, endColorstr=<?php echo $second; ?>); /* IE7 */
	zoom: 1;
	-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=<?php echo $first; ?>, endColorstr=<?php echo $second; ?>)"; /* IE8 */
	<?php echo $header_image; ?>
	background-size: auto auto;
}

<?php if ( $links ) : ?>
	/* Global link colors */
	.page-links a,
	.gallery-caption a,
	.widget-area .widget li a,
	footer.entry-meta a {
		color: <?php echo $links; ?>;
	}
<?php endif; // $links ?>

<?php if ( $hover ) : ?>
	.page-links aLhover,
	.gallery-caption a:hover,
	.widget-area .widget li a:hover,
	footer.entry-meta a:hover {
		color: <?php echo $hover; ?>;
	}
<?php endif; // $hover ?>