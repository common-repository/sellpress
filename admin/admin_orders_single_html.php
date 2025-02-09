<?php
/**
 * @var $order SellPress_Order
 * @var $countries array
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

?>
<div  class="sellpress_wrap">
    <h1><?php printf(__('Viewing Order #%d', 'sellpress'), $order->id); ?></h1>

    <table id="sellpress_order_data_table" >
        <tr>
            <td>ID</td>
            <td><b><?php echo $order->id; ?></b></td>
        </tr>
        <tr>
            <td><?php _e('First Name', 'sellpress'); ?></td>
            <td><b><?php echo $order->shipping_first_name; ?></b></td>
        </tr>
        <tr>
            <td><?php _e('Last Name', 'sellpress'); ?></td>
            <td><b><?php echo $order->shipping_last_name; ?></b></td>
        </tr>
        <tr>
            <td><?php _e('Phone Number', 'sellpress'); ?></td>
            <td><b><?php echo $order->shipping_phone; ?></b></td>
        </tr>
        <tr>
            <td><?php _e('Address 1', 'sellpress'); ?></td>
            <td><b><?php echo $order->shipping_address_1; ?></b></td>
        </tr>
        <tr>
            <td><?php _e('Address 2', 'sellpress'); ?></td>
            <td><b><?php echo $order->shipping_address_2; ?></b></td>
        </tr>
        <tr>
            <td><?php _e('Country', 'sellpress'); ?></td>
            <td><b><?php echo $countries[$order->shipping_country] . ' (' . $order->shipping_country . ')'; ?></b></td>
        </tr>
        <tr>
            <td><?php _e('City', 'sellpress'); ?></td>
            <td><b><?php echo $order->shipping_city; ?></b></td>
        </tr>
        <tr>
            <td><?php _e('State', 'sellpress'); ?></td>
            <td><b><?php echo $order->shipping_state; ?></b></td>
        </tr>
        <tr>
            <td><?php _e('Additional Notes', 'sellpress'); ?></td>
            <td><b><?php echo $order->shipping_notes; ?></b></td>
        </tr>
        <tr>
            <td><?php _e('Total Amount', 'sellpress'); ?></td>
            <td><b><?php echo SellPress_General_Settings::getCurrencySymbol() . (float) $order->total_amount; ?></b></td>
        </tr>
        <tr>
            <td><?php _e('Product', 'sellpress'); ?></td>
            <td>
                <?php
                $product_id = $wpdb->get_var($wpdb->prepare('select product_id from `' . $wpdb->prefix . 'sellpress_order_products` where order_id=%d', $order->id));
                $product = $wpdb->get_row($wpdb->prepare('select * from `' . $wpdb->prefix . 'sellpress_products` where id=%d', $product_id), ARRAY_A);
                if($product):
                    $product = new SellPress_Product($product);
                    ?>
                    <a href="<?php echo $product->getEditLink(); ?>"><?php echo $product->name; ?></a>
                    <?php
                else:
                    echo '<b>Removed product</b>';
                endif;
                ?>
            </td>
        </tr>
        <tr data-id="<?php echo $order->id ?>">
            <td><?php _e('Status', 'sellpress'); ?></td>
            <td>
                <select class="sellpress_order_status">
                    <option value="<?php echo SellPress_Order::STATUS_PROCESSING; ?>" <?php selected($order->status, SellPress_Order::STATUS_PROCESSING); ?> ><?php _e('Processing', 'sellpress'); ?></option>
                    <option value="<?php echo SellPress_Order::STATUS_IN_PROGRESS; ?>" <?php selected($order->status, SellPress_Order::STATUS_IN_PROGRESS); ?> ><?php _e('In Progress', 'sellpress'); ?></option>
                    <option value="<?php echo SellPress_Order::STATUS_COMPLETED; ?>" <?php selected($order->status, SellPress_Order::STATUS_COMPLETED); ?> ><?php _e('Completed', 'sellpress'); ?></option>
                </select>
            </td>
        </tr>
        <tr data-id="<?php echo $order->id ?>">
            <td><?php _e('Payment Status', 'sellpress'); ?></td>
            <td>
                <select class="sellpress_order_payment_status">
                    <option value="<?php echo SellPress_Order::PAYMENT_STATUS_PENDING; ?>" <?php selected($order->payment_status, SellPress_Order::PAYMENT_STATUS_PENDING); ?> ><?php _e('Pending', 'sellpress'); ?></option>
                    <option value="<?php echo SellPress_Order::PAYMENT_STATUS_PAID; ?>" <?php selected($order->payment_status, SellPress_Order::PAYMENT_STATUS_PAID); ?> ><?php _e('Paid', 'sellpress'); ?></option>
                    <option value="<?php echo SellPress_Order::PAYMENT_STATUS_FAILED; ?>" <?php selected($order->payment_status, SellPress_Order::PAYMENT_STATUS_FAILED); ?> ><?php _e('Failed', 'sellpress'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php _e('Payment Method', 'sellpress'); ?></td>
            <td><b><?php echo ucfirst($order->payment_method); ?></b></td>
        </tr>
    </table>
</div>


<style>
    #sellpress_order_data_table {
        width: 100%;
        border-spacing: 0;
        border-collapse: collapse;
    }

    #sellpress_order_data_table tr td {
        color: #555;
        font-size: 13px;
        line-height: 1.5em;
        padding: 8px 10px;
    }

    #sellpress_order_data_table tr td:first-child {
        width: 150px;
        text-align: right;
    }

    #sellpress_order_data_table tr td:nth-child(2) {
        background-color: white;
        border: 1px solid #c8c8c8
    }
</style>