(function (blocks, i18n, element, components) {
    var SelectControl = components.SelectControl;
    var el = element.createElement; // The wp.element.createElement() function to create elements.

    blocks.registerBlockType('sellpress/product', {
        title: 'Product',
        icon: 'products',
        category: 'sellpress',
        attributes: {
            product_id: {type: 'string'}
        },
        edit: function (props) {
            var focus = props.focus;
            props.attributes.product_id =  props.attributes.product_id &&  props.attributes.product_id != '0' ?  props.attributes.product_id : false;
            return [
                !focus && el(
                    SelectControl,
                    {
                        label: 'Select Product',
                        value: props.attributes.product_id ? parseInt(props.attributes.product_id) : 0,
                        instanceId: 'sellpress-product-selector',
                        onChange: function (value) {
                            props.setAttributes({product_id: value});
                        },
                        options: sellpressBlockL10n.products,
                    }
                ),
                el('div',{}, props.attributes.product_id ? 'Product: ' + sellpressBlockL10n.productMetas[props.attributes.product_id].title : 'Select Product')
            ];
        },
        save: function (props) {
            return el('p', {}, '[sellpress_product id="'+props.attributes.product_id+'"]');
        },
    });

    /*blocks.registerBlockType('sellpress/category', {
        title: 'Category',
        icon: 'category',
        category: 'sellpress',
        attributes: {
            category_id: {type: 'string'}
        },
        edit: function (props) {
            var focus = props.focus;
            props.attributes.category_id =  props.attributes.category_id &&  props.attributes.category_id != '0' ?  props.attributes.category_id : false;
            return [
                !focus && el(
                    SelectControl,
                    {
                        label: 'Select Category',
                        value: props.attributes.category_id ? parseInt(props.attributes.category_id) : 0,
                        instanceId: 'sellpress-category-selector',
                        onChange: function (value) {
                            props.setAttributes({category_id: value});
                        },
                        options: shopCTBlockI10n.categories,
                    }
                ),
                el('div',{}, props.attributes.category_id ? 'Category: ' + shopCTBlockI10n.categoryMetas[props.attributes.category_id].title : 'Select Category')
            ];
        },
        save: function (props) {
            return el('p', {}, '[ShopConstruct_category id="'+props.attributes.category_id+'"]');
        },
    });

    blocks.registerBlockType('sellpress/catalog', {
        title: 'Catalog',
        icon: 'screenoptions',
        category: 'sellpress',

        edit: function () {
            return el('div',{}, 'ShopConstruct Catalog') ;
        },
        save: function () {
            return el('p', {}, '[ShopConstruct_catalog]');
        },
    });

    blocks.registerBlockType('sellpress/cart-button', {
        title: 'Cart Button',
        icon: 'cart',
        category: 'sellpress',

        edit: function () {
            return el('div',{}, 'ShopConstruct Cart Button') ;
        },
        save: function () {
            return el('p', {}, '[ShopConstruct_cart_button]');
        },
    });*/
})(
    window.wp.blocks,
    window.wp.i18n,
    window.wp.element,
    window.wp.components
);