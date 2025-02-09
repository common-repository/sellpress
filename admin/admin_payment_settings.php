<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function sellpress_payment_settings_page_html(){
    global $wpdb;

    $paymentSettings = @json_decode($wpdb->get_var('select value from `' . $wpdb->prefix . 'sellpress_settings` where name=\'payment\''), true);

    include __DIR__ . DIRECTORY_SEPARATOR . 'admin_payment_settings_html.php';
}

add_action('wp_ajax_sellpress_save_payment_settings', 'sellpress_save_payment_settings');
function sellpress_save_payment_settings(){
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'sellpress_payment_settings')) {
        die(json_encode(array('status' => 0)));
    }

    global $wpdb;

    $paymentSettings = @json_decode($wpdb->get_var('select value from `' . $wpdb->prefix . 'sellpress_settings` where name=\'payment\''), true);

    $paymentSettings['cash']['enabled'] = isset($_POST['cash_enabled']) && $_POST['cash_enabled'] === 'yes';
    $paymentSettings['cash']['display_name'] = sanitize_text_field($_POST['cash_display_name']);
    $paymentSettings['paypal']['enabled'] = isset($_POST['paypal_enabled']) && $_POST['paypal_enabled'] === 'yes';
    $paymentSettings['paypal']['display_name'] = sanitize_text_field($_POST['paypal_display_name']);
//    $paymentSettings['paypal']['email'] = sanitize_email($_POST['paypal_email']);
    $paymentSettings['paypal']['sandbox'] = isset($_POST['paypal_sandbox']) && $_POST['paypal_sandbox'] === 'yes';
    $paymentSettings['paypal']['client_id'] = sanitize_text_field($_POST['paypal_client_id']);
    $paymentSettings['paypal']['sandbox_client_id'] = sanitize_text_field($_POST['paypal_sandbox_client_id']);


    $result = $wpdb->update($wpdb->prefix . 'sellpress_settings', array(
        'value' => json_encode($paymentSettings)
    ), array('name' => 'payment'));

    if ($result) {
        die(json_encode(array('status' => 1)));
    } else {
        die(json_encode(array('status' => 0)));
    }
}