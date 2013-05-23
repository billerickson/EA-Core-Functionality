<?php
/**
 * Plugin Name: Core Functionality
 * Plugin URI:  https://github.com/billerickson/Core-Functionality
 * Description: This contains all your site's core functionality so that it is theme independent. <strong>It should always be activated</strong>.
 * Version:     1.1.0
 * Author:      Bill Erickson & Jared Atchison
 * Author URI:  http://www.billerickson.net
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
 * @author     Bill Erickson & Jared Atchison
 * @version    1.1.0
 * @package    CoreFunctionality
 * @copyright  Copyright (c) 2012, Bill Erickson & Jared Atchison
 * @link       https://github.com/billerickson/Core-Functionality
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// Plugin Directory 
define( 'EA_DIR', dirname( __FILE__ ) );
 
// General
include_once( EA_DIR . '/inc/general.php' );

// WordPress Cleanup
include_once( EA_DIR . '/inc/wordpress-cleanup.php' );

// Editor Style Refresh
include_once( EA_DIR . '/inc/editor-style-refresh.php' );

// Post Types
//include_once( EA_DIR . '/inc/post-types.php' );

// Taxonomies 
//include_once( EA_DIR . '/inc/taxonomies.php' );

// Widgets
//include_once( EA_DIR . '/inc/widget-social.php' );

// Image Resize
//include_once( EA_DIR . '/inc/image-resize.php' );

// Nav Menu Dropdown
//include_once( EA_DIR . '/inc/nav-menu-dropdown.php' );
