<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action('init', 'sellpress_shortcodes');
function sellpress_shortcodes()
{
    add_shortcode('sellpress_product', 'sellpress_product_shortcode');
}


function sellpress_product_shortcode($atts = [])
{
    do_action('sellpress_product');

    wp_enqueue_script('sellpress-elevatezoom', plugin_dir_url(__FILE__) . 'lib/jquery.elevateZoom-3.0.8.min.js', array('jquery'), SELLPRESS_VERSION, true);
    // normalize attribute keys, lowercase
    $atts = array_change_key_case((array)$atts, CASE_LOWER);

    // override default attributes with user attributes
    $atts = shortcode_atts([
        'id' => null,
    ], $atts);

    if (!$atts['id']) {
        return '<h2>' . __('SellPress product shortcode requires valid "id" parameter', 'sellpress') . '</h2>';
    }

    $id = absint($atts['id']);
    global $wpdb;

    $product = $wpdb->get_row($wpdb->prepare('select * from `' . $wpdb->prefix . 'sellpress_products` where id=%s', $id), ARRAY_A);

    $product = new SellPress_Product($product);

    if (!$product) {
        return '<h2>' . __('Product does not exist', 'sellpress') . '</h2>';
    }

    $paymentSettings = SellPress_Payment_Settings::get();

    $enabledPaymentSettings = array_filter($paymentSettings, function($paymentMethod) {
        return (bool) $paymentMethod['enabled'];
    });

    ob_start();

    include __DIR__ . DIRECTORY_SEPARATOR . 'product_html.php';

    // return output
    return ob_get_clean();
}