<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$wpdb->query('create table if not exists `' . $wpdb->prefix . 'sellpress_products` 
(
    id bigint(20) primary key AUTO_INCREMENT  not null,
    name varchar(255),
    description longtext,
    short_description varchar(255),
    price decimal(16,8),
    images longtext COMMENT \'array of media item ids\',
    visibility int(1) default \'0\',
    created_at datetime default now()
) ENGINE=\'InnoDB\' CHARACTER SET utf8 COLLATE utf8_general_ci;');