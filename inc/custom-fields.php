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
 * Register Fields
 *
 */
function ea_register_custom_fields() {

}
add_action( 'carbon_fields_register_fields', 'ea_register_custom_fields' );
