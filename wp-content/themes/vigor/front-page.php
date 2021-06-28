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
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="vg-text-secondary my-3">
                    <?php echo carbon_get_the_post_meta('vg_home_featured_section_title'); ?>
                </h2>
            </div>
            <div class="col-lg-6">
                <div class="my-3">
                    <?php echo wpautop(carbon_get_the_post_meta('vg_home_featured_section_description')); ?>
                </div>
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

<?php get_footer(); ?>