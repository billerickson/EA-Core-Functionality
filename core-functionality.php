<?php
/**
 * Plugin Name: Core Functionality
 * Plugin URI: https://github.com/billerickson/Core-Functionality
 * Description: This contains all your site's core functionality so that it is theme independent.
 * Version: 1.1.0
 * Author: Bill Erickson
 * Author URI: http://www.billerickson.net
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 */

// Plugin Directory 
define( 'BE_DIR', dirname( __FILE__ ) );
 
// General
include_once( BE_DIR . '/inc/general.php' );

// Editor Style Refresh
include_once( BE_DIR . '/inc/editor-style-refresh.php' );

// Post Types
//include_once( BE_DIR . '/inc/post-types.php' );

// Taxonomies 
//include_once( BE_DIR . '/inc/taxonomies.php' );

// Widgets
//include_once( BE_DIR . '/inc/widget-social.php' );

// Image Resize
//include_once( BE_DIR . '/inc/image-resize.php' );

// Nav Menu Dropdown
//include_once( BE_DIR . '/inc/nav-menu-dropdown.php' );
