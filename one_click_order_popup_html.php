<?php
/**
 * @var $enabledPaymentSettings array
 * @var $countries array
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$enabledPaymentsCount = count($enabledPaymentSettings);
?>
<div class="sellpress_one_click_order_popup">
    <div class="sellpress_once_click_order_popup_background"></div>
    <div class="sellpress_one_click_order_popup_body">
        <div class="sellpress_one_click_order_popup_close"></div>
        <form action="#" method="post" class="sellpress_one_click_order_popup_form">
            <input type="hidden" name="action" value="sellpress_create_one_click_order"/>
            <?php wp_nonce_field('sellpress_ajax_nonce', 'nonce'); ?>
            <table>
                <tr>
                    <td align="right">
                        <label for="sellpress_one_click_shipping_first_name"><?php _e('First Name', 'sellpress'); ?>
                            <span style="color:red">*</span></label>
                    </td>
                    <td>
                        <input type="text" name="shipping_first_name" id="sellpress_one_click_shipping_first_name"
                               title="<?php _e('First Name', 'sellpress'); ?>" required="required"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="sellpress_one_click_shipping_last_name"><?php _e('Last Name', 'sellpress'); ?><span
                                    style="color:red">*</span></label>
                    </td>
                    <td>
                        <input type="text" name="shipping_last_name" id="sellpress_one_click_shipping_last_name"
                               title="<?php _e('Last Name', 'sellpress'); ?>" required="required"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="sellpress_one_click_shipping_phone"><?php _e('Phone Number', 'sellpress'); ?><span
                                    style="color:red">*</span></label>
                    </td>
                    <td>
                        <input type="text" name="shipping_phone" id="sellpress_one_click_shipping_phone"
                               title="<?php _e('Phone Number', 'sellpress'); ?>" required="required"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="sellpress_one_click_shipping_address_1"><?php _e('Address 1', 'sellpress'); ?><span
                                    style="color:red">*</span></label>
                    </td>
                    <td>
                        <input type="text" name="shipping_address_1" id="sellpress_one_click_shipping_address_1"
                               title="<?php _e('Address 1', 'sellpress'); ?>" required="required"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="sellpress_one_click_shipping_address_2"><?php _e('Address 2', 'sellpress'); ?></label>
                    </td>
                    <td>
                        <input type="text" name="shipping_address_2" id="sellpress_one_click_shipping_address_2"
                               title="<?php _e('Address 2', 'sellpress'); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="sellpress_one_click_shipping_country"><?php _e('Country', 'sellpress'); ?><span
                                    style="color:red">*</span></label>
                    </td>
                    <td>
                        <select name="shipping_country" id="sellpress_one_click_shipping_country" required="required">
                            <option value=""><?php _e('Select Country', 'sellpress'); ?></option>
                            <?php foreach ($countries as $code => $name): ?>
                                <option value="<?php echo $code; ?>"><?php echo $name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="sellpress_one_click_shipping_city"><?php _e('City', 'sellpress'); ?><span
                                    style="color:red">*</span></label>
                    </td>
                    <td>
                        <input type="text" name="shipping_city" id="sellpress_one_click_shipping_city"
                               title="<?php _e('City', 'sellpress'); ?>" required="required"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="sellpress_one_click_shipping_state"><?php _e('State', 'sellpress'); ?></label>
                    </td>
                    <td>
                        <input type="text" name="shipping_state" id="sellpress_one_click_shipping_state"
                               title="<?php _e('State', 'sellpress'); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="sellpress_one_click_shipping_notes"><?php _e('Additional Notes', 'sellpress'); ?></label>
                    </td>
                    <td>
                        <textarea rows="4" id="sellpress_one_click_shipping_notes" name="shipping_notes"></textarea>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="sellpress_one_click_payment_method"><?php _e('Payment Method', 'sellpress'); ?></label>
                    </td>
                    <td>
                        <?php if (empty($enabledPaymentSettings)): ?>
                            <b><?php _e('Not Defined', 'sellpress'); ?></b>
                        <?php else: ?>
                            <select name="payment_method" id="sellpress_one_click_payment_method">
                                <option value=""><?php _e('Select Payment method', 'sellpress'); ?></option>
                                <?php foreach ($enabledPaymentSettings as $paymentMethod => $settings): ?>
                                    <option value="<?php echo $paymentMethod; ?>"><?php echo $settings['display_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <?php if(isset($enabledPaymentSettings['cash'])): ?>
                        <input class="button sellpress_hide" type="submit" name="submit_one_click_order" id="submit_one_click_order_submit"
                               value="<?php _e('Submit Order', 'sellpress'); ?>"/>
                        <span class="sellpress_one_click_popup_loading"></span>
                        <?php endif; ?>

                        <?php if(isset($enabledPaymentSettings['paypal'])): ?>
                            <div id="sellpress_one_click_paypal" class="sellpress_hide"></div>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>