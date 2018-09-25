<?php
/**
 * The template for displaying the footer.
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

	</div><!-- #content -->
</div><!-- #page -->

<?php
/**
 * dokanee_before_footer hook.
 *
 * @since 0.1
 */
do_action( 'dokanee_before_footer' );
?>
<?php
/**
 * Trust Factors Section.
 *
 * @since 0.1
 */

if( ( get_theme_mod( 'show_trusted_factors_section', 'on' ) == 'on' ) and ( is_front_page() or is_woocommerce() ) ) {
	get_template_part( 'template-parts/page/section-trust' );
}

?>

<div <?php dokanee_footer_class(); ?>>
	<?php
	/**
	 * dokanee_before_footer_content hook.
	 *
	 * @since 0.1
	 */
	do_action( 'dokanee_before_footer_content' );

	/**
	 * dokanee_footer hook.
	 *
	 * @since 1.3.42
	 *
	 * @hooked dokanee_construct_footer_widgets - 5
	 * @hooked dokanee_construct_footer - 10
	 */
	do_action( 'dokanee_footer' );

	/**
	 * dokanee_after_footer_content hook.
	 *
	 * @since 0.1
	 */
	do_action( 'dokanee_after_footer_content' );
	?>
</div><!-- .site-footer -->

<?php
/**
 * dokanee_after_footer hook.
 *
 * @since 2.1
 */
do_action( 'dokanee_after_footer' );

wp_footer();
?>

</body>
</html>
