<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$wpdb->query('create table if not exists `' . $wpdb->prefix . 'sellpress_order_products`
(
    order_id bigint(20),
    product_id bigint(20),
    amount decimal(16,8),
    qty int(20) unsigned,
    UNIQUE KEY (order_id, product_id),
    FOREIGN KEY (order_id) REFERENCES ' . $wpdb->prefix . 'sellpress_orders (id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES ' . $wpdb->prefix . 'sellpress_products (id) ON DELETE CASCADE
) ENGINE=\'InnoDB\' CHARACTER SET utf8 COLLATE utf8_general_ci;');