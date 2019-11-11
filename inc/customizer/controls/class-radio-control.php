<?php

/**
 * Radio image customize control.
 *
 * @since  1.0.0
 */
class Dokanee_Customize_Control_Radio_Image extends WP_Customize_Control {

	/**
	 * The type of customize control being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'radio-image';

	/**
	 * Loads the jQuery UI Button script and custom scripts/styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue() {
		wp_enqueue_script( 'jquery-ui-button' );
		wp_enqueue_script( 'dokanee-customize-radio-controls-script', trailingslashit( get_template_directory_uri() ) . 'inc/customizer/controls/js/radio-control.js', array( 'jquery' ) );
		wp_enqueue_style(  'dokanee-customize-radio-controls-style', trailingslashit( get_template_directory_uri() ) . 'inc/customizer/controls/css/radio-control.css' );
	}

	/**
	 * Add custom JSON parameters to use in the JS template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		// We need to make sure we have the correct image URL.
		foreach ( $this->choices as $value => $args )
			$this->choices[ $value ]['url'] = esc_url( sprintf( $args['url'], get_template_directory_uri(), get_stylesheet_directory_uri() ) );

		$this->json['choices'] = $this->choices;
		$this->json['link']    = $this->get_link();
		$this->json['value']   = $this->value();
		$this->json['id']      = $this->id;
	}

	/**
	 * Underscore JS template to handle the control's output.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function content_template() { ?>

		<# if ( ! data.choices ) {
		return;
		} #>

		<# if ( data.label ) { #>
		<span class="customize-control-title">{{ data.label }}</span>
		<# } #>

		<# if ( data.description ) { #>
		<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<div class="buttonset">

			<# for ( key in data.choices ) { #>

			<input type="radio" value="{{ key }}" name="_customize-{{ data.type }}-{{ data.id }}" id="{{ data.id }}-{{ key }}" {{{ data.link }}} <# if ( key === data.value ) { #> checked="checked" <# } #> />

			<label for="{{ data.id }}-{{ key }}">
				<span class="screen-reader-text">{{ data.choices[ key ]['label'] }}</span>
				<img src="{{ data.choices[ key ]['url'] }}" alt="{{ data.choices[ key ]['label'] }}" />
			</label>
			<# } #>

		</div><!-- .buttonset -->
	<?php }
}