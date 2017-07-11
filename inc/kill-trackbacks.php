<?php
/**
 * Kill Trackbacks
 *
 * @package      CoreFunctionality
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

/*
Plugin Name: Kill Trackbacks
Plugin URI: http://pmg.co/category/wordpress
Description: Kill all trackbacks on WordPress
Version: 1.0
Author: Christopher Davis
Author URI: http://pmg.co/people/chris
*/

add_filter( 'wp_headers', 'pmg_kt_filter_headers', 10, 1 );
function pmg_kt_filter_headers( $headers )
{
	if( isset( $headers['X-Pingback'] ) )
	{
		unset( $headers['X-Pingback'] );
	}
	return $headers;
}

// Kill the rewrite rule
add_filter( 'rewrite_rules_array', 'pmg_kt_filter_rewrites' );
function pmg_kt_filter_rewrites( $rules )
{
	foreach( $rules as $rule => $rewrite )
	{
		if( preg_match( '/trackback\/\?\$$/i', $rule ) )
		{
			unset( $rules[$rule] );
		}
	}
	return $rules;
}

// Kill bloginfo( 'pingback_url' )
add_filter( 'bloginfo_url', 'pmg_kt_kill_pingback_url', 10, 2 );
function pmg_kt_kill_pingback_url( $output, $show )
{
	if( $show == 'pingback_url' )
	{
		$output = '';
	}
	return $output;
}

// remove RSD link
remove_action( 'wp_head', 'rsd_link' );

// hijack options updating for XMLRPC
add_filter( 'pre_update_option_enable_xmlrpc', '__return_false' );
add_filter( 'pre_option_enable_xmlrpc', '__return_zero' );

// Disable XMLRPC call
add_action( 'xmlrpc_call', 'pmg_kt_kill_xmlrpc' );
function pmg_kt_kill_xmlrpc( $action )
{
	if( 'pingback.ping' === $action )
	{
		wp_die(
			'Pingbacks are not supported',
			'Not Allowed!',
			array( 'response' => 403 )
		);
	}
}
