<?php
/**
 * Core Functionality Plugin
 * 
 * @package    CoreFunctionality
 * @since      1.0.0
 * @copyright  Copyright (c) 2014, Bill Erickson & Jared Atchison
 * @license    GPL-2.0+
 */

if ( ! ( defined('WP_CLI') && WP_CLI ) )
	return;

class EA_Recent_DB extends WP_CLI_Command {

    /**
     * Displays most recent entries in wp_options table
     * 
     * ## OPTIONS
     * 
     * [--count=<count>]
     * : How many to show
     * 
     * ## EXAMPLES
     * 
     *     wp recent-db option --count=5
     *
     * @synopsis [--count=<count>]
     */
    function option( $args, $assoc_args ) {
        $count = isset( $assoc_args['count'] ) && !empty( $assoc_args['count'] ) ? (int) $assoc_args['count'] : 1;

		// Get options
		global $wpdb;
		$results = $wpdb->get_results( 
			$wpdb->prepare( 'SELECT * FROM wp_options ORDER BY option_id DESC LIMIT 0, %d', (int) $count ),
			OBJECT
		);
		if( $results ) {
			$output = '';
			foreach( $results as $result )
				$output .= '
				
				Option ID: ' . $result->option_id . '
				Option Name: ' . $result->option_name . '
				Option Value: ' . $result->option_value . '
				';
		} else {
			$output = 'No results found';
		}
		
        // Print Results
        WP_CLI::success( $output );
    }

    /**
     * Displays most recent entries in wp_postmeta table
     * 
     * ## OPTIONS
     * 
     * [--count=<count>]
     * : How many to show
     *
     * [--post_id=<post_id>]
     * : Limit to meta associated with this post
     * 
     * ## EXAMPLES
     * 
     *     wp recent-db postmeta --count=5 --post_id=2
     *
     * @synopsis [--count=<count>] [--post_id=<post_id>]
     */
    function postmeta( $args, $assoc_args ) {
        $count = isset( $assoc_args['count'] ) && !empty( $assoc_args['count'] ) ? (int) $assoc_args['count'] : 1;
        $post_id = isset( $assoc_args['post_id'] ) && !empty( $assoc_args['post_id'] ) ? (int) $assoc_args['post_id'] : false;

		// Get post meta
		global $wpdb;
		if( $post_id ) {
			$results = $wpdb->get_results( 
				$wpdb->prepare( 'SELECT * FROM wp_postmeta WHERE post_id = %d ORDER BY meta_id DESC LIMIT 0, %d', $post_id, $count ),
				OBJECT
			);		
		} else {
			$results = $wpdb->get_results( 
				$wpdb->prepare( 'SELECT * FROM wp_postmeta ORDER BY meta_id DESC LIMIT 0, %d', $count ),
				OBJECT
			);		
		}
		if( $results ) {
			
			$output = '';
			foreach( $results as $result )
				$output .= '
				
				Meta ID: ' . $result->meta_id . '
				Post ID: ' . $result->post_id . '
				Meta Key: ' . $result->meta_key . '
				Meta Value: ' . $result->meta_value . '
				';
		} else {
			$output = 'No results found';
		}
		
        // Print Results
        WP_CLI::success( $output );
	}

}
WP_CLI::add_command( 'recent-db', 'EA_Recent_DB' );