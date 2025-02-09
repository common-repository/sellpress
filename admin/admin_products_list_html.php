<?php
/**
 * @var $products []
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

?>
<div class="sellpress_wrap">
    <h1><?php _e('Products', 'sellpress'); ?><a class="button sellpress_action"
                                                href="<?php echo admin_url('admin.php?page=sellpress_products&action=add'); ?>"><?php _e('Add New Product', 'sellpress'); ?></a>
    </h1>

    <table class="widefat striped">
        <thead>
        <tr>
            <th>id</th>
            <th><?php _e('name', 'sellpress'); ?></th>
            <th><?php _e('price', 'sellpress'); ?></th>
            <th><?php _e('shortcode', 'sellpress'); ?></th>
            <th><?php _e('actions', 'sellpress'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product):
            $product = new SellPress_Product($product);
            ?>
            <tr data-id="<?php echo $product->id; ?>">
                <td><?php echo $product->id; ?></td>
                <td>
                    <a href="<?php echo admin_url('admin.php?page=sellpress_products&action=edit&id=' . $product->id); ?>"><?php echo $product->name; ?></a>
                </td>
                <td><?php echo SellPress_General_Settings::getCurrencySymbol() . (float)$product->price; ?></td>
                <td><input type="text" readonly="readonly" value="[sellpress_product id='<?php echo $product->id; ?>']"
                           title="<?php _e('shortcode', 'sellpress'); ?>"/></td>
                <td>
                    <a href="<?php echo $product->getEditLink(); ?>"><?php _e('Edit', 'sellpress'); ?></a>
                    &nbsp;&nbsp;<a href="#" class="sellpress_remove_product"><?php _e('Remove', 'sellpress'); ?></a>
                    <span class="sellpress_loading"></span>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    (function () {
        let adminUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
        let nonce = '<?php echo wp_create_nonce('sellpress_ajax_nonce'); ?>';
        let removeButtons = document.querySelectorAll('.sellpress_remove_product');

        [...removeButtons].forEach(btn => {
            btn.addEventListener('click', removeProduct);
        });

        function removeProduct(e) {
            e.preventDefault();

            const id = +e.target.closest('tr').getAttribute('data-id');

            let conf = confirm('Please, confirm product removal');

            if(!conf) {
                return false;
            }

            let loading = e.target.parentNode.querySelector('.sellpress_loading');
            e.target.style.display = 'none';

            loading.classList.add('active');
            jQuery.post(adminUrl, {
                nonce: nonce,
                action: 'sellpress_remove_product',
                id: id
            }, function (data) {

                if (data && data.status) {
                    let row = e.target.closest('tr');
                    row.classList.add('sellpress_fadeout');
                    setTimeout(function () {
                        row.remove();
                    }, 200);
                } else {
                    loading.classList.remove('active');
                    e.target.style.display = 'initial';
                }
            }, 'json');
        }
    }())
</script>