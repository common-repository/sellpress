<?php
/**
 * @var $product SellPress_Product
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

include __DIR__ . DIRECTORY_SEPARATOR . 'admin_status_html.php';

?>
<div class="sellpress_wrap">
    <h1><?php
        if ($product->id) {
            printf(
                __('Editing %s', 'sellpress'),
                $product->name
            );
            echo '<a class="button sellpress_action"
       href="' . admin_url('admin.php?page=sellpress_products&action=add') . '">' . __('Add New Product', 'sellpress') . '</a>';
        } else {
            _e('Adding New Product', 'sellpress');
        }
        ?></h1>
    <form method="post" action="<?php admin_url('admin.php?page=sellpress_products&action=save_product') ?>">
        <?php if ($product->id): ?>
            <input type="hidden" name="sellpress_product_id" value="<?php echo $product->id ?>"/>
        <?php endif; ?>
        <?php wp_nonce_field('sellpress_save_product'); ?>
        <table class="sellpress_form_table">
            <tr>
                <td><?php _e('Product Images', 'sellpress'); ?></td>
                <td>
                    <div class="sellpress_product_images">
                        <a href="#" class="sellpress_product_add_image sellpress_product_image"><span
                                    class="sellpress_product_add_image_inner"><?php _e('Add Photos', 'sellpress'); ?></span></a>
                        <?php
                        $images = $product->images;

                        if (is_string($images)) {
                            $images = @json_decode($images);
                        } elseif (!is_array($images)) {
                            $images = array();
                        }

                        foreach ($images as $image):
                            $media = wp_get_attachment_image_url($image);
                            ?>
                            <a href="#" class="sellpress_product_image sellpress_product_edit_image">
                                <img src="<?php echo $media ?>"/>
                                <span class="sellpress_product_remove_image"></span>
                                <input type="hidden" name="sellpress_product_images[]" value="<?php echo $image; ?>"/>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><?php _e('Product Short Description', 'sellpress'); ?></td>
                <td>
                    <input type="text" name="sellpress_product_short_description"
                           value="<?php echo $product->short_description; ?>"
                           class="sellpress_product_form_short_description"
                           placeholder="<?php _e('Short Description', 'sellpress'); ?>"
                           title="<?php _e('Short Description', 'sellpress'); ?>"/>
                </td>
            </tr>
            <tr>
                <td><?php _e('Product Name', 'sellpress'); ?></td>
                <td>
                    <input type="text" name="sellpress_product_name" value="<?php echo $product->name; ?>"
                           class="sellpress_product_form_name" placeholder="<?php _e('Name', 'sellpress'); ?>"
                           title="<?php _e('Name', 'sellpress'); ?>" required="required"/>
                </td>
            </tr>
            <tr>
                <td><?php _e('Product Price', 'sellpress'); ?></td>
                <td>
                    <input type="number" name="sellpress_product_price" value="<?php echo (float)$product->price; ?>"
                           step="0.01"
                           class="sellpress_product_form_price" placeholder="<?php _e('Price', 'sellpress'); ?>"
                           title="<?php _e('Price', 'sellpress'); ?>" required="required"/>$
                </td>
            </tr>
            <tr>
                <td><?php _e('Product Description', 'sellpress'); ?></td>
                <td>
                    <textarea name="sellpress_product_description" class="sellpress_product_form_description"
                              placeholder="<?php _e('Description', 'sellpress'); ?>"><?php echo $product->description; ?></textarea>
                </td>
            </tr>
            <?php if ($product->id): ?>
                <tr>
                    <td><?php _e('Shortcode', 'sellpress'); ?></td>
                    <td>
                        <input type="text" name="shortcode" title="shortcode"
                               value="[sellpress_product id='<?php echo $product->id; ?>']" readonly="readonly"/>
                    </td>
                </tr>
                <!--<tr>
                    <td><?php /*_e('Public Url','sellpress'); */?></td>
                    <td>
                        <a href="<?php /*echo $product->getPermalink(); */?>"><?php /*echo $product->getPermalink(); */?></a>
                    </td>
                </tr>-->
            <?php endif; ?>
            <tr>
                <td></td>
                <td class="sellpress_form_table_transparent">
                    <input class="button-primary" type="submit" name="sellpress_save_product"
                           value="<?php _e('Save', 'sellpress'); ?>"/>
                </td>
            </tr>
        </table>
    </form>
</div>

<script>
    (function () {
        let addImageButton = document.querySelector('.sellpress_product_add_image');
        let imagesContainer = document.querySelector('.sellpress_product_images');
        let imageItemSample = '<a href="#" class="sellpress_product_image sellpress_product_edit_image">\n' +
            '                <img src="{imageUrl}" />\n' +
            '                <span class="sellpress_product_remove_image"></span>\n' +
            '<input type="hidden" name="sellpress_product_images[]" value="{imageId}" />\n' +
            '            </a>';


        addImageButton.addEventListener('click', addImage);

        function addImage(e) {
            e.preventDefault();

            let uploader = wp.media({
                title: 'Add image',
                button: {
                    text: 'Insert'
                },
                multiple: true,
                library: {
                    type: ['video', 'image']
                },
            })
                .on('select', function () {
                    let attachment = uploader.state().get('selection').first().toJSON();

                    let html = imageItemSample
                        .replace('{imageUrl}', attachment.url)
                        .replace('{imageId}', attachment.id);

                    imagesContainer.insertAdjacentHTML('beforeend', html);

                })
                .open();
        }

        imagesContainer.addEventListener('click', delegateClick);

        function delegateClick(e) {
            e.preventDefault();
            if (e.target.classList.contains('sellpress_product_remove_image')) {
                removeImage(e);
            } else if (e.target.classList.contains('sellpress_product_edit_image')) {
                // edit image?
            }
        }

        function removeImage(e) {
            e.target.parentNode.remove();
        }
    }());
</script>