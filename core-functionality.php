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

// Plugin basename, directory, and url.
define( 'EA_BASE', plugin_basename( __FILE__ ) );
define( 'EA_DIR' , plugin_dir_path( __FILE__ ) );
define( 'EA_URL' , plugin_dir_url(  __FILE__ ) );
 
// Includes
require_once( EA_DIR . '/inc/general.php'              ); // General
require_once( EA_DIR . '/inc/wordpress-cleanup.php'    ); // Misc WP cleanup
require_once( EA_DIR . '/inc/editor-style-refresh.php' ); // Force editor refresh
//require_once( EA_DIR . '/inc/cpt-testimonial.php'      ); // CPT functionality
//require_once( EA_DIR . '/inc/cpt-columns.php'          ); // CPT column tweaks
//require_once( EA_DIR . '/inc/widget.php'               ); // Widget template
//require_once( EA_DIR . '/inc/synthesis.php'            ); // WebSynthesis tweaks