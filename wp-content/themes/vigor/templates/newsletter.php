<?php if (!empty($form = carbon_get_the_post_meta('vg_home_newsletter_section_form_shortcode'))): ?>
    <section id="newsletter" class="vg-bg-primary py-5">
        <div class="container">
            <div class="row my-4 text-center">
                <div class="col-lg-12">
                    <h2>
                        <?php echo esc_html(carbon_get_the_post_meta('vg_home_newsletter_section_title')); ?>
                    </h2>
                    <span class="font-weight-500">
                        <?php echo esc_html(carbon_get_the_post_meta('vg_home_newsletter_section_subtitle')); ?>
                    </span>

                    <div id="newsletter-form" class="mt-5 d-block">
                        <?php echo do_shortcode($form); ?>
                    </div>

                    <div class="my-3 caption">
                        <?php echo esc_html(carbon_get_the_post_meta('vg_home_newsletter_section_form_caption')); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
