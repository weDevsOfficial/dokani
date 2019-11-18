<?php
/**
 * The template for displaying the header.
 *
 * @package dokani
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php dokani_body_schema();?> <?php body_class( 'woocommerce' ); ?>>
	<?php
	/**
	 * dokani_before_header hook.
	 *
	 * @since 1.0.0
	 *
	 * @hooked dokani_do_skip_to_content_link - 2
	 * @hooked dokani_top_bar - 5
	 * @hooked dokani_add_navigation_before_header - 5
	 */
	 do_action( 'dokani_before_header' );

	/**
	 * dokani_header hook.
	 *
	 * @since 1.0.0
	 *
	 * @hooked dokani_construct_header - 10
	 */
	do_action( 'dokani_header' );

	/**
	 * dokani_after_header hook.
	 *
	 * @since 1.0.0
	 *
	 * @hooked dokani_featured_page_header - 10
	 */
	do_action( 'dokani_after_header' );
	?>

	<div id="page" class="hfeed site grid-container container grid-parent">
		<div id="content" class="site-content">
			<?php
			/**
			 * dokani_inside_container hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'dokani_inside_container' );
