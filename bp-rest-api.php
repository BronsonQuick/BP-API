<?php
/**
 * Plugin Name: BuddyPress REST API
 * Plugin URI: https://github.com/BronsonQuick/BP-API
 * Description: This plugin extends the WP JSON API to add endpoints for BuddyPress
 * Author: Bronson Quick
 * Version: 0.1
 * Author URI: https://github.com/BronsonQuick/BP-API
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register our new endpoints.
 */
function bp_rest_endpoints() {

	// Check is BuddyPress is active.
	if ( class_exists( 'BuddyPress' ) ) {

		// If the actvity component is loaded bring in the activity schema.
		if ( bp_is_active( 'activity' ) ) {
			/**
			 * BP_REST_Activity_Controller class.
			 */
			include_once( __DIR__ . '/lib/endpoints/class-bp-rest-activity-controller.php' );
			$GLOBALS['bp_rest_activity_controller'] = $activity = new BP_REST_Activity_Controller();
			$activity->register_routes();
		}
	}

}

add_action( 'rest_api_init', 'bp_rest_endpoints' );
