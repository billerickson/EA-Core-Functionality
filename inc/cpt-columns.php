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
 * Customize the columns show for the CPTs we create.
 *
 * @since 1.2.0
 */
class ea_cpt_columns {

	static $instance;

	/**
	 * Initialize all the things
	 *
	 * @since 1.2.0
	 */
	function __construct() {
		
		self::$instance =& $this;
		
		// Filters
		add_filter( 'manage_edit-testimonial_columns', array( $this, 'testimonial_columns' )        );

		// Actions
		add_action( 'manage_pages_custom_column',      array( $this, 'custom_columns'      ), 10, 2 );
		add_action( 'manage_posts_custom_column',      array( $this, 'custom_columns'      ), 10, 2 );
	}

	/**
	 * Testimonials custom columns
	 *
	 * @since 1.2.0
	 * @param array $columns
	 */
	function testimonial_columns( $columns ) {

		$columns = array(
			'cb'                  => '<input type="checkbox" />',
			'title'               => 'Name',
			'testimonial_byline'  => 'Byline',
			'testimonial_email'   => 'Email',
			'testimonial_website' => 'Website',
			'date'                => 'Date',
		);

		return $columns;
	}

	/**
	 * Cases for the custom columns
	 *
	 * @since 1.2.0
	 * @param array $column
	 * @param int $post_id
	 * @global array $post
	 */
	function custom_columns( $column, $post_id ) {

		global $post;

		switch ( $column ) {
			case 'testimonial_byline':
				$testimonial = get_post_meta( $post_id, '_ja_testimonial', true );
				echo !empty( $testimonial['byline'] ) ? $testimonial['byline'] : '-';
				break;
			case 'testimonial_email':
				$testimonial = get_post_meta( $post_id, '_ja_testimonial', true );
				if ( !empty( $testimonial['email'] ) ) {
					echo get_avatar( $testimonial['email'], 16 ) . ' ' . $testimonial['email'];
				} else {
					echo '-';
				}
				break;
			case 'testimonial_website':
				$testimonial = get_post_meta( $post_id, '_ja_testimonial', true );
				echo !empty( $testimonial['website'] ) ? $testimonial['website'] : '-';
				break;
			case 'thumbnail':
				the_post_thumbnail( 'thumbnail' );
				break;
		}
	}

}
new ea_cpt_columns;