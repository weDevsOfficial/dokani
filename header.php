<?php
/**
 * The template for displaying the header.
 *
 * @package Dokanee
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

<body <?php dokanee_body_schema();?> <?php body_class( 'woocommerce' ); ?>>
	<?php
	/**
	 * dokanee_before_header hook.
	 *
	 * @since 0.1
	 *
	 * @hooked dokanee_do_skip_to_content_link - 2
	 * @hooked dokanee_top_bar - 5
	 * @hooked dokanee_add_navigation_before_header - 5
	 */
	do_action( 'dokanee_before_header' );

	/**
	 * dokanee_header hook.
	 *
	 * @since 1.3.42
	 *
	 * @hooked dokanee_construct_header - 10
	 */
	do_action( 'dokanee_header' );

	/**
	 * dokanee_after_header hook.
	 *
	 * @since 0.1
	 *
	 * @hooked dokanee_featured_page_header - 10
	 */
	do_action( 'dokanee_after_header' );
	?>

	<div id="page" class="hfeed site grid-container container grid-parent">
		<div id="content" class="site-content">
			<?php
			/**
			 * dokanee_inside_container hook.
			 *
			 * @since 0.1
			 */
			do_action( 'dokanee_inside_container' );
