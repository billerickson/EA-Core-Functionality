<?php
/**
 * Post Types
 *
 * This file registers any custom post types
 *
 * @package      CoreFunctionality
 * @since        1.0.0
 * @link         https://github.com/billerickson/Core-Functionality
 * @author       Bill Erickson <bill@billerickson.net>
 * @copyright    Copyright (c) 2011, Bill Erickson
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

/**
 * Create Rotator post type
 * 
 * @since 1.0.0
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function ea_register_events_post_type() {
	$labels = array(
		'name'               => 'Events',
		'singular_name'      => 'Event',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Event',
		'edit_item'          => 'Edit Event',
		'new_item'           => 'New Event',
		'view_item'          => 'View Event',
		'search_items'       => 'Search Events',
		'not_found'          => 'No Events found',
		'not_found_in_trash' => 'No Events found in trash',
		'parent_item_colon'  => '',
		'menu_name'          => 'Events'
	);
	
	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true, 
		'show_in_menu'       => true, 
		'query_var'          => true,
		'rewrite'            => true,
		'capability_type'    => 'post',
		'has_archive'        => false, 
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title','thumbnail','editor' )
	); 

	register_post_type( 'event', $args );
}
add_action( 'init', 'ea_register_events_post_type' );