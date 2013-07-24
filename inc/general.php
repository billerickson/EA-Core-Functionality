<?php
/**
 * General
 *
 * This file contains any general functions
 *
 * @package      CoreFunctionality
 * @since        1.0.0
 * @link         https://github.com/billerickson/Core-Functionality
 * @author       Bill Erickson <bill@billerickson.net>
 * @copyright    Copyright (c) 2011, Bill Erickson
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
 
// Use shortcodes in widgets
add_filter( 'widget_text', 'do_shortcode' );

// Disable WPSEO columns on edit screen 
add_filter( 'wpseo_use_page_analysis', '__return_false' );

/**
 * Don't Update Plugin
 * 
 * This prevents you being prompted to update if there's a public plugin
 * with the same name.
 *
 * @since 1.0.0
 * @author Mark Jaquith
 * @link http://markjaquith.wordpress.com/2009/12/14/excluding-your-plugin-or-theme-from-update-checks/
 * @param array $r, request arguments
 * @param string $url, request url
 * @return array request arguments
 */
function ea_core_functionality_hidden( $r, $url ) {
	if ( 0 !== strpos( $url, 'http://api.wordpress.org/plugins/update-check' ) )
		return $r; // Not a plugin update request. Bail immediately.
	$plugins = unserialize( $r['body']['plugins'] );
	unset( $plugins->plugins[ plugin_basename( __FILE__ ) ] );
	unset( $plugins->active[ array_search( plugin_basename( __FILE__ ), $plugins->active ) ] );
	$r['body']['plugins'] = serialize( $plugins );
	return $r;
}
add_filter( 'http_request_args', 'ea_core_functionality_hidden', 5, 2 );

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
 * Hide ACF menu item from the admin menu
 *
 * @since 1.0.0
 * @global array $current_user
 */
function ea_hide_acf_admin_menu(){
	global $current_user;
	get_currentuserinfo();
 
	if ( !in_array( $current_user->user_login, array( 'billerickson', 'j-atchison' ) ) )
    echo '<style type="text/css">#toplevel_page_edit-post_type-acf{display:none;}</style>';
}
add_action( 'admin_head', 'ea_hide_acf_admin_menu' );

/**
 * Disable Inactive Plugins Nag on Synthesis
 *
 * @since 1.0.0
 */
function ea_disable_inactive_plugins_nag() {
	if ( method_exists( 'Synthesis_Software_Monitor', 'inactive_plugin_notifications' ) )
		remove_action( 'admin_notices', array( 'Synthesis_Software_Monitor', 'inactive_plugin_notifications' ) );
} 
add_action( 'plugins_loaded', 'ea_disable_inactive_plugins_nag' );

/**
 * Disable Scribe
 *
 */
function ea_disable_scribe() {
	class Scribe_SEO {}
}
add_action( 'plugins_loaded', 'ea_disable_scribe', 4 );

/**
 * Rename WYSIWYG widget
 *
 * @since 1.0.0
 * @param string $translation
 * @param string $text
 * @param string $domain
 * @return string
 */
function ea_change_tinymce_widget_title( $translation, $text, $domain ) {
    if ( $text == 'Black Studio TinyMCE' )
        $translation = 'WYSIWYG Editor';
    return $translation;
}
add_filter( 'gettext', 'ea_change_tinymce_widget_title', 10, 3 );
