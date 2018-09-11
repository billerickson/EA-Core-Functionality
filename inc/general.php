<?php
/**
 * General
 *
 * @package      CoreFunctionality
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

/**
 * Dont Update the Plugin
 * If there is a plugin in the repo with the same name, this prevents WP from prompting an update.
 *
 * @since  1.0.0
 * @author Jon Brown
 * @param  array $r Existing request arguments
 * @param  string $url Request URL
 * @return array Amended request arguments
 */
function ea_dont_update_core_func_plugin( $r, $url ) {
  if ( 0 !== strpos( $url, 'https://api.wordpress.org/plugins/update-check/1.1/' ) )
    return $r; // Not a plugin update request. Bail immediately.
    $plugins = json_decode( $r['body']['plugins'], true );
    unset( $plugins['plugins'][plugin_basename( __FILE__ )] );
    $r['body']['plugins'] = json_encode( $plugins );
    return $r;
 }
add_filter( 'http_request_args', 'ea_dont_update_core_func_plugin', 5, 2 );

/**
 * Author Links on CF Plugin
 *
 */
function ea_author_links_on_cf_plugin( $links, $file ) {

	if ( strpos( $file, 'core-functionality.php' ) !== false ) {
		$links[1] = 'By <a href="http://www.billerickson.net">Bill Erickson</a> & <a href="http://www.jaredatchison.com">Jared Atchison</a>';
    }

    return $links;
}
add_filter( 'plugin_row_meta', 'ea_author_links_on_cf_plugin', 10, 2 );

// Don't let WPSEO metabox be high priority
add_filter( 'wpseo_metabox_prio', function(){ return 'low'; } );

/**
 * Remove WPSEO Notifications
 *
 */
function ea_remove_wpseo_notifications() {

	if( ! class_exists( 'Yoast_Notification_Center' ) )
		return;

	remove_action( 'admin_notices', array( Yoast_Notification_Center::get(), 'display_notifications' ) );
	remove_action( 'all_admin_notices', array( Yoast_Notification_Center::get(), 'display_notifications' ) );
}
add_action( 'init', 'ea_remove_wpseo_notifications' );

/**
 * WPForms, default large field size
 *
 */
function ea_wpforms_default_large_field_size( $field ) {

        if ( empty( $field['size'] ) ) {
            $field['size'] = 'large';
        }

        return $field;
    }
add_filter( 'wpforms_field_new_default', 'ea_wpforms_default_large_field_size' );

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
  * Exclude No-index content from search
  *
  */
function ea_exclude_noindex_from_search( $query ) {

	if( $query->is_main_query() && $query->is_search() && ! is_admin() ) {

		$meta_query = empty( $query->query_vars['meta_query'] ) ? array() : $query->query_vars['meta_query'];
		$meta_query[] = array(
			'key' => '_yoast_wpseo_meta-robots-noindex',
			'compare' => 'NOT EXISTS',
		);

		$query->set( 'meta_query', $meta_query );
	}
}
add_action( 'pre_get_posts', 'ea_exclude_noindex_from_search' );

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
