<?php
/**
 * Core Functionality Plugin
 * 
 * @package    CoreFunctionality
 * @since      1.0.0
 * @copyright  Copyright (c) 2014, Bill Erickson & Jared Atchison
 * @license    GPL-2.0+
 */


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
 * Hide ACF menu item from the admin menu
 *
 * @since 1.0.0
 */
function ea_hide_acf_admin_menu() {
	if ( ea_is_developer() == false ) {
		remove_menu_page( 'edit.php?post_type=acf' );
		remove_menu_page( 'edit.php?post_type=acf-field-group' );
	}
}
add_action( 'admin_menu', 'ea_hide_acf_admin_menu', 999 );

/**
 * ACF Options Page 
 *
 */
function ea_acf_options_page() {
    if( function_exists( 'acf_add_options_page' ) ) {
        acf_add_options_page( 'Site Options' );
    }
}
//add_action( 'init', 'ea_acf_options_page' );