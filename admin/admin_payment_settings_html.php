<?php
/**
 * @var $paymentSettings array
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="sellpress_wrap">
    <h1><?php _e('Payment Settings', 'sellpress'); ?></h1>
    <form id="sellpress_payment_settings_form" method="post" action="<?php admin_url('admin.php?page=sellpress_payment_settings'); ?>">
        <?php wp_nonce_field('sellpress_payment_settings', 'nonce'); ?>
        <input type="hidden" name="action" value="sellpress_save_payment_settings" />
        <table class="sellpress_settings_data_table">
            <!--Cash-->
            <tr>
                <td></td>
                <td class="sellpress_table_heading"><b><?php _e('Cash Payment Settings', 'sellpress'); ?></b></td>
            </tr>
            <tr>
                <td><?php _e('Cash Payments Enabled', 'sellpress'); ?></td>
                <td>
                    <label class="sellpress_switch">
                        <input type="checkbox" name="cash_enabled" <?php checked($paymentSettings['cash']['enabled']); ?> value="yes">
                        <span class="sellpress_slider round"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td><?php _e('Display Name', 'sellpress'); ?></td>
                <td>
                    <input type="text" name="cash_display_name"  value="<?php echo $paymentSettings['cash']['display_name']; ?>" placeholder="<?php _e('Display Name', 'sellpress'); ?>" title="<?php _e('Display Name', 'sellpress'); ?>">
                </td>
            </tr>
            <!--End Cash-->

            <!--PayPal-->
            <tr>
                <td></td>
                <td class="sellpress_table_heading"><b><?php _e('PayPal Payment Settings', 'sellpress'); ?></b></td>
            </tr>
            <tr>
                <td><?php _e('PayPal Payments Enabled', 'sellpress'); ?></td>
                <td>
                    <label class="sellpress_switch">
                        <input type="checkbox" name="paypal_enabled" <?php checked($paymentSettings['paypal']['enabled']); ?> value="yes">
                        <span class="sellpress_slider round"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td><?php _e('Display Name', 'sellpress'); ?></td>
                <td>
                    <input type="text" name="paypal_display_name"  value="<?php echo $paymentSettings['paypal']['display_name']; ?>" placeholder="<?php _e('Display Name', 'sellpress'); ?>" title="<?php _e('Display Name', 'sellpress'); ?>">
                </td>
            </tr>
            <tr>
                <td><?php _e('Sandbox mode', 'sellpress'); ?></td>
                <td>
                    <label class="sellpress_switch">
                        <input type="checkbox" name="paypal_sandbox" <?php checked($paymentSettings['paypal']['sandbox']); ?> value="yes">
                        <span class="sellpress_slider round"></span>
                    </label>
                </td>
            </tr>
           <!-- <tr>
                <td><?php /*_e('PayPal Email', 'sellpress'); */?></td>
                <td>
                    <input type="email" name="paypal_email" value="<?php /*echo $paymentSettings['paypal']['email']; */?>" placeholder="<?php /*_e('Email', 'sellpress'); */?>" title="Email" >
                </td>
            </tr>-->
            <tr>
                <td><?php _e('Client ID', 'sellpress'); ?></td>
                <td>
                    <input type="text" name="paypal_client_id" value="<?php echo $paymentSettings['paypal']['client_id']; ?>" placeholder="<?php _e('Client ID', 'sellpress'); ?>" title="Client ID" >
                </td>
            </tr>
            <tr>
                <td><?php _e('Sandbox Client ID', 'sellpress'); ?></td>
                <td>
                    <input type="text" name="paypal_sandbox_client_id" value="<?php echo $paymentSettings['paypal']['sandbox_client_id']; ?>" placeholder="<?php _e('Sandbox Client ID', 'sellpress'); ?>" title="Sandbox Client ID" >
                </td>
            </tr>
            <!--End PayPal-->

            <tr>
                <td></td>
                <td>
                    <input type="submit" name="sellpress_save_payment_settings" value="<?php _e('Save', 'sellpress'); ?>" class="button-primary" id="sellpress_payment_settings_save" />
                </td>
            </tr>
        </table>
    </form>
</div>

<script>
    (function () {
        let form = document.getElementById('sellpress_payment_settings_form');

        form.addEventListener('submit', submit);

        function submit(e){
            e.preventDefault();

            let formData = new FormData(e.target);
            let submitBtn = document.getElementById('sellpress_payment_settings_save');


            submitBtn.setAttribute('disabled', true);
            fetch("<?php echo admin_url('admin-ajax.php'); ?>", {
                method: 'post',
                body: formData
            })
                .then(function (response) {
                    submitBtn.removeAttribute('disabled');
                    if (response.status === 200) {
                        return response.json();
                    } else {
                        toastr.error('Something went wrong, please try again later');
                    }

                })
                .then(function (result) {
                    if (result.status) {
                        toastr.success('Payment Settings Saved Successfully')
                    } else {
                        if (result.error) {
                            toastr.error(json.error);
                        }
                    }
                })
        }
    }());
</script>