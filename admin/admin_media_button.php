<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action('media_buttons', 'sellpress_media_button');
function sellpress_media_button()
{
    global $wpdb;
    ?>
    <div class="sellpress_media_wrapper">
        <a href="#" class="button sellpress_media_button"><img
                    src="<?php echo plugin_dir_url(__FILE__) . 'images/icon-bg.png'; ?>"/>SellPress</a>
        <?php
        $products = $wpdb->get_results('select * from `' . $wpdb->prefix . 'sellpress_products`');
        ?>
        <div class="sellpress_media_selector">
            <?php if (!empty($products)): ?>
                <b class="sellpress_media_subtitle"><?php _e('Select product', 'sellpress'); ?></b>
                <ul>
                    <?php foreach ($products as $product) { ?>
                        <li class="sellpress_media_item" data-type="product" data-id="<?php echo $product->id; ?>"
                            data-id="<?php echo esc_attr($product->id); ?>">
                            <?php echo esc_html($product->name); ?> -
                            <?php echo SellPress_General_Settings::getCurrencySymbol() . (float)$product->price; ?>
                        </li>
                    <?php } ?>
                </ul>
            <?php else: ?>
                <?php _e('No products found', 'sellpress'); ?>&nbsp;<a
                        href="<?php admin_url('admin.php?page=sellpress_products&action=add'); ?>"
                        style="color:#0073aa">Add New Product</a>
            <?php endif; ?>
        </div>
    </div>

    <script>
        (function () {
            let btn = document.querySelector('.sellpress_media_button');
            let selector = document.querySelector('.sellpress_media_selector');
            let selectBoxOpen = false;
            let mediaItems = document.querySelectorAll('.sellpress_media_item');

            [...mediaItems].forEach(item => {
                item.addEventListener('click', itemClicked)
            });

            btn.addEventListener('click', btnClicked);

            function btnClicked(e) {
                e.preventDefault();
                if (!selectBoxOpen) {
                    selector.classList.add('sellpress_selecting');
                    selectBoxOpen = true;
                } else {
                    selector.classList.remove('sellpress_selecting');
                    selectBoxOpen = false;
                }
            }

            document.addEventListener("click", function (event) {
                // If user clicks inside the element, do nothing
                if (event.target.closest(".sellpress_media_wrapper")) return;

                // If user clicks outside the element, hide it!
                if (selectBoxOpen) {
                    selector.classList.remove('sellpress_selecting');
                    selectBoxOpen = false;
                }
            });

            function itemClicked(e) {
                e.preventDefault();

                const type = e.target.getAttribute('data-type');
                const id = +e.target.getAttribute('data-id');
                switch (type) {
                    case 'product':
                        window.send_to_editor('[sellpress_product id="' + id + '"]');
                        break;
                }

                selector.classList.remove('sellpress_selecting');
                selectBoxOpen = false;
            }
        }());
    </script>
    <style>
        .sellpress_media_wrapper {
            position: relative;
            display: inline-block;
        }

        .sellpress_media_selector {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            padding: 5px 0;
            background: white;
            border: 1px solid darkgray;
            min-width: 200px;
        }

        .sellpress_media_selector.sellpress_selecting {
            display: block;
        }

        .sellpress_media_subtitle {
            padding: 0 20px;
        }

        .sellpress_media_selector:before,
        .sellpress_media_selector:after {
            display: block;
            content: '';
            position: absolute;
            left: 20px;
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 0 10px 10px 10px;

        }

        .sellpress_media_selector:before {
            top: -10px;
            border-color: transparent transparent darkgray transparent;
        }

        .sellpress_media_selector:after {
            top: -9px;
            border-color: transparent transparent white transparent;
        }

        .sellpress_media_selector ul {
            padding: 0;
            margin: 0;
        }

        .sellpress_media_selector li {
            padding: 5px 20px;
            cursor: pointer;
            margin: 0;
        }

        .sellpress_media_selector li:hover {
            background-color: lightgray;
        }
    </style>
    <?php
}