<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$column = $wpdb->get_results( $wpdb->prepare(
    "select * from INFORMATION_SCHEMA.COLUMNS where TABLE_SCHEMA = %s and table_name = %s and column_name = %s ",
    DB_NAME, $wpdb->prefix . 'sellpress_orders', 'payment_method'
) );

if ( empty( $column ) ) {
    $wpdb->query('alter table `'.$wpdb->prefix .'sellpress_orders` add column payment_method varchar(20) not null default \'cash\' after payment_status;');
}