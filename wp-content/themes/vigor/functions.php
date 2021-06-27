<?php

use Carbon_Fields\Carbon_Fields;
use Customs\HomePageOptions;
use Customs\ThemeSettings;

/**
 * Get all custom fields/taxonomies list
 * 
 * @return array
 */
if (!function_exists('vg_get_all_custom_fields')) {
    function vg_get_all_custom_fields(): array {
        return array(
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

/**
 * Enqueue all styles and scripts and other related assets needed
 */
if (!function_exists('vg_enque_assets')) {
    function vg_enque_assets() {
        wp_enqueue_style('style', get_stylesheet_uri());
        wp_enqueue_style('bootstrap', get_stylesheet_directory_uri().'/assets/css/bootstrap.min.css', array(), 1.0);
        wp_enqueue_style('main', get_stylesheet_directory_uri().'/assets/css/main.css', array('bootstrap'), time());
    
        wp_enqueue_script('jQuery', get_stylesheet_directory_uri().'/assets/js/jQuery.min.js', array (), 1.0);
        wp_enqueue_script('popper', get_stylesheet_directory_uri().'/assets/js/popper.min.js', array ('jQuery'), 1.0);
        wp_enqueue_script('bootstrap', get_stylesheet_directory_uri().'/assets/js/bootstrap.min.js', array ('popper'), 1.0);
    }
}
add_action('wp_enqueue_scripts', 'vg_enque_assets');

/**
 * Customizations
 * 
 * Woocomerce cart icon
 */
if (!!function_exists('vg_get_woo_cart_icon')) {
    /**
     * @return string|false
     */
    function vg_get_woo_cart_icon() {
        ob_start();
        
        $html       = '';
        $cart_count = WC()->cart->cart_contents_count;
        $cart_url   = wc_get_cart_url();
        
        $html .= '<li><a class="menu-item cart-contents" href="' . $cart_url . '" title="My Cart">';
        
        if ($cart_count > 0) {
            $html .= '<span class="cart-contents-count">' . $cart_count . '</span>';
        }

        $html .= '</a></li>';
	        
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
        $fragments['a.cart-contents'] = vg_get_woo_cart_icon();

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
        $items .= do_shortcode("[vg_get_woo_cart_icon]");

        return $items;
    }
}
add_filter('wp_nav_menu_primary-menu_items', 'vg_get_woo_cart_icon_menu', 10, 2);