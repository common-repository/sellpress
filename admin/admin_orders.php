<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function sellpress_orders_page_html()
{
    global $wpdb;
    if (isset($_GET['id'])) {
        $order = $wpdb->get_row($wpdb->prepare('select * from `' . $wpdb->prefix . 'sellpress_orders` where id=%d', absint($_GET['id'])));
        $countries = include __DIR__ . DIRECTORY_SEPARATOR . '../' . 'lib' . DIRECTORY_SEPARATOR . 'countries.php';
        include __DIR__ . DIRECTORY_SEPARATOR . 'admin_orders_single_html.php';
    } else {
        $query = 'select * from `' . $wpdb->prefix . 'sellpress_orders`';

        $ordering = 'order by id desc';
        $where = '';

        $allowedStatuses = array(SellPress_Order::STATUS_PROCESSING, SellPress_Order::STATUS_IN_PROGRESS, SellPress_Order::STATUS_COMPLETED, SellPress_Order::STATUS_CANCELED);
        if(isset($_GET['status']) && in_array($_GET['status'], $allowedStatuses)) {
            switch ($_GET['status']) {
                case 'processing':
                    $where .= ($where ? 'and status = \''. SellPress_Order::STATUS_PROCESSING . '\'' : 'where status=\''. SellPress_Order::STATUS_PROCESSING .'\'');
                    break;
                case 'in_progress':
                    $where .= ($where ? 'and status = \''. SellPress_Order::STATUS_IN_PROGRESS . '\'' : 'where status=\''. SellPress_Order::STATUS_IN_PROGRESS .'\'');
                    break;
                case 'completed':
                    $where .= ($where ? 'and status = \''. SellPress_Order::STATUS_COMPLETED . '\'' : 'where status=\''. SellPress_Order::STATUS_COMPLETED .'\'');
                    break;
                case 'canceled':
                    $where .= ($where ? 'and status = \''. SellPress_Order::STATUS_CANCELED . '\'' : 'where status=\''. SellPress_Order::STATUS_CANCELED .'\'');
                    break;
            }
        }

        $allowedPaymentStatuses = array(SellPress_Order::PAYMENT_STATUS_PENDING, SellPress_Order::PAYMENT_STATUS_PAID, SellPress_Order::PAYMENT_STATUS_FAILED);
        if(isset($_GET['payment_status']) && in_array($_GET['payment_status'], $allowedPaymentStatuses)) {
            switch ($_GET['payment_status']) {
                case 'pending':
                    $where .= ($where ? 'and payment_status = \''. SellPress_Order::PAYMENT_STATUS_PENDING . '\'' : 'where payment_status=\''. SellPress_Order::PAYMENT_STATUS_PENDING .'\'');
                    break;
                case 'paid':
                    $where .= ($where ? 'and payment_status = \''. SellPress_Order::PAYMENT_STATUS_PAID . '\'' : 'where payment_status=\''. SellPress_Order::PAYMENT_STATUS_PAID .'\'');
                    break;
                case 'failed':
                    $where .= ($where ? 'and payment_status = \''. SellPress_Order::PAYMENT_STATUS_FAILED . '\'' : 'where payment_status=\''. SellPress_Order::PAYMENT_STATUS_FAILED .'\'');
                    break;
            }
        }

        $orders = $wpdb->get_results($query . ($where ? ' ' . $where: '') . ' '. $ordering);

        include __DIR__ . DIRECTORY_SEPARATOR . 'admin_orders_list_html.php';
    }
}

add_action('wp_ajax_sellpress_update_order_status', 'sellpress_update_order_status');
function sellpress_update_order_status()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'sellpress_ajax_nonce')) {
        die(json_encode(array(
            'status' => 0,
            'error' => __('Security token failed, please try again later', 'sellpress')
        )));
    }

    global $wpdb;

    $errors = array();

    $allowedStatuses = array(SellPress_Order::STATUS_PROCESSING, SellPress_Order::STATUS_IN_PROGRESS, SellPress_Order::STATUS_COMPLETED, SellPress_Order::STATUS_CANCELED);

    if (!isset($_POST['status']) || !in_array($_POST['status'], $allowedStatuses))
        $errors[] = __('Status field is invalid', 'sellpress');

    if (!isset($_POST['id']) || empty($_POST['id']))
        $errors[] = __('"id" field is required', 'sellpress');

    if (!empty($errors)) {
        die(json_encode(array(
            'status' => 0,
            'error' => implode(PHP_EOL, $errors)
        )));
    }

    $result = $wpdb->update($wpdb->prefix . 'sellpress_orders', array('status' => $_POST['status']), array('id' => absint($_POST['id'])));

    if ($result) {
        die(json_encode(array(
            'status' => 1,
        )));
    } else {
        die(json_encode(array(
            'status' => 0,
            'error' => __('Could not update order', 'sellpress')
        )));
    }
}

add_action('wp_ajax_sellpress_update_order_payment_status', 'sellpress_update_order_payment_status');
function sellpress_update_order_payment_status()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'sellpress_ajax_nonce')) {
        die(json_encode(array(
            'status' => 0,
            'error' => __('Security token failed, please try again later', 'sellpress')
        )));
    }

    global $wpdb;

    $errors = array();

    if (!isset($_POST['payment_status']) || !in_array($_POST['payment_status'], array(SellPress_Order::PAYMENT_STATUS_FAILED, SellPress_Order::PAYMENT_STATUS_PAID, SellPress_Order::PAYMENT_STATUS_PENDING))) $errors[] = __('Status field is invalid', 'sellpress');
    if (!isset($_POST['id']) || empty($_POST['id'])) $errors[] = __('"id" field is required', 'sellpress');

    if (!empty($errors)) {
        die(json_encode(array(
            'status' => 0,
            'error' => implode(PHP_EOL, $errors)
        )));
    }

    $result = $wpdb->update($wpdb->prefix . 'sellpress_orders', array('payment_status' => $_POST['payment_status']), array('id' => absint($_POST['id'])));

    if ($result) {
        die(json_encode(array(
            'status' => 1,
        )));
    } else {
        die(json_encode(array(
            'status' => 0,
            'error' => __('Could not update order', 'sellpress')
        )));
    }
}