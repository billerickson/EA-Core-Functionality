<?php
/**
 * Core Functionality Plugin
 * 
 * @package    CoreFunctionality
 * @since      1.0.0
 * @copyright  Copyright (c) 2014, Bill Erickson & Jared Atchison
 * @license    GPL-2.0+
 */

// Don't let WPGA create yet another top level menu
add_filter( 'wpga_menu_on_top', '__return_false' );

// Don't let WPSEO metabox be high priority
add_filter( 'wpseo_metabox_prio', function(){ return 'low'; } );

/**
 * Gravity Forms Domain
 *
 * Adds a notice at the end of admin email notifications 
 * specifying the domain from which the email was sent.
 *
 * @param array $notification
 * @param object $form
 * @param object $entry
 * @return array $notification
 */
function ea_gravityforms_domain( $notification, $form, $entry ) {

	if( $notification['name'] == 'Admin Notification' ) {
		$notification['message'] .= 'Sent from ' . home_url();
	}

	return $notification;
}
add_filter( 'gform_notification', 'ea_gravityforms_domain', 10, 3 );

/**
 * Prevent ACF access site-wide for non-developers.
 *
 */
function ea_prevent_acf_access() {
	if ( function_exists( 'ea_is_developer' ) && ea_is_developer() ) {
		return 'manage_options';
	}
	return false;
}
add_filter ('acf/settings/capability', 'ea_prevent_acf_access' );

/**
 * ACF Options Page 
 *
 */
function ea_acf_options_page() {
    if ( function_exists( 'acf_add_options_page' ) ) {
        acf_add_options_page( 'Site Options' );
    }
    if ( function_exists( 'acf_add_options_sub_page' ) ){
 		 acf_add_options_sub_page( array(
			'title'      => 'CPT Settings',
			'parent'     => 'edit.php?post_type=CPT_slug',
			'capability' => 'manage_options'
		) );
 	}
}
//add_action( 'init', 'ea_acf_options_page' );

 /**
 * Dont Update the Plugin
 * If there is a plugin in the repo with the same name, this prevents WP from prompting an update.
 *
 * @since  1.0.0
 * @author Jon Brown
 * @param  array $r Existing request arguments
 * @param  string $url Request URL
 * @return array Amended request arguments
 */
function ea_dont_update_core_func_plugin( $r, $url ) {
  if ( 0 !== strpos( $url, 'https://api.wordpress.org/plugins/update-check/1.1/' ) )
    return $r; // Not a plugin update request. Bail immediately.
    $plugins = json_decode( $r['body']['plugins'], true );
    unset( $plugins['plugins'][plugin_basename( __FILE__ )] );
    $r['body']['plugins'] = json_encode( $plugins );
    return $r;
 }
add_filter( 'http_request_args', 'ea_dont_update_core_func_plugin', 5, 2 );

/**
 * Author Links on CF Plugin
 *
 */
function ea_author_links_on_cf_plugin( $links, $file ) {

	if ( strpos( $file, 'core-functionality.php' ) !== false ) {
		$links[1] = 'By <a href="http://www.billerickson.net">Bill Erickson</a> & <a href="http://www.jaredatchison.com">Jared Atchison</a>';
    }
    
    return $links;
}
add_filter( 'plugin_row_meta', 'ea_author_links_on_cf_plugin', 10, 2 );