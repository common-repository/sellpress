<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$wpdb->query('create table if not exists `' . $wpdb->prefix . 'sellpress_settings` 
(
    id bigint(20) primary key AUTO_INCREMENT  not null,
    name varchar(255),
    value longtext
) ENGINE=\'InnoDB\' CHARACTER SET utf8 COLLATE utf8_general_ci;');

if (empty($wpdb->get_row('select * from `' . $wpdb->prefix . 'sellpress_settings` where name=\'general\''))) {
    $wpdb->insert($wpdb->prefix . 'sellpress_settings', array(
        'name' => 'general',
        'value' => json_encode(array(
            'currency' => 'USD'
        ))
    ));
}

if (empty($wpdb->get_row('select * from `' . $wpdb->prefix . 'sellpress_settings` where name=\'payment\''))) {
    $wpdb->insert($wpdb->prefix . 'sellpress_settings', array(
        'name' => 'payment',
        'value' => json_encode(array(
            'paypal' => array(
                'enabled' => false,
                'display_name' => 'PayPal',
//                'email' => '',
                'sandbox' => false,
                'client_id' => '',
                'sandbox_client_id' => '',
            ),
            'cash' => array(
                'enabled' => true,
                'display_name' => 'Cash on delivery'
            )
        ))
    ));
}


