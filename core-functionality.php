<?php
/**
 * Plugin Name: Core Functionality
 * Description: This contains all your site's core functionality so that it is theme independent. <strong>It should always be activated</strong>.
 * Version:     1.2.0
 * Author:      Bill Erickson & Jared Atchison
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 2, as published by the
 * Free Software Foundation.  You may NOT assume that you can use any other
 * version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.
 *
 * @package    CoreFunctionality
 * @since      1.0.0
 * @copyright  Copyright (c) 2014, Bill Erickson & Jared Atchison
 * @license    GPL-2.0+
 */

// Plugin directory
define( 'EA_DIR' , plugin_dir_path( __FILE__ ) );

// Developer Tools, can be removed in Production
require_once( EA_DIR . '/inc/dev-tools.php'            ); // Developer tools

// Site Functionality
require_once( EA_DIR . '/inc/general.php'              ); // General
require_once( EA_DIR . '/inc/wordpress-cleanup.php'    ); // Misc WP cleanup
require_once( EA_DIR . '/inc/kill-trackbacks.php'      ); // Kill trackbacks
require_ocne( EA_DIR . '/inc/custom-fields-helper.php' ); // Custom fields helper
//require_once( EA_DIR . '/inc/cpt-testimonial.php'      ); // CPT functionality
//require_once( EA_DIR . '/inc/widget.php'               ); // Widget template

/**
 * Load Custom Fields
 *
 */
function ea_load_custom_fields() {
	require_once( EA_DIR . '/inc/custom-fields.php' );
}
add_action( 'carbon_register_fields', 'ea_load_custom_fields' );
