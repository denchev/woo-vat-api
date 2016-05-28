<?php

/**
 * Plugin Name: Woocommerce VAT to API
 * Description: Show VAT number as part of billing address in the WooCommerce API (Requires Booster for WooCommerce)
 * Version: 1.0.1
 * License: GPL-3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: woo-vat-api
 * Domain Path: /languages
 * Author: HTML Pet Ltd
 * Author URI: https://www.htmlpet.com
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return; // Check if WooCommerce is active

if ( ! in_array( 'woocommerce-jetpack/woocommerce-jetpack.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return; // Check if Booster for WooCommerce is active

function woovatapi_api_order_response($order_data) {

	$order_id = $order_data['id'];

	$order = wc_get_order($order_id);

	$order_data['billing_address']['vat_number'] = '';

	if(isset($order->billing_eu_vat_number)) {
		$order_data['billing_address']['vat_number'] = $order->billing_eu_vat_number;
	}

	return $order_data;
}

function woovatapi_found_customer_details($customer_data, $user_id = null, $type_to_load = null) {

	$billing_eu_vat_number = get_user_meta( $user_id, $type_to_load . '_eu_vat_number', true);

	$customer_data[$type_to_load . '_eu_vat_number'] = !empty($billing_eu_vat_number) ? $billing_eu_vat_number : '';

	return $customer_data;
}

// Add VAT to API response
add_filter('woocommerce_api_order_response', 'woovatapi_api_order_response');

// Add VAT to customer data response
add_filter('woocommerce_found_customer_details', 'woovatapi_found_customer_details', 10, 3);

// Enqueue javascript that will fill customer prepopulated VAT number to manual order form
wp_enqueue_script('vat-to-api-wc-admin-order-meta-boxes', plugins_url('js/meta-boxes-order.js', __FILE__), array('wc-admin-order-meta-boxes'));
