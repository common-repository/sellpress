<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function sellpress_settings_page_html()
{
    global $wpdb;

    $generalSettings = @json_decode($wpdb->get_var('select value from `' . $wpdb->prefix . 'sellpress_settings` where name=\'general\''), true);

    $currencies = include __DIR__ . DIRECTORY_SEPARATOR . '../lib'. DIRECTORY_SEPARATOR .'currency_symbols.php';

    include __DIR__ . DIRECTORY_SEPARATOR . 'admin_settings_html.php';
}


add_action('wp_ajax_sellpress_save_settings', 'sellpress_save_settings');
function sellpress_save_settings()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'sellpress_settings')) {
        die(json_encode(array('status' => 0)));
    }

    if (!$_POST['general_settings']) {
        die(json_encode(array('status' => 0)));
    }

    if (!isset($_POST['general_settings']['currency']) || empty($_POST['general_settings']['currency'])) {
        die(json_encode(array('status' => 0)));
    }

    global $wpdb;


    $result = $wpdb->update($wpdb->prefix . 'sellpress_settings', array(
        'value' => json_encode($_POST['general_settings'])
    ), array('name' => 'general'));

    if ($result) {
        die(json_encode(array('status' => 1)));
    } else {
        die(json_encode(array('status' => 0)));
    }
}