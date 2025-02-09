<?php
/**
 * @var $orders SellPress_Order[]
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

?>
<div class="sellpress_wrap">
    <h1><?php _e('Orders', 'sellpress'); ?></h1>
    <form method="get" action="<?php echo admin_url('admin.php'); ?>" class="sellpress_admin_filters">
        <h2><?php _e('Filter Orders', 'sellpress'); ?></h2>
        <input type="hidden" name="page" value="sellpress_orders" />
        <label class="sellpress_admin_filter">
            <span class="sellpress_admin_filter_label"><?php _e('Status', 'sellpress'); ?></span>
            <select name="status" onchange="this.form.submit();">
                <option value="all"><?php _e('All', 'sellpress'); ?></option>
                <option value="processing" <?php if(isset($_GET['status']) && $_GET['status'] === 'processing') echo 'selected="selected"'; ?> ><?php _e('Processing', 'sellpress'); ?></option>
                <option value="in_progress" <?php if(isset($_GET['status']) && $_GET['status'] === 'in_progress') echo 'selected="selected"'; ?> ><?php _e('In Progress', 'sellpress'); ?></option>
                <option value="completed" <?php if(isset($_GET['status']) && $_GET['status'] === 'completed') echo 'selected="selected"'; ?> ><?php _e('Completed', 'sellpress'); ?></option>
                <option value="canceled" <?php if(isset($_GET['status']) && $_GET['status'] === 'canceled') echo 'selected="selected"'; ?> ><?php _e('Canceled', 'sellpress'); ?></option>
            </select>
        </label>

        <label class="sellpress_admin_filter">
            <span class="sellpress_admin_filter_label"><?php _e('Payment Status', 'sellpress'); ?></span>
            <select name="payment_status" onchange="this.form.submit();">
                <option value="all"><?php _e('All', 'sellpress'); ?></option>
                <option value="pending" <?php if(isset($_GET['payment_status']) && $_GET['payment_status'] === 'pending') echo 'selected="selected"'; ?> ><?php _e('Pending', 'sellpress'); ?></option>
                <option value="paid" <?php if(isset($_GET['payment_status']) && $_GET['payment_status'] === 'paid') echo 'selected="selected"'; ?> ><?php _e('Paid', 'sellpress'); ?></option>
                <option value="failed" <?php if(isset($_GET['payment_status']) && $_GET['payment_status'] === 'failed') echo 'selected="selected"'; ?> ><?php _e('Failed', 'sellpress'); ?></option>
            </select>
        </label>

    </form>

    <table class="widefat fixed striped">
        <thead>
        <tr>
            <th>id</th>
            <th><?php _e('Date', 'sellpress'); ?></th>
            <th><?php _e('Name', 'sellpress'); ?></th>
            <th><?php _e('Phone Number', 'sellpress'); ?></th>
            <th><?php _e('Amount', 'sellpress'); ?></th>
<!--            <th>--><?php //_e('Product(s)', 'sellpress'); ?><!--</th>-->
            <th><?php _e('Status', 'sellpress'); ?></th>
            <th><?php _e('Payment Status', 'sellpress'); ?></th>
            <th><?php _e('Payment Method', 'sellpress'); ?></th>
            <th><?php _e('Actions', 'sellpress'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $order):
            $product_id = $wpdb->get_var($wpdb->prepare('select product_id from `' . $wpdb->prefix . 'sellpress_order_products` where order_id=%d', $order->id));
            $product = $wpdb->get_row($wpdb->prepare('select * from `' . $wpdb->prefix . 'sellpress_products` where id=%d', $product_id));
            ?>
            <tr data-id="<?php echo $order->id; ?>">
                <td><?php echo $order->id; ?></td>
                <td><?php echo date('r',strtotime($order->created_at)); ?></td>
                <td><?php echo $order->shipping_first_name . ' ' . $order->shipping_last_name; ?></td>
                <td><?php echo $order->shipping_phone; ?></td>
                <td><?php echo SellPress_General_Settings::getCurrencySymbol() . (float) $order->total_amount; ?></td>
<!--                <td>--><?php //echo $product->name; ?><!--</td>-->
                <td>
                    <select class="sellpress_order_status">
                        <option value="<?php echo SellPress_Order::STATUS_PROCESSING; ?>" <?php selected($order->status, SellPress_Order::STATUS_PROCESSING); ?> ><?php _e('Processing', 'sellpress'); ?></option>
                        <option value="<?php echo SellPress_Order::STATUS_IN_PROGRESS; ?>" <?php selected($order->status, SellPress_Order::STATUS_IN_PROGRESS); ?> ><?php _e('In Progress', 'sellpress'); ?></option>
                        <option value="<?php echo SellPress_Order::STATUS_COMPLETED; ?>" <?php selected($order->status, SellPress_Order::STATUS_COMPLETED); ?> ><?php _e('Completed', 'sellpress'); ?></option>
                        <option value="<?php echo SellPress_Order::STATUS_CANCELED; ?>" <?php selected($order->status, SellPress_Order::STATUS_CANCELED); ?> ><?php _e('Canceled', 'sellpress'); ?></option>
                    </select>
                </td>
                <td>
                    <select class="sellpress_order_payment_status">
                        <option value="<?php echo SellPress_Order::PAYMENT_STATUS_PENDING; ?>" <?php selected($order->payment_status, SellPress_Order::PAYMENT_STATUS_PENDING); ?> ><?php _e('Pending', 'sellpress'); ?></option>
                        <option value="<?php echo SellPress_Order::PAYMENT_STATUS_PAID; ?>" <?php selected($order->payment_status, SellPress_Order::PAYMENT_STATUS_PAID); ?> ><?php _e('Paid', 'sellpress'); ?></option>
                        <option value="<?php echo SellPress_Order::PAYMENT_STATUS_FAILED; ?>" <?php selected($order->payment_status, SellPress_Order::PAYMENT_STATUS_FAILED); ?> ><?php _e('Failed', 'sellpress'); ?></option>
                    </select>
                </td>
                <td>
                    <?php echo ucfirst($order->payment_method); ?>
                </td>
                <td>
                    <a href="<?php echo admin_url('admin.php?page=sellpress_orders&id='.$order->id); ?>">View & Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
