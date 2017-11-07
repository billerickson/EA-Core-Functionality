<?php
/**
 * Custom Fields
 *
 * @package      CoreFunctionality
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Load Carbon Fields
 *
 */
function ea_load_carbon_fields() {
    require_once( EA_DIR . '/vendor/autoload.php' );
    \Carbon_Fields\Carbon_Fields::boot();
}
add_action( 'after_setup_theme', 'ea_load_carbon_fields' );

/**
 * Register Fields
 *
 */
function ea_register_custom_fields() {

	Container::make( 'post_meta', 'Page Options' )
		->where( 'post_type', '=', 'page' )
		->add_fields( array(
			Field::make( 'text', 'ea_test', 'Test' )
		));
}
add_action( 'carbon_fields_register_fields', 'ea_register_custom_fields' );
