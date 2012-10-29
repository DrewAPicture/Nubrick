<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package Twenty_Twelve
 * @subpackage Nubrick
 * @since Nubrick 1.0
 */
?>
	</div><!-- #main .wrapper -->
	<footer id="colophon" role="contentinfo">
		<div class="site-info">
			<?php do_action( 'twentytwelve_credits' ); ?>
			<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'twentytwelve' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'twentytwelve' ); ?>" rel="generator">
				<?php printf( __( 'proudly powered by %s', 'twentytwelve' ), 'WordPress' ); ?>
			</a><br />
			<a href="<?php bloginfo( 'rss2_url' ); ?>">Entries (RSS)</a>
			and <a href="<?php bloginfo( 'comments_rss2_url' ); ?>">Comments (RSS)</a>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<!-- Original gorgeous design by Michael Heilmann - http://binarybonsai.com/kubrick/ -->
<!-- Rehashed in responsive living color by Drew Jaynes - http://www.werdswords.com -->

<?php /* I doubt this is the Dave you are looking for */ ?>
<?php wp_footer(); ?>
</body>
</html>