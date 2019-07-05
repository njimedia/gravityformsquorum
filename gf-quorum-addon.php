<?php
/**
 * Plugin Name:   Integration for Gravity Forms and Quorum
 * Plugin URI:
 * Description:   Add-on for Gravity Forms. Integrates Gravity Forms with Quorum public affairs database, allowing form submissions to update Quorum Supporter contacts.
 * Version:       1.0
 * Author:        NJI Media
 * Author URI:    https://www.njimedia.com
 * License:       GPL-2.0+
 * Text Domain:   gravityformsquorum
 *
 * @package       GravityForms
 * @subpackage    GF_Quorum_AddOn
 */

// Deny direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

// Define plugin constants.
define( 'GF_QUORUM_VERSION', '0.1' );

// Load the add-on bootstrapper.
require_once 'class-gfquorumbootstrap.php';

// Add-on plug-in does not explicitly depend on Gravity Forms. Instead, it is to
// wait for gform_loaded hook before initializing.
add_action( 'gform_loaded', array( 'GFQuorumBootstrap', 'load' ), 5 );
