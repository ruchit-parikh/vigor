<div class="infobox">
    <div class="infobox-content">
        <h3 class="text-uppercase font-weight-600 font-style-normal mb-3">
            <?php echo $office['name']; ?>
        </h3>
        <address>
            <?php echo wpautop($office['address']); ?>
        </address>
    </div>
    
    <ul class="infobox-timings">
        <?php foreach ($office['times'] as $time): ?>
            <li class="d-flex">
                <span><?php echo $time['day']; ?></span>
                <time class="ml-3"><?php echo $time['time']; ?></time>
            </li>
        <?php endforeach; ?>
    </ul>
    
    <div class="infobox-gallery">
        <?php 
            $image_count = count($office['images']);

            foreach ($office['images'] as $key => $image_id): 
                $image_url = wp_get_attachment_image_src($image_id, 'full')[0];
                $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
        ?>
                <a data-count="<?php echo $image_count - $key; ?>" href="<?php echo $image_url; ?>" data-fancybox="gallery" data-caption="<?php echo $image_alt; ?>">
                    <img src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>">
                </a>
        <?php 
            endforeach; 
        ?>
    </div>
</div>