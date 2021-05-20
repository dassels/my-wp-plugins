<?php
/*
Plugin Name:	Custom Woo MinMax
Plugin URI:		https://objectivenutrients.com
Description:	Adds max qty limit for products based on user roles
Version:		1.0.0
Author:			Damian Assels
Author URI:		https://objectivenutrients.com
License:		GPL-2.0+
License URI:	http://www.gnu.org/licenses/gpl-2.0.txt

This plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

This plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with This plugin. If not, see {URI to Plugin License}.
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'wp_enqueue_scripts', 'custom_enqueue_files' );
/**
 * Loads <list assets here>.
 */
function custom_enqueue_files() {
	// if this is not the front page, abort.
	// if ( ! is_front_page() ) {
	// 	return;
	// }

	// loads a CSS file in the head.
	// wp_enqueue_style( 'highlightjs-css', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );

	/**
	 * loads JS files in the footer.
	 */
	// wp_enqueue_script( 'highlightjs', plugin_dir_url( __FILE__ ) . 'assets/js/highlight.pack.js', '', '9.9.0', true );

	// wp_enqueue_script( 'highlightjs-init', plugin_dir_url( __FILE__ ) . 'assets/js/highlight-init.js', '', '1.0.0', true );
}


/*
if ( is_user_logged_in() ) { 
    $current_user = null;
    $current_user = wp_get_current_user();
 
    if (!current_user_can('practitioner') ) {
    //do something
    }
}
*/

/* Create Admin fields for Max Qty for each user role on product 'inventory' tab */

add_action( 'woocommerce_product_options_inventory_product_data', 'wc_qty_add_product_field' );

function wc_qty_add_product_field() {

	echo '<div class="options_group">';
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_guest_max_qty_product', 
			'label'       => __( 'Guest User Max Quantity', 'custom-woo-max-qty' ), 
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Optional. Set a max quantity limit allowed per order for guests. Enter a number, 1 or greater.', 'custom-woo-max-qty' ) 
		)
	);
	echo '</div>';

	echo '<div class="options_group">';
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_customer_max_qty_product', 
			'label'       => __( 'Customer Max Quantity', 'custom-woo-max-qty' ), 
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Optional. Set a max quantity limit allowed per order for customer. Enter a number, 1 or greater.', 'custom-woo-max-qty' ) 
		)
	);
	echo '</div>';

	echo '<div class="options_group">';
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_practitioner_max_qty_product', 
			'label'       => __( 'Practitioner Max Quantity', 'custom-woo-max-qty' ), 
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Optional. Set a maximum quantity limit allowed per order for practitioner. Enter a number, 1 or greater.', 'custom-woo-max-qty' ) 
		)
	);
	echo '</div>';	

	echo '<div class="options_group">';
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_wholesaler_max_qty_product', 
			'label'       => __( 'Wholesale Max Quantity', 'custom-woo-max-qty' ), 
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Optional. Set a maximum quantity limit allowed per order for wholesale. Enter a number, 1 or greater.', 'custom-woo-max-qty' ) 
		)
	);
	echo '</div>';	

}

/*
* This function will save the value set to Minimum Quantity and Maximum Quantity options
* into _(role)_max_qty_product meta keys respectively
*/

add_action( 'woocommerce_process_product_meta', 'wc_qty_save_product_field' );

function wc_qty_save_product_field( $post_id ) {

	$val_max1 = trim( get_post_meta( $post_id, '_customer_max_qty_product', true ) );
	$new_max1 = sanitize_text_field( $_POST['_customer_max_qty_product'] );

	$val_max2 = trim( get_post_meta( $post_id, '_practitioner_max_qty_product', true ) );
	$new_max2 = sanitize_text_field( $_POST['_practitioner_max_qty_product'] );

	$val_max3 = trim( get_post_meta( $post_id, '_wholesaler_max_qty_product', true ) );
	$new_max3 = sanitize_text_field( $_POST['_wholesaler_max_qty_product'] );

	$val_max4 = trim( get_post_meta( $post_id, '_guest_max_qty_product', true ) );
	$new_max4 = sanitize_text_field( $_POST['_guest_max_qty_product'] );
	
	if ( $val_max1 != $new_max1 ) {
		update_post_meta( $post_id, '_customer_max_qty_product', $new_max1 );
	}

	if ( $val_max2 != $new_max2 ) {
		update_post_meta( $post_id, '_practitioner_max_qty_product', $new_max2 );
	}

	if ( $val_max3 != $new_max3 ) {
		update_post_meta( $post_id, '_wholesaler_max_qty_product', $new_max3 );
	}

	if ( $val_max4 != $new_max4 ) {
		update_post_meta( $post_id, '_guest_max_qty_product', $new_max4 );
	}

}

/* Set our maximums */

add_filter( 'woocommerce_quantity_input_args', 'objnut_woocommerce_quantity_max', 10, 2 );
 
function objnut_woocommerce_quantity_max( $args, $product ) {

	$product_id = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
	
	$cust_product_max = customer_get_product_max_limit( $product_id );
	$prac_product_max = practitioner_get_product_max_limit( $product_id );
	$whol_product_max = wholesaler_get_product_max_limit( $product_id );
	$guest_product_max = guest_get_product_max_limit( $product_id);
	$admin_product_max = 9999;		

	if ( current_user_can('customer') ) {

			$args['max_value'] = $cust_product_max;
			return $args;		

	} elseif ( current_user_can( 'practitioner' ) ) {

			$args['max_value'] = $prac_product_max;
			return $args;		

	} elseif ( current_user_can('wholesaler') ) {

			$args['max_value'] = $whol_product_max;
			return $args;		

	} elseif ( is_user_logged_in()) {

			$args['max_value'] = $admin_product_max;
			return $args;	

	} else {

		if (! is_user_logged_in()) {
		$args['max_value'] = $guest_product_max;
		return $args;
		}

	}

}

function customer_get_product_max_limit( $product_id ) {
	$qty = get_post_meta( $product_id, '_customer_max_qty_product', true );
	if ( empty( $qty ) ) {
		$limit = false;
	} else {
		$limit = (int) $qty;
	}
	return $limit;
}

function practitioner_get_product_max_limit( $product_id ) {
	$qty = get_post_meta( $product_id, '_practitioner_max_qty_product', true );
	if ( empty( $qty ) ) {
		$limit = false;
	} else {
		$limit = (int) $qty;
	}
	return $limit;
}

function wholesaler_get_product_max_limit( $product_id ) {
	$qty = get_post_meta( $product_id, '_wholesaler_max_qty_product', true );
	if ( empty( $qty ) ) {
		$limit = false;
	} else {
		$limit = (int) $qty;
	}
	return $limit;
}

function guest_get_product_max_limit( $product_id ) {
	$qty = get_post_meta( $product_id, '_guest_max_qty_product', true );
	if ( empty( $qty ) ) {
		$limit = false;
	} else {
		$limit = (int) $qty;
	}
	return $limit;
}

/** Validations - Limit add to cart on product page to quantities entered **/

add_filter( 'woocommerce_add_to_cart_validation', 'wc_qty_add_to_cart_validation', 1, 5 );

function wc_qty_add_to_cart_validation( $passed, $product_id, $quantity, $variation_id = '', $variations = '' ) {

	if ( current_user_can('customer') ) {

		$product_max = customer_get_product_max_limit( $product_id );

	} elseif ( current_user_can( 'practitioner' ) ) {

		$product_max = practitioner_get_product_max_limit( $product_id );	

	} elseif ( current_user_can('wholesaler') ) {

		$product_max = wholesaler_get_product_max_limit( $product_id );
		
	} elseif ( is_user_logged_in()) {

		$product_max = 9999;

	} else {

		if (! is_user_logged_in()) {

			$product_max = guest_get_product_max_limit( $product_id );
		}

	}

	if ( ! empty( $product_max ) ) {
		// min is empty
		if ( false !== $product_max ) {
			$new_max = $product_max;
		} else {
			// neither max is set, so get out
			return $passed;
		}
	}

	$already_in_cart 	= wc_qty_get_cart_qty( $product_id );
	$product 			= wc_get_product( $product_id );
	$product_title 		= $product->get_title();
	
	if ( !is_null( $new_max ) && !empty( $already_in_cart ) ) {
		
		if ( ( $already_in_cart + $quantity ) > $new_max ) {
			// oops. too much.
			$passed = false;			

			wc_add_notice( apply_filters( 'isa_wc_max_qty_error_message_already_had', sprintf( __( 'You can add a maximum of %1$s %2$s\'s to %3$s. You already have %4$s.', 'custom-woo-max-qty' ), 
						$new_max,
						$product_title,
						'<a href="' . esc_url( wc_get_cart_url() ) . '">' . __( 'your cart', 'custom-woo-max-qty' ) . '</a>',
						$already_in_cart ),
					$new_max,
					$already_in_cart ),
			'error' );

		}
	}

	return $passed;
}


/*
* Get the total quantity of the product available in the cart.
*/ 

function wc_qty_get_cart_qty( $product_id ) {
	global $woocommerce;
	$running_qty = 0; // iniializing quantity to 0

	// search the cart for the product in and calculate quantity.
	foreach($woocommerce->cart->get_cart() as $other_cart_item_keys => $values ) {
		if ( $product_id == $values['product_id'] ) {				
			$running_qty += (int) $values['quantity'];
		}
	}

	return $running_qty;
}

/* Validate product quantity when cart is UPDATED */

add_filter( 'woocommerce_update_cart_validation', 'wc_qty_update_cart_validation', 1, 4 );

function wc_qty_update_cart_validation( $passed, $cart_item_key, $values, $quantity ) {

	if ( current_user_can('customer') ) {

		$product_max = customer_get_product_max_limit( $values['product_id'] );

	} elseif ( current_user_can( 'practitioner' ) ) {

		$product_max = practitioner_get_product_max_limit( $values['product_id'] );	

	} elseif ( current_user_can('wholesaler') ) {

		$product_max = wholesaler_get_product_max_limit( $values['product_id'] );
		
	} elseif ( is_user_logged_in()) {

		$product_max = 9999;	

	} else {

		if (! is_user_logged_in()) {

			$product_max = guest_get_product_max_limit( $values['product_id'] );
		}

	}

	if ( ! empty( $product_max ) ) {
		// min is empty
		if ( false !== $product_max ) {
			$new_max = $product_max;
		} else {
			// neither max is set, so get out
			return $passed;
		}
	}

	$product = wc_get_product( $values['product_id'] );
	$already_in_cart = wc_qty_get_cart_qty( $values['product_id'], $cart_item_key );


	if ( isset( $new_max) && ( $already_in_cart + ($quantity - $already_in_cart) ) > $new_max ) {
		wc_add_notice( apply_filters( 'wc_qty_error_message', sprintf( __( 'You can add a maximum of %1$s %2$s\'s to %3$s.', 'woocommerce-max-quantity' ),
					$new_max,
					$product->get_name(),
					'<a href="' . esc_url( wc_get_cart_url() ) . '">' . __( 'your cart', 'woocommerce-max-quantity' ) . '</a>'),
				$new_max ),
		'error' );
		$passed = false;
	}		

	return $passed;
}






