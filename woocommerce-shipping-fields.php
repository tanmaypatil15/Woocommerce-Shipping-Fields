<?php
/*
Plugin Name: Woocommerce Shipping Field
Description: Add email and phone fields to the frontend shipping form and admin order view in WooCommerce.
Version: 1.0
Author: Tanmay Patil
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 *	Woocommerce Shipping Field
 */
class Woocommerce_Shipping_Field
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // Add fields to checkout
        add_filter('woocommerce_shipping_fields', array($this, 'add_shipping_fields'));

        // Add fields to admin order view
        add_filter('woocommerce_admin_shipping_fields', array($this, 'admin_shipping_fields'));

        // Save custom shipping email and phone fields
        add_action('woocommerce_checkout_update_order_meta', array($this, 'save_woocommerce_shipping_field'));

        // Display custom shipping fields in order details
        add_action('woocommerce_order_details_after_customer_details', array($this, 'display_woocommerce_shipping_field'));
    }

    /**
     * Add email and phone fields to the frontend shipping form
     *
     * @param array $fields Shipping fields array
     * @return array Modified shipping fields array
     */
    public function add_shipping_fields($fields)
    {
        $fields['shipping_email'] = array(
            'label' => __('Email', 'woocommerce'),
            'required' => true,
            'type' => 'email',
            'class' => array('form-row-wide'),
            'validate' => array('email'),
        );

        $fields['shipping_phone'] = array(
            'label' => __('Phone', 'woocommerce'),
            'required' => true,
            'type' => 'tel',
            'class' => array('form-row-wide'),
            'clear' => true,
            'validate' => array('phone'),
        );

        return $fields;
    }

    /**
     * Add email and phone fields to the admin order view
     *
     * @param array $fields Admin shipping fields array
     * @return array Modified admin shipping fields array
     */
    public function admin_shipping_fields($fields)
    {
    $fields['email'] = array(
        'label' => __('Email Address', 'woocommerce'),
    );

    $fields['shipping_phone'] = array(
        'label' => __('Phone', 'woocommerce'),
    );

    return $fields;
    }

    /**
     * Save shipping email and phone fields
     *
     * @param int $order_id Order ID
     */
    public function save_woocommerce_shipping_field($order_id)
    {
        if (!empty($_POST['shipping_email'])) {
            update_post_meta($order_id, 'shipping_email', sanitize_email($_POST['shipping_email']));
        }

        if (!empty($_POST['_shipping_phone'])) {
            update_post_meta($order_id, 'shipping_phone', sanitize_text_field($_POST['_shipping_phone']));
        }
    }

    /**
     * Display shipping email and phone fields in order details
     *
     * @param WC_Order $order WooCommerce order object
     */
    public function display_woocommerce_shipping_field($order)
    {
        $shipping_email = get_post_meta($order->get_id(), 'shipping_email', true);
        // $shipping_phone = get_post_meta($order->get_id(), '_shipping_phone', true);

        if ($shipping_email) {
            echo '<p><strong>' . __('Email:', 'woocommerce') . '</strong> ' . $shipping_email . '</p>';
        }

        if ($shipping_phone) {
            echo '<p><strong>' . __('Phone:', 'woocommerce') . '</strong> ' . $shipping_phone . '</p>';
        }
    }
}

// Initialize the plugin
$woocommerce_shipping_field = new Woocommerce_Shipping_Field();
