<?php

/**
 * 
 *
 * @link              http://bigbenteam.com/
 * @since             1.0.0
 * @package           Easy_Note_For_WC
 *
 * @wordpress-plugin
 * Plugin Name:       Easy Note For WooCommerce
 * Plugin URI:        https://bigbenteam.com/easy-note-for-woocommerce/
 * Description:       Write notes for orders with this plugin. Sometimes you need to write a note or explanation for orders, you can do it easily using WooCommerce EasyNote.
 * Version:           1.0.0
 * Author:            Bigben Team
 * Author URI:        http://bigbenteam.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       Easy-Note-For-wc
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'EASY_NOTE_FOR_WC_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wc-easy-note-activator.php
 */
function activate_easy_note_for_wc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-easy-note-for-wc-activator.php';
	Easy_Note_For_WC_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wc-easy-note-deactivator.php
 */
function deactivate_easy_note_for_wc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-easy-note-for-wc-deactivator.php';
	Easy_Note_For_WC_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_easy_note_for_wc' );
register_deactivation_hook( __FILE__, 'deactivate_easy_note_for_wc' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-easy-note-for-wc.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_easy_note_for_wc() {

	$plugin = new Easy_Note_For_WC();
	$plugin->run();

}
run_easy_note_for_wc();
