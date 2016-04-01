<?php
/**
 * Core Functionality Plugin
 * 
 * @package    CoreFunctionality
 * @since      1.0.0
 * @copyright  Copyright (c) 2014, Bill Erickson & Jared Atchison
 * @license    GPL-2.0+
 */

// Use shortcodes in widgets
add_filter( 'widget_text', 'do_shortcode' );

/**
 * Attachment ID on Images
 *
 * @since  1.1.0
 */
function ea_attachment_id_on_images( $attr, $attachment ) {
	if( !strpos( $attr['class'], 'wp-image-' . $attachment->ID ) ) {
		$attr['class'] .= ' wp-image-' . $attachment->ID;
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'ea_attachment_id_on_images', 10, 2 );

/**
 * Default Image Link is None
 *
 * @since 1.2.0
 */
function ea_default_image_link() {
	$link = get_option( 'image_default_link_type' );
	if( 'none' !== $link )
		update_option( 'image_default_link_type', 'none' );
}
add_action( 'init', 'ea_default_image_link' );

/**
 * Remove extra dashboard widgets
 *
 * @since 1.0.0
 */
function ea_remove_dashboard_widgets() {
	//remove_meta_box( 'dashboard_right_now',       'dashboard', 'core' ); // Right Now
	//remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'core' ); // Recent Comments
	remove_meta_box( 'dashboard_incoming_links',  'dashboard', 'core' );   // Incoming Links
	remove_meta_box( 'dashboard_plugins',         'dashboard', 'core' );   // Plugins
	remove_meta_box( 'dashboard_quick_press',     'dashboard', 'core' );   // Quick Press
	remove_meta_box( 'dashboard_recent_drafts',   'dashboard', 'core' );   // Recent Drafts
	remove_meta_box( 'dashboard_primary',         'dashboard', 'core' );   // WordPress Blog
	remove_meta_box( 'dashboard_secondary',       'dashboard', 'core' );   // Other WordPress News
}
add_action( 'admin_menu', 'ea_remove_dashboard_widgets' );

/**
 * Remove default WordPress widgets
 * 
 * @since 1.0.0
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
//add_action( 'widgets_init', 'ea_remove_default_wp_widgets', 1 );

/**
 * Remove admin menu items
 *
 * @since 1.0.0
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
//add_action( 'admin_menu', 'ea_remove_admin_menus' );

/**
 * Remove admin bar items
 *
 * @since 1.0.0
 * @global array $wp_admin_bar
 */
function ea_remove_admin_bar_items() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'new-link', 'new-content' );  // Links
}
//add_action( 'wp_before_admin_bar_render', 'ea_remove_admin_bar_items' );


/**
 * Fix the Post Title Link on Edit Comments
 *
 */
function be_fix_post_title_link_on_edit_comments( $link, $post_id, $context ) {

	$screen = get_current_screen();
	if( is_admin() && $screen->base == 'edit-comments' )
		$link = get_permalink( $post_id );

	return $link;
}
add_filter( 'get_edit_post_link', 'be_fix_post_title_link_on_edit_comments', 10, 3 );