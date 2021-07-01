$(document).ready(function() {
    let topMenu = $("#navbar");

    $(window).scroll(function() {
        let fromTop = $(this).scrollTop();
    
        if (fromTop > 100) {
            topMenu.addClass('scrolled vg-bg-primary');
        } else {
            topMenu.removeClass('scrolled vg-bg-primary');
        }
    });

    // Ajax add to cart
    var $wrapFragmentRefresh = {
        url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'),
        type: 'POST',
        success: function(data) {
            if (data && data.fragments) {
    
                $.each(data.fragments, function(key, value) {
                    $(key).replaceWith(value);
                });
    
                $(document.body).trigger('wc_fragments_refreshed');
            }
        }
    };
    
    $('.add-to-cart').on('click', function(e) {
        e.preventDefault();

        let $this = $(this);
    
        let productUrl = $this.attr('href');

        let data = {
            action: 'woocommerce_ajax_add_to_cart',
            product_id: $this.attr('data-product_id'),
            product_sku: $this.attr('data-product_sku'),
            quantity: $this.attr('data-quantity'),
            variation_id: $this.attr('data-variation_id'),
        };
    
        $.post(productUrl, JSON.stringify(data) + '&_wp_http_referer=' + productUrl, function (result) {
            $.ajax($wrapFragmentRefresh);

            $('.add-to-cart').text('Go to cart');

            $('.add-to-cart').removeClass('add-to-cart');
        });
    });
});