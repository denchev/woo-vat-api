<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Woocommerce VAT to API
 * Description:       Add customer VAT
 * Version:           1.0.0
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       woocoomerce-vat-to-api
 * Domain Path:       /languages
 */


function vattoapi_api_order_response($order_data) {

	$order_id = $order_data['id'];

	$order = wc_get_order($order_id);

	$order_data['billing_address']['vat_number'] = '';

	if(isset($order->billing_eu_vat_number)) {
		$order_data['billing_address']['vat_number'] = $order->billing_eu_vat_number;
	}

	return $order_data;
}

function vattoapi_found_customer_details($customer_data, $user_id = null, $type_to_load = null) {

	$billing_eu_vat_number = get_user_meta( $user_id, $type_to_load . '_eu_vat_number', true);

	$customer_data[$type_to_load . '_eu_vat_number'] = !empty($billing_eu_vat_number) ? $billing_eu_vat_number : '';

	return $customer_data;
}

add_filter('woocommerce_api_order_response', 'vattoapi_api_order_response');
add_filter('woocommerce_found_customer_details', 'vattoapi_found_customer_details', 10, 3);
wp_enqueue_script('vat-to-api-wc-admin-order-meta-boxes', plugins_url('js/meta-boxes-order.js', __FILE__), array('wc-admin-order-meta-boxes'));