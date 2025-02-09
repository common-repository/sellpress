<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once __DIR__ . DIRECTORY_SEPARATOR . 'admin_products.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'admin_orders.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'admin_settings.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'admin_payment_settings.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'admin_media_button.php';

add_action('admin_menu', 'sellpress_admin_page');
function sellpress_admin_page()
{
    do_action('sellpress_before_admin_menu');

    $GLOBALS['sellpress_page_hook'] = add_menu_page(
        'SellPress',
        'SellPress',
        'manage_options',
        'sellpress_products',
        'sellpress_products_page_html',
        SELLPRESS_DIR_URl . 'images/icon.png'
    );

    $GLOBALS['sellpress_products_page_hook'] = add_submenu_page(
        'sellpress_products',
        'Products - SellPress',
        'Products',
        'manage_options',
        'sellpress_products',
        'sellpress_products_page_html'
    );

    $GLOBALS['sellpress_orders_page_hook'] = add_submenu_page(
        'sellpress_products',
        'Orders - SellPress',
        'Orders', 'manage_options',
        'sellpress_orders',
        'sellpress_orders_page_html'
    );

    $GLOBALS['sellpress_settings_page_hook'] = add_submenu_page(
        'sellpress_products',
        'Settings - SellPress',
        'Settings', 'manage_options',
        'sellpress_settings',
        'sellpress_settings_page_html'
    );

    $GLOBALS['sellpress_payment_settings_page_hook'] = add_submenu_page(
        'sellpress_products',
        'Payment Settings - SellPress',
        'Payment Settings', 'manage_options',
        'sellpress_payment_settings',
        'sellpress_payment_settings_page_html'
    );

    do_action('sellpress_after_admin_menu');
}

add_action('admin_enqueue_scripts', 'sellpress_enqueue');
function sellpress_enqueue($hook)
{
    $pageHooks = apply_filters('sellpress_admin_assets_pages', array(
        $GLOBALS['sellpress_products_page_hook'],
        $GLOBALS['sellpress_orders_page_hook'],
        $GLOBALS['sellpress_settings_page_hook'],
        $GLOBALS['sellpress_payment_settings_page_hook'],
    ));

    if (in_array($hook, $pageHooks)) {
        wp_enqueue_style('sellpress_admin_styles', SELLPRESS_DIR_URl . 'styles/admin_style.css');

        wp_enqueue_style('sellpress_toastr', SELLPRESS_DIR_URl . 'lib/toastr/toastr.min.css', array(), SELLPRESS_VERSION);
        wp_enqueue_script('sellpress_toastr', SELLPRESS_DIR_URl . 'lib/toastr/toastr.min.js', array(), SELLPRESS_VERSION, true);

        wp_enqueue_script('sellpress_admin_order_statuses', SELLPRESS_DIR_URl . 'js/admin-order-statuses.js', array('sellpress_toastr'), SELLPRESS_VERSION, true);
        wp_localize_script('sellpress_admin_order_statuses', 'sellpressL10n', array(
            'nonce' => wp_create_nonce('sellpress_ajax_nonce'),
            'ajaxUrl' => admin_url('admin-ajax.php')
        ));


        if ($hook === $GLOBALS['sellpress_products_page_hook']) {
            wp_enqueue_media();
        }
    }
}