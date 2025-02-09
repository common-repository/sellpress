<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$wpdb->query('create table if not exists `' . $wpdb->prefix . 'sellpress_orders`
(
    id bigint(20) primary key AUTO_INCREMENT not null,
    shipping_first_name varchar(255),
    shipping_last_name varchar(255),
    shipping_phone varchar(255),
    shipping_address_1 varchar(255),
    shipping_address_2 varchar(255),
    shipping_country varchar(255),
    shipping_city varchar(255),
    shipping_state varchar(255),
    shipping_notes text,
    total_amount decimal(16,8),
    status int(1) not null default \'0\' COMMENT \'0 - pending
1 - processing
2 - in progress
3 - completed\',
    payment_status int(1) not null default \'0\' COMMENT \'0 - pending
1 - paid
2 - failed\',
    created_at datetime default now()
) ENGINE=\'InnoDB\' CHARACTER SET utf8 COLLATE utf8_general_ci;');