<?php
/**
 * @var $generalSettings array
 * @var $currencies array
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="sellpress_wrap">
    <h1><?php _e('General Settings', 'sellpress'); ?></h1>
    <form id="sellpress_settings_form" method="post" action="<?php admin_url('admin.php?page=sellpress_settings'); ?>">
        <?php wp_nonce_field('sellpress_settings', 'nonce'); ?>
        <input type="hidden" name="action" value="sellpress_save_settings" />
        <table class="sellpress_settings_data_table">
            <tr>
                <td>Currency</td>
                <td>
                    <select name="general_settings[currency]" title="<?php _e('Currency', 'sellpress'); ?>">
                        <?php foreach ($currencies as $name => $symbol): ?>
                            <option value="<?php echo $name; ?>" <?php selected($generalSettings['currency'], $name); ?>><?php echo $name . ' ('.$symbol.')'; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" name="sellpress_save_settings" value="<?php _e('Save', 'sellpress'); ?>" class="button-primary" id="sellpress_settings_save" />
                </td>
            </tr>
        </table>
    </form>
</div>

<script>
    (function () {
        let form = document.getElementById('sellpress_settings_form');

        form.addEventListener('submit', submit);

        function submit(e){
            e.preventDefault();

            let formData = new FormData(e.target);
            let submitBtn = document.getElementById('sellpress_settings_save');


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
                        toastr.success('Settings Saved Successfully')
                    } else {
                        if (result.error) {
                            toastr.error(json.error);
                        }
                    }
                })
        }
    }());
</script>

