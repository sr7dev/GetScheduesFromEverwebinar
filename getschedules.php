<?php
/*
Plugin Name: Get EW Schedules
Plugin URI:  
Description: Get EverWebinar Schedules
Version:     1.0.0
Author:      Armels
Author URI:  
License:     
License URI: 
Text Domain: divi-get-ew-schedules
Domain Path: /languages
*/


if ( ! function_exists( 'divi_initialize_extension' ) ):
/**
 * Creates the extension's main class instance.
 *
 * @since 2.0.0
 */
function divi_initialize_extension() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/Getschedules.php';
}
add_action( 'divi_extensions_init', 'divi_initialize_extension' );
endif;
