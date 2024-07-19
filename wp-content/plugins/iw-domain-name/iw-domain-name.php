<?php
/*
Plugin Name: Domain to cart item
Description: Adds domain name to WooCommerce cart items
Version: 1.0
Author: Michal Prihoda
Developer: Michal Prihoda
Text-Domain: iw-domain-name
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 * Activation and deactivation hooks for WordPress
 */
function iw_domain_name_extension_activate() {
	// Your activation logic goes here.
}
register_activation_hook( __FILE__, 'iw_domain_name_extension_activate' );

function iw_domain_name_extension_deactivate() {
	// Your deactivation logic goes here.

	// Don't forget to:
	// Remove Scheduled Actions
	// Remove Notes in the Admin Inbox
	// Remove Admin Tasks
}
register_deactivation_hook( __FILE__, 'iw_domain_name_extension_deactivate' );

add_filter('woocommerce_add_cart_item_data', 'iw_domain_name_cart_item', 10, 3);

function iw_domain_name_cart_item($cart_item_data, $product_id, $variation_id) {
	$domain_name = isset($_POST['domain_name']) ? sanitize_text_field($_POST['domain_name']) : '';

	if (!empty($domain_name)) {
		$cart_item_data['domain_name'] = $domain_name;
	}

	return $cart_item_data;
}

add_filter('woocommerce_get_item_data', 'iw_domain_name_cart_display', 10, 2);

function iw_domain_name_cart_display($item_data, $cart_item) {
	if (isset($cart_item['domain_name'])) {
		$item_data[] = array(
			'key'   => 'Doména',
			'value' => $cart_item['domain_name']
		);
	}
	return $item_data;
}

add_action('woocommerce_checkout_create_order_line_item', 'iw_domain_name_order_item', 10, 4);

function iw_domain_name_order_item($item, $cart_item_key, $values, $order) {
	if (isset($values['domain_name'])) {
		$item->add_meta_data('Doména', $values['domain_name']);
	}
}

add_action('woocommerce_before_add_to_cart_button', 'iw_domain_name_product_page');

function iw_domain_name_product_page() {
	echo '<div class="domain-name">';
	echo '<label for="domain_name">Doména:</label>';
	echo '<input type="text" id="domain_name" name="domain_name">';
	echo '</div>';
}

add_action('wp_head', 'iw_domain_name_css');

function iw_domain_name_css() {
	echo '<style>
        .domain-name {
            margin-bottom: 1em;
        }
        .domain-name label {
            display: block;
            margin-bottom: 0.5em;
        }
        .domain-name input {
            width: 100%;
            padding: 0.5em;
        }
    </style>';
}
