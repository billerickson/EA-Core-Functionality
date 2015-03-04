<?php
/**
 * Core Functionality Plugin
 * 
 * @package    CoreFunctionality
 * @since      1.0.0
 * @copyright  Copyright (c) 2014, Bill Erickson & Jared Atchison
 * @license    GPL-2.0+
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Share Count Class
 *
 */
final class EA_Share_Count {

	/**
	 * Instance of the class
	 *
	 * @since 1.0.0
	 * @var object
	 */
	private static $instance;

	/**
	 * Domain for accessing SharedCount API
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $domain = 'http://free.sharedcount.com';
	
	/**
	 * API Key for SharedCount
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $api_key = 'xxx';
	
	/** 
	 * Share Count Instance
	 *
	 * @since 1.0.0
	 * @return EA_Share_Count
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof EA_Share_Count ) ) {
			self::$instance = new EA_Share_Count;
		}
		return self::$instance;
	}
	
	/**
	 * Get Post Share Count
	 *
	 * @since 1.0.0
	 * @param int $post_id
	 * @return object $share_count
	 */
	public static function post_share_count( $post_id = false ) {

		$post_id = $post_id ? $post_id : get_the_ID();
		
		$share_count  = ea_cf( 'ea_share_count', $post_id );
		$last_updated = ea_cf( 'ea_share_count_datetime', $post_id );
	
		// Rebuild and update meta if necessary
		if( ! $share_count || ! $last_updated || self::$instance->needs_updating( $last_updated ) ) {

			$share_count = self::$instance->query_api( get_permalink( $post_id ) );
			if( $share_count ) {
				update_post_meta( $post_id, 'ea_share_count', $share_count );
				update_post_meta( $post_id, 'ea_share_count_datetime', time() );
				return $share_count;
			}
		
		}
		
		return $share_count;

	}

	
	/**
	 * Site Share Count
	 *
	 * @since 1.0.0
	 * @return object $share_count
	 */
	public static function site_share_count() {

		$share_count  = get_option( 'ea_share_count' );
		$last_updated = get_option( 'ea_share_count_datetime' );
		
		if( ! $share_count || ! $last_updated || self::$instance->needs_updating( $last_updated, 1 ) ) {
			$share_count = self::$instance->query_api( home_url() );
			if( $share_count ) {
				update_option( 'ea_share_count', $share_count );
				update_option( 'ea_share_count_datetime', time() );
			}
		}
		
		return $share_count;
	}

	/**
	 * Check if share count needs updating
	 *
	 * @since 1.0.0
	 * @param int $last_updated, unix timestamp
	 * @param int $post_date, unix timestamp
	 * @return bool $needs_updating
	 */
	function needs_updating( $last_updated = false, $post_date = false ) {
	
		if( ! $last_updated )
			return true;
			
		if( ! $post_date )
			$post_date = get_the_date( 'U', $post_id );
	
		$update_increments = array(
			array(
				'post_date' => strtotime( '-1 day' ),
				'increment' => strtotime( '-30 minutes'),
			),
			array(
				'post_date' => strtotime( '-5 days' ),
				'increment' => strtotime( '-6 hours' )
			),
			array(
				'post_date' => 0,
				'increment' => strtotime( '-2 days' ),
			)
		);
		
		$increment = false;
		foreach( $update_increments as $i ) {
			if( $post_date > $i['post_date'] ) {
				$increment = $i['increment'];
				break;
			}
		}
		
		return $last_updated < $increment;
			
	}

	/**
	 * Query the SharedCount API
	 *
	 * @since 1.0.0
	 * @param string $url
	 * @return object $share_count
	 */
	function query_api( $url = false ) {
	
		if( ! $url )
			return;
			
		// For filtering during development
		$url = apply_filters( 'ea_share_count_url', $url );
		
		$query = add_query_arg( array( 'url' => $url, 'apikey' => self::$instance->api_key ), self::$instance->domain . '/url' );
		$results = wp_remote_get( $query );
		if( 200 == $results['response']['code'] )
			return $results['body'];
		else
			return false;
	}

}

/**
 * Get Post Share Count
 *
 * @since 1.0.0
 * @param int $post_id
 * @return object $share_count
 */
function ea_post_share_count( $post_id = false ) {
	return EA_Share_Count::instance()->post_share_count( $post_id );
}

/**
 * Get Site Share Count 
 *
 * @since 1.0.0
 * @return object $share_count
 */
function ea_site_share_count() {
	return EA_Share_Count::instance()->site_share_count();
}