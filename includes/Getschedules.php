<?php

class DIVI_Getschedules extends DiviExtension {

	/**
	 * The gettext domain for the extension's translations.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $gettext_domain = 'divi-getschedules';

	/**
	 * The extension's WP Plugin name.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $name = 'getschedules';

	/**
	 * The extension's version
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $version = '2.0.0';

	/**
	 * DIVI_Getschedules constructor.
	 *
	 * @param string $name
	 * @param array  $args
	 */
	public function __construct( $name = 'getschedules', $args = array() ) {
		$this->plugin_dir     = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url = plugin_dir_url( $this->plugin_dir );

		parent::__construct( $name, $args );
	}
}

new DIVI_Getschedules;
