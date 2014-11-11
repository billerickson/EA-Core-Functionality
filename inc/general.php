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
 * Pretty Printing
 *
 * @since 1.0.0
 * @author Chris Bratlien
 * @param mixed $obj
 * @param string $label
 * @return null
 */
function ea_pp( $obj, $label = '' ) {  
	$data = json_encode( print_r( $obj,true ) );
	?>
	<style type="text/css">
		#bsdLogger {
		position: absolute;
		top: 30px;
		right: 0px;
		border-left: 4px solid #bbb;
		padding: 6px;
		background: white;
		color: #444;
		z-index: 999;
		font-size: 1.25em;
		width: 400px;
		height: 800px;
		overflow: scroll;
		}
	</style>    
	<script type="text/javascript">
		var doStuff = function(){
			var obj = <?php echo $data; ?>;
			var logger = document.getElementById('bsdLogger');
			if (!logger) {
				logger = document.createElement('div');
				logger.id = 'bsdLogger';
				document.body.appendChild(logger);
			}
			////console.log(obj);
			var pre = document.createElement('pre');
			var h2 = document.createElement('h2');
			pre.innerHTML = obj;
			h2.innerHTML = '<?php echo addslashes($label); ?>';
			logger.appendChild(h2);
			logger.appendChild(pre);      
		};
		window.addEventListener ("DOMContentLoaded", doStuff, false);
	</script>
	<?php
}

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
 * Shortcut function for get_post_meta();
 *
 * @since 1.2.0
 * @param string $key
 * @param int $id
 * @param boolean $echo
 * @param string $prepend
 * @param string $append
 * @param string $escape
 * @return string
 */
function ea_cf( $key = '', $id = '', $echo = false, $prepend = false, $append = false, $escape = false ) {
	$id    = ( empty( $id ) ? get_the_ID() : $id );
	$value = get_post_meta( $id, $key, true );
	if( $escape )
		$value = call_user_func( $escape, $value );
	if( $value && $prepend )
		$value = $prepend . $value;
	if( $value && $append )
		$value .= $append;
		
	if ( $echo ) {
		echo $value;
	} else {
		return $value;
	}
}

/**
 * Check if current user is the developer
 *
 * @since 1.0.0
 * @global array $current_user
 * @return boolean
 */
function ea_is_developer() {

	// Check if user is logged in
	if ( !is_user_logged_in() ) {
		return false;
	}

	// Approved developer
	$approved = array( 
		'j-atchison',
		'jatchison',
		'jared',
		'jaredatchison',
		'b-erickson',
		'berickson',
		'bill',
		'billerickson',
	);

	// Get the current user
	$current_user = wp_get_current_user();
	$current_user = strtolower( $current_user->user_login );

	// Check if current user is approved developer
	return in_array( $current_user, $approved );
}

/**
 * Check if current site is a development site
 *
 * @since 1.2.0
 * @return boolean
 */
function ea_is_dev_site() {

	$ja_dev    =  strpos( home_url(), 'wpdev.io'      );
	$be_dev    =  strpos( home_url(), 'master-wp.com' );
	$wpe_dev   =  strpos( home_url(), 'staging'       );
	$local_dev =  strpos( home_url(), 'localhost'     );

	return ( $ja_dev || $be_dev || $wpe_dev || $local_dev );
}

/**
 * Force different color scheme when user is developer on development server
 *
 * @since 1.0.0
 * @param string $color_scheme
 * @return string
 */
function ea_dev_color_scheme( $color_scheme ) {

	if ( ea_is_developer() && ea_is_dev_site() ) {
		$color_scheme = 'coffee';
	} else {
		$color_scheme = 'fresh';
	}

	return $color_scheme;

}
add_filter( 'get_user_option_admin_color', 'ea_dev_color_scheme', 5 );

/**
 * Search Engine Visiblity Settings
 *
 */
function ea_se_visibility( $value ) {
	return (int) ! ea_is_dev_site();
}
add_filter( 'pre_option_blog_public', 'ea_se_visibility' );

/**
 * Search Engine Visibility Warning
 *
 */
function ea_se_visibility_warning() {
	$visible = get_option( 'blog_public' );
	if( ! ea_is_dev_site() && ! $visible )
		echo '<div class="error"><p>This website is not visible to search engines. Please correct this in Settings > Reading</p></div>';
}
add_action( 'admin_notices', 'ea_se_visibility_warning' );

/**
 * Hide ACF menu item from the admin menu
 *
 * @since 1.0.0
 */
function ea_hide_acf_admin_menu() {
	if ( ea_is_developer() == false ) {
		remove_menu_page( 'edit.php?post_type=acf' );
		remove_menu_page( 'edit.php?post_type=acf-field-group' );
	}
}
add_action( 'admin_menu', 'ea_hide_acf_admin_menu', 999 );

/**
 * Force Jetpack dev mode on development sites
 *
 * If Jetpack is activated on two sites with the same Blog ID (say production 
 * and development) this can severely screw things with the URL associated for
 * the account. To prevent this, if Jetpack is activated on a development site,
 * we force it into development mode.
 *
 * @since 1.2.0
 * @param boolean $development_mode
 * @return boolean
 */
function ea_jetpack_dev_mode( $development_mode ) {
	if ( ea_is_dev_site() == true ) {
		$development_mode = true;
	}
	return $development_mode;
}
add_filter( 'jetpack_development_mode', 'ea_jetpack_dev_mode' );

/**
 * Gravity Forms Domain
 *
 * Adds a notice at the end of admin email notifications 
 * specifying the domain from which the email was sent.
 *
 * @param array $notification
 * @param object $form
 * @param object $entry
 * @return array $notification
 */
function ea_gravityforms_domain( $notification, $form, $entry ) {

	if( $notification['name'] == 'Admin Notification' ) {
		$notification['message'] .= 'Sent from ' . home_url();
	}

	return $notification;
}
add_filter( 'gform_notification', 'ea_gravityforms_domain', 10, 3 );

/**
 * Add Page Template as Page Column
 *
 */
function ea_page_template_columns( $columns ) {
	if( ! ea_is_developer() )
		return $columns;
	
	$new_columns = array();	
	foreach( $columns as $slug => $title ) {
		$new_columns[$slug] = $title;
		if( 'title' == $slug )
			$new_columns['page_template'] = 'Page Template';
	}
	
	return $new_columns;
}
add_filter( 'manage_edit-page_columns', 'ea_page_template_columns' );

/**
 * Page Template Column
 *
 */
function ea_page_template_column( $column, $post_id ) {
	
	if( 'page_template' == $column ) {
		$template = get_post_meta( $post_id, '_wp_page_template', true );
		echo $template;	
	}
}
add_action( 'manage_pages_custom_column', 'ea_page_template_column', 10, 2 );

/**
 * Disable Registered Users Only 
 *
 */
function ea_disable_registered_users_only( $exclusions ) {
	$exclusions[] = basename($_SERVER['PHP_SELF']);
	return $exclusions;
}
//add_filter( 'registered-users-only_exclusions', 'ea_disable_registered_users_only' );

/**
 * ACF Options Page 
 *
 */
function ea_acf_options_page() {
    if( function_exists( 'acf_add_options_page' ) ) {
        acf_add_options_page( 'Site Options' );
    }
}
//add_action( 'init', 'ea_acf_options_page' );