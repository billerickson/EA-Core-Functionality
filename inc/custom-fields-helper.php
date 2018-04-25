<?php
/**
 * Custom Fields Helper
 *
 * @package      CoreFunctionality
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

/**
  * Shortcut function for accessing custom fields
  *
  * @since   1.2.0
  * @param   string $key   custom field key
  * @param   int $id       object ID (post ID, term ID...)
  * @param   array $args   optional arguments
  * @return  mixed
*/
function ea_cf( $key = '', $id = '', $args = array() ) {

    $default_args = array(
        'echo'        => false,
        'prepend'     => false,
        'append'      => false,
        'escape'      => false,
        'type'        => 'post_meta',
        'cf_type'     => false, // use "complex" for complex fields
        'back_compat' => true, // prepend _ on keys if Carbon Fields isn't active
    );
    $args = wp_parse_args( $args, $default_args );
    $value = false;

    // Carbon Fields
    if( function_exists( 'carbon_get_post_meta' ) ) {

        // -- Post Meta
        if( 'post_meta' == $args['type'] ) {
            $id    = ( empty( $id ) ? get_the_ID() : $id );
            $value = carbon_get_post_meta( $id, $key, $args['cf_type'] );

        // -- Theme Option
        } elseif( 'theme_option' == $args['type'] ) {
            $value = carbon_get_theme_option( $key, $args['cf_type'] );

        // -- Term Meta
        } elseif( 'term_meta' == $args['type'] ) {
            $id = ( empty( $id ) ? get_queried_object_id() : $id );
            $value = carbon_get_term_meta( $id, $key, $args['cf_type'] );

        // -- User Meta
        } elseif( 'user_meta' == $args['type'] ) {
            $id = ( empty( $id ) ? get_queried_object_id() : $id );
            $value = carbon_get_user_meta( $id, $key, $args['cf_type'] );

        // -- Comment Meta
        } elseif( 'comment_meta' == $args['type'] ) {
            $id = ( empty( $id ) ? get_queried_object_id() : $id );
            $value = carbon_get_comment_meta( $id, $key, $args['cf_type'] );

        }

    // Fallback to standard post meta (doesn't work with other types of metadata)
    } elseif( 'post_meta' == $args['type'] ) {
        $id    = ( empty( $id ) ? get_the_ID() : $id );
        $key   = $args['back_compat'] ? '_' . $key : $key;
        $value = get_post_meta( $id, $key, true );
    }

    // Additional formatting before output
    // Only run on simple fields (not complex ones)
    if( ! $args['cf_type'] ) {

        if( $args['escape'] ) {
            $value = call_user_func( $args['escape'], $value );
        }

        if( $value && $args['prepend'] ) {
            $value = $args['prepend'] . $value;
        }
        if( $value && $args['append'] ) {
            $value .= $args['append'];
        }
    }

    // Echo vs Return
    if ( $args['echo'] ) {
        echo $value;

    } else {
        return $value;
    }
}

/**
 * Metabox Header Template
 *
 */
function ea_metabox_header_template( $key = '' ) {
	return '<% if (' . $key . ') { %><%- ' . $key . ' %><% } %>';
}

/**
 * Theme Icons
 *
 */
function ea_get_theme_icons() {
	$icons = get_option( 'ea_theme_icons' );
	$version = get_option( 'ea_theme_icons_version' );
	if( empty( $icons ) || ( defined( 'THEME_VERSION' ) && version_compare( THEME_VERSION, $version ) ) ) {
		$icons = scandir( get_stylesheet_directory() . '/assets/icons/' );
		$icons = array_diff( $icons, array( '..', '.' ) );
		$icons = array_values( $icons );
		if( empty( $icons ) )
			return $icons;
		// remove the .svg
		foreach( $icons as $i => $icon ) {
			$icons[ $i ] = substr( $icon, 0, -4 );
		}
		update_option( 'ea_theme_icons', $icons );
		if( defined( 'THEME_VERSION' ) )
			update_option( 'ea_theme_icons_version', THEME_VERSION );
	}
	return $icons;
}

/**
 * Metabox Icon Callback
 *
 */
function ea_metabox_icon_callback() {
	$icons = ea_get_theme_icons();
	$output = array( '' => '(None)' );
	foreach( $icons as $icon )
		$output[ $icon ] = $icon;
	return $output;
}
