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
 * Testimonials
 *
 * This file registers the testimonials custom post type
 * and setups the various functions and items it uses.
 *
 * @since 1.2.0
 */
class ea_testimonials {

	static $instance;

	/**
	 * Initialize all the things
	 *
	 * @since 1.2.0
	 */
	function __construct() {
		
		self::$instance =& $this;
		
		// Actions
		add_action( 'init',       array( $this, 'register_tax'      )    );
		add_action( 'init',       array( $this, 'register_cpt'      )    );
		add_action( 'gettext',    array( $this, 'title_placeholder' )    );
	}

	/**
	 * Register the taxonomies
	 *
	 * @since 1.2.0
	 */
	function register_tax() {

		$labels = array( 
			'name'                       => 'FOO',
			'singular_name'              => 'FOO',
			'search_items'               => 'Search FOOs',
			'popular_items'              => 'Popular FOOs',
			'all_items'                  => 'All FOOs',
			'parent_item'                => 'Parent FOO',
			'parent_item_colon'          => 'Parent FOO:',
			'edit_item'                  => 'Edit FOO',
			'update_item'                => 'Update FOO',
			'add_new_item'               => 'Add New FOO',
			'new_item_name'              => 'New FOO',
			'separate_items_with_commas' => 'Separate FOOs with commas',
			'add_or_remove_items'        => 'Add or remove FOOs',
			'choose_from_most_used'      => 'Choose from most used FOOs',
			'menu_name'                  => 'FOOs',
		);

		$args = array( 
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => false,
			'hierarchical'      => false,
			'rewrite'           => array( 'slug' => 'cpt-slug/foo', 'with_front' => false ),
			'query_var'         => true,
			'show_admin_column' => false,
			// 'meta_box_cb'    => false,
		);

		register_taxonomy( 'foo', array( 'testimonials' ), $args );
	}

	/**
	 * Register the custom post type
	 *
	 * @since 1.2.0
	 */
	function register_cpt() {

		$labels = array( 
			'name'               => 'Testimonials',
			'singular_name'      => 'Testimonial',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Testimonial',
			'edit_item'          => 'Edit Testimonial',
			'new_item'           => 'New Testimonial',
			'view_item'          => 'View Testimonial',
			'search_items'       => 'Search Testimonials',
			'not_found'          => 'No Testimonials found',
			'not_found_in_trash' => 'No Testimonials found in Trash',
			'parent_item_colon'  => 'Parent Testimonial:',
			'menu_name'          => 'Testimonials',
		);

		$args = array( 
			'labels'              => $labels,
			'hierarchical'        => false,
			'supports'            => array( 'title', 'editor' ),   
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => array( 'slug' => 'testimonials' ),
			'menu_icon'           => 'dashicons-group',
		);

		register_post_type( 'testimonial', $args );

	}

	/**
	 * Change the default title placeholder text
	 *
	 * @since 1.2.0
	 * @global array $post
	 * @param string $translation
	 * @return string Customized translation for title
	 */
	function title_placeholder( $translation ) {

		global $post;
		if ( isset( $post ) ) {
			switch( $post->post_type ){
				case 'testimonials' :
					if ( $translation == 'Enter title here' ) return 'Enter Name Here';
					break;
			}
		}
		return $translation;

	}
	
}
new ea_testimonials();