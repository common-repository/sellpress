<?php
/*
Plugin Name:  SellPress - Sell your products the easiest way
Plugin URI:   http://sellpress.net
Description:  Sell anything online with this easy-to-use plugin
Version:      1.0.2
Author:       SellPress
Author URI:   http://sellpress.net/about
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  sellpress
Domain Path:  /languages

SellPress is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

SellPress is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with SellPress. If not, see http://sellpress.net/license.
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('SELLPRESS_VERSION', '1.0.2');
define('SELLPRESS_DIR_URl', plugin_dir_url(__FILE__));

include __DIR__ . DIRECTORY_SEPARATOR . 'shortcode.php';
include __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'SellPress_Route.php';


if (is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
    include __DIR__ . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'admin.php';
}

class SellPress_Product
{
    public $id;
    public $name;
    public $short_description;
    public $description;
    public $price;
    public $images;
    public $created_at;
    public $visibility;

    public function __construct($args = array())
    {
        if ($args && is_array($args)) {
            foreach ($args as $key => $value) {
                if (property_exists(__CLASS__, $key)) {
                    switch ($key) {
                        case 'images':
                            $this->images = @json_decode($value);
                            break;
                        default:
                            $this->$key = $value;
                            break;
                    }
                }
            }
        }
    }

    public function getPermalink()
    {
        return site_url() . '/product/' . $this->id;
    }

    public function getEditLink()
    {
        return admin_url('admin.php?page=sellpress_products&action=edit&id='.$this->id);
    }
}

class SellPress_Order
{
    const STATUS_PROCESSING = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_CANCELED = 3;

    const PAYMENT_STATUS_PENDING = 0;
    const PAYMENT_STATUS_PAID = 1;
    const PAYMENT_STATUS_FAILED = 2;

    public $id;
    public $shipping_first_name;
    public $shipping_last_name;
    public $shipping_phone;
    public $shipping_address_1;
    public $shipping_address_2;
    public $shipping_country;
    public $shipping_city;
    public $shipping_state;
    public $shipping_notes;
    public $total_amount;
    public $status;
    public $payment_status;
    public $payment_method;
    public $created_at;

    public function __construct($args = array())
    {
        if ($args && is_array($args)) {
            foreach ($args as $key => $value) {
                if (property_exists(__CLASS__, $key)) {
                    $this->$key = $value;
                }
            }
        }
    }
}

class SellPress_General_Settings
{
    private static $settings;
    private static $currency_symbol;
    private static $currency_name;

    /**
     * @return mixed
     */
    public static function getCurrencyName()
    {
        if (!self::$currency_name) {
            $generalSettings = self::get();
            $currencies = include __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'currency_symbols.php';

            self::$currency_name = $generalSettings['currency'];
            self::$currency_symbol = $currencies[$generalSettings['currency']];
        }


        return self::$currency_name;
    }

    /**
     * @return mixed
     */
    public static function getCurrencySymbol()
    {
        if (!self::$currency_name) {
            $generalSettings = self::get();
            $currencies = include __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'currency_symbols.php';

            self::$currency_name = $generalSettings['currency'];
            self::$currency_symbol = $currencies[$generalSettings['currency']];
        }

        return self::$currency_symbol;
    }

    /**
     * @return array|mixed|object
     */
    public static function get()
    {
        if (!self::$settings) {
            global $wpdb;
            self::$settings = @json_decode($wpdb->get_var('select value from `' . $wpdb->prefix . 'sellpress_settings` where name=\'general\''), true);
        }

        return self::$settings;
    }
}

class SellPress_Payment_Settings
{
    private static $settings;

    /**
     * @return array|mixed|object
     */
    public static function get()
    {
        if (!self::$settings) {
            global $wpdb;
            self::$settings = @json_decode($wpdb->get_var('select value from `' . $wpdb->prefix . 'sellpress_settings` where name=\'payment\''), true);
        }

        return self::$settings;
    }
}

register_activation_hook(__FILE__, 'sellpress_check_version');
add_action('init', 'sellpress_check_version', 0);
function sellpress_check_version()
{
    if (get_option('sellpress_version') === SELLPRESS_VERSION) {
        return;
    }

    sellpress_update_db();

    update_option('sellpress_version', SELLPRESS_VERSION);
}


function sellpress_update_db()
{
    global $wpdb;

    require __DIR__ . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . '001_create_products_table.php';
    require __DIR__ . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . '002_create_orders_table.php';
    require __DIR__ . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . '003_create_order_products_table.php';
    require __DIR__ . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . '004_create_settings_table.php';
    require __DIR__ . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . '005_add_payment_method_to_orders_table.php';

}

add_action('sellpress_product', 'sellpress_one_click_order_scripts');
function sellpress_one_click_order_scripts()
{
    wp_enqueue_style('sellpress_frontend', plugin_dir_url(__FILE__) . 'styles/frontend.css', array(), SELLPRESS_VERSION);
    wp_enqueue_style('sellpress_toastr', plugin_dir_url(__FILE__) . 'lib/toastr/toastr.min.css', array(), SELLPRESS_VERSION);
    wp_enqueue_script('sellpress_toastr', plugin_dir_url(__FILE__) . 'lib/toastr/toastr.min.js', array(), SELLPRESS_VERSION, true);
    wp_enqueue_script('sellpress_one_click_order', plugin_dir_url(__FILE__) . 'js/one-click-order.js', array('sellpress_toastr'), SELLPRESS_VERSION, true);

    wp_enqueue_script('sellpress_paypal_checkout', 'https://www.paypalobjects.com/api/checkout.js');

    $paymentSettings = SellPress_Payment_Settings::get();

    $enabledPaymentSettings = array_filter($paymentSettings, function ($paymentMethod) {
        return (bool)$paymentMethod['enabled'];
    });

    $generalSettings = SellPress_General_Settings::get();

    $countries = include __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'countries.php';

    ob_start();

    include __DIR__ . DIRECTORY_SEPARATOR . 'one_click_order_popup_html.php';

    $popupHtml = ob_get_clean();

    wp_localize_script('sellpress_one_click_order', 'sellpressOneClickL10n', array(
        'popupHtml' => $popupHtml,
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'paymentSettings' => $paymentSettings,
        'generalSettings' => $generalSettings
    ));

    wp_enqueue_style('sellpress_one_click_order', plugin_dir_url(__FILE__) . 'styles/one-click-order.css', array(), SELLPRESS_VERSION);

}

add_action('wp_ajax_sellpress_create_one_click_order', 'sellpress_create_one_click_order');
add_action('wp_ajax_nopriv_sellpress_create_one_click_order', 'sellpress_create_one_click_order');
function sellpress_create_one_click_order()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'sellpress_ajax_nonce')) {
        die(json_encode(array(
            'status' => 0,
            'error' => __('Security token failed, please try again later', 'sellpress')
        )));
    }

    $errors = array();

    if (!isset($_POST['product_id']) || empty($_POST['product_id'])) $errors[] = __('Product is not selected', 'sellpress');
    if (!isset($_POST['shipping_first_name']) || empty($_POST['shipping_first_name'])) $errors[] = __('First Name is required', 'sellpress');
    if (!isset($_POST['shipping_last_name']) || empty($_POST['shipping_last_name'])) $errors[] = __('Last Name is required', 'sellpress');
    if (!isset($_POST['shipping_phone']) || empty($_POST['shipping_phone'])) $errors[] = __('Phone Number is required', 'sellpress');
    if (!isset($_POST['shipping_address_1']) || empty($_POST['shipping_address_1'])) $errors[] = __('Address 1 is required', 'sellpress');
    if (!isset($_POST['shipping_country']) || empty($_POST['shipping_country'])) $errors[] = __('Country is required', 'sellpress');
    if (!isset($_POST['shipping_city']) || empty($_POST['shipping_city'])) $errors[] = __('City is required', 'sellpress');

    if (!empty($errors)) {
        die(json_encode(array(
            'status' => 0,
            'error' => implode(PHP_EOL, $errors)
        )));
    }


    global $wpdb;

    $data = array();

    $product = $wpdb->get_row($wpdb->prepare('select * from `' . $wpdb->prefix . 'sellpress_products` where id=%d', $_POST['product_id']));

    $data['shipping_first_name'] = sanitize_text_field($_POST['shipping_first_name']);
    $data['shipping_last_name'] = sanitize_text_field($_POST['shipping_last_name']);
    $data['shipping_phone'] = sanitize_text_field($_POST['shipping_phone']);
    $data['shipping_address_1'] = sanitize_text_field($_POST['shipping_address_1']);
    if (isset($_POST['shipping_address_2']) && !empty($_POST['shipping_address_2']))
        $data['shipping_address_2'] = sanitize_text_field($_POST['shipping_address_2']);
    $data['shipping_country'] = sanitize_text_field($_POST['shipping_country']);
    $data['shipping_city'] = sanitize_text_field($_POST['shipping_city']);
    if (isset($_POST['shipping_state']) && !empty($_POST['shipping_state']))
        $data['shipping_state'] = sanitize_text_field($_POST['shipping_state']);
    if (isset($_POST['shipping_notes']) && !empty($_POST['shipping_notes']))
        $data['shipping_notes'] = sanitize_text_field($_POST['shipping_notes']);

    if (isset($_POST['payment_status'])) {
        switch ($_POST['payment_status']):
            case 'pending':
                $data['payment_status'] = SellPress_Order::PAYMENT_STATUS_PENDING;
                break;
            case 'paid':
                $data['payment_status'] = SellPress_Order::PAYMENT_STATUS_PAID;
                break;
            case 'failed':
                $data['payment_status'] = SellPress_Order::PAYMENT_STATUS_FAILED;
                break;
            default:
                $data['payment_status'] = SellPress_Order::PAYMENT_STATUS_PENDING;
                break;
        endswitch;
    }

    if (isset($_POST['payment_method']) && in_array($_POST['payment_method'], array('cash', 'paypal'))) {
        $data['payment_method'] = $_POST['payment_method'];
    } else {
        $data['payment_method'] = 'cash';
    }

    $data['total_amount'] = $product->price;


    $result = $wpdb->insert($wpdb->prefix . 'sellpress_orders', $data);

    if ($result) {
        $orderId = $wpdb->insert_id;
        $wpdb->insert($wpdb->prefix . 'sellpress_order_products', array(
            'order_id' => $orderId,
            'product_id' => absint($_POST['product_id']),
            'amount' => $product->price,
            'qty' => 1

        ));

        die(json_encode(array(
            'status' => 1
        )));
    } else {
        die(json_encode(array(
            'status' => 1
        )));
    }
}

//todo
/*SellPress_Route::get('product/{product}', 'sellpress_product_page');
function sellpress_product_page($productId){
    $productId = absint($productId);
    global $wpdb;

    $product = $wpdb->get_row($wpdb->prepare('select * from `'.$wpdb->prefix.'sellpress_products` where id=%d', $productId), ARRAY_A);
    $product = new SellPress_Product($product);
    $paymentSettings = SellPress_Payment_Settings::get();

    $enabledPaymentSettings = array_filter($paymentSettings, function($paymentMethod) {
        return (bool) $paymentMethod['enabled'];
    });

    get_header();

    do_action('sellpress_product');

    include __DIR__ . DIRECTORY_SEPARATOR . 'product_html.php';

    get_footer();
}*/

function sellpress_get_status_message($code)
{
    switch ($code) {
        case 'product_update_success':
            return __('Product updated successfully!', 'sellpress');
            break;
        case 'product_insert_success':
            return __('Product added successfully!', 'sellpress');
            break;
        case 'product_name_required':
            return __('Product name is required!', 'sellpress');
            break;
        case 'product_price_required':
            return __('Product price is required!', 'sellpress');
            break;
        case 'error_nonce_validation':
            return __('Could not authorize this action, please try again later', 'sellpress');
            break;
    }
}

add_action('init', 'sellpress_gutenberg_block');
add_filter( 'block_categories', 'sellpress_gutenberg_block_categories', 10, 2 );

function sellpress_gutenberg_block() {
    if (!function_exists('register_block_type')) {
        return;
    }
    wp_register_script('sellpress_gutenberg_block', SELLPRESS_DIR_URl . 'js/block.js', array('wp-blocks', 'wp-element', 'wp-components'));
    wp_register_style('sellpress_gutenberg_block',SELLPRESS_DIR_URl . 'styles/block.css',array('wp-edit-blocks'));

    global $wpdb;

    $products = $wpdb->get_results('select * from `' . $wpdb->prefix . 'sellpress_products`');
    $productOptions = array(array('value'=>'', 'label' => 'Select Product'));
    $productMetas = array();
    if (!empty($products)) {
        foreach ($products as $product) {
            $productOptions[] = [
                'value' => $product->id,
                'label' => $product->name,
            ];
            $productMetas[$product->id] = array(
                'title' => $product->name,
            );
        }
    }

    wp_localize_script('sellpress_gutenberg_block', 'sellpressBlockL10n', array(
        'products' => $productOptions,
        'productMetas' => $productMetas
    ));

    register_block_type('sellpress/product', array(
        'editor_script' => 'sellpress_gutenberg_block',
        'editor_style' => 'sellpress_gutenberg_block',
    ));
}

function sellpress_gutenberg_block_categories($categories, $post) {
    if ($post->post_type !== 'post') {
        return $categories;
    }
    return array_merge(
        $categories,
        array(
            array(
                'slug' => 'sellpress',
                'title' => __('SellPress', 'sellpress'),
            ),
        )
    );
}


