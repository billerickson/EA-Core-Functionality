<?php
/**
 * Core Functionality Plugin
 * 
 * @package    CoreFunctionality
 * @since      1.2.0
 * @copyright  Copyright (c) 2014, Bill Erickson & Jared Atchison
 * @license    GPL-2.0+
 */

/**
 * Disable Inactive Plugins Nag on Synthesis
 *
 * @since 1.2.0
 */
function ea_disable_inactive_plugins_nag() {
	if ( method_exists( 'Synthesis_Software_Monitor', 'inactive_plugin_notifications' ) )
		remove_action( 'admin_notices', array( 'Synthesis_Software_Monitor', 'inactive_plugin_notifications' ) );
} 
add_action( 'plugins_loaded', 'ea_disable_inactive_plugins_nag' );

/**
 * Disable Scribe
 *
 * @since 1.2.0
 */
function ea_disable_scribe() {
	class Scribe_SEO {}
}
add_action( 'plugins_loaded', 'ea_disable_scribe', 4 );

/**
 * Allow Core Updates
 *
 * @since 1.2.0
 */
function ea_enable_core_updates() {
	remove_filter( 'pre_site_transient_update_core', 'ra_no_update_check_30' );
}
add_action( 'plugins_loaded', 'ea_enable_core_updates' );