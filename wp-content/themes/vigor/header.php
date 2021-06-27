<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php 
            echo is_front_page() ? 
                bloginfo('name') : 
                (
                    !empty(get_the_title()) ? 
                    get_the_title() : 
                    '404'
                ); 
        ?> | <?php echo !is_front_page() ? bloginfo('name') : bloginfo('description'); ?>
    </title>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <header>
        <nav class="navbar navbar-expand-lg bg-transparent vg-bg-nlg-primary fixed-top navbar-light" id="navbar">
            <div class="container">
                <a class="navbar-brand" href="<?php echo home_url(); ?>">
                    <?php
                        $light_logo = wp_get_attachment_image_src(carbon_get_theme_option('vg_logo_light'), 'full');
                        $dark_logo = wp_get_attachment_image_src(carbon_get_theme_option('vg_logo_dark'), 'full');
                        if (!empty($light_logo) && !empty($dark_logo)) : 
                    ?>
                            <img class="img-fluid" src="<?php echo $light_logo[0]; ?>" alt="<?php echo bloginfo('name'); ?>">
                            <img class="d-none img-fluid" src="<?php echo $dark_logo[0]; ?>" alt="<?php echo bloginfo('name'); ?>">
                    <?php 
                        else:
                            echo bloginfo('name'); 
                    ?>
                    <?php endif; ?>
                </a>
                <div class="float-right">
                    <a class="nav-cart d-lg-none" href="<?php echo wc_get_cart_url(); ?>">
                        <i class="vg-icon-cart text-dark"></i>

                        <?php if (($cart_count = WC()->cart->cart_contents_count)  > 0): ?>
                            <span class="cart-contents-count">
                                <?php echo $cart_count; ?> 
                            </span>
                        <?php endif; ?>
                    </a>
                    <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="bs4navbar" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
                <?php
                    wp_nav_menu([
                        'menu'            => 'primary',
                        'theme_location'  => 'header-menu',
                        'container'       => 'div',
                        'container_id'    => 'main-menu',
                        'container_class' => 'collapse navbar-collapse vg-bg-nlg-primary bg-lg-transparent',
                        'menu_id'         => false,
                        'menu_class'      => 'navbar-nav ml-auto',
                        'depth'           => 99,
                        'fallback_cb'     => 'bs4navwalker::fallback',
                        'walker'          => new bs4navwalker()
                    ]);
                ?>
            </div>
        </nav>
    </header>

    <main>