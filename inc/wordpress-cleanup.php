<?php
/**
 * WordPress Cleanup
 *
 * This file registers any custom taxonomies
 *
 * @package      Core_Functionality
 * @since        1.0.0
 * @link         https://github.com/billerickson/Core-Functionality
 * @author       Bill Erickson <bill@billerickson.net>
 * @copyright    Copyright (c) 2011, Bill Erickson
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Disable Post Formats UI
add_filter( 'enable_post_format_ui', '__return_false' );

/**
 * Remove default WordPress widgets
 * 
 * @since 1.0
 */
function ea_remove_default_wp_widgets() {
    unregister_widget( 'WP_Widget_Pages'           );
    unregister_widget( 'WP_Widget_Calendar'        );
    unregister_widget( 'WP_Widget_Archives'        );
    unregister_widget( 'WP_Widget_Links'           );
    unregister_widget( 'WP_Widget_Meta'            );
    unregister_widget( 'WP_Widget_Search'          );
    unregister_widget( 'WP_Widget_Text'            );
    unregister_widget( 'WP_Widget_Categories'      );
    unregister_widget( 'WP_Widget_Recent_Posts'    );
    unregister_widget( 'WP_Widget_Recent_Comments' );
    unregister_widget( 'WP_Widget_RSS'             );
    unregister_widget( 'WP_Widget_Tag_Cloud'       );
}
//add_action( 'widgets_init', 'ja_remove_default_wp_widgets', 1 );

/**
 * Remove extra dashboard widgets
 *
 * @since 1.0
 */
function ea_remove_dashboard_widgets() {
    //remove_meta_box( 'dashboard_right_now',       'dashboard', 'core' ); // Right Now
    //remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'core' ); // Recent Comments
    remove_meta_box( 'dashboard_incoming_links',  'dashboard', 'core' );   // Incoming Links
    remove_meta_box( 'dashboard_plugins',         'dashboard', 'core' );   // Plugins
    //remove_meta_box( 'dashboard_quick_press',     'dashboard', 'core' );   // Quick Press
    remove_meta_box( 'dashboard_recent_drafts',   'dashboard', 'core' );   // Recent Drafts
    remove_meta_box( 'dashboard_primary',         'dashboard', 'core' );   // WordPress Blog
    remove_meta_box( 'dashboard_secondary',       'dashboard', 'core' );   // Other WordPress News
}
add_action( 'admin_menu', 'ja_remove_dashboard_widgets' );

/**
 * Remove admin menu items
 *
 * @since 1.0
 */
function ea_remove_admin_menus(){
    // remove_menu_page( 'edit.php'               ); // Posts
    // remove_menu_page( 'upload.php'             ); // Media
    // remove_menu_page( 'edit-comments.php'      ); // Comments
    // remove_menu_page( 'edit.php?post_type=page'); // Pages
    // remove_menu_page( 'plugins.php'            ); // Plugins
    // remove_menu_page( 'themes.php'             ); // Appearance
    // remove_menu_page( 'users.php'              ); // Users
    // remove_menu_page( 'tools.php'              ); // Tools
    // remove_menu_page( 'options-general.php'    ); // Settings
}
//add_action( 'admin_menu', 'ja_remove_admin_menus' );

/**
 * Remove admin bar items
 *
 * @since 1.0
 * @global array $wp_admin_bar
 */
function ea_remove_admin_bar_items() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu( 'new-link', 'new-content' );  // Links
}
//add_action( 'wp_before_admin_bar_render', 'ja_remove_admin_bar_items' );