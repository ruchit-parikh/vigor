<?php

use Carbon_Fields\Carbon_Fields;
use Customs\CPTCoach;
use Customs\HomePageOptions;
use Customs\ThemeSettings;

if (!function_exists('vg_get_all_custom_fields')) {
    /**
     * Get all custom fields/taxonomies list
     *
     * @return array
     */
    function vg_get_all_custom_fields(): array {
        return array(
            CPTCoach::class,
            ThemeSettings::class,
            HomePageOptions::class,
        );
    }
}

if (!function_exists('vg_register_my_menu')) {
    function vg_register_my_menu() {
        register_nav_menu('header-menu',__('Primary Menu'));
    }
}
add_action('init', 'vg_register_my_menu');

if (!function_exists('vg_register_widgets')) {
    function vg_register_widgets() {
        register_sidebar(array(
            'name'          => 'Footer Widget 1',
            'id'            => 'footer_widget_1',
            'before_widget' => '<div>',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="rounded">',
            'after_title'   => '</h2>',
        ));
    
        register_sidebar(array(
            'name'          => 'Footer Widget 2',
            'id'            => 'footer_widget_2',
            'before_widget' => '<div>',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="rounded">',
            'after_title'   => '</h2>',
        ));
    
        register_sidebar(array(
            'name'          => 'Footer Widget 3',
            'id'            => 'footer_widget_3',
            'before_widget' => '<div>',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="rounded">',
            'after_title'   => '</h2>',
        ));
    }
}
add_action('widgets_init', 'vg_register_widgets');

/**
 * Load initial files and settings after theme setup. We are using bootstrap navwalker 
 * for generating our menu which uses wordpress Walker_Nav_Menu
 */
if (!function_exists('vg_after_theme_setup')) {
    function vg_after_theme_setup() {
        if (!file_exists(get_template_directory().'/class-wp-bootstrap-navwalker.php')) {
            return new WP_Error('class-wp-bootstrap-navwalker-missing', __('It appears the class-wp-bootstrap-navwalker.php file may be missing.', 'wp-bootstrap-navwalker'));
        } else {
            require_once get_template_directory() . '/class-wp-bootstrap-navwalker.php';
        }

        /**
         * Load carbon fields to generate custom meta fields on all pages where needed.
         * We generally use carbon fields so that we can bundle every thing in just 
         * one theme rather than installing other plugin separately to generate 
         * our custom fields.
         */
        require_once('vendor/autoload.php');
        
        Carbon_Fields::boot();

        $custom_fields = vg_get_all_custom_fields();

        //load all custom fields before registering it
        foreach ($custom_fields as $field) {
            require_once(str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $field).'.php');
        }

        //enable woocomerce support
        add_theme_support('woocommerce');
    }
}
add_action('after_setup_theme', 'vg_after_theme_setup');

/**
 * Register all our meta fields to their respective page templates and Create 
 * custom posts/taxonomies which are needed for this theme
 */
if (!function_exists('vg_attach_theme_options')) {
    function vg_attach_theme_options() {
        $custom_fields = vg_get_all_custom_fields();

        foreach ($custom_fields as $field) {
            $custom_field = new $field();

            $custom_field->register();
        }
    }
}
add_action('carbon_fields_register_fields', 'vg_attach_theme_options');

if (!function_exists('vg_get_gmaps_api_key')) {
    /**
     * @return string
     */
    function vg_get_gmaps_api_key() {
        return carbon_get_theme_option('vg_google_map_api_key');
    }
}
add_filter('carbon_fields_map_field_api_key', 'vg_get_gmaps_api_key');

if (!function_exists('vg_change_login_logo')) {
    function vg_change_login_logo() {
        ?>
            <style type="text/css">
                #login h1 a, .login h1 a {
                    background-image: url("<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-dark.svg");
                    height:65px;
                    width:320px;
                    background-size: 320px 90px;
                    background-repeat: no-repeat;
                    padding-bottom: 30px;
                }
            </style>
        <?php
    }
}
add_action('login_enqueue_scripts', 'vg_change_login_logo');

if (!function_exists('vg_change_login_url')) {
    function vg_change_login_url() {
        return home_url();
    }
}
add_filter('login_headerurl', 'vg_change_login_url');

if (!function_exists('vg_get_map_locations')) {
    /**
     * @return array
     */
    function vg_get_map_locations() {
        $locations = array();

        foreach (carbon_get_theme_option('vg_office_locations') as $location) {
            ob_start();

            set_query_var('office', $location);
            get_template_part('templates/gmap', 'infowindow');

            $content = ob_get_contents();
            ob_end_clean();

            $location['content'] = $content;
            $locations[]         = $location;
        }

        return $locations;
    }
}

/**
 * Enqueue all styles and scripts and other related assets needed
 */
if (!function_exists('vg_enque_assets')) {
    function vg_enque_assets() {
        $map_key = carbon_get_theme_option('vg_google_map_api_key');

        wp_enqueue_style('style', get_stylesheet_uri());
        wp_enqueue_style('bootstrap', get_stylesheet_directory_uri().'/assets/css/bootstrap.min.css', array(), 1.0);
        wp_enqueue_style('main', get_stylesheet_directory_uri().'/assets/css/main.css', array('bootstrap'), time());
        wp_enqueue_style('icons', get_stylesheet_directory_uri().'/assets/css/icons.css', array('bootstrap'), time());
    
        wp_enqueue_script('jQuery', get_stylesheet_directory_uri().'/assets/js/jQuery.min.js', array (), 1.0);
        wp_enqueue_script('popper', get_stylesheet_directory_uri().'/assets/js/popper.min.js', array ('jQuery'), 1.0);
        wp_enqueue_script('bootstrap', get_stylesheet_directory_uri().'/assets/js/bootstrap.min.js', array ('popper'), 1.0);
        wp_enqueue_script('maps', "https://maps.googleapis.com/maps/api/js?key=$map_key&sensor=false", array ('bootstrap'), 1.0);
        wp_enqueue_script('main', get_stylesheet_directory_uri().'/assets/js/main.js', array ('bootstrap'), time());

        //load all map needed data
        wp_localize_script('maps', 'vg_map_data', array(
            'locations'     => vg_get_map_locations(),
            'marker'        => get_template_directory_uri() . '/assets/images/marker.svg',
            'active_marker' => get_template_directory_uri() . '/assets/images/active-marker.svg',
        ));
    }
}
add_action('wp_enqueue_scripts', 'vg_enque_assets');

/**
 * Customizations
 * 
 * Woocomerce cart icon
 */
if (!function_exists('vg_get_woo_cart_icon')) {
    /**
     * @return string|false
     */
    function vg_get_woo_cart_icon() {
        ob_start();
        
        $html       = '';
        $cart_count = WC()->cart->cart_contents_count;
        $cart_url   = wc_get_cart_url();
        
        $html .= '<a class="nav-link" href="' . $cart_url . '" title="My Cart"><i class="vg-icon-cart"></i>';
        
        if ($cart_count > 0) {
            $html .= '<span class="cart-contents-count">' . $cart_count . '</span>';
        }

        $html .= '</a>';

        echo $html;
	        
        return ob_get_clean();
    }
}
add_shortcode('vg_get_woo_cart_icon', 'vg_get_woo_cart_icon');

if (!function_exists('vg_woo_cart_ajax')) {
    /**
     * @param array $fragments
     * 
     * @return array
     */
    function vg_woo_cart_ajax($fragments) {
        $fragments['.nav-cart .nav-link'] = vg_get_woo_cart_icon();

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) { 
            $fragments['.add-to-cart[data-product_id="'.$cart_item['product_id'].'"]'] = vg_add_to_cart_button(__('Buy Now'), wc_get_product($cart_item['product_id']), array()); 
        } 

        return $fragments;
    }
}
add_filter('woocommerce_add_to_cart_fragments', 'vg_woo_cart_ajax');

if (!function_exists('vg_get_woo_cart_icon_menu')) {
    /**
     * @param string $items
     * @param array  $args
     * 
     * @return string
     */
    function vg_get_woo_cart_icon_menu($items, $args) {
        $items .= '<li class="menu-item menu-item-type-custom nav-item nav-cart d-none d-lg-inline">';
        $items .= do_shortcode("[vg_get_woo_cart_icon]");
        $items .= '</li>';

        return $items;
    }
}
add_filter('wp_nav_menu_primary-menu_items', 'vg_get_woo_cart_icon_menu', 10, 2);

if (!function_exists('vg_is_in_cart')) {
    /**
     * @param WC_Product $product
     * 
     * @return bool
     */
    function vg_is_in_cart($product) {
        foreach (WC()->cart->get_cart() as $cart_item)
            if ($cart_item['product_id'] == $product->get_id()) {
                return true;
            }
    
        return false;
    }
}

if (!function_exists('vg_add_to_cart_button_text')) {
    /**
     * @param string     $button_text
     * @param WC_Product $product
     * 
     * @return string
     */
    function vg_add_to_cart_button_text($button_text, $product) {
        if (vg_is_in_cart($product)) {
            return __('Go to Cart', 'woocommerce');
        }

        return __('Buy Now', 'woocommerce');
    }
}
add_filter('woocommerce_product_add_to_cart_text', 'vg_add_to_cart_button_text', 10, 2);
add_filter('woocommerce_product_single_add_to_cart_text', 'vg_add_to_cart_button_text', 10, 2);

if (!function_exists('vg_woocommerce_get_product_thumbnail')) {
    /**
     * @param string $size
     * @param int    $placeholder_width
     * @param int    $placeholder_height
     * 
     * @return string
     */
    function vg_woocommerce_get_product_thumbnail($size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0) {
        global $post, $woocommerce;

        $image_url = '';

        if (has_post_thumbnail()) {
            $image_url = get_the_post_thumbnail_url($post->ID, $size); 
        } else {
            $image_url = woocommerce_placeholder_img_src();
        }

        $output = '<img class="w-100" src="'. $image_url .'" alt="'. $post->post_title .'" />';
        
        return $output;
    }
}

if (!function_exists('woocommerce_template_loop_product_thumbnail')) {
    function woocommerce_template_loop_product_thumbnail() {
        echo vg_woocommerce_get_product_thumbnail();
    } 
}
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

if (!function_exists('vg_add_to_cart_button')) {
    /**
     * @param string     $button_text
     * @param WC_Product $product
     * @param array      $args
     * 
     * @return string
     */
    function vg_add_to_cart_button($button_text, $product, $args) {
        $is_in_cart    = vg_is_in_cart($product);
        $args['class'] = 'vg-btn vg-btn-sm vg-btn-primary text-uppercase font-weight-bold';

        if (!$is_in_cart) {
            $args['class'] .= ' add-to-cart';
        }
        
        return sprintf(
            '<a href="%s" data-quantity="%s" class="%s" %s><span>%s</span></a>',
            esc_url($is_in_cart ? wc_get_cart_url() : $product->add_to_cart_url()),
            esc_attr(isset($args['quantity']) ? $args['quantity'] : 1),
            esc_attr(isset($args['class']) ? $args['class'] : 'vg-btn'),
            isset($args['attributes']) ? wc_implode_html_attributes( $args['attributes']) : '',
            esc_html($product->add_to_cart_text())
        );
    }
}
add_filter('woocommerce_loop_add_to_cart_link', 'vg_add_to_cart_button', 10, 3);

//TODO: Remove this actions once finished to show links of product.
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);