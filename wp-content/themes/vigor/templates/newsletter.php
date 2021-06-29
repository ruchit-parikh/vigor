<?php if (!empty($form = carbon_get_the_post_meta('vg_home_newsletter_section_form_shortcode'))): ?>
    <section id="newsletter" class="vg-bg-primary py-5">
        <div class="container">
            <div class="row my-5 text-center">
                <div class="col-lg-12">
                    <h2>
                        <?php echo carbon_get_the_post_meta('vg_home_newsletter_section_title'); ?>
                    </h2>
                    <span class="subtitle">
                        <?php echo carbon_get_the_post_meta('vg_home_newsletter_section_subtitle'); ?>
                    </span>

                    <div id="newsletter-form" class="mt-5 d-block">
                        <?php echo do_shortcode($form); ?>
                    </div>

                    <div class="my-3">
                        <?php echo carbon_get_the_post_meta('vg_home_newsletter_section_form_caption'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>