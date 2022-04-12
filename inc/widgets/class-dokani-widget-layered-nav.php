<?php
/**
 * Custom output for WooCommerce attribute filter widgets
 *
 * @package WordPress
 * @subpackage Promo Printing
 * @since Promo Printing 1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Dokani_Widget_Layered_Navphpcs
 */
class Dokani_Widget_Layered_Nav extends WC_Widget {

	/**
	 * Constructor
	 * Customise classes added to all instances, ID, backend label, backend description
	 */
	public function __construct() {
		$this->widget_cssclass    = 'woocommerce widget_layered_nav woocommerce-widget-layered-nav dokani-widget-layered-nav placeholder-class';
		$this->widget_description = __( 'Display a list of attributes to only color filter products in your store.', 'dokani' );
		$this->widget_id          = 'dokani_layered_nav';
		$this->widget_name        = __( 'Dokani: Color Filter' );

		parent::__construct( $this->widget_id, $this->widget_name );
	}

	/**
	 * Output the html at the start of a widget.
	 * Replaces placeholder-class with the relevant attribute from $instance
	 *
	 * @param array $args Arguments.
	 * @param array $instance Instance.
	 */
	public function widget_start( $args, $instance ) {

		$attribute = 'widget_layered_nav--' . $instance['attribute'];

		$title = isset( $instance['title'] ) ? $instance['title'] : '';

		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$args['before_widget'] = str_replace( 'placeholder-class', $attribute, $args['before_widget'] );

		echo $args['before_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped

		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}
	}

	/**
	 * Updates a particular instance of a widget.
	 *
	 * @see WP_Widget->update
	 *
	 * @param array $new_instance New Instance.
	 * @param array $old_instance Old Instance.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$this->init_settings();
		return parent::update( $new_instance, $old_instance );
	}

	/**
	 * Outputs the settings update form.
	 *
	 * @see WP_Widget->form
	 *
	 * @param array $instance Instance.
	 */
	public function form( $instance ) {
		$this->init_settings();
		parent::form( $instance );
	}

	/**
	 * Init settings after post types are registered.
	 */
	public function init_settings() {
		$attribute_array      = array();
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		if ( ! empty( $attribute_taxonomies ) ) {
			foreach ( $attribute_taxonomies as $tax ) {
				if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
					$attribute_array[ $tax->attribute_name ] = $tax->attribute_name;
				}
			}
		}

		$this->settings = array(
			'title'        => array(
				'type'  => 'text',
				'std'   => __( 'Filter by', 'dokani' ),
				'label' => __( 'Title', 'dokani' ),
			),
			'attribute'    => array(
				'type'    => 'select',
				'std'     => '',
				'label'   => __( 'Attribute ( Only color filter )', 'dokani' ),
				'options' => $attribute_array,
			),
		);
	}

	/**
	 * Output widget.
	 *
	 * @see WP_Widget
	 *
	 * @param array $args Arguments.
	 * @param array $instance Instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! is_shop() && ! is_product_taxonomy() ) {
			return;
		}

		$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
		$taxonomy           = isset( $instance['attribute'] ) ? wc_attribute_taxonomy_name( $instance['attribute'] ) : $this->settings['attribute']['std'];

		if ( ! taxonomy_exists( $taxonomy ) ) {
			return;
		}

		$get_terms_args = array( 'hide_empty' => '1' );

		$terms = get_terms( $taxonomy, $get_terms_args );

		if ( 0 === count( $terms ) ) {
			return;
		}

		ob_start();

		$this->widget_start( $args, $instance );

		$found = $this->layered_nav_list( $terms, $taxonomy );

		$this->widget_end( $args );

		// Force found when option is selected - do not force found on taxonomy attributes.
		if ( ! is_tax() && is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) ) {
			$found = true;
		}

		if ( ! $found ) {
			ob_end_clean();
		} else {
			echo ob_get_clean(); // @codingStandardsIgnoreLine
		}
	}

	/**
	 * Return the currently viewed term ID.
	 *
	 * @return int
	 */
	protected function get_current_term_id() {
		return absint( is_tax() ? get_queried_object()->term_id : 0 );
	}

	/**
	 * Return the currently viewed term slug.
	 *
	 * @return int
	 */
	protected function get_current_term_slug() {
		return absint( is_tax() ? get_queried_object()->slug : 0 );
	}

	/**
	 * Count products within certain terms, taking the main WP query into consideration.
	 *
	 * This query allows counts to be generated based on the viewed products, not all products.
	 *
	 * @param  array  $term_ids Term IDs.
	 * @param  string $taxonomy Taxonomy.
	 * @return array
	 */
	protected function get_filtered_term_product_counts( $term_ids, $taxonomy ) {
		global $wpdb;

		$tax_query  = WC_Query::get_main_tax_query();
		$meta_query = WC_Query::get_main_meta_query();

		$meta_query     = new WP_Meta_Query( $meta_query );
		$tax_query      = new WP_Tax_Query( $tax_query );
		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

		// Generate query.
		$query           = array();
		$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
		$query['from']   = "FROM {$wpdb->posts}";
		$query['join']   = "
		INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
		INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
		INNER JOIN {$wpdb->terms} AS terms USING( term_id )
		" . $tax_query_sql['join'] . $meta_query_sql['join'];

		$query['where'] = "
		WHERE {$wpdb->posts}.post_type IN ( 'product' )
		AND {$wpdb->posts}.post_status = 'publish'"
			. $tax_query_sql['where'] . $meta_query_sql['where'] .
			'AND terms.term_id IN (' . implode( ',', array_map( 'absint', $term_ids ) ) . ')';

		$search = WC_Query::get_main_search_query_sql();
		if ( $search ) {
			$query['where'] .= ' AND ' . $search;
		}

		$query['group_by'] = 'GROUP BY terms.term_id';
		$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
		$query             = implode( ' ', $query );

		// We have a query - let's see if cached results of this query already exist.
		$query_hash = md5( $query );

		// Maybe store a transient of the count values.
		$cache = apply_filters( 'woocommerce_layered_nav_count_maybe_cache', true );
		if ( true === $cache ) {
			$cached_counts = (array) get_transient( 'wc_layered_nav_counts_' . $taxonomy );
		} else {
			$cached_counts = array();
		}

		if ( ! isset( $cached_counts[ $query_hash ] ) ) {
			$results                      = $wpdb->get_results( $query, ARRAY_A ); // @codingStandardsIgnoreLine
			$counts                       = array_map( 'absint', wp_list_pluck( $results, 'term_count', 'term_count_id' ) );
			$cached_counts[ $query_hash ] = $counts;
			if ( true === $cache ) {
				set_transient( 'wc_layered_nav_counts_' . $taxonomy, $cached_counts, DAY_IN_SECONDS );
			}
		}

		return array_map( 'absint', (array) $cached_counts[ $query_hash ] );
	}

	/**
	 * Show list based layered nav.
	 *
	 * @param  array  $terms Terms.
	 * @param  string $taxonomy Taxonomy.
	 * @return bool   Will nav display?
	 */
	protected function layered_nav_list( $terms, $taxonomy ) {

		// List display.
		echo '<ul class="woocommerce-widget-layered-nav-list dokani__color-filter">';

		$term_counts        = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy );
		$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
		$found              = false;

		foreach ( $terms as $term ) {
			$current_values = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
			$option_is_set  = in_array( $term->slug, $current_values, true );
			$count          = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

			// Skip the term for the current archive.
			if ( $this->get_current_term_id() === $term->term_id ) {
				continue;
			}

			// Only show options with count > 0.
			if ( 0 < $count ) {
				$found = true;
			} elseif ( 0 === $count && ! $option_is_set ) {
				continue;
			}

			$filter_name    = 'filter_' . sanitize_key( str_replace( 'pa_', '', $taxonomy ) );
			$current_filter = isset( $_GET[ $filter_name ] ) && 'pa_brand' !== $taxonomy ? explode( ',', wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) ) : array();
			$current_filter = array_map( 'sanitize_title', $current_filter );

			if ( ! in_array( $term->slug, $current_filter, true ) ) {
				$current_filter[] = $term->slug;
			}

			$link = remove_query_arg( $filter_name, $this->get_current_page_url() );

			// Add current filters to URL.
			foreach ( $current_filter as $key => $value ) {
				// Exclude query arg for current term archive term.
				if ( $value === $this->get_current_term_slug() ) {
					unset( $current_filter[ $key ] );
				}

				// Exclude self so filter can be unset on click.
				if ( $option_is_set && $value === $term->slug ) {
					unset( $current_filter[ $key ] );
				}
			}

			if ( ! empty( $current_filter ) ) {
				asort( $current_filter );
				$link = add_query_arg( $filter_name, implode( ',', $current_filter ), $link );

			}

			if ( $count > 0 || $option_is_set ) {
				$link      = esc_url( apply_filters( 'woocommerce_layered_nav_link', $link, $term, $taxonomy ) );
				$term_html = '<a rel="nofollow" style="background: ' . get_term_meta( $term->term_id, 'dokani_theme_pa_color', true ) . ' " href="' . $link . '">' . esc_html( $term->name ) . '</a>';
			} else {
				$link      = false;
				$term_html = '<span>' . esc_html( $term->name ) . '</span>';
			}

			echo '<li class="woocommerce-widget-layered-nav-list__item wc-layered-nav-term--' . esc_attr( $term->slug ) . ' wc-layered-nav-tax--' . esc_attr( $taxonomy ) . ' ' . ( $option_is_set ? 'woocommerce-widget-layered-nav-list__item--chosen chosen' : '' ) . '">';
			echo wp_kses_post( apply_filters( 'woocommerce_layered_nav_term_html', $term_html, $term, $link, $count ) );
			echo '</li>';
		}

		echo '</ul>';

		return $found;
	}
}

// Register widget overrides.
register_widget( 'Dokani_Widget_Layered_Nav' );
