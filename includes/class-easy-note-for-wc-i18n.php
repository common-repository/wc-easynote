<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://bigbenteam.com
 * @since      1.0.0
 *
 * @package    Easy_Note_For_WC
 * @subpackage Easy_Note_For_WC/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Easy_Note_For_WC
 * @subpackage Easy_Note_For_WC/includes
 * @author     Bigben Team <hello@bigbenteam.com>
 */
class Easy_Note_For_WC_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'easy-note-for-wc',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
