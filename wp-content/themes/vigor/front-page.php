<?php 
    get_header();

    $banner_image_url = wp_get_attachment_image_src(carbon_get_the_post_meta('vg_home_banner_image'), 'full')[0];
?>

<section id="home-banner" style="background-image: url(<?php echo $banner_image_url; ?>)">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="heading">
                    <h1 class="vg-text-primary"><?php echo carbon_get_the_post_meta('vg_home_banner_header'); ?></h1>
                    <span class="text-light h3">
                        <?php echo carbon_get_the_post_meta('vg_home_banner_subheader'); ?>
                    </span>
                </div>
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

                    <a href="<?php echo $primary_page_link; ?>" class="vg-btn vg-btn-primary text-uppercase">
                        <span><?php echo carbon_get_the_post_meta('vg_home_banner_cta_primary_text'); ?></span>
                    </a>

                    <a href="<?php echo $secondary_page_link; ?>" class="vg-btn vg-btn-outline-primary text-uppercase">
                        <span><?php echo carbon_get_the_post_meta('vg_home_banner_cta_secondary_text'); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="featured-description">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="vg-text-secondary my-3">
                    <?php echo carbon_get_the_post_meta('vg_home_featured_section_title'); ?>
                </h2>
            </div>
            <div class="col-lg-6">
                <div class="font-12 my-3">
                    <?php echo wpautop(carbon_get_the_post_meta('vg_home_featured_section_description')); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>