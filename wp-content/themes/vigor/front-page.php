<?php 
    get_header();

    $banner_image_url = wp_get_attachment_image_src(carbon_get_the_post_meta('vg_home_banner_image'), 'full')[0];
?>

<section id="home-banner" style="background-image: url(<?php echo $banner_image_url; ?>)">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="heading">
                    <h1 class="vg-text-primary"><?php echo carbon_get_the_post_meta('vg_home_banner_header'); ?></h1>
                    <span class="text-light h3 font-weight-600">
                        <?php echo carbon_get_the_post_meta('vg_home_banner_subheader'); ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="vg-btn-group">
                    <?php
                        $primary_page_link = '#';
                        $secondary_page_link = '#';

                        $primary_page_id = carbon_get_the_post_meta('vg_home_banner_cta_primary_link');
                        $secondary_page_id = carbon_get_the_post_meta('vg_home_banner_cta_secondary_link');

                        if (-1 === $primary_page_id):
                            $primary_page_link = carbon_get_the_post_meta('vg_home_banner_cta_primary_link_url');
                        else:
                            $primary_page_link = get_page_link($primary_page_id);
                        endif;

                        if (-1 === $secondary_page_id):
                            $secondary_page_link = carbon_get_the_post_meta('vg_home_banner_cta_secondary_link_url');
                        else:
                            $secondary_page_link = get_page_link($secondary_page_id);
                        endif;
                    ?>

                    <?php if (!empty($primary_text = carbon_get_the_post_meta('vg_home_banner_cta_primary_text'))) : ?>
                        <a href="<?php echo $primary_page_link; ?>" class="vg-btn vg-btn-primary text-uppercase">
                            <span><?php echo $primary_text; ?></span>
                        </a>
                    <?php endif; ?>

                    <?php if (!empty($secondary_text = carbon_get_the_post_meta('vg_home_banner_cta_secondary_text'))) : ?>
                        <a href="<?php echo $secondary_page_link; ?>" class="vg-btn vg-btn-outline-primary text-uppercase">
                            <span><?php echo $secondary_text; ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="featured-description">
    <div class="container">
        <div class="row my-4">
            <div class="col-lg-6">
                <h2>
                    <?php echo carbon_get_the_post_meta('vg_home_featured_section_title'); ?>
                </h2>
            </div>
            <div class="col-lg-6">
                <?php echo wpautop(carbon_get_the_post_meta('vg_home_featured_section_description')); ?>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($coaches = carbon_get_the_post_meta('vg_home_coaches_section_coaches'))) : ?>
    <section id="coaches">
        <div id="coaches-slider" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <?php foreach ($coaches as $key => $coach) : ?>
                    <li data-target="#coaches-slider" data-slide-to="<?php echo $key; ?>" class="<?php echo $key === 0 ? 'active' : ''; ?>"></li>
                <?php endforeach; ?>
            </ol>

            <div class="container carousel-header">
                <h2 class="text-light text-center">
                    <?php echo carbon_get_the_post_meta('vg_home_coaches_section_title'); ?>
                </h2>
                <div class="text-light font-weight-500 text-center">
                    <?php echo wpautop(carbon_get_the_post_meta('vg_home_coaches_section_subtitle')); ?>
                </div>
            </div>

            <div class="carousel-inner">
                <?php foreach ($coaches as $key => $coach) : ?>
                    <div class="carousel-item <?php echo $key === 0 ? 'active' : ''; ?>">
                        <?php 
                            $coach_post      = get_post($coach['id']);
                            $coach_image_url = wp_get_attachment_image_src(get_post_thumbnail_id($coach_post), 'full')[0];
                            $coach_title     = get_the_title($coach_post->ID);
                        ?>

                        <img class="d-block w-100 mx-auto" src="<?php echo $coach_image_url; ?>" alt="<?php echo $coach_title; ?>">
                        <div class="carousel-caption">
                            <h4 class="author">
                                <?php echo $coach_title; ?>
                            </h4>
                            <div class="description">
                                <?php echo apply_filters('the_content', $coach_post->post_content); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (!empty($link_text = carbon_get_the_post_meta('vg_home_coaches_section_cta_text'))): ?>
                <?php 
                    $link_url     = '#';
                    $link_page_id = carbon_get_the_post_meta('vg_home_coaches_section_cta_link');

                    if (-1 === $link_page_id) : 
                        $link_url = carbon_get_the_post_meta('vg_home_coaches_section_cta_link_url');
                    else :
                        $link_url = get_page_link($link_page_id);
                    endif;
                ?>

                <a class="text-uppercase view-all-link" href="<?php echo $link_url; ?>">
                    <?php echo $link_text; ?>
                </a>
            <?php endif; ?>
            
            <a class="carousel-control-prev" href="#coaches-slider" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#coaches-slider" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </section>
<?php endif; ?>

<?php if (!empty($plans = carbon_get_the_post_meta('vg_home_plans_section_plans'))): ?>
    <section id="plans-overview" class="py-5">
        <div class="container">
            <div class="row my-4">
                <div class="col-lg-12 mb-md-5">
                    <h2 class="text-center">
                        <?php echo carbon_get_the_post_meta('vg_home_plans_section_title'); ?>
                    </h2>
                </div>
            </div>
            <div class="row row-eq-height py-4">
                <?php foreach ($plans as $key => $plan): ?>
                    <div class="col-md-4 mb-5">
                        <div class="plan">
                            <h3 class="text-uppercase font-style-normal">
                                <?php echo $plan['name']; ?>
                            </h3>
                            <ul class="features">
                                <?php foreach ($plan['features'] as $feature): ?>
                                    <li>
                                        <span>
                                            <?php echo $feature['title']; ?>
                                        </span>
                                        <i class="vg-check"></i>
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                            <div class="text-center">
                                <?php if (!empty($plan['btn_text'])): ?>
                                    <a href="<?php echo $plan['btn_link']; ?>" class="vg-btn vg-btn-primary">
                                        <span>
                                            <?php echo $plan['btn_text']; ?>
                                        </span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<section id="share-passion" class="py-5">
    <div class="container">
        <div class="row my-4">
            <div class="col-lg">
                <h2>
                    <?php echo carbon_get_the_post_meta('vg_home_share_passion_section_title'); ?>
                </h2>

                <div class="my-4">
                    <?php echo wpautop(carbon_get_the_post_meta('vg_home_share_passion_section_description')); ?>
                </div>
                
                <?php if (!empty($shop_link_text = carbon_get_the_post_meta('vg_home_share_passion_section_shop_text'))) : ?>
                    <?php 
                        $shop_link_url = '#';
                        $link_page_id  = carbon_get_the_post_meta('vg_home_share_passion_section_shop_link');

                        if (-1 === $link_page_id) : 
                            $shop_link_url = carbon_get_the_post_meta('vg_home_share_passion_section_shop_url');
                        else :
                            $shop_link_url = get_page_link($link_page_id);
                        endif;
                    ?>
                    <a href="<?php echo $shop_link_url; ?>" class="text-uppercase font-weight-bold">
                        <?php echo $shop_link_text; ?>
                    </a>
                <?php endif; ?>
            </div>

            <?php
                $products = wc_get_products(array('featured' => true, 'limit' => 2));
                
                foreach ($products as $product):
                    $post = get_post($product->get_id());
                    setup_postdata($post);
            ?>
                    <div class="col-lg">
                        <?php wc_get_template_part('content', 'product'); ?>
                    </div>

            <?php 
                endforeach; 
            ?>
        </div>
    </div>
</section>

<?php get_template_part('templates/newsletter'); ?>

<?php get_footer(); ?>