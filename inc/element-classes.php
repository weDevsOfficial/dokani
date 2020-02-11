<?php
/**
 * Builds filterable classes throughout the theme.
 *
 * @package dokani
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'dokani_right_sidebar_class' ) ) {
	/**
	 * Display the classes for the sidebar.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 */
	function dokani_right_sidebar_class( $class = '' ) {
		// Separates classes with a single space, collates classes for post DIV
		echo 'class="' . join( ' ', dokani_get_right_sidebar_class( $class ) ) . '"'; // WPCS: XSS ok, sanitization ok.
	}
}

if ( ! function_exists( 'dokani_get_right_sidebar_class' ) ) {
	/**
	 * Retrieve the classes for the sidebar.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 * @return array Array of classes.
	 */
	function dokani_get_right_sidebar_class( $class = '' ) {

		$classes = array();

		if ( !empty($class) ) {
			if ( !is_array( $class ) )
				$class = preg_split('#\s+#', $class);
			$classes = array_merge($classes, $class);
		}

		$classes = array_map('esc_attr', $classes);

		return apply_filters('dokani_right_sidebar_class', $classes, $class);
	}
}

if ( ! function_exists( 'dokani_left_sidebar_class' ) ) {
	/**
	 * Display the classes for the sidebar.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 */
	function dokani_left_sidebar_class( $class = 'pull-75' ) {
		// Separates classes with a single space, collates classes for post DIV
		echo 'class="' . join( ' ', dokani_get_left_sidebar_class( $class ) ) . '"'; // WPCS: XSS ok, sanitization ok.
	}
}

if ( ! function_exists( 'dokani_get_left_sidebar_class' ) ) {
	/**
	 * Retrieve the classes for the sidebar.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 * @return array Array of classes.
	 */
	function dokani_get_left_sidebar_class( $class ) {

		$classes = array();

		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) )
				$class = preg_split('#\s+#', $class );
			$classes = array_merge ( $classes, $class );
		}

		$classes = array_map('esc_attr', $classes );

		return apply_filters( 'dokani_left_sidebar_class', $classes, $class );
	}
}

if ( ! function_exists( 'dokani_content_class' ) ) {
	/**
	 * Display the classes for the content.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 */
	function dokani_content_class( $class = '' ) {
		// Separates classes with a single space, collates classes for post DIV
		echo 'class="' . join( ' ', dokani_get_content_class( $class ) ) . '"'; // WPCS: XSS ok, sanitization ok.
	}
}

if ( ! function_exists( 'dokani_get_content_class' ) ) {
	/**
	 * Retrieve the classes for the content.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 * @return array Array of classes.
	 */
	function dokani_get_content_class( $class = '' ) {

		$classes = array();

		if ( !empty($class) ) {
			if ( !is_array( $class ) )
				$class = preg_split('#\s+#', $class);
			$classes = array_merge($classes, $class);
		}

		$classes = array_map('esc_attr', $classes);

		return apply_filters('dokani_content_class', $classes, $class);
	}
}

if ( ! function_exists( 'dokani_header_class' ) ) {
	/**
	 * Display the classes for the header.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 */
	function dokani_header_class( $class = '' ) {
		// Separates classes with a single space, collates classes for post DIV
		echo 'class="' . join( ' ', dokani_get_header_class( $class ) ) . '"'; // WPCS: XSS ok, sanitization ok.
	}
}

if ( ! function_exists( 'dokani_get_header_class' ) ) {
	/**
	 * Retrieve the classes for the content.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 * @return array Array of classes.
	 */
	function dokani_get_header_class( $class = '' ) {

		$classes = array();

		if ( !empty($class) ) {
			if ( !is_array( $class ) )
				$class = preg_split('#\s+#', $class);
			$classes = array_merge($classes, $class);
		}

		$classes = array_map('esc_attr', $classes);

		return apply_filters('dokani_header_class', $classes, $class);
	}
}

if ( ! function_exists( 'dokani_inside_header_class' ) ) {
	/**
	 * Display the classes for inside the header.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 */
	function dokani_inside_header_class( $class = '' ) {
		// Separates classes with a single space, collates classes for post DIV
		echo 'class="' . join( ' ', dokani_get_inside_header_class( $class ) ) . '"'; // WPCS: XSS ok, sanitization ok.
	}
}

if ( ! function_exists( 'dokani_get_inside_header_class' ) ) {
	/**
	 * Retrieve the classes for inside the header.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 * @return array Array of classes.
	 */
	function dokani_get_inside_header_class( $class = '' ) {

		$classes = array();

		if ( !empty($class) ) {
			if ( !is_array( $class ) )
				$class = preg_split('#\s+#', $class);
			$classes = array_merge($classes, $class);
		}

		$classes = array_map('esc_attr', $classes);

		return apply_filters('dokani_inside_header_class', $classes, $class);
	}
}

if ( ! function_exists( 'dokani_container_class' ) ) {
	/**
	 * Display the classes for the container.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 */
	function dokani_container_class( $class = '' ) {
		// Separates classes with a single space, collates classes for post DIV
		echo 'class="' . join( ' ', dokani_get_container_class( $class ) ) . '"'; // WPCS: XSS ok, sanitization ok.
	}
}

if ( ! function_exists( 'dokani_get_container_class' ) ) {
	/**
	 * Retrieve the classes for the content.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 * @return array Array of classes.
	 */
	function dokani_get_container_class( $class = '' ) {

		$classes = array();

		if ( !empty($class) ) {
			if ( !is_array( $class ) )
				$class = preg_split('#\s+#', $class);
			$classes = array_merge($classes, $class);
		}

		$classes = array_map('esc_attr', $classes);

		return apply_filters('dokani_container_class', $classes, $class);
	}
}

if ( ! function_exists( 'dokani_navigation_class' ) ) {
	/**
	 * Display the classes for the navigation.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 */
	function dokani_navigation_class( $class = '' ) {
		// Separates classes with a single space, collates classes for post DIV
		echo 'class="' . join( ' ', dokani_get_navigation_class( $class ) ) . '"'; // WPCS: XSS ok, sanitization ok.
	}
}

if ( ! function_exists( 'dokani_get_navigation_class' ) ) {
	/**
	 * Retrieve the classes for the navigation.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 * @return array Array of classes.
	 */
	function dokani_get_navigation_class( $class = '' ) {

		$classes = array();

		if ( !empty($class) ) {
			if ( !is_array( $class ) )
				$class = preg_split('#\s+#', $class);
			$classes = array_merge($classes, $class);
		}

		$classes = array_map('esc_attr', $classes);

		return apply_filters('dokani_navigation_class', $classes, $class);
	}
}

if ( ! function_exists( 'dokani_inside_navigation_class' ) ) {
	/**
	 * Display the classes for the inner navigation.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 */
	function dokani_inside_navigation_class( $class = '' ) {
		$classes = array();

		if ( !empty($class) ) {
			if ( !is_array( $class ) )
				$class = preg_split('#\s+#', $class);
			$classes = array_merge($classes, $class);
		}

		$classes = array_map('esc_attr', $classes);

		$return = apply_filters('dokani_inside_navigation_class', $classes, $class);

		// Separates classes with a single space, collates classes for post DIV
		echo 'class="' . join( ' ', $return ) . '"'; // WPCS: XSS ok, sanitization ok.
	}
}

if ( ! function_exists( 'dokani_menu_class' ) ) {
	/**
	 * Display the classes for the navigation.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 */
	function dokani_menu_class( $class = '' ) {
		// Separates classes with a single space, collates classes for post DIV
		echo 'class="' . join( ' ', dokani_get_menu_class( $class ) ) . '"'; // WPCS: XSS ok, sanitization ok.
	}
}

if ( ! function_exists( 'dokani_get_menu_class' ) ) {
	/**
	 * Retrieve the classes for the navigation.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 * @return array Array of classes.
	 */
	function dokani_get_menu_class( $class = '' ) {

		$classes = array();

		if ( !empty($class) ) {
			if ( !is_array( $class ) )
				$class = preg_split('#\s+#', $class);
			$classes = array_merge($classes, $class);
		}

		$classes = array_map('esc_attr', $classes);

		return apply_filters('dokani_menu_class', $classes, $class);
	}
}

if ( ! function_exists( 'dokani_main_class' ) ) {
	/**
	 * Display the classes for the <main> container.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 */
	function dokani_main_class( $class = '' ) {
		// Separates classes with a single space, collates classes for post DIV
		echo 'class="' . join( ' ', dokani_get_main_class( $class ) ) . '"'; // WPCS: XSS ok, sanitization ok.
	}
}

if ( ! function_exists( 'dokani_get_main_class' ) ) {
	/**
	 * Retrieve the classes for the footer.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 * @return array Array of classes.
	 */
	function dokani_get_main_class( $class = '' ) {

		$classes = array();

		if ( !empty($class) ) {
			if ( !is_array( $class ) )
				$class = preg_split('#\s+#', $class);
			$classes = array_merge($classes, $class);
		}

		$classes = array_map('esc_attr', $classes);

		return apply_filters('dokani_main_class', $classes, $class);
	}
}

if ( ! function_exists( 'dokani_footer_class' ) ) {
	/**
	 * Display the classes for the footer.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 */
	function dokani_footer_class( $class = '' ) {
		// Separates classes with a single space, collates classes for post DIV
		echo 'class="' . join( ' ', dokani_get_footer_class( $class ) ) . '"'; // WPCS: XSS ok, sanitization ok.
	}
}

if ( ! function_exists( 'dokani_get_footer_class' ) ) {
	/**
	 * Retrieve the classes for the footer.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 * @return array Array of classes.
	 */
	function dokani_get_footer_class( $class = '' ) {

		$classes = array();

		if ( !empty($class) ) {
			if ( !is_array( $class ) )
				$class = preg_split('#\s+#', $class);
			$classes = array_merge($classes, $class);
		}

		$classes = array_map('esc_attr', $classes);

		return apply_filters('dokani_footer_class', $classes, $class);
	}
}

if ( ! function_exists( 'dokani_inside_footer_class' ) ) {
	/**
	 * Display the classes for the footer.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 */
	function dokani_inside_footer_class( $class = '' ) {
		$classes = array();

		if ( !empty($class) ) {
			if ( !is_array( $class ) )
				$class = preg_split('#\s+#', $class);
			$classes = array_merge($classes, $class);
		}

		$classes = array_map('esc_attr', $classes);

		$return = apply_filters( 'dokani_inside_footer_class', $classes, $class );

		// Separates classes with a single space, collates classes for post DIV
		echo 'class="' . join( ' ', $return ) . '"'; // WPCS: XSS ok, sanitization ok.
	}
}

if ( ! function_exists( 'dokani_top_bar_class' ) ) {
	/**
	 * Display the classes for the top bar.
	 *
	 * @since 1.0.0
	 * @param string|array $class One or more classes to add to the class list.
	 */
	function dokani_top_bar_class( $class = '' ) {
		$classes = array();

		if ( !empty($class) ) {
			if ( !is_array( $class ) )
				$class = preg_split('#\s+#', $class);
			$classes = array_merge($classes, $class);
		}

		$classes = array_map('esc_attr', $classes);

		$return = apply_filters( 'dokani_top_bar_class', $classes, $class );

		// Separates classes with a single space, collates classes for post DIV
		echo 'class="' . join( ' ', $return ) . '"'; // WPCS: XSS ok, sanitization ok.
	}
}
