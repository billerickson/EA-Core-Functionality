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

// Disable WPSEO columns on edit screen 
add_filter( 'wpseo_use_page_analysis', '__return_false' );

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
	if( !strpos( $attr['class'], 'wp-image-' . $attachment->ID ) )
		$attr['class'] .= ' wp-image-' . $attachment->ID;
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'ja_attachment_id_on_images', 10, 2 );

/**
 * Shortcut function for get_post_meta();
 *
 * @since 1.2.0
 * @param string $key
 * @param int $id
 * @param boolean $echo
 * @return string
 */
function ea_cf( $key = '', $id = '', $echo = false ) {
	$id    = ( empty( $id ) ? get_the_ID() : $id );
	$value = get_post_meta( $id, $key, true );
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
	if ( !is_user_logged_in() )
		return false;

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

	$ja_dev  =  strpos( home_url(), 'wpdev.io'      );
	$be_dev  =  strpos( home_url(), 'master-wp.com' );
	$wpe_dev =  strpos( home_url(), 'staging'       );

	return ( $ja_dev || $be_dev || $wpe_dev );
}

/**
 * Force different color scheme when user is developer on development server
 *
 * @since 1.0.0
 * @param string $color_scheme
 * @return string
 */
function ea_dev_color_scheme( $color_scheme ) {

	if ( ea_is_developer() && ea_is_dev_site() )
		$color_scheme = 'coffee';

	return $color_scheme;

}
add_filter( 'get_user_option_admin_color', 'ea_dev_color_scheme', 5 );

/**
 * Hide ACF menu item from the admin menu
 *
 * @since 1.0.0
 */
function ea_hide_acf_admin_menu() {
	if ( ea_is_developer() == false )
		remove_menu_page( 'edit.php?post_type=acf' );
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

	if( $notification['name'] == 'Admin Notification' )
		$notification['message'] .= 'Sent from ' . home_url();

	return $notification;
}
add_filter( 'gform_notification', 'ea_gravityforms_domain', 10, 3 );


/**
 * Disable Registered Users Only 
 *
 */
function ea_disable_registered_users_only( $exclusions ) {
	$exclusions[] = basename($_SERVER['PHP_SELF']);
	return $exclusions;
}
//add_filter( 'registered-users-only_exclusions', 'ea_disable_registered_users_only' );
