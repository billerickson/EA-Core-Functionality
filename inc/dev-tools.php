<?php
/**
 * Developer Tools
 *
 * @package      CoreFunctionality
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/


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
		'rbuff',
		'richard',
		'richardbuff',
		'payam',
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

	$dev_strings = array( 'wpdev.io', 'master-wp.com', 'localdev', 'staging', 'localhost', 'dev.', '.dev', 'gibraltar', 'wpengine' );
	$is_dev_site = false;
	foreach( $dev_strings as $string )
		if( strpos( home_url(), $string ) )
			$is_dev_site = true;

	return $is_dev_site;
}

/**
 * Force SSL on WPEngine install
 *
 * @author Bill Erickson
 * @see https://www.billerickson.net/force-ssl-on-wpengine
 *
 */
function ea_force_ssl_on_wpengine() {

  if( strpos( home_url(), 'wpengine' ) && ! is_ssl() ) {

    wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
		exit();

	}
}
add_action( 'template_redirect', 'ea_force_ssl_on_wpengine' );


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
 * Force frontend admin bar color scheme when user is developer on development server
 *
 * @since 1.2.0
 */
function ea_dev_color_scheme_admin_bar() {
	if ( ea_is_developer() && ea_is_dev_site() ) {
		$color = apply_filters( 'ea_admin_bar_dev', '#59524c' );
		echo "<style type='text/css'>#wpadminbar{background-color:$color!important}</style>";
	}
}
add_action( 'wp_head', 'ea_dev_color_scheme_admin_bar' );

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
 * Collect Images
 *
**/
function ea_debug_collect_images( $image, $image_id ) {
	global $ea_images;
	$ea_images[] = $image_id;
	return $image;
}

/**
 * Display Images
 *
 */
function ea_debug_display_images() {
	global $ea_images;
	ea_pp( join( ' ', array_unique( $ea_images ) ) );
}
//add_action( 'wp_footer', 'ea_debug_display_images' );
//add_filter( 'wp_get_attachment_image_src', 'ea_debug_collect_images', 10, 2 );
