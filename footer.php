<?php
/**
 * The template for displaying the footer.
 *
 * @package dokani
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

	</div><!-- #content -->
</div><!-- #page -->

<?php
/**
 * dokani_before_footer hook.
 *
 * @since 1.0.0
 */
do_action( 'dokani_before_footer' );
?>
<?php
/**
 * Trust Factors Section.
 *
 * @since 1.0.0
 */

if( ( get_theme_mod( 'show_trusted_factors_section', 'on' ) == 'on' ) && class_exists( 'WooCommerce' ) ) {
	get_template_part( 'template-parts/page/section-trust' );
}

?>

<div <?php dokani_footer_class(); ?>>
	<?php
	/**
	 * dokani_before_footer_content hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'dokani_before_footer_content' );

	/**
	 * dokani_footer hook.
	 *
	 * @since 1.0.0
	 *
	 * @hooked dokani_construct_footer_widgets - 5
	 * @hooked dokani_construct_footer - 10
	 */

	do_action( 'dokani_footer' );

	/**
	 * dokani_after_footer_content hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'dokani_after_footer_content' );
	?>
</div><!-- .site-footer -->

<?php
/**
 * dokani_after_footer hook.
 *
 * @since 1.0.0
 */
do_action( 'dokani_after_footer' );

wp_footer();
?>

</body>
</html>
