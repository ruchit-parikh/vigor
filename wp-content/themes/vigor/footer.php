    </main>

    <footer class="py-5 vg-bg-secondary">
        <div class="container">
            <div class="row my-5">
                <?php if (!empty($image_id = carbon_get_theme_option('vg_logo_special'))): ?>
                    <div class="col-sm col-6 mb-5">
                        <img src="<?php echo wp_get_attachment_image_src($image_id, 'full')[0]; ?>" class="w-50" alt="<?php echo bloginfo('name'); ?>">
                    </div>
                <?php endif; ?>

                <div class="col-sm col-6 mb-5">
                    <?php 
                        if (is_active_sidebar('footer_widget_1')) :
                            dynamic_sidebar('footer_widget_1');
                        endif;
                    ?>
                </div>

                <div class="col-sm col-6 mb-5">
                    <?php 
                        if (is_active_sidebar('footer_widget_2')) :
                            dynamic_sidebar('footer_widget_2');
                        endif;
                    ?>
                </div>

                <div class="col-sm col-6 mb-5">
                    <?php 
                        if (is_active_sidebar('footer_widget_3')) :
                            dynamic_sidebar('footer_widget_3');
                        endif;
                    ?>
                </div>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
    </body>
</html>