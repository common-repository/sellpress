<?php
/**
 * @var $product SellPress_Product
 * @var $enabledPaymentSettings array
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<section class="sellpress_product sellpress_container">
    <div class="sellpress_product_meta">
        <div class="sellpress_product_images">
            <div class="sellpress_product_featured_image">
                <?php if ($product->images && count($product->images)): ?>
                    <img id="sellpress_product_image"
                         src="<?php echo wp_get_attachment_image_url($product->images[0], 'medium'); ?>"
                         alt="<?php echo $product->name; ?>"
                         data-zoom-image="<?php echo wp_get_attachment_image_url($product->images[0], 'full'); ?>"/>
                <?php else: ?>
                    <img id="sellpress_product_image"
                         src="<?php echo plugin_dir_url(__FILE__) . 'images/picture.svg'; ?>" width="300"
                         data-zoom-image="<?php echo plugin_dir_url(__FILE__) . 'images/picture.svg'; ?>"/>
                <?php endif; ?>
            </div>
            <?php if ($product->images && count($product->images)): ?>
                <div id="sellpress_product_gallery" class="sellpress_product_secondary_images">
                    <?php foreach ($product->images as $i => $image):
                        $url = wp_get_attachment_image_url($image, 'thumbnail');
                        ?>
                        <a href="#"
                           class="sellpress_product_secondary_image <?php if ($i === 0) echo 'sellpress_active_image'; ?>"
                           data-image="<?php echo wp_get_attachment_image_url($image, 'medium'); ?>"
                           data-zoom-image="<?php echo wp_get_attachment_image_url($image, 'full'); ?>">
                            <img src="<?php echo $url; ?>" alt="<?php echo $product->name ?>"/>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="sellpress_product_meta_info">
            <h1><?php echo $product->name; ?></h1>
            <div class="sellpress_product_price"><?php _e('Price', 'sellpress'); ?> -
                <u><?php echo SellPress_General_Settings::getCurrencySymbol() . (float)$product->price; ?></u></div>
            <div class="sellpress_product_short_description"><?php echo $product->short_description; ?></div>
            <div class="sellpress_product_create_order">
                <a href="#" class="sellpress_one_click_order"
                   data-product-id="<?php echo $product->id; ?>"
                   data-product-price="<?php echo (float) $product->price; ?>"><?php _e('Order Now', 'sellpress'); ?></a>
            </div>
        </div>
    </div>

    <?php if ($product->description && !empty($product->description)): ?>
        <h2 class="sellpress_product_details_title"><?php _e('Product Description', 'sellpress'); ?></h2>
        <div class="sellpress_product_details">
            <?php echo $product->description; ?>
        </div>
    <?php endif; ?>

</section>

<style>
    .sellpress_product img {
        max-width: 100% !important;
        box-shadow: none !important;
        outline: none !important;
    }

    .sellpress_product_meta {
        display: flex;
        justify-content: flex-start;
        align-items: flex-start;
    }

    .sellpress_product_images {
        margin-right: 10px;
        width: 300px;
        box-sizing: content-box;
    }

    .sellpress_product_featured_image {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        border: 1px solid gray;
        margin-bottom: 10px;
    }

    .sellpress_product_featured_image img {
        max-width: 100% !important;
        max-height: 100% !important;
    }

    .sellpress_product_secondary_images {
        display: flex;
        justify-content: flex-start;
        align-items: flex-start;
        flex-flow: row wrap;
    }

    .sellpress_product_secondary_image {
        display: inline-flex !important;
        justify-content: center !important;
        align-items: center !important;
        width: 92px !important;
        height: 92px !important;
        border: 1px solid gray !important;
        outline: none !important;
        box-shadow: none !important;
        line-height: initial;
        box-sizing: content-box;
        margin-right: 5px;
        margin-bottom: 5px;
    }

    .sellpress_product_secondary_image img {
        max-width: 100%;
        max-height: 100%;
    }

    .sellpress_product_price {
        margin-bottom: 5px;
    }

    .sellpress_one_click_order {
        display: inline-block !important;
        vertical-align: top !important;
        height: 44px !important;
        border-radius: 3px !important;
        font-size: 18px !important;
        line-height: 42px !important;
        border: 1px solid #fd9729 !important;
        color: #fff !important;
        background-color: #fd9729 !important;
        padding: 0 35px !important;
        margin: 20px 10px 5px 0 !important;
        box-shadow: none !important;
        outline: none !important;
        text-decoration: none !important;
    }

    .sellpress_product_details_title {
        margin-bottom: 0;
    }

    .sellpress_product_details {
        padding-top: 10px;
        border-top: 1px solid darkgray;
    }
</style>

<script>
    jQuery(document).ready(function () {
        let img = jQuery("#sellpress_product_image");
        img.elevateZoom({
            gallery: 'sellpress_product_gallery',
            cursor: 'pointer',
            galleryActiveClass: 'active',
            imageCrossfade: true
        });
    });
</script>