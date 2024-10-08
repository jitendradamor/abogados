<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package abogados
 */

?>

	<footer id="colophon" class="site-footer">
		<div class="container">
			<div class="inner-sec">
				<div class="left">
					<?php dynamic_sidebar( 'sidebar-2' );?>
				</div>
				<div class="right">
					<?php dynamic_sidebar( 'sidebar-3' );?>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
