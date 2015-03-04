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
 * Shortcut function for get_post_meta();
 *
 * @since 1.2.0
 * @param string $key
 * @param int $id
 * @param boolean $echo
 * @param string $prepend
 * @param string $append
 * @param string $escape
 * @return string
 */
function ea_cf( $key = '', $id = '', $echo = false, $prepend = false, $append = false, $escape = false ) {
	$id    = ( empty( $id ) ? get_the_ID() : $id );
	$value = get_post_meta( $id, $key, true );
	if( $escape )
		$value = call_user_func( $escape, $value );
	if( $value && $prepend )
		$value = $prepend . $value;
	if( $value && $append )
		$value .= $append;
		
	if ( $echo ) {
		echo $value;
	} else {
		return $value;
	}
}

/**
 * Get the first term attached to post
 *
 * @param string $taxonomy
 * @param string/int $field, pass false to return object
 * @param int $post_id
 * @return string/object
 */
function ea_first_term( $taxonomy = 'category', $field = 'name', $post_id = false ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$terms = get_the_terms( $post_id, $taxonomy );
	if( empty( $terms ) || is_wp_error( $terms ) )
		return false;
	$term = array_shift( $terms );
		
	if( $field && isset( $term->$field ) )
		return $term->$field;
	
	else
		return $term;
}

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
	if ( ! function_exists( 'ea_is_developer' ) || ea_is_developer() == false ) {
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
