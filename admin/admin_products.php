<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function sellpress_products_page_html()
{
    global $wpdb;

    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'edit':
                $id = absint($_GET['id']);
                $product = $wpdb->get_row($wpdb->prepare('select * from `' . $wpdb->prefix . 'sellpress_products` where id=%d', $id), ARRAY_A);
                $product = new SellPress_Product($product);
                include __DIR__ . DIRECTORY_SEPARATOR . 'admin_edit_product_html.php';
                break;
            case 'add':
                $product = new SellPress_Product();
                include __DIR__ . DIRECTORY_SEPARATOR . 'admin_edit_product_html.php';
                break;
        }
    } else {
        $products = $wpdb->get_results('select * from `' . $wpdb->prefix . 'sellpress_products`', ARRAY_A);


        include __DIR__ . DIRECTORY_SEPARATOR . 'admin_products_list_html.php';
    }
}

add_action('admin_init', 'sellpress_save_product');
function sellpress_save_product()
{
    global $wpdb;
    if (isset($_POST['sellpress_save_product'])) {
        if ($_POST['sellpress_product_id']) {
            $id = absint($_POST['sellpress_product_id']);

            if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'sellpress_save_product')) {
                wp_redirect(admin_url('admin.php?page=sellpress_products&action=add&status=error_nonce_validation'));
                exit;
            }

            $errors = array();
            if (!isset($_POST['sellpress_product_name']) || empty($_POST['sellpress_product_name'])) $errors[] = 'product_name_required';
            if (!isset($_POST['sellpress_product_price']) || empty($_POST['sellpress_product_price'])) $errors[] = 'product_price_required';
            if (!empty($errors)) {
                wp_redirect(admin_url('admin.php?page=sellpress_products&action=add&status=' . implode(';', $errors)));
                exit;
            }

            $data = array();
            $data['name'] = sanitize_text_field($_POST['sellpress_product_name']);
            $data['short_description'] = sanitize_text_field($_POST['sellpress_product_short_description']);
            $data['price'] = floatval($_POST['sellpress_product_price']);
            $data['description'] = wp_kses_post($_POST['sellpress_product_description']);
            $images = $_POST['sellpress_product_images'];
            if ($images && is_array($images)) {
                $images = json_encode($images);
            } else {
                $images = null;
            }
            $data['images'] = $images;
            $result = $wpdb->update($wpdb->prefix . 'sellpress_products', $data, array('id' => $id));

            if ($result) {
                wp_redirect(admin_url('admin.php?page=sellpress_products&action=edit&id=' . $id . '&status=product_update_success'));
                exit;
            }
        } else {

            if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'sellpress_save_product')) {
                wp_redirect(admin_url('admin.php?page=sellpress_products&action=add&status=error_nonce_validation'));
                exit;
            }

            $errors = array();
            if (!isset($_POST['sellpress_product_name']) || empty($_POST['sellpress_product_name'])) $errors[] = 'product_name_required';
            if (!isset($_POST['sellpress_product_price']) || empty($_POST['sellpress_product_price'])) $errors[] = 'product_price_required';
            if (!empty($errors)) {
                wp_redirect(admin_url('admin.php?page=sellpress_products&action=add&status=' . implode(';', $errors)));
                exit;
            }

            $data = array();
            $data['name'] = sanitize_text_field($_POST['sellpress_product_name']);
            $data['short_description'] = sanitize_text_field($_POST['sellpress_product_short_description']);
            $data['price'] = floatval($_POST['sellpress_product_price']);
            $data['description'] = wp_kses_post($_POST['sellpress_product_description']);
            $images = $_POST['sellpress_product_images'];
            if ($images && is_array($images)) {
                $images = json_encode($images);
            } else {
                $images = null;
            }
            $data['images'] = $images;
            $result = $wpdb->insert($wpdb->prefix . 'sellpress_products', $data);

            if ($result) {
                wp_redirect(admin_url('admin.php?page=sellpress_products&action=edit&id=' . $wpdb->insert_id . '&status=product_insert_success'));
                exit;
            }
        }
    }
}

add_action('wp_ajax_sellpress_remove_product', 'sellpress_remove_product');
function sellpress_remove_product()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'sellpress_ajax_nonce') || !isset($_POST['id'])) {
        die(json_encode(array('status' => 0)));
    }

    global $wpdb;

    $id = absint($_POST['id']);

    //todo: remove orders???
    /*$orders = $wpdb->get_results($wpdb->prepare('select * from `'.$wpdb->prefix.'sellpress_orders` as o inner join `'.$wpdb->prefix.'sellpress_order_products` as op on o.id=op.order_id where op.product_id=%d', $id));

    if(!empty($orders)) {

    }*/

    $result = $wpdb->delete($wpdb->prefix . 'sellpress_products', array('id' => $id));



    if ($result) {
        die(json_encode(array('status' => 1)));
    } else {
        die(json_encode(array('status' => 0)));
    }
}