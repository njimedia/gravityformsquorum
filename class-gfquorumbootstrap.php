<?php
/**
 * Gravity_Forms Quorum Add-On Bootstrapper
 *
 * @package       GravityForms
 * @subpackage    GravityForms_Quorum_AddOn
 */

// Deny direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Registration wtih the add-on framework.
 */
class GFQuorumBootstrap {

	/**
	 * Load up and register the add-on.
	 */
	public static function load() {

		// Bail if the add-on framework isn't available.
		if ( ! method_exists( 'GFForms', 'include_feed_addon_framework' ) ) {
			return;
		}

		// Make the add-on framework available to be extended.
		GFForms::include_feed_addon_framework();

		// Include the main add-on class.
		require_once 'class-gfquorum.php';

		// Register the add-on.
		GFAddOn::register( 'GFQuorum' );
	}

}
